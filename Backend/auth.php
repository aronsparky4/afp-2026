<?php
    session_start();

    //Is the user logged in, it will return a True or False.
    //Pl: if the user is not logged in, the website can ask if he want to make an account or login.
    function loggedIn()
    {
        return isset($_SESSION["user_id"]);
    }

    //The user can only be on this page if he is logged in with an account, else it can cause trouble with pull requests and the website wont work properly.
    function OnlyLoggedIn()
    {
        if(!loggedIn())
            {
                //This will return the user to the given html page 
                //If we dont have a "login.php" page make one or change it to a html where the user can make an account/log in
                header("Location: loginform.php");
                exit();
            }
    }
?>