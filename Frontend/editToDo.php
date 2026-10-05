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
    <title>Document</title>
    <link rel="stylesheet" href="../Frontend/index.css">
</head>
<body>
    <div class="ToDoEdit">
        <h1>ToDo Szerkesztése</h1>
        <form action="../Backend/editToDo.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            <input type="text name="tasks" placeholder="Feladat" value="<?php echo $_GET['task']; ?>" required>
            <input type="priority" name="priority" placeholder="Prioritás" value="<?php echo $_GET['priority']; ?>" required>
            <button type="submit">Mentés</button>
            <button type="button" onclick="window.location.href='index.php'">Mégse</button>
        </form>
    </div>
</body>
</html>