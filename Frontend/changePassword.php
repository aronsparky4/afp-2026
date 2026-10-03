<?php
    require_once "../Backend/db.php";
    require_once "../Backend/auth.php";

    OnlyLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Frontend/other.css">
    <title>Jelszó megváltozatása</title>
</head>
<body>
    <div class="loginBox">
        <h2>Jelszó megváltozatása, <?= htmlspecialchars($_SESSION["username"]) ?></h2>
        <?php if (isset($_GET['error'])): ?>
            <p style="color:red">
                <?php
                    switch ($_GET['error']) {
                        case 'missing':
                            echo "Minden mező kitöltése kötelező.";
                            break;
                        case 'notmatch':
                            echo "Az új jelszó és a megerősítés nem egyezik.";
                            break;
                        case 'invalidlength':
                            echo "A jelszónak legalább 8 és legfeljebb 64 karakter hosszúnak kell lennie.";
                            break;
                        case 'wrongold':
                            echo "A régi jelszó helytelen.";
                            break;
                        case 'error':
                            echo "Hiba történt a jelszó módosítása során. Kérlek próbáld újra.";
                            break;
                        default:
                            echo "Ismeretlen hiba történt.";
                    }
                ?>
            </p>
        <?php endif; ?>
        <form action="../Backend/changePassword.php" method="POST">
            <input type="password" name="old_Password" placeholder="Régi jelszó" minlength="8" maxlength="64" required>
            <input type="password" name="new_Password" placeholder="Új jelszó" minlength="8" maxlength="64" required>
            <input type="password" name="confirm_Password" placeholder="Új jelszó megerősítése" minlength="8" maxlength="64" required>
            <button type="submit">Jelszó módosítása</button>
            <a href="index.php">Vissza a főoldalra</a>
        </form>
    </div>
</body>
</html>