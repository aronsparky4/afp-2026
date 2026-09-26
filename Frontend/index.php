<?php
    require_once "../Backend/db.php";
    require_once "../Backend/auth.php";

    OnlyLoggedIn();

    $lekerdezes = $connection->prepare("SELECT tasks.id, tasks.task, categories.category_name, priorities.priority_name, tasks.is_done FROM tasks 
                                        JOIN categories ON tasks.category_id = categories.id
                                        JOIN priorities ON tasks.priority_id = priorities.id 
                                        WHERE tasks.user_id = ?");
    $lekerdezes->bind_param("i", $_SESSION["user_id"]);
    $lekerdezes->execute();
    $result = $lekerdezes->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>AFP 2026 - Todo Lista</title>
</head>
<body>
    <!-- Teszteléshez -->
    <?= "Üdvözlünk, " . htmlspecialchars($_SESSION["username"]) . "!"; ?>
    <div class="title-box">
        <h1>To-Do Lista</h1>
    </div>
    <div class="input-box">
        <form action="../Backend/addToDo.php" method="POST">
            <input type="text" name="toDoInput" class="toDoInputBox" placeholder="Írj ide valamit...." required>
            <select name="fontossagi" class="fontossagi">
                <option value="5">Rendkívül fontos</option>
                <option value="4">Nagyon fontos</option>
                <option value="3">Fontos</option>
                <option value="2">Nem annyira fontos</option>
                <option value="1">Hanyatló</option>
            </select>
            <select name="category">
                <option value="1">Munka</option>
                <option value="2">Otthon</option>
                <option value="3">Iskola</option>
                <option value="4">Egyéb</option>
            </select>
            <button type="submit">ToDo hozzáadása</button>
        </form>

    </div>
    <div class="ToDoLoad">
        <h1>ToDo-k</h1>
        <?php if(isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
            <p style="color:green">Sikeresen törölted a ToDo-t!</p>
        <?php endif; ?>
        <?php if(!$result->num_rows): ?>
            <p>Nincs még egyetlen ToDo sem. Adj hozzá egyet!</p>
        <?php else: ?>
            <?php foreach($result as $row): ?>
                <div>
                    <input type="checkbox" name="is_done" style="pointer-events: none;" <?= $row['is_done'] ? 'checked' : '' ?>>
                    <span><?= htmlspecialchars($row['task']) ?></span>
                    <span>[<?= htmlspecialchars($row['category_name']) ?>]</span>
                    <span>(<?= htmlspecialchars($row['priority_name']) ?>)</span>
                    <form action="../Backend/isDone.php" method="POST">
                        <input type="hidden" name="is_done" value="<?= $row['is_done'] ? 0 : 1 ?>">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <button type="submit">Késznek jelölés</button>
                    </form>
                    <form action="../Backend/deleteToDo.php" method="POST">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <button type="submit">ToDo törlése</button>
                    </form>
                </div>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="RemoveToDos">
        <form action="../Backend/deleteAllToDo.php" method="POST">
            <button type="submit">Minden ToDo törlése</button>
        </form>
        <br></br>
        <form action="../Backend/deleteDoneToDos.php" method="POST">
            <button type="submit">Elvégzett ToDo-k törlése</button>
        </form>
        <a href="../Backend/logout.php">Kijelentkezés</a>
    </div>
</body>
</html>
