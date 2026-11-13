<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];

        if (!isset($_POST['teamCode']) || empty(trim($_POST['teamCode']))) {
            header("Location: ../Frontend/index_v2.php?error=invalid_request");
            exit();
        }

        $teamCode = trim($_POST['teamCode']);

        $lekerdezes = $connection->prepare("SELECT id FROM teams WHERE join_code = ?");
        $lekerdezes->bind_param("s", $teamCode);
        $lekerdezes->execute();
        $result = $lekerdezes->get_result();

        if ($result->num_rows <= 0) {
            header("Location: ../Frontend/startpage.php?error=team_not_found");
            exit();
        }
        $team_id = $result->fetch_assoc()['id'];
        $lekerdezes = $connection->prepare("INSERT INTO team_members (user_id, team_id, role) VALUES (?, ?, 'member')");
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