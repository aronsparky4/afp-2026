<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>AFP 2026 -Regisztrációs form</title>
    <style>
        body {
            overflow-y: auto;
        }
        .hero {
            max-width: 800px;
            margin: 60px auto 30px auto;
            text-align: center;
            padding: 0 20px;
        }
        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 12px;
        }
        .hero p {
            color: var(--text-sub);
            font-size: 1.1rem;
            margin-bottom: 24px;
        }
        .board {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .card {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <h1>PHP ToDo Lista</h1>
            <span class="badge">V2</span>
        </div>
    </header>
    <div class="hero">
        <h1>Regisztráció</h1>
    </div>
    <div class="board">
        <div class="card">
        <form action="../Backend/registration.php" method="POST">
            <h3>Felhasználónév</h3>
            <input type="text" name="username" placeholder="Felhasználónév" class="form-control" required>
            <h3>E-mail cím</h3>
            <input type="email" name="email" placeholder="E-mail cím" class="form-control" required>
            <h3>Jelszó</h3>
            <input type="password" name="password" placeholder="Jelszó" minlength="8" maxlength="64" class="form-control" required>
            <h3>Jelszó megerősítése</h3>
            <input type="password" name="confirm_password" placeholder="Jelszó megerősítése" minlength="8" maxlength="64" class="form-control" required>
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
            <input type="submit" name="" value="Regisztráció" class="btn">
            <br></br>
            <a href="loginform.php" class="btn">Vissza a bejelentkezés oldalra</a>
        </form>
        </div>
    </div>
</body>
</html>