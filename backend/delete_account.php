<?php 

require_once "db.php";
require_once "../frontend/session_check.php";

$id = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql_journal = "DELETE FROM journal_entries WHERE user_id=:u_id";
    $sql_user = "DELETE FROM users WHERE user_id=:u_id";
    
    $statement = $pdo->prepare($sql_journal);
    $statement->bindParam(':u_id', $id, PDO::PARAM_STR);
    $statement->execute();

    $statement = $pdo->prepare($sql_user);
    $statement->bindParam(':u_id', $id, PDO::PARAM_STR);
    $statement->execute();

    session_destroy();
    header("Location: ../frontend/index.php");
    exit();
}

?>