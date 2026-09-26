<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];

        $lekerdezes = $connection->prepare("DELETE FROM tasks WHERE user_id = ?");
        $lekerdezes->bind_param("i", $user_id);
        $lekerdezes->execute();
    }
    Header("Location: ../Frontend/index.php?delete=success");
    exit;
?>