<?php
    require_once '../Backend/db.php';
    require_once '../Backend/auth.php';
    OnlyLoggedIn();
    $_SESSION['team_id'] = null;
    $sql = "SELECT team_members.role, teams.team_name, teams.id FROM team_members JOIN teams ON team_members.team_id = teams.id WHERE team_members.user_id = ?";
    $lekerdezes = $connection->prepare($sql);
    $lekerdezes->bind_param("i", $_SESSION['user_id']);
    $lekerdezes->execute();
    $teams = $lekerdezes->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
    body {
      overflow-y: auto;
    }
    .hero {
      max-width: 800px;
      margin: 60px auto 30px auto;
      text-align: center;
      padding: 0 20px;
    }
    .hero h1 {
      font-size: 2.5rem;
      margin-bottom: 12px;
    }
    .hero p {
      color: var(--text-sub);
      font-size: 1.1rem;
      margin-bottom: 24px;
    }
    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      max-width: 900px;
      margin: 0 auto;
      padding: 20px;
    }
    </style>
    <title>Felhasználó dashboard</title>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <h1>Kezdőlap - PHP ToDo Lista</h1>
            <span class="badge">V2</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="changePassword.php" class="btn btn-secondary" style="text-decoration: none;"><span>Bejelentkezve: <?php echo htmlspecialchars($_SESSION['username']); ?></span></a>
            <button id="theme-toggle" class="btn btn-secondary" type="button">Sötét mód</button>
            <a href="../Backend/logout.php" class="btn">Kijelentkezés</a>
        </div>
    </header>
    <section class="hero">
        <h1>ToDo-Lista - PHP & SQL</h1>
        <p>Válassz az opciók közül.</p>
        <a href="index.php" class="btn">Régi ToDo oldalra</a>
        <a class="btn" href="index_v2.php">Új ToDo oldalra</a>
        <a class="btn" onclick="openTeamsModal()">Csapatok</a>
        <a class="btn" id="editUserBtn" onclick="openModal()">Felhasználó módosítása</a>

    </section>

    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Felhasználó módosítása</h3>
                <span class="close" id="closeModal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST" action="../Backend/modifyUser.php">
                    <label for="username">Felhasználónév:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                    <br>
                    <label for="email">Email cím:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
                    <br>
                    <label for="oldPassword">Jelenlegi jelszó:</label>
                    <input type="password" id="oldPassword" name="oldPassword" required>
                    <br>
                    <label for="newPassword">Új jelszó:</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                    <br>
                    <label for="confirmPassword">Jelszó megerősítése:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                    <br>
                    <button type="submit" class="btn">Módosítás</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal-overlay" id="teamsModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Csapatok</h3>
                <span class="close" id="closeTeamsModal">&times;</span>
            </div>
            <div class="modal-body">
                <form action="../Backend/addTeam.php" method="POST">
                    <label for="teamName">Csapat neve:</label>
                    <input type="text" id="teamName" name="teamName" class="form-control" required>
                    <button type="submit" class="btn" id="createTeamBtn" style="margin-top: 10px;">Csapat létrehozása</button>
                </form>

                <br></br>

                <form action="../Backend/joinTeam.php" method="POST">
                    <label for="teamCode">Csatlakozási kód:</label>
                    <input type="text" id="teamCode" name="teamCode" class="form-control" required>
                    <button type="submit" class="btn" id="joinTeamBtn" style="margin-top: 10px;">Csatlakozás</button>
                </form>
                <br></br>
            </div>
            <br>
            <div class="modal-body">
                <h4>Csapatok listája:</h4>
                <?php if ($teams->num_rows > 0): ?>
                    <?php foreach ($teams as $team): ?>
                        <form action="../Frontend/index_v2.php" method="POST">
                        <input type="hidden" name="team_id" value="<?php echo htmlspecialchars($team['id']); ?>">
                        <div class="card" type="submit" onclick="this.closest('form').submit();">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($team['team_name']); ?></h5>
                                <span class="badge" style="margin-top: 8px;">Role: <?php echo htmlspecialchars($team['role']); ?></span>
                            </div>
                        </div>
                        </form>
                        <form action="../Backend/leaveTeam.php" method="POST">
                            <input type="hidden" name="team_id" value="<?php echo htmlspecialchars($team['id']); ?>">
                            <button type="submit" class="btn" id="leaveTeamBtn">Csapat elhagyása</button>
                        </form>
                        <form action="../Backend/deleteTeam.php" method="POST">
                            <input type="hidden" name="team_id" value="<?php echo htmlspecialchars($team['id']); ?>">
                            <button type="submit" class="btn" id="deleteTeamBtn">Csapat törlése</button>
                        </form>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nincs csapatod.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" style="margin-top: 10px;" id="closeModal">Bezárás</button>
            </div>
        </div>
    </div>
<script>
    function openModal() {
        document.getElementById('editModal').classList.add('active');
    }

    document.getElementById('closeModal').onclick = function() {
        document.getElementById('editModal').classList.remove('active');
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('editModal')) {
            document.getElementById('editModal').classList.remove('active');
        }
    }

    function openTeamsModal() {
        document.getElementById('teamsModal').classList.add('active');
    }

    document.getElementById('closeTeamsModal').onclick = function() {
        document.getElementById('teamsModal').classList.remove('active');
    } 

    window.onclick = function(event) {
        if (event.target == document.getElementById('teamsModal')) {
            document.getElementById('teamsModal').classList.remove('active');
        }
    }
</script>
<script src="sitetheme.js"></script>
</body>
</html>