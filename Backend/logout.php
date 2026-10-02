<?php
    require_once "auth.php";
    if ($_GET['message'] ?? null === 'passwordchanged') {
        session_destroy();
        header("Location: ../Frontend/loginform.php?message=passwordchanged");
        exit;
    }
    else {
        session_destroy();
        header("Location: ../Frontend/loginform.php");
        exit;
    }
    exit;
?>