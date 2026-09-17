<?php
    require_once "db.php";
    require_once "auth.php";

    OnlyLoggedIn();

    $query = $connection->prepare("SELECT * FROM users WHERE user_id = ?");
    $query->bind_param("i", $_SESSION["user_id"]);
    $query->execute();

    $result = $query->get_result()->fetch_assoc();

    echo json_encode([
        "user_id" => $result["user_id"],
        "username" => $result["username"],
        "email" => $result["email"]
    ]);
?>