<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


        $toDo =  trim($_POST["toDoInput"]);
        if (empty($toDo)) {
            Header("Location: ../Frontend/index.php?hiba=empty");
            exit;
        }
        $fontossagi = intval($_POST["fontossagi"]);
        $category = intval($_POST["category"]);

        $lekerdezes = $connection->prepare("SELECT id FROM tasks WHERE user_id = ? AND task = ? and category_id = ? AND priority_id = ?");
        $lekerdezes->bind_param("isii", $_SESSION['user_id'], $toDo, $category, $fontossagi);
        $lekerdezes->execute();
        $result = $lekerdezes->get_result();

        if ($result->num_rows > 0) {
            Header("Location: ../Frontend/index.php?hiba=exists");
            exit;
        } else {
            $lekerdezes = $connection->prepare("INSERT INTO tasks (user_id, task, category_id, priority_id) VALUES (?, ?, ?, ?)");
            $lekerdezes->bind_param("isis", $_SESSION['user_id'], $toDo, $category, $fontossagi);
            $lekerdezes->execute();
        }
    }
    Header("Location: ../Frontend/index.php");
    exit;
?>