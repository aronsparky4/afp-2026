<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $category_id = $_POST["category_id"];

        $lekerdezes = $connection->prepare("DELETE FROM categories WHERE id = ?");
        $lekerdezes->bind_param("i", $category_id);
        try {if ($lekerdezes->execute()) {
            header("Location: ../Frontend/index_v2.php?message=success");
            exit();
            }
        } catch (mysqli_sql_exception $exception) {
            header("Location: ../Frontend/index_v2.php?error=error");
            $hiba = "Adatbázis hiba: " . $exception->getMessage();
        }
    }
    Header("Location: ../Frontend/index_v2.php?delete=success");
    exit;
?>