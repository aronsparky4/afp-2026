<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


        if (!isset($_POST["toDoInput"]) || !isset($_POST["fontossagi"]) || !isset($_POST["category"]) || !isset($_POST["task_id"])) {
            Header("Location: ../Frontend/index.php?hiba=missing");
            exit;
        }
        else {
            $toDo =  trim($_POST["toDoInput"]);
            if (empty($toDo)) {
                Header("Location: ../Frontend/index.php?hiba=empty");
                exit;
            }
            $fontossagi = intval($_POST["fontossagi"]);
            $category = intval($_POST["category"]);
        }

        if (!in_array($fontossagi, [1, 2, 3, 4, 5], true) || !in_array($category, [1, 2, 3, 4], true)) {
            Header("Location: ../Frontend/index.php?hiba=invalid");
            exit;
        }


        $lekerdezes = $connection->prepare("SELECT id FROM tasks WHERE user_id = ? AND task = ? and category_id = ? AND priority_id = ?");
        $lekerdezes->bind_param("isii", $_SESSION['user_id'], $toDo, $category, $fontossagi);
        $lekerdezes->execute();
        $result = $lekerdezes->get_result();

        if ($result->num_rows > 0) {
            Header("Location: ../Frontend/index.php?hiba=exists");
            exit;
        } else {
            $lekerdezes = $connection->prepare("UPDATE tasks SET task = ?, category_id = ?, priority_id = ? WHERE id = ?");
            $lekerdezes->bind_param("siii", $toDo, $category, $fontossagi, $_POST['task_id']);
            try {if ($lekerdezes->execute()) {
                header("Location: ../Frontend/index.php?message=success");
                exit();
                }
            } catch (mysqli_sql_exception $exception) {
                header("Location: ../Frontend/index.php?error=error");
                $hiba = "Adatbázis hiba: " . $exception->getMessage();
            }
        }
    }
    Header("Location: ../Frontend/index.php");
    exit;
?>