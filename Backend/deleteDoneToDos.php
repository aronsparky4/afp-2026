<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];

        $lekerdezes = $connection->prepare("DELETE FROM tasks WHERE user_id = ? AND is_done = 1");
        $lekerdezes->bind_param("i", $user_id);
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