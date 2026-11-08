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
    <title>AFP 2026 - ToDo oldal (új)</title>
</head>
<body>
    <header>
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <h1>Kedzőlap - ToDo Lista</h1>
        <span class="badge">V2</span>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="startpage.php" class="btn btn-secondary" style="text-decoration: none;"><span>Bejelentkezve: <?php echo htmlspecialchars($_SESSION['username']); ?></span></a>
        <a href="../Backend/logout.php" class="btn">Kijelentkezés</a>
    </div>
    </header>
</body>
</html>