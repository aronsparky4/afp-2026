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
            <span class="btn btn-secondary">Csapat: <?php echo htmlspecialchars($team['team_name']); ?></span>
            <span class="btn btn-secondary">Csatlakozási kód: <?php echo htmlspecialchars($team['join_code'] ?? ''); ?></span>
        <?php endif; ?>
        <a href="startpage.php" class="btn btn-secondary" style="text-decoration: none;"><span>Bejelentkezve: <?php echo htmlspecialchars($_SESSION['username']); ?></span></a>
        <a class="btn" href="#" onclick="openCategoryModal(event)">+ Új kategória</a>
        <a class="btn" href="#" onclick="openCategoryListModal(event)">Kategória lista</a>
        <a href="../Backend/logout.php" class="btn">Kijelentkezés</a>
    </div>
    </header>
            <?php if(!empty($_SESSION["team_id"])): ?>
                <?php
                $lekerdezes = $connection->prepare("SELECT users.username, users.id, team_members.role, team_members.team_id, team_members.user_id FROM team_members JOIN users ON team_members.user_id = users.id WHERE team_members.team_id = ?");
                $lekerdezes->bind_param("i", $_SESSION["team_id"]);
                $lekerdezes->execute();
                $members = $lekerdezes->get_result();
                ?>
                <form method="POST" action="../Backend/kickMember.php">
                    <input type="hidden" name="team_id" value="<?php echo htmlspecialchars($_SESSION["team_id"]); ?>">
                    <select name="member_id" required>
                        <?php foreach ($members as $member): ?>
                            <option value="<?php echo htmlspecialchars($member['user_id']); ?>"><?php echo htmlspecialchars($member['username']); ?> (<?php echo htmlspecialchars($member['role']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn" style="font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">Tag kirúgása</button>
                </form>
                <br>
                <form method="POST" action="../Backend/changeRole.php">
                    <input type="hidden" name="team_id" value="<?php echo htmlspecialchars($_SESSION["team_id"]); ?>">
                    <select name="member_id" required>
                        <?php foreach ($members as $member): ?>
                            <option value="<?php echo htmlspecialchars($member['user_id']); ?>"><?php echo htmlspecialchars($member['username']); ?> (<?php echo htmlspecialchars($member['role']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <select name="new_role" required>
                        <option value="admin">Admin</option>
                        <option value="member">Member</option>
                        <option value="viewer">Viewer</option>
                    </select>
                    <button type="submit"  class="btn">Tag szerepkörének módosítása</button>
                </form>
            <?php endif; ?>
            <br>

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
                        <!-- ToDo szöveg -->
                        <span><?= htmlspecialchars($task['task']) ?></span>
                        <!-- ToDo kategória és fontosság -->
                        <span class="tag tag-feature"><?= htmlspecialchars($task['priority_name']) ?></span>
                        <span class="tag tag-feature"><?= $task['is_done'] ? 'Kész' : 'Nincs kész' ?></span>
                        <!-- ToDo késznek jelölés gomb -->
                        <form action="../Backend/isDone.php" method="POST">
                            <input type="hidden" name="is_done" value="<?= $task['is_done'] ? 0 : 1 ?>">
                            <input type="hidden" name="task_id" value="<?= intval($task['id']) ?>">
                            <button type="submit" class="btn btn-secondary" style="font-family: -apple-system, BlinkMacSystemFont, 
                            Segoe UI, Roboto, Helvetica, Arial, sans-serif;">Késznek jelölés</button>
                        </form>
                        <!-- ToDo törlés gomb -->
                        <form action="../Backend/deleteToDo.php" method="POST">
                            <input type="hidden" name="task_id" value="<?= intval($task['id']) ?>">
                            <button type="submit" class="btn btn-secondary" style="font-family: -apple-system, BlinkMacSystemFont, 
                            Segoe UI, Roboto, Helvetica, Arial, sans-serif;">ToDo törlése</button>
                        </form>
                        <form action="../Frontend/editToDo.php" method="post">
                            <input type="hidden" name="task_id" value="<?= intval($task['id']) ?>">
                            <button type="button" class="btn btn-secondary" style="font-family: -apple-system, BlinkMacSystemFont, 
                            Segoe UI, Roboto, Helvetica, Arial, sans-serif;" onclick="openEditTodoModal(<?= intval($task['id']) ?>, <?= htmlspecialchars(json_encode($task['task'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>, <?= intval($task['priority_id']) ?>, <?= intval($task['category_id']) ?>)">ToDo szerkesztése</button>
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
                <input type="text" id="categoryName" name="categoryName"  class="form-control" required>
                <br></br>
                <button type="submit" class="btn" style="font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">Létrehozás</button>
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
                <button type="button" class="btn btn-cancel" id="closeTodoAddModal" style="font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">Mégse</button>
                <button type="submit" class="btn" style="font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">Létrehozás</button>
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
                <?php if ($categories->num_rows === 0): ?>
                    <li>Nincsenek kategóriák.</li>
                <?php endif; ?>
                <?php foreach ($categories as $category): ?>
                    <li><?php echo htmlspecialchars($category['category_name']); ?> (<?php echo $category['task_count'] ?? 0 ?> feladat)</li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- ToDo szerkesztése modal -->
    <div class="modal-overlay" id="editTodoModal">
        <div class="modal">
            <div class="modal-header">
                <h3>ToDo szerkesztése</h3>
                <span class="close" id="closeEditTodoModal">&times;</span>
            </div>
            <form id="editTodoForm" method="POST" action="../Backend/saveEdit.php">
                <input type="hidden" name="task_id" id="editTaskId">
                <label for="editToDoInput">ToDo szövege:</label>
                <input type="text" id="editToDoInput" name="toDoInput" class="form-control" required>
                <br></br>
                <label for="editFontossagi">Fontosság:</label>
                <select name="fontossagi" id="editFontossagi" class="form-control">
                    <option value="5">Rendkívül fontos</option>
                    <option value="4">Nagyon fontos</option>
                    <option value="3">Fontos</option>
                    <option value="2">Nem annyira fontos</option>
                    <option value="1">Hanyatló</option>
                </select>
                <br></br>
                <label for="editCategory">Kategória:</label>
                <select name="category" id="editCategory" class="form-control" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <br></br>
                <button type="button" class="btn btn-cancel" id="closeEditTodoModalBtn" style="font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">Mégse</button>
                <button type="submit" class="btn" style="font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">Mentés</button>
            </form>
        </div>
    </div>
<script src="modalfunctions.js"></script>
<script src="sitetheme.js"></script>
</body>
</html>