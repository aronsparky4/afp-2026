<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $task_id = $_POST["task_id"];

        $lekerdezes = $connection->prepare("DELETE FROM tasks WHERE id = ?");
        $lekerdezes->bind_param("i", $task_id);
        $lekerdezes->execute();
    }
    Header("Location: ../Frontend/index.php?delete=success");
    exit;
?>