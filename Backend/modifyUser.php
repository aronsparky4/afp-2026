<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $oldPassword = $_POST["oldPassword"] ?? null;
        $newPassword = $_POST["newPassword"] ?? null;
        $confirmPassword = $_POST["confirmPassword"] ?? null;
        $username = $_POST["username"] ?? null;
        $email = $_POST["email"] ?? null;


        if (is_null($oldPassword) || is_null($newPassword) || is_null($confirmPassword) || is_null($username) || is_null($email)) {
            Header("Location: ../Frontend/changePassword.php?error=missing");
            exit;
        } elseif ($newPassword !== $confirmPassword) {
            Header("Location: ../Frontend/changePassword.php?error=notmatch");
            exit;
        } elseif (strlen($newPassword) < 8 || strlen($newPassword) > 64) {
            Header("Location: ../Frontend/changePassword.php?error=invalidlength");
            exit;
        }



        $lekerdezes = $connection->prepare("SELECT id FROM users WHERE email = ?");
        $lekerdezes->bind_param("s", $email);
        $lekerdezes->execute();
        $emails = $lekerdezes->get_result();

        $lekerdezes = $connection->prepare("SELECT id FROM users WHERE username = ?");
        $lekerdezes->bind_param("s", $username);
        $lekerdezes->execute();
        $usernames = $lekerdezes->get_result();

        if ($emails->num_rows > 0 && $email !== $_SESSION['email'] || $usernames->num_rows > 0 && $username !== $_SESSION['username']) {
            header("Location: ../Frontend/registerform.php?error=error");
        }
        else {
            $lekerdezes = $connection->prepare("SELECT password FROM users WHERE id = ?");
            $lekerdezes->bind_param("i", $_SESSION['user_id']);
            $lekerdezes->execute();
            $result = $lekerdezes->get_result();
            $user = $result->fetch_assoc();

            if (!password_verify($oldPassword, $user['password'])) {
                Header("Location: ../Frontend/changePassword.php?error=wrongold");
                exit;
            }
            else{
                
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $lekerdezes = $connection->prepare("UPDATE users SET password = ?, username = ?, email = ? WHERE id = ?");
                $lekerdezes->bind_param("sssi", $hash, $username, $email, $_SESSION['user_id']);
                try {
                    if ($lekerdezes->execute()) {
                        $_SESSION["username"] = $username;
                        $_SESSION["email"] = $email;
                        header("Location: ../Frontend/startpage.php?message=success");
                        exit();
                    }
                } catch (mysqli_sql_exception $exception) {
                    header("Location: ../Frontend/changePassword.php?error=error");
                    $hiba = "Adatbázis hiba: " . $exception->getMessage();
                }
            }
        }
    }
?>