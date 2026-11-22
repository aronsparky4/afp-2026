<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];

        if (!isset($_POST['team_id']) || empty(trim($_POST['team_id']))) {
            header("Location: ../Frontend/index_v2.php?error=invalid_request");
            exit();
        }

        $team_id = trim($_POST['team_id']);

        $lekerdezes = $connection->prepare("SELECT id FROM teams WHERE id = ?");
        $lekerdezes->bind_param("i", $team_id);
        $lekerdezes->execute();
        $result = $lekerdezes->get_result();

        if ($result->num_rows <= 0) {
            header("Location: ../Frontend/startpage.php?error=team_not_found");
            exit();
        }
        
        $lekerdezes = $connection->prepare("SELECT role FROM team_members WHERE role = 'admin' AND team_id = ?");
        $lekerdezes->bind_param("i", $team_id);
        $lekerdezes->execute();
        $role_result = $lekerdezes->get_result();
        if ($role_result->num_rows  <= 1) {
            header("Location: ../Frontend/startpage.php?error=admin_exists");
            exit();
        }

        $lekerdezes = $connection->prepare("DELETE FROM team_members WHERE user_id = ? AND team_id = ?");
        $lekerdezes->bind_param("ii", $user_id, $team_id);
        try {if ($lekerdezes->execute()) {
            header("Location: ../Frontend/startpage.php?message=success");
            exit();
            }
            } catch (mysqli_sql_exception $exception) {
                header("Location: ../Frontend/startpage.php?error=error");
            $hiba = "Adatbázis hiba: " . $exception->getMessage();
        }
    }
    Header("Location: ../Frontend/startpage.php?");
    exit;
?>