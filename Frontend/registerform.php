<?php
    require_once "../Backend/db.php";
    require_once "../Backend/auth.php";

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST["username"] ?? null);
        $email = trim($_POST["email"] ?? null);
        $password = $_POST["password"] ?? null;
        $confirmPassword = $_POST["confirm_password"] ?? null;

        if (is_null($username) || is_null($email) || is_null($password) || is_null($confirmPassword)) {
            $hiba = "Minden mező kitöltése kötelező.";
        } elseif ($password !== $confirmPassword) {
            $hiba = "A jelszavak nem egyeznek.";
        } elseif (strlen($password) < 8 || strlen($password) > 64) {
            $hiba = "A jelszónak legalább 8 és legfeljebb 64 karakter hosszúnak kell lennie.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hiba = "Érvénytelen e-mail cím.";
        } else {

            $lekerdezes = $connection->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $lekerdezes->bind_param("ss", $username, $email);
            $lekerdezes->execute();
            $result = $lekerdezes->get_result();

            if ($result->num_rows > 0) {
                $hiba = "Ez az felhasználónév vagy e-mail cím már regisztrált.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $lekerdezes = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $lekerdezes->bind_param("sss", $username, $email, $hash);
                try {
                    if ($lekerdezes->execute()) {
                        header("Location: ../Frontend/loginform.php?signup=success");
                        exit();
                    }
                } catch (mysqli_sql_exception $exception) {
                    $hiba = "Adatbázis hiba: " . $exception->getMessage();
                }
            }
        }
        
    }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="other.css">
    <title>AFP 2026 -Regisztrációs form</title>
</head>
<body>
    <div class="loginBox">
        <form action="" method="POST">
        <h2>Regisztráció</h2>
        <h3>Felhasználónév</h3>
        <input type="text" name="username" placeholder="Felhasználónév" required>
        <h3>E-mail cím</h3>
        <input type="email" name="email" placeholder="E-mail cím" required>
        <h3>Jelszó</h3>
        <input type="password" name="password" placeholder="Jelszó" minlength="8" maxlength="64" required>
        <h3>Jelszó megerősítése</h3>
        <input type="password" name="confirm_password" placeholder="Jelszó megerősítése" minlength="8" maxlength="64" required>
        <br></br>
        <p style="color:red"><?= $hiba ?? '' ?></p>
        <input type="submit" name="" value="Regisztráció">
        <br></br>
        <a href="loginform.php">Vissza a bejelentkezés oldalra</a>
        <!-- <input type="submit" name="" value="Vissza a bejelentkezés oldalra" > -->
        </form>
    </div>
</body>
</html>