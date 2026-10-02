<?php
    require_once "db.php";
    require_once "auth.php";

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST["username"] ?? null);
        $email = trim($_POST["email"] ?? null);
        $password = $_POST["password"] ?? null;
        $confirmPassword = $_POST["confirm_password"] ?? null;

        if (is_null($username) || is_null($email) || is_null($password) || is_null($confirmPassword)) {
            header("Location: ../Frontend/registerform.php?error=missing");
        } elseif ($password !== $confirmPassword) {
            header("Location: ../Frontend/registerform.php?error=notmatch");
        } elseif (strlen($password) < 8 || strlen($password) > 64) {
            header("Location: ../Frontend/registerform.php?error=invalidlength");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../Frontend/registerform.php?error=invalidemail");
        } else {

            $lekerdezes = $connection->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $lekerdezes->bind_param("ss", $username, $email);
            $lekerdezes->execute();
            $result = $lekerdezes->get_result();

            if ($result->num_rows > 0) {
                header("Location: ../Frontend/registerform.php?error=alreadyregistered");
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $lekerdezes = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $lekerdezes->bind_param("sss", $username, $email, $hash);
                try {
                    if ($lekerdezes->execute()) {
                        header("Location: ../Frontend/loginform.php?message=success");
                        exit();
                    }
                } catch (mysqli_sql_exception $exception) {
                    header("Location: ../Frontend/registerform.php?error=error");
                    $hiba = "Adatbázis hiba: " . $exception->getMessage();
                }
            }
        }
        
    }
?>
