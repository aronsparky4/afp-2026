<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (!isset($_POST["toDoInput"]) || !isset($_POST["fontossagi"]) || !isset($_POST["category"])) {
            Header("Location: ../Frontend/index_v2.php?hiba=missing");
            exit;
        }
        else {
            $toDo =  trim($_POST["toDoInput"]);
            if (empty($toDo)) {
                Header("Location: ../Frontend/index_v2.php?hiba=empty");
                exit;
            }
            $fontossagi = intval($_POST["fontossagi"]);
            $category = intval($_POST["category"]);
        }

        if (!in_array($fontossagi, [1, 2, 3, 4, 5], true)) {
            Header("Location: ../Frontend/index_v2.php?hiba=invalid");
            exit;
        }
        $lekerdezes = $connection->prepare("SELECT id FROM categories WHERE user_id = ? AND id = ?");
        $lekerdezes->bind_param("ii", $_SESSION['user_id'], $category);
        $lekerdezes->execute();
        $category_result = $lekerdezes->get_result();

        if ($category_result->num_rows == 0) {
            Header("Location: ../Frontend/index_v2.php?hiba=missing");
            exit;
        }

        if (!empty($_SESSION["team_id"])) {
            $team_id = intval($_SESSION["team_id"]);
            $lekerdezes = $connection->prepare("SELECT id FROM team_members WHERE user_id = ? AND team_id = ?");
            $lekerdezes->bind_param("ii", $_SESSION['user_id'], $team_id);
            $lekerdezes->execute();
            $team_result = $lekerdezes->get_result();

            if ($team_result->num_rows == 0) {
                Header("Location: ../Frontend/index_v2.php?hiba=invalid");
                exit;
            }

        }
        else {
            $team_id = null;
        }

        $lekerdezes = $connection->prepare("SELECT id FROM tasks WHERE user_id = ? AND task = ? AND category_id = ? AND priority_id = ?  AND team_id = ?" );
        $lekerdezes->bind_param("isiis", $_SESSION['user_id'], $toDo, $category, $fontossagi, $team_id);
        $lekerdezes->execute();
        $result = $lekerdezes->get_result();

        if ($result->num_rows > 0) {
            Header("Location: ../Frontend/index_v2.php?hiba=exists");
            exit;
        } else {
            $lekerdezes = $connection->prepare("INSERT INTO tasks (user_id, task, category_id, priority_id, team_id) VALUES (?, ?, ?, ?, ?)");
            $lekerdezes->bind_param("isiis", $_SESSION['user_id'], $toDo, $category, $fontossagi, $team_id);
            try {if ($lekerdezes->execute()) {
                header("Location: ../Frontend/index_v2.php?message=success");
                exit();
                }
            } catch (mysqli_sql_exception $exception) {
                header("Location: ../Frontend/index_v2.php?error=error");
                $hiba = "Adatbázis hiba: " . $exception->getMessage();
            }
        }
    }
    Header("Location: ../Frontend/index_v2.php");
    exit;
?>