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
        <form action="../Backend/registration.php" method="POST">
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
            <?php if (isset($_GET['error'])): ?>
                <p style="color:red">
                    <?php
                        switch ($_GET['error']) {
                            case 'missing':
                                echo "Minden mező kitöltése kötelező.";
                                break;
                            case 'invalidemail':
                                echo "Érvénytelen e-mail cím.";
                                break;
                            case 'notmatch':
                                echo "A jelszavak nem egyeznek.";
                                break;
                            case 'invalidlength':
                                echo "A jelszónak legalább 8 és legfeljebb 64 karakter hosszúnak kell lennie.";
                                break;
                            case 'alreadyregistered':
                                echo "Ez az felhasználónév vagy e-mail cím már regisztrált.";
                                break;
                            case 'error':
                                echo "Hiba történt a regisztráció során. Kérlek próbáld újra.";
                                break;
                            default:
                                echo "Ismeretlen hiba történt.";
                        }
                    ?>
                </p>
            <?php endif; ?>
            <input type="submit" name="" value="Regisztráció">
            <br></br>
            <a href="loginform.php">Vissza a bejelentkezés oldalra</a>
        </form>
    </div>
</body>
</html>