<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $task_id = $_POST["task_id"];
        $is_done = $_POST["is_done"];
        if ($_SESSION['team_id'] !== null) {
            $lekerdezes = $connection->prepare("SELECT role FROM team_members WHERE user_id = ? AND team_id = ?");
            $lekerdezes->bind_param("ii",  $_SESSION['user_id'], $_SESSION['team_id']);
            $lekerdezes->execute();
            $role_result = $lekerdezes->get_result();
            $role_row = $role_result->fetch_assoc();
            $user_role = $role_row['role'];

            if ($user_role !== 'admin' && $user_role !== 'member') {
                header("Location: ../Frontend/index_v2.php?error=not_allowed");
                exit();
            }
        }
        if ($is_done == 0) {
            $lekerdezes = $connection->prepare("UPDATE tasks SET is_done = 0 WHERE id = ?");
            $lekerdezes->bind_param("i", $task_id);
            $lekerdezes->execute();
        } else {
            $lekerdezes = $connection->prepare("UPDATE tasks SET is_done = 1 WHERE id = ?");
            $lekerdezes->bind_param("i", $task_id);
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