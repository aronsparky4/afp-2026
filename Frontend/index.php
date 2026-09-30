<?php
    require_once "../Backend/db.php";
    require_once "../Backend/auth.php";

    OnlyLoggedIn();

    $sql = "SELECT tasks.id, tasks.task, categories.category_name, priorities.priority_name, tasks.is_done FROM tasks 
            JOIN categories ON tasks.category_id = categories.id
            JOIN priorities ON tasks.priority_id = priorities.id 
            WHERE tasks.user_id = ?";
    
    $params = [$_SESSION["user_id"]];
    $types = "i";

    if (!empty($_POST['category_id'])) {
        $sql .= " AND tasks.category_id = ?";
        $params[] = intval($_POST['category_id']);
        $types .= "i";
    }
        
    if (!empty($_POST['is_done'])) {
        $sql .= " AND tasks.is_done = ?";
        $params[] = intval($_POST['is_done']);
        $types .= "i";
    }

    if (!empty($_POST['priority_id'])) {
        $sql .= " AND tasks.priority_id = ?";
        $params[] = intval($_POST['priority_id']);
        $types .= "i";
    }

    $AllowedSorts = [
        'priority_asc' => 'priorities.priority_id ASC',
        'priority_desc' => 'priorities.priority_id DESC',
        'category_asc' => 'categories.category_id ASC',
        'category_desc' => 'categories.category_id DESC',
        'newest' => 'tasks.created_at DESC',
        'oldest' => 'tasks.created_at ASC'
    ];

    $sortBy = $_POST['sort_by'] ?? 'newest';
    if (array_key_exists($sortBy, $AllowedSorts)) {
        $sql .= " ORDER BY " . $AllowedSorts[$sortBy];
    } else {
        $sql .= " ORDER BY tasks.created_at DESC";
    }

    $lekerdezes = $connection->prepare($sql);
    $lekerdezes->bind_param($types, ...$params);
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
    
    <div class="title-box">
        <h1>To-Do Lista</h1>
        <div class="userinfo">
            <h3><?= "Üdvözlünk, " . htmlspecialchars($_SESSION["username"]) . "!"; ?></h3>
            <h3>|<a href="../Backend/logout.php">Kijelentkezés</a></h3>
        </div>
    </div>
    <div class="input-box">
        <?php if(isset($_GET['hiba']) && $_GET['hiba'] == 'empty'): ?>
            <p style="color:red">A ToDo mező nem lehet üres!</p>
        <?php endif; ?>
        <?php if(isset($_GET['hiba']) && $_GET['hiba'] == 'exists'): ?>
            <p style="color:red">Ez a ToDo már létezik!</p>
        <?php endif; ?>
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
    </div>
</body>
</html>
