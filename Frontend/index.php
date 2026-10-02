<?php
    require_once "../Backend/db.php";
    require_once "../Backend/auth.php";

    OnlyLoggedIn();
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $filterCategory = $_POST['category_id'] ?? null;
        $filterIsDone = $_POST['is_done'] ?? null;
        $filterPriority = $_POST['priority_id'] ?? null;
        $sortBy = $_POST['sort_by'] ?? 'newest';
    } else {
        $filterCategory = null;
        $filterIsDone = null;
        $filterPriority = null;
        if (isset($_GET['sort_by'])) {
            $sortBy = $_GET['sort_by'];
        } else {
            $sortBy = 'newest';
        }
    }
    if (!in_array($sortBy, ['priority_asc', 'priority_desc', 'category_asc', 'category_desc', 'newest', 'oldest'])) {
        $sortBy = 'newest';
    }
    if (!is_null($filterCategory) && !is_numeric($filterCategory) && !in_array($filterCategory, [1, 2, 3, 4])) {
        $filterCategory = null;
    }
    if (!is_null($filterIsDone) && !is_numeric($filterIsDone) && !in_array($filterIsDone, [0, 1])) {
        $filterIsDone = null;
    }
    if (!is_null($filterPriority) && !is_numeric($filterPriority) && !in_array($filterPriority, [1, 2, 3, 4, 5])) {
        $filterPriority = null;
    }

    $sql = "SELECT tasks.id, tasks.task, categories.category_name, priorities.priority_name, tasks.is_done, categories.id as category_id, priorities.id as priority_id FROM tasks 
            JOIN categories ON tasks.category_id = categories.id
            JOIN priorities ON tasks.priority_id = priorities.id 
            WHERE tasks.user_id = ?";
    
    $params = [$_SESSION["user_id"]];
    $types = "i";

    if (!empty($filterCategory)) {
        $sql .= " AND tasks.category_id = ?";
        $params[] = intval($filterCategory);
        $types .= "i";
    }
        
    if (!empty($filterIsDone)) {
        $sql .= " AND tasks.is_done = ?";
        $params[] = intval($filterIsDone);
        $types .= "i";
    }

    if (!empty($filterPriority)) {
        $sql .= " AND tasks.priority_id = ?";
        $params[] = intval($filterPriority);
        $types .= "i";
    }

    $AllowedSorts = [
        'priority_asc' => 'tasks.priority_id ASC',
        'priority_desc' => 'tasks.priority_id DESC',
        'category_asc' => 'tasks.category_id ASC',
        'category_desc' => 'tasks.category_id DESC',
        'newest' => 'tasks.created_at DESC',
        'oldest' => 'tasks.created_at ASC'
    ];

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
            <select id="userOptions" onchange="handleUserOptionChange(this)">
                <option value="">Opciók</option>
                <option value="logOut">Kijelentkezés</option>
                <option value="changePassword">Jelszó módosítása</option>
                <option value="DragDropPage">Drag & Drop oldalra</option>
            </select>
        </div>
    </div>
    <div class="input-box">
        <?php if(isset($_GET['hiba']) && $_GET['hiba'] == 'missing'): ?>
            <p style="color:red">Minden mező kitöltése kötelező!</p>
        <?php endif; ?>
        <?php if(isset($_GET['hiba']) && $_GET['hiba'] == 'empty'): ?>
            <p style="color:red">A ToDo mező nem lehet üres!</p>
        <?php endif; ?>
        <?php if(isset($_GET['hiba']) && $_GET['hiba'] == 'exists'): ?>
            <p style="color:red">Ez a ToDo már létezik!</p>
        <?php endif; ?>
        <?php if(isset($_GET['hiba']) && $_GET['hiba'] == 'invalid'): ?>
            <p style="color:red">Érvénytelen adatok!</p>
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
        <br>
        <form method="POST" action="index.php">
            <select name="sort_by">
                <option value="newest" <?= $sortBy === 'newest' ? 'selected' : ''; ?>>Legújabb</option>
                <option value="oldest" <?= $sortBy === 'oldest' ? 'selected' : ''; ?>>Legrégibb</option>
                <option value="category_desc" <?= $sortBy === 'category_desc' ? 'selected' : ''; ?>>Kategória (desc)</option>
                <option value="category_asc" <?= $sortBy === 'category_asc' ? 'selected' : ''; ?>>Kategória (asc)</option>
                <option value="priority_desc" <?= $sortBy === 'priority_desc' ? 'selected' : ''; ?>>Fontosság (desc)</option>
                <option value="priority_asc" <?= $sortBy === 'priority_asc' ? 'selected' : ''; ?>>Fontosság (asc)</option>
            </select>
            <button type="submit">Rendezés</button>
        </form>
        <br>
        <?php if(isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
            <p style="color:green">Sikeresen törölted a ToDo-t!</p>
        <?php endif; ?>

        <!-- ToDo-k listázása -->
        <?php if(!$result->num_rows): ?>
            <p>Nincs még egyetlen ToDo sem. Adj hozzá egyet!</p>
        <?php else: ?>
            <?php foreach($result as $row): ?>
                <div>
                    <!-- Ha azt akarjuk, hogy a ToDo késznek legyen jelölve, akkor a checkbox legyen bejelölve
                        Így szét lehet választani a kész és a nem kész ToDo-kat egy gyors <?php if($row['is_done']) ?> segítségével, és a felhasználó is láthatja, hogy melyik ToDo van kész állapotban. 
                        Ezt lehet alkalmazni kategóriákra és fontosságra is pl:  <?php if($row['category_id'] === 1) ?>-->
                    <!-- ToDo elem -->
                    <!-- ToDo Checkbox, késznek van jelölve -->
                    <input type="checkbox" name="is_done" style="pointer-events: none;" <?= $row['is_done'] ? 'checked' : '' ?>>
                    <!-- ToDo szöveg -->
                    <span><?= htmlspecialchars($row['task']) ?></span>
                    <!-- ToDo kategória és fontosság -->
                    <span>[<?= htmlspecialchars($row['category_name']) ?>]</span>
                    <span>(<?= htmlspecialchars($row['priority_name']) ?>)</span>
                    <!-- ToDo késznek jelölés gomb -->
                    <form action="../Backend/isDone.php" method="POST">
                        <input type="hidden" name="is_done" value="<?= $row['is_done'] ? 0 : 1 ?>">
                        <input type="hidden" name="task_id" value="<?= intval($row['id']) ?>">
                        <button type="submit">Késznek jelölés</button>
                    </form>
                    <!-- ToDo törlés gomb -->
                    <form action="../Backend/deleteToDo.php" method="POST">
                        <input type="hidden" name="task_id" value="<?= intval($row['id']) ?>">
                        <button type="submit">ToDo törlése</button>
                    </form>
                </div>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="ToDoLoad">
        <h1>Kész ToDo-k</h1>
        <?php foreach($result as $row): ?>
            <?php if($row['is_done']): ?>
                <div>
                    <span><?= htmlspecialchars($row['task']) ?></span>
                    <span>[<?= htmlspecialchars($row['category_name']) ?>]</span>
                    <span>(<?= htmlspecialchars($row['priority_name']) ?>)</span>
                    <form action="../Backend/deleteToDo.php" method="POST">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <button type="submit">ToDo törlése</button>
                    </form>
                </div>
                <br>
            <?php endif; ?>
        <?php endforeach; ?>
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
    <script src="../Frontend/index.js"></script>
</body>
</html>
