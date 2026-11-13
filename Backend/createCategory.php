<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


        if (!isset($_POST["categoryName"])) {
            Header("Location: ../Frontend/index_v2.php?error=missing");
            exit;
        }
        else {
            $categoryName = trim($_POST["categoryName"]);
            if (empty($categoryName)) {
                Header("Location: ../Frontend/index_v2.php?error=missing");
                exit;
            }
        }
        $lekerdezes = $connection->prepare("SELECT id FROM categories WHERE user_id = ? AND category_name = ?");
        $lekerdezes->bind_param("is", $_SESSION['user_id'], $categoryName);
        $lekerdezes->execute();
        $result = $lekerdezes->get_result();

        if ($result->num_rows > 0) {
            Header("Location: ../Frontend/index_v2.php?error=exists");
            exit;
        } else {
            $lekerdezes = $connection->prepare("INSERT INTO categories (user_id, category_name) VALUES (?, ?)");
            $lekerdezes->bind_param("is", $_SESSION['user_id'], $categoryName);
            try {if ($lekerdezes->execute()) {
                header("Location: ../Frontend/index_v2.php?message=success");
                exit();
                }
            } catch (mysqli_sql_exception $exception) {
                header("Location: ../Frontend/index_v2.php");
                $hiba = "Adatbázis hiba: " . $exception->getMessage();
            }
        }
    }
    Header("Location: ../Frontend/index_v2.php");
    exit;
?>