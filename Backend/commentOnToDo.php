<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {



        if (!isset($_POST["comment"]) || !isset($_POST["task_id"])) {
            Header("Location: ../Frontend/index.php?hiba=missing");
            exit;
        }
        else {
            $text = trim($_POST['comment']);
            if (empty($text)) {
                Header("Location: ../Frontend/index.php?hiba=empty");
                exit;
            }
            $task_id = intval($_POST['task_id']);
        }
        
        $lekerdezes = $connection->prepare("SELECT id FROM tasks WHERE user_id = ? AND id = ?");
        $lekerdezes->bind_param("ii", $_SESSION['user_id'], $task_id);
        $lekerdezes->execute();
        $result = $lekerdezes->get_result();

        if ($result->num_rows === 0) {
            Header("Location: ../Frontend/index.php?hiba=invalid");
            exit;
        } else {
            $lekerdezes = $connection->prepare("INSERT INTO comments (user_id, task_id, text) VALUES (?, ?, ?)");
            $lekerdezes->bind_param("iis", $_SESSION['user_id'], $task_id, $text);
            try {if ($lekerdezes->execute()) {
                header("Location: ../Frontend/index.php?test");
                exit();
                }
            } catch (mysqli_sql_exception $exception) {
                header("Location: ../Frontend/index.php?error=error");
                $hiba = "Adatbázis hiba: " . $exception->getMessage();
            }
        }
    }
    Header("Location: ../Frontend/index.php?test2");
    exit;
?>