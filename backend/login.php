<?php

session_start();

require_once "db.php";

// function register_user($username, $password, $pdo){
//     $password = password_hash($password, PASSWORD_BCRYPT);
//     $email = "default@default.com";
//     $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";

//     $statement = $pdo->prepare($sql);
//     $statement->execute([":username" => $username, ":password" => $password, ":email" => $email]);
//     return $pdo->lastInsertId();
// }

function get_user_by_username($username, $pdo)
{
    try {
        $sql = "SELECT * FROM users WHERE username = :username";
        $statement = $pdo->prepare($sql);
        $statement->execute([":username" => $username]);

        $result = $statement->fetch();

        if ($result) {
            return $result;
        } else {
            header("Location: ../frontend/jurney_login.php");
        }

        // return $statement->fetch();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
}

function authenticate_user($username, $password, $pdo)
{
    $user = get_user_by_username($username, $pdo);

    if (password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["username"] = $user["username"];
        header("Location: ../frontend/jurney_journal.php"); // enter page redirect here
        //echo ("LOGGED IN");
        //echo($_SESSION["username"]);
        exit();
    } else {
        header("Location: ../frontend/jurney_login.php");
        //echo ("Incorrect username or password.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    authenticate_user($username, $password, $pdo);
}
?>