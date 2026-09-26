<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $task_id = $_POST["task_id"];
        $is_done = $_POST["is_done"];

        if ($is_done == 0) {
            $lekerdezes = $connection->prepare("UPDATE tasks SET is_done = 0 WHERE id = ?");
            $lekerdezes->bind_param("i", $task_id);
            $lekerdezes->execute();
        } else {
            $lekerdezes = $connection->prepare("UPDATE tasks SET is_done = 1 WHERE id = ?");
            $lekerdezes->bind_param("i", $task_id);
            $lekerdezes->execute();
        }
    }
    Header("Location: ../Frontend/index.php");
    exit;
?>