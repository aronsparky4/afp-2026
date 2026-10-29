<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AFP 2026 - Bejelentkezés</title>
    <link rel="stylesheet" href="style.css">
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
        <h1>Bejelentkezés</h1>
    </div>
    <div class="board">
        <div class="card">
        <form action="..\Backend\login.php" method="POST">
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
            <input type="text" name="username_or_email" placeholder="Felhasználónév vagy e-mail cím" class="form-control" required>
            <h3>Jelszó</h3>
            <input type="password" name="password" placeholder="Jelszó" minlength="8" maxlength="64" class="form-control" required>
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
            <input type="submit" name="" class="btn" value="Bejelentkezés">
            <a href="registerform.php" class="btn">Regisztráció</a>
        </form>
        </div>
    </div>
</body>
</html>