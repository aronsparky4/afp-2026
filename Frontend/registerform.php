<?php
    require_once "db.php";
    require_once "auth.php";

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST["username"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = trim($_POST["password"] ?? "");
        $confirmPassword = trim($_POST["confirm_password"] ?? "");

        if ($username === "" || $email === "" || $password === "" || $confirmPassword === "") {
            $hiba = "Minden mező kitöltése kötelező.";
        } elseif ($password !== $confirmPassword) {
            $hiba = "A jelszavak nem egyeznek.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $lekerdezes = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $lekerdezes->bind_param("sss", $username, $email, $hash);
            try {
                if ($lekerdezes->execute()) {
                    header("Location: login.php?signup=success");
                    exit();
                }
            } catch (mysqli_sql_exception $exception) {
                $hiba = "Adatbázis hiba: " . $exception->getMessage();
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
        <input type="password" name="password" placeholder="Jelszó" required>
        <h3>Jelszó megerősítése</h3>
        <input type="password" name="confirm_password" placeholder="Jelszó megerősítése" required>
        <br></br>
        <p style="color:red"><?= $hiba ?? '' ?></p>
        <br><br>
        <button type="submit">Regisztráció</button>
        <br></br>
        <a href="loginform.html">Vissza a bejelentkezés oldalra</a>
        </form>
    </div>
</body>
</html>