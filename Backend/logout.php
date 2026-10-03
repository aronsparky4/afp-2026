<?php
    require_once "auth.php";
    OnlyLoggedIn();
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    if ($_GET['message'] ?? null === 'passwordchanged') {
        header("Location: ../Frontend/loginform.php?message=passwordchanged");
        exit;
    }
    else {
        header("Location: ../Frontend/loginform.php");
        exit;
    }
    exit;
?>