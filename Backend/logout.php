<?php
    require_once "auth.php";

    session_destroy();

    header("Location: ../Frontend/loginform.php");
    exit;
?>