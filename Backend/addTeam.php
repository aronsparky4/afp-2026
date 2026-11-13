<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];

        if (!isset($_POST['teamName']) || empty(trim($_POST['teamName']))) {
            header("Location: ../Frontend/startpage.php?error=invalid_request");
            exit();
        }

        $teamName = trim($_POST['teamName']);

        $lekerdezes = $connection->prepare("SELECT id FROM teams WHERE team_name = ?");
        $lekerdezes->bind_param("s", $teamName);
        $lekerdezes->execute();
        $result = $lekerdezes->get_result();

        if ($result->num_rows > 0) {
            header("Location: ../Frontend/startpage.php?error=team_exists");
            exit();
        }
        $joinCode = strtoupper(bin2hex(random_bytes(4)));
        $lekerdezes = $connection->prepare("INSERT INTO teams (team_name, join_code) VALUES (?, ?)");
        $lekerdezes->bind_param("ss", $teamName, $joinCode);
        try {if ($lekerdezes->execute()) {
            $lekerdezes = $connection->prepare("SELECT id FROM teams WHERE join_code = ?");
            $lekerdezes->bind_param("s", $joinCode);
            $lekerdezes->execute();
            $team = $lekerdezes->get_result()->fetch_assoc();
            $team_id = $team['id'];
            $lekerdezes = $connection->prepare("INSERT INTO team_members (user_id, team_id, role) VALUES (?, ?, 'admin')");
            $lekerdezes->bind_param("ii", $user_id, $team_id);
            try {if ($lekerdezes->execute()) {
                    header("Location: ../Frontend/startpage.php?message=success");
                    exit();
                } else {
                    header("Location: ../Frontend/startpage.php?error=error");
                    exit();
                }
            } catch (mysqli_sql_exception $exception) {
                header("Location: ../Frontend/startpage.php?error=error");
                $hiba = "Adatbázis hiba: " . $exception->getMessage();
            }
            header("Location: ../Frontend/startpage.php?message=success");
            exit();
            }
        } catch (mysqli_sql_exception $exception) {
            header("Location: ../Frontend/startpage.php?error=error");
            $hiba = "Adatbázis hiba: " . $exception->getMessage();
        }
    }
    Header("Location: ../Frontend/startpage.php?delete=success");
    exit;
?>