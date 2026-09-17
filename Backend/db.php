<?php
    //This PHP code cennects the website to the Database.
    //Hostname = localhost
    //Username = Username for youre mysql pl: root
    //Password = Null, if you have a password for your mysql write that
    //Database = ToDoList
    require_once "config.php";
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if($connection->connect_error)
        {
            die("Connection failed: " . $connection->connect_error);
        }
    $connection->set_charset("utf8mb4")
?>