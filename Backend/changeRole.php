<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];

        if (!isset($_POST['team_id']) || empty(trim($_POST['team_id'])) || !isset($_POST['member_id']) || empty(trim($_POST['member_id'])) || !isset($_POST['new_role']) || empty(trim($_POST['new_role']))) {
            header("Location: ../Frontend/index_v2.php?error=invalid_request");
            exit();
        }

        $team_id = trim($_POST['team_id']);
        $member_id = trim($_POST['member_id']);
        $new_role = trim($_POST['new_role']);

        $allowedRoles = ['admin', 'member', 'viewer'];

        if (!in_array($new_role, $allowedRoles)) {
            header("Location: ../Frontend/index_v2.php?error=invalid_role");
            exit();
        }

        $lekerdezes = $connection->prepare("SELECT id FROM teams WHERE id = ?");
        $lekerdezes->bind_param("i", $team_id);
        $lekerdezes->execute();
        $result = $lekerdezes->get_result();

        if ($result->num_rows <= 0) {
            header("Location: ../Frontend/index_v2.php?error=team_not_found");
            exit();
        }

        $lekerdezes = $connection->prepare("SELECT role FROM team_members WHERE user_id = ? AND team_id = ?");
        $lekerdezes->bind_param("ii", $user_id, $team_id);
        $lekerdezes->execute();
        $role_result = $lekerdezes->get_result();
        $role_row = $role_result->fetch_assoc();
        $user_role = $role_row['role'];

        $lekerdezes = $connection->prepare("SELECT role FROM team_members WHERE role = 'admin' AND team_id = ?");
        $lekerdezes->bind_param("i", $team_id);
        $lekerdezes->execute();
        $role_result = $lekerdezes->get_result();

        if ($user_role !== 'admin') {
            header("Location: ../Frontend/index_v2.php?error=not_allowed");
            exit();
        }
        
        if ($role_result->num_rows <= 1 && $new_role !== 'admin' && $member_id == $user_id) {
            header("Location: ../Frontend/index_v2.php?error=admin_exists");
            exit();
        }

        $lekerdezes = $connection->prepare("UPDATE team_members SET role = ? WHERE user_id = ? AND team_id = ?");
        $lekerdezes->bind_param("sii", $new_role, $member_id, $team_id);
        try {if ($lekerdezes->execute()) {
            header("Location: ../Frontend/index_v2.php?message=success");
            exit();
            }
            } catch (mysqli_sql_exception $exception) {
                header("Location: ../Frontend/index_v2.php?error=error");
            $hiba = "Adatbázis hiba: " . $exception->getMessage();
        }
    }
    Header("Location: ../Frontend/index_v2.php?");
    exit;
?>