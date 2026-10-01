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
        <form action="../Backend/changePassword.php" method="POST">
            <input type="password" name="oldPassword" placeholder="Régi jelszó" required>
            <input type="password" name="newPassword" placeholder="Új jelszó" required>
            <input type="password" name="confirmPassword" placeholder="Új jelszó megerősítése" required>
            <button type="submit">Jelszó módosítása</button>
            <a href="index.php">Vissza a főoldalra</a>
        </form>
    </div>
</body>
</html>