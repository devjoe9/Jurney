<?php

require_once "db.php";
require_once "login.php";


//Functions
function emptyInputSignup($email, $username, $password, $password_confirm)
{
    $result = "";
    if (empty($email) || empty($username) || empty($password) || empty($password_confirm))
        $result = true;
    else {
        $result = false;
    }
    return $result;
}

function invalidUsername($username)
{
    $result = "";
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidEmail($email)
{
    $result = "";
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = false;
    } else {
        $result = true;
    }
    return $result;
}

function usernameExists($username, $pdo)
{
    $result = "";
    //Get usernames from database
    $sql = "SELECT * FROM users WHERE username LIKE :username";
    $statement = $pdo->prepare($sql);
    $statement->execute([":username" => $username]);
    //If username in database return true, else return false 
    if ($statement->fetch(PDO::FETCH_ASSOC)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidPassword($password)
{
    $result = "";
    //Include special characters here (!, ? etc)
    if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function passwordMatch($password, $password_confirm)
{
    $result = "";
    if ($password !== $password_confirm) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function createUser($email, $username, $password, $pdo)
{
    //Connect to database where we will add the new users details

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    //Put values into database (including HASHED password)
    $sql = "INSERT INTO users (username, password, email) VALUES (:uname, :pswd, :email)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':uname', $username, PDO::PARAM_STR);
    $statement->bindParam(':pswd', $hashed_password, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();

    // Get the user id of new user to store in session
    $user = get_user_by_username($username, $pdo);
    $_SESSION["user_id"] = $user["user_id"];
    $_SESSION["username"] = $user["username"];
    echo($_SESSION["user_id"]);
    echo($_SESSION["username"]);
    //header("location: /jurney_home.html");
    echo ("Worked");
    exit();
}


if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $password_confirm = $_POST["confirm-password"];

    //For the thrown errors add to front end return messages when invalid inputs
    if (emptyInputSignup($email, $username, $password, $password_confirm) !== false) {
        //header("location: /backend_signup.php?error=emptyInput");
        echo ("Error empty");
        exit();
    }
    if (invalidUsername($username) !== false) {
        //header("location: /backend_signup.php?error=invalidusername");
        echo ("Error invalid username");
        exit();
    }

    if (invalidEmail($email) !== false) {
        //header("location: /backend_signup.php?error=invalidemail");
        echo ("Error invalid email");

        exit();
    }
    if (usernameExists($username, $pdo) !== false) {
        //header("location: /backend_signup.php?error=existingusername");
        echo ("Error username exists");
        exit();
    }
    if (invalidPassword($password) !== false) {
        //header("location: /backend_signup.php?error=invalidpassword");
        echo ("Error invalid password");
        exit();
    }
    if (passwordMatch($password, $password_confirm) !== false) {
        //header("location: /backend_signup.php?error=passwordsdontmatch");
        echo ("Error password no match");
        exit();
    }

    createUser($email, $username, $password, $pdo);

} else {
    //header("location: /backend_signup.php");
    echo ("Hi");
    exit();
}