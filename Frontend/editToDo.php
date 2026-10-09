<?php
    require_once '../Backend/db.php';
    require_once '../Backend/auth.php';

    OnlyLoggedIn();

    if($_SERVER["REQUEST_METHOD"] === "POST")
    {
        $task_id = $_POST['task_id'];


        $lekerdezes = $connection->prepare("SELECT * FROM tasks WHERE id = ?");
        $lekerdezes->bind_param("i", $task_id);
        try {
            if ($lekerdezes->execute()) {
                $result = $lekerdezes->get_result();
                if ($result->num_rows > 0) {
                    $task = $result->fetch_assoc();
                } else {
                    header("Location: ../Frontend/index.php?error=error");
                    exit();
                }
            }
        } catch (mysqli_sql_exception $exception) {
            header("Location: ../Frontend/index.php?error=error");
            $hiba = "Adatbázis hiba: " . $exception->getMessage();
        }


    }
        
    
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
        <form action="../Backend/saveEdit.php" method="POST">
            <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']) ?>">
            <input type="text" name="toDoInput" placeholder="Feladat" value="<?= htmlspecialchars($task['task']) ?>" required>
            <br>
            <select name="category">
                <option value="1" <?= $task['category_id'] === 1 ? 'selected' : ''; ?>>Munka</option>
                <option value="2" <?= $task['category_id'] === 2 ? 'selected' : ''; ?>>Otthon</option>
                <option value="3" <?= $task['category_id'] === 3 ? 'selected' : ''; ?>>Iskola</option>
                <option value="4" <?= $task['category_id'] === 4 ? 'selected' : ''; ?>>Egyéb</option>
            </select>
            <br>
            <select name="fontossagi" class="fontossagi" >
                <option value="5" <?= $task['priority_id'] === 5 ? 'selected' : ''; ?>>Rendkívül fontos</option>
                <option value="4" <?= $task['priority_id'] === 4 ? 'selected' : ''; ?>>Nagyon fontos</option>
                <option value="3" <?= $task['priority_id'] === 3 ? 'selected' : ''; ?>>Fontos</option>
                <option value="2" <?= $task['priority_id'] === 2 ? 'selected' : ''; ?>>Nem annyira fontos</option>
                <option value="1" <?= $task['priority_id'] === 1 ? 'selected' : ''; ?>>Hanyatló</option>
            </select>
            <br>
            <button type="submit">Mentés</button>
            <button type="button" onclick="window.location.href='index.php'">Mégse</button>
        </form>
    </div>
</body>
</html>