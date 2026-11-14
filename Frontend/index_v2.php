<?php
    require_once '../Backend/db.php';
    require_once '../Backend/auth.php';
    OnlyLoggedIn();

    if($_SERVER["REQUEST_METHOD"] === "POST")
    {   
        $_SESSION['team_id'] = intval($_POST['team_id']) ?? NULL;
    }

    if (!empty($_SESSION["team_id"])) {
        $lekerdezes = $connection->prepare("SELECT * FROM teams WHERE id = ?");
        $lekerdezes->bind_param("i", $_SESSION["team_id"]);
        $lekerdezes->execute();
        $team = $lekerdezes->get_result()->fetch_assoc();


        $lekerdezes = $connection->prepare("SELECT categories.id, categories.category_name, COUNT(tasks.id) as task_count FROM categories LEFT JOIN tasks ON categories.id = tasks.category_id WHERE categories.team_id = ? GROUP BY categories.id");
        $lekerdezes->bind_param("i", $_SESSION["team_id"]);
        $lekerdezes->execute();
        $categories = $lekerdezes->get_result();
    }
    else
    {
        $lekerdezes = $connection->prepare("SELECT categories.id, categories.category_name, COUNT(tasks.id) as task_count FROM categories LEFT JOIN tasks ON categories.id = tasks.category_id WHERE categories.user_id = ?  AND categories.team_id IS NULL GROUP BY categories.id");
        $lekerdezes->bind_param("i", $_SESSION['user_id']);
        $lekerdezes->execute();
        $categories = $lekerdezes->get_result();
    }

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
    .tag-feature {
        padding: 2px 6px;
        border-radius: 4px;
        align-self: flex-start;
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
        <?php if (isset($team['team_name'])): ?>
            <span class="btn btn-secondary">Team: <?php echo htmlspecialchars($team['team_name']); ?><br>Join Code: <?php echo htmlspecialchars($team['join_code'] ?? ''); ?></span>
        <?php endif; ?>
        <a href="startpage.php" class="btn btn-secondary" style="text-decoration: none;"><span>Bejelentkezve: <?php echo htmlspecialchars($_SESSION['username']); ?></span></a>
        <a class="btn" href="#" onclick="openCategoryModal(event)">+ Új kategória</a>
        <a class="btn" href="#" onclick="openCategoryListModal(event)">Kategória lista</a>
        <a href="../Backend/logout.php" class="btn">Kijelentkezés</a>
    </div>
    </header>
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
    <main class="board">
        <?php if($categories->num_rows === 0): ?>
            <section class="hero">
                <h3>A kezdéshez hozzon létre egy kategóriát!</h3>
                <a class="btn btn-secondary" href="#" onclick="openCategoryModal(event)">Új kategória létrehozása</a>
            </section>
        <?php endif; ?>
        <?php foreach ($categories as $category): ?>
            <div class="column">
                <div class="column-header">
                    <h2><?php echo htmlspecialchars($category['category_name']); ?></h2>
                    <span class="badge">Sum: <?php echo $category['task_count'] ?? 0 ?></span>
                </div>

                <?php
                if (!empty($_SESSION["team_id"])) {
                    $sql = "SELECT tasks.id, tasks.task, categories.category_name, priorities.priority_name, tasks.is_done, categories.id as category_id, priorities.id as priority_id FROM tasks 
                    JOIN categories ON tasks.category_id = categories.id
                    JOIN priorities ON tasks.priority_id = priorities.id 
                    WHERE categories.id = ? AND tasks.team_id = ?";
                    $lekerdezes = $connection->prepare($sql);
                    $lekerdezes->bind_param("ii",$category['id'], $_SESSION['team_id']);
                } else {
                    $sql = "SELECT tasks.id, tasks.task, categories.category_name, priorities.priority_name, tasks.is_done, categories.id as category_id, priorities.id as priority_id FROM tasks 
                    JOIN categories ON tasks.category_id = categories.id
                    JOIN priorities ON tasks.priority_id = priorities.id 
                    WHERE tasks.user_id = ? AND categories.id = ? AND tasks.team_id IS NULL";
                    $lekerdezes = $connection->prepare($sql);
                    $lekerdezes->bind_param("ii", $_SESSION['user_id'], $category['id']);
                }
                $lekerdezes->execute();
                $tasks = $lekerdezes->get_result();
                ?>
                <?php if ($tasks->num_rows === 0): ?>
                    <p>Nincsenek feladatok ebben a kategóriában.</p>
                <?php endif; ?>
                <?php foreach ($tasks as $task): ?>
                    <div class="card" style='margin-bottom: 10px;'>
                        <!-- ToDo Checkbox, késznek van jelölve -->
                        <input type="checkbox" name="is_done" style="pointer-events: none;" <?= $task['is_done'] ? 'checked' : '' ?>>
                        <!-- ToDo szöveg -->
                        <span><?= htmlspecialchars($task['task']) ?></span>
                        <!-- ToDo kategória és fontosság -->
                        <span class="tag tag-feature"><?= htmlspecialchars($task['priority_name']) ?></span>
                        <!-- ToDo késznek jelölés gomb -->
                        <form action="../Backend/isDone.php" method="POST">
                            <input type="hidden" name="is_done" value="<?= $task['is_done'] ? 0 : 1 ?>">
                            <input type="hidden" name="task_id" value="<?= intval($task['id']) ?>">
                            <button type="submit" class="btn btn-secondary">Késznek jelölés</button>
                        </form>
                        <!-- ToDo törlés gomb -->
                        <form action="../Backend/deleteToDo.php" method="POST">
                            <input type="hidden" name="task_id" value="<?= intval($task['id']) ?>">
                            <button type="submit" class="btn btn-secondary">ToDo törlése</button>
                        </form>
                        <form action="../Frontend/editToDo.php" method="post">
                            <input type="hidden" name="task_id" value="<?= intval($task['id']) ?>">
                            <button class="btn btn-secondary">ToDo szerkesztése</button>
                        </form>
                    </div>
                <?php endforeach; ?>
                <a class="btn btn-secondary" onclick="openTodoAddModal(event)" style="margin-top: 10px;">ToDo hozzáadása</a>
            </div>
        <?php endforeach; ?>
    </main>

    <!-- Kategória létrehozás modal -->
    <div class="modal-overlay" id="categoryModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Új kategória létrehozása</h3>
                <span class="close" id="closeCategoryModal">&times;</span>
            </div>
            <form id="taskForm" method="POST" action="../Backend/createCategory.php">
                <label for="categoryName">Kategória neve:</label>
                <input type="text" id="categoryName" name="categoryName" required>
                <button type="submit" class="btn">Létrehozás</button>
            </form>
        </div>
    </div>

    <!-- új todo létrehozása modal -->
    <div class="modal-overlay" id="todoAddModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Új ToDo létrehozása</h3>
            </div>
            <form id="taskForm" method="POST" action="../Backend/addToDo.php">
                <label for="toDoInput">ToDo szövege:</label>
                <input type="text" id="toDoInput" name="toDoInput"  class="form-control" required>
                <br></br>
                <label for="fontossagi">Fontosság:</label>
                <select name="fontossagi" id="fontossagi" class="form-control">
                    <option value="5">Rendkívül fontos</option>
                    <option value="4">Nagyon fontos</option>
                    <option value="3">Fontos</option>
                    <option value="2">Nem annyira fontos</option>
                    <option value="1">Hanyatló</option>
                </select>
                <br></br>
                <label for="category">Kategória:</label>
                <select name="category" id="category" class="form-control" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <br></br>
                <button type="button" class="btn btn-cancel" id="closeTodoAddModal">Mégse</button>
                <button type="submit" class="btn">Létrehozás</button>
            </form>
        </div>
    </div>

    <!-- Kategória lista modal -->
    <div class="modal-overlay" id="categoryListModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Kategória lista</h3>
                <span class="close" id="closeCategoryListModal">&times;</span>
            </div>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li><?php echo htmlspecialchars($category['category_name']); ?> (<?php echo $category['task_count'] ?? 0 ?> feladat)</li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

<script>
    // Placeholder
    function alertPlace() {
        alert("Jelenleg ez a funkció nincs implementálva.");
    }

    // Kategória létrehozása modal
    function openCategoryModal(event) {
        event.preventDefault();
        document.getElementById("categoryModal").classList.add('active');
    }

    document.getElementById("closeCategoryModal").onclick = function() {
        document.getElementById("categoryModal").classList.remove('active');
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById("categoryModal")) {
            document.getElementById("categoryModal").classList.remove('active');
        }
    }

    // Új ToDo létrehozása modal
    function openTodoAddModal(event) {
        event.preventDefault();
        document.getElementById("todoAddModal").classList.add('active');
    }

    document.getElementById("closeTodoAddModal").onclick = function() {
        document.getElementById("todoAddModal").classList.remove('active');
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById("todoAddModal")) {
            document.getElementById("todoAddModal").classList.remove('active');
        }
    }

    // Kategória lista modal
    function openCategoryListModal(event) {
        event.preventDefault();
        document.getElementById("categoryListModal").classList.add('active');
    }

    document.getElementById("closeCategoryListModal").onclick = function() {
        document.getElementById("categoryListModal").classList.remove('active');
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById("categoryListModal")) {
            document.getElementById("categoryListModal").classList.remove('active');
        }
    }
</script>
<script src="sitetheme.js"></script>
</body>
</html>