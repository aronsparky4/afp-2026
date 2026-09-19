<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $toDo = $_POST["toDoInput"];
        $fontossagi = $_POST["fontossagi"];
        $category = $_POST["category"];

        $lekerdezes = $connection->prepare("INSERT INTO tasks (user_id, task, category, priority) VALUES (?, ?, ?, ?)");
        $lekerdezes->bind_param("isss", $_SESSION['user_id'], $toDo, $category, $fontossagi);
        $lekerdezes->execute();
    }
    Header("Location: ../Frontend/index.php");
    exit;
?>