<?php
    require_once "../Backend/db.php";
    require_once "../Backend/auth.php";

    if(isset($_GET['signup']) && $_GET['signup'] == 'success'):
        $signup="Sikeres regisztráció! Lépjen be fiókjába.";
    endif;

    if($_SERVER["REQUEST_METHOD"] === "POST")
        {
            $username_or_email = trim(($_POST["username_or_email"] ?? ""));
            $password = trim(($_POST["password"] ?? ""));

            $lekerdezes = $connection->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $lekerdezes->bind_param("ss", $username_or_email, $username_or_email);
            $lekerdezes->execute();

            $result = $lekerdezes->get_result();
            $user = $result->fetch_assoc();

            if($user && password_verify($password, $user["password"]))
            {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];

                header("Location: index.php");
                exit();

            }
            else
            {
                $hiba = "Hibás felhasználónév vagy jelszó.";
            }
        }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AFP 2026 - Bejelentkezés</title>
    <link rel="stylesheet" href="other.css">
</head>
<body>
    <div class="loginBox">
        <form method="POST">
            <h2>Bejelentkezés</h2>
            <p><?= $signup ?? '' ?></p>
            <h3>Felhasználónév vagy e-mail cím</h3>
            <input type="text" name="username_or_email" placeholder="Felhasználónév vagy e-mail cím" required>
            <h3>Jelszó</h3>
            <input type="password" name="password" placeholder="Jelszó" required>
            <br></br>
            <p style="color:red"><?= $hiba ?? '' ?></p>
            <input type="submit" name="" value="Bejelentkezés">
            <a href="registerform.php">Regisztráció</a>
            <!-- <input type="submit" name="" value="Regisztráció"> -->
        </form>
    </div>
</body>
</html>