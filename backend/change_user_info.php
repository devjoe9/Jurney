<?php
require_once "../frontend/session_check.php";
require_once "db.php";


if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $query = "SELECT email FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $email = $user['email'];
    } else {
        $email = "Email not found";
    }
} else {
    $email = "Not logged in";
}


function emptyInput($input)
{
    $result = "";
    if (empty($input)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

//------------------------------username change------------------------------------------------

function invalidUsername($new_user)
{
    $result = "";
    if (!preg_match("/^[a-zA-Z0-9]*$/", $new_user)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}


function usernameExists($new_user, $pdo)
{
    $result = "";
    //Get usernames from database
    $sql = "SELECT * FROM users WHERE username LIKE :username";
    $statement = $pdo->prepare($sql);
    $statement->execute([":username" => $new_user]);
    //If username in database return true, else return false 
    if ($statement->fetch(PDO::FETCH_ASSOC)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function changeUser($username, $new_user, $pdo)
{
    $sql = "UPDATE users SET username = :new_user WHERE username = :prev_user;";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':new_user', $new_user, PDO::PARAM_STR);
    $statement->bindParam(':prev_user', $username, PDO::PARAM_STR);
    $statement->execute();

    $_SESSION["username"] = $new_user;
    header("Location: ../frontend/profile.php");
    exit();
}

//------------------------------email change------------------------------------------------

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

function changeEmail($email, $new_email, $pdo)
{
    $sql = "UPDATE users SET email = :new_email WHERE email = :prev_email;";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':new_email', $new_email, PDO::PARAM_STR);
    $statement->bindParam(':prev_email', $email, PDO::PARAM_STR);
    $statement->execute();

    $_SESSION["email"] = $new_email;
    header("Location: ../frontend/profile.php");
    exit();
}


//------------------------------password change------------------------------------------------


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
    if ($password == $password_confirm) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}


$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_username"])) 
{
    $new_user = $_POST["new_username"];

    if (invalidUsername($new_user) || emptyInput($new_user))
    {
        // print error
        $response['error'] = "Please enter a valid username";
    } else {
        if (usernameExists($new_user, $pdo)) {
            // print error
            $response['error'] = "The username exists. Please enter another username";
        } else {
            changeUser($username, $new_user, $pdo);
            $response['error'] = "Username changed successfully!";
        }
    }

    header("Location: ../frontend/profile.php");
    exit();

} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_email"])) 
{
    $new_email = $_POST["new_email"];

    if (invalidEmail($new_email) || emptyInput($new_email)) {
        // print error
        header("Location: ../frontend/profile.php");
    } else {
        changeEmail($email, $new_email, $pdo);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_pswd"])){

    $old_pswd = $_POST["old_pswd"];
    $new_pswd = $_POST["new_pswd"];
    $new_pswd_conf = $_POST["new_pswd_conf"];

    $query = "SELECT password FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $db_pswd = $user['password'];
   

    if(invalidPassword($new_pswd) || emptyInput($new_pswd)){
        echo "Invalid input of password";
        header("Location: ../frontend/profile.php");
    } else {
        if(passwordMatch($new_pswd, $new_pswd_conf) && password_verify($old_pswd, $db_pswd)){
            $hashed_password = password_hash($new_pswd, PASSWORD_BCRYPT);

            $sql = "UPDATE users SET password = :new_pswd WHERE password = :prev_pswd;";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':new_pswd', $hashed_password, PDO::PARAM_STR);
            $statement->bindParam(':prev_pswd', $db_pswd, PDO::PARAM_STR);
            $statement->execute();

            header("Location: ../frontend/profile.php");

        }else{
            echo "No password match, or wrong old password";
            header("Location: ../frontend/profile.php");
        }
    }

}
?>