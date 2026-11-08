<?php
    require_once '../Backend/db.php';
    require_once '../Backend/auth.php';
    OnlyLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      max-width: 900px;
      margin: 0 auto;
      padding: 20px;
    }
    </style>
    <title>Felhasználó dashboard</title>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <h1>Kezdőlap - ToDo Lista</h1>
            <span class="badge">V2</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="changePassword.php" class="btn btn-secondary" style="text-decoration: none;"><span>Bejelentkezve: <?php echo htmlspecialchars($_SESSION['username']); ?></span></a>
            <a href="../Backend/logout.php" class="btn">Kijelentkezés</a>
        </div>
    </header>
    <section class="hero">
        <h1>ToDo-Lista - PHP & SQL</h1>
        <p>Válassz az opciók közül.</p>
        <a href="index.php" class="btn">Régi ToDo oldalra</a>
        <a class="btn" href="index_v2.php">Új ToDo oldalra</a>
        <a class="btn" id="editUserBtn" onclick="openModal()">Felhasználó módosítása</a>
    </section>

    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <div class="modal-header">
                <h2>Felhasználó módosítása</h2>
                <span class="close" id="closeModal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST" action="../Backend/changePassword.php">
                    <label for="username">Felhasználónév:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                    <br>
                    <label for="email">Email cím:</label>
                    <!--<input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
                    <label for="currentPassword">Jelenlegi jelszó:</label>-->
                    <input type="password" id="currentPassword" name="currentPassword" required>
                    <br>
                    <label for="newPassword">Új jelszó:</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                    <br>
                    <label for="confirmPassword">Jelszó megerősítése:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                    <br>
                    <button type="submit" class="btn">Módosítás</button>
                </form>
            </div>
        </div>
    </div>

<script>
    function openModal() {
        document.getElementById('editModal').classList.add('active');
    }

    document.getElementById('closeModal').onclick = function() {
        document.getElementById('editModal').classList.remove('active');
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('editModal')) {
            document.getElementById('editModal').classList.remove('active');
        }
    }
</script>
</body>
</html>