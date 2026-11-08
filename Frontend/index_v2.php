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
    <style>
    .card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }
    </style>
</head>
<body>
    <header>
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <h1>Új Lista - ToDo Lista</h1>
        <span class="badge">V2</span>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="startpage.php" class="btn btn-secondary" style="text-decoration: none;"><span>Bejelentkezve: <?php echo htmlspecialchars($_SESSION['username']); ?></span></a>
        <a class="btn">+ Új kategória</a>
        <a href="../Backend/logout.php" class="btn">Kijelentkezés</a>
    </div>
    </header>
    <main class="board">
        <div class="column">
            <div class="column-header">
                <h2>Kategória neve</h2>
                <span class="badge">sum</span>
            </div>
            <div class="card">
                <p>Placeholder szöveg</p>
                <span class="badge" style="margin-top: 8px;">Prioritás ide</span>
            </div>
    </main>
</body>
</html>