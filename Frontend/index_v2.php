<?php
    require_once '../Backend/db.php';
    require_once '../Backend/auth.php';
    OnlyLoggedIn();

    $lekerdezes = $connection->prepare("SELECT categories.id, categories.category_name, COUNT(tasks.id) as task_count FROM categories LEFT JOIN tasks ON categories.id = tasks.category_id WHERE categories.user_id = ? GROUP BY categories.id");
    $lekerdezes->bind_param("i", $_SESSION['user_id']);
    $lekerdezes->execute();
    $categories = $lekerdezes->get_result();
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
    .hero{
        max-width: 800px;
        margin: 60px auto 30px auto;
        text-align: center;
        padding: 0 20px;
    }
    </style>
</head>
<body>
    <header>
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <h1>Dashboard - PHP ToDo Lista</h1>
        <span class="badge">V2</span>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="startpage.php" class="btn btn-secondary" style="text-decoration: none;"><span>Bejelentkezve: <?php echo htmlspecialchars($_SESSION['username']); ?></span></a>
        <a class="btn" onclick="alertPlace()">+ Új kategória</a>
        <a href="../Backend/logout.php" class="btn">Kijelentkezés</a>
    </div>
    </header>
                <!-- Új kategória létrehozása -->
            <form action="../Backend/createCategory.php" method="POST">
                <input type="text" name="categoryName" placeholder="Új kategória neve" required>
                <button type="submit" class="btn">Létrehozás</button>
            </form>
            <form action="../Backend/deleteCategory.php" method="POST">
                 <select name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn">Kategória törlése</button>
            </form>
            <!-- Új prioritás létrehozása -->
            <!--<form action="../Backend/createPriority.php" method="POST">
                <input type="text" name="priorityName" placeholder="Új prioritás neve" required>
                <button type="submit" class="btn">Létrehozás</button>
            </form> -->

            <!-- Új task létrehozása -->
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
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">ToDo hozzáadása</button>
            </form>
    <main class="board">
        <?php if($categories->num_rows === 0): ?>
            <section class="hero">
                <h3>A kezdéshez hozzon létre egy kategóriát!</h3>
            </section>
        <?php endif; ?>
        <?php foreach ($categories as $category): ?>
            <div class="column">
                <div class="column-header">
                    <h2><?php echo htmlspecialchars($category['category_name']); ?></h2>
                    <span class="badge">Sum: <?php echo $category['task_count'] ?? 0 ?></span>
                </div>

                <?php
                $sql = "SELECT tasks.id, tasks.task, categories.category_name, priorities.priority_name, tasks.is_done, categories.id as category_id, priorities.id as priority_id FROM tasks 
                    JOIN categories ON tasks.category_id = categories.id
                    JOIN priorities ON tasks.priority_id = priorities.id 
                    WHERE tasks.user_id = ? AND categories.id = ?";
                $lekerdezes = $connection->prepare($sql);
                $lekerdezes->bind_param("ii", $_SESSION['user_id'], $category['id']);
                $lekerdezes->execute();
                $tasks = $lekerdezes->get_result();
                ?>
                <?php if ($tasks->num_rows === 0): ?>
                    <p>Nincsenek feladatok ebben a kategóriában.</p>
                <?php endif; ?>
                <?php foreach ($tasks as $task): ?>
                    <div class="card">
                        <!-- ToDo Checkbox, késznek van jelölve -->
                        <input type="checkbox" name="is_done" style="pointer-events: none;" <?= $task['is_done'] ? 'checked' : '' ?>>
                        <!-- ToDo szöveg -->
                        <span><?= htmlspecialchars($task['task']) ?></span>
                        <!-- ToDo kategória és fontosság -->
                        <span class="badge" style="margin-top: 8px;">(<?= htmlspecialchars($task['priority_name']) ?>)</span>
                        <!-- ToDo késznek jelölés gomb -->
                        <form action="../Backend/isDone.php" method="POST">
                            <input type="hidden" name="is_done" value="<?= $task['is_done'] ? 0 : 1 ?>">
                            <input type="hidden" name="task_id" value="<?= intval($task['id']) ?>">
                            <button type="submit">Késznek jelölés</button>
                        </form>
                        <!-- ToDo törlés gomb -->
                        <form action="../Backend/deleteToDo.php" method="POST">
                            <input type="hidden" name="task_id" value="<?= intval($task['id']) ?>">
                            <button type="submit">ToDo törlése</button>
                        </form>
                        <form action="../Frontend/editToDo.php" method="post">
                            <input type="hidden" name="task_id" value="<?= intval($task['id']) ?>">
                            <button>ToDo szerkesztése</button>
                        </form>
                        
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </main>
<script>
    function alertPlace() {
        alert("Jelenleg ez a funkció nincs implementálva.");
    }
</script>
</body>
</html>