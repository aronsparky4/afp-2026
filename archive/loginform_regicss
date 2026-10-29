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

        <form action="..\Backend\login.php" method="POST">
            <h2>Bejelentkezés</h2>
            <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
                <p style="color:green">
                    Sikeres regisztráció! Lépjen be fiókjába.
                </p>
            <?php endif; ?>
            <?php if (isset($_GET['message']) && $_GET['message'] === 'passwordchanged'): ?>
                <p style="color:green">
                    A jelszó sikeresen megváltozott. Kérlek jelentkezz be újra.
                </p>
            <?php endif; ?>
            <h3>Felhasználónév vagy e-mail cím</h3>
            <input type="text" name="username_or_email" placeholder="Felhasználónév vagy e-mail cím" required>
            <h3>Jelszó</h3>
            <input type="password" name="password" placeholder="Jelszó" minlength="8" maxlength="64" required>
            <br></br>
            <?php if (isset($_GET['error'])): ?>
                <p style="color:red">
                    <?php
                        switch ($_GET['error']) {
                            case 'missing':
                                echo "Minden mező kitöltése kötelező.";
                                break;
                            case 'invalidcredentials':
                                echo "Hibás felhasználónév vagy jelszó.";
                                break;
                            case 'error':
                                echo "Hiba történt a bejelentkezés során. Kérlek próbáld újra.";
                                break;
                            default:
                                echo "Ismeretlen hiba történt.";
                        }
                    ?>
                </p>
            <?php endif; ?>
            <input type="submit" name="" value="Bejelentkezés">
            <a href="registerform.php">Regisztráció</a>
        </form>
    </div>
</body>
</html>