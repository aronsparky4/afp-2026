<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $task_id = $_POST["task_id"];

        $lekerdezes = $connection->prepare("DELETE FROM tasks WHERE id = ?");
        $lekerdezes->bind_param("i", $task_id);
        try {if ($lekerdezes->execute()) {
            header("Location: ../Frontend/index.php?message=success");
            exit();
            }
        } catch (mysqli_sql_exception $exception) {
            header("Location: ../Frontend/index.php?error=error");
            $hiba = "Adatbázis hiba: " . $exception->getMessage();
        }
    }
    Header("Location: ../Frontend/index.php?delete=success");
    exit;
?>