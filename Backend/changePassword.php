<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $oldPassword = $_POST["old_Password"] ?? null;
        $newPassword = $_POST["new_Password"] ?? null;
        $confirmPassword = $_POST["confirm_Password"] ?? null;

        if (is_null($oldPassword) || is_null($newPassword) || is_null($confirmPassword)) {
            Header("Location: ../Frontend/changePassword.php?error=missing");
            exit;
        } elseif ($newPassword !== $confirmPassword) {
            Header("Location: ../Frontend/changePassword.php?error=notmatch");
            exit;
        } elseif (strlen($newPassword) < 8 || strlen($newPassword) > 64) {
            Header("Location: ../Frontend/changePassword.php?error=invalidlength");
            exit;
        }

        $lekerdezes = $connection->prepare("SELECT password FROM users WHERE id = ?");
        $lekerdezes->bind_param("i", $_SESSION['user_id']);
        $lekerdezes->execute();
        $result = $lekerdezes->get_result();
        $user = $result->fetch_assoc();

        if (!password_verify($oldPassword, $user['password'])) {
            Header("Location: ../Frontend/changePassword.php?error=wrongold");
            exit;
        }

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $lekerdezes = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
        $lekerdezes->bind_param("si", $hash, $_SESSION['user_id']);
        try {if ($lekerdezes->execute()) {
            header("Location: logout.php?message=passwordchanged");
            exit();
            }
        } catch (mysqli_sql_exception $exception) {
            header("Location: ../Frontend/changePassword.php?error=error");
            $hiba = "Adatbázis hiba: " . $exception->getMessage();
        }
    }

?>