<?php
    require_once "db.php";
    require_once "auth.php";

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