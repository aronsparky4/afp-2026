<?php
    require_once "db.php";
    require_once "auth.php";

    if($_SERVER["REQUEST_METHOD"] === "POST")
        {
            $username_or_email = trim(($_POST["username_or_email"] ?? ""));
            $password = ($_POST["password"] ?? "");

            $lekerdezes = $connection->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $lekerdezes->bind_param("ss", $username_or_email, $username_or_email);
            try {if ($lekerdezes->execute()) {
                $result = $lekerdezes->get_result();
                $user = $result->fetch_assoc();

                if($user && password_verify($password, $user["password"]))
                {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];

                    header("Location: ../Frontend/index.php");
                    exit();

                }
                else
                {
                    header("Location: ../Frontend/loginform.php?error=invalidcredentials");
                }
                }
            } catch (mysqli_sql_exception $exception) {
                header("Location: ../Frontend/loginform.php?error=error");
                $hiba = "Adatbázis hiba: " . $exception->getMessage();
            }


        }
    
?>
