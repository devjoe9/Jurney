<?php
session_start();
require_once "db.php";

function get_journal_texts($user_id, $pdo){
    try{
        $sql = "SELECT text FROM journal_entries WHERE user_id = :user_id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e){
        echo "Error: " . $e->getMessage();
    }
}

$user_id = $_SESSION["user_id"];
$journal_texts = get_journal_texts($user_id, $pdo);
$separated_texts = array_column($journal_texts, "text");
$combined_texts = implode(" ", $separated_texts);

$input = escapeshellarg($combined_texts);
$output = json_decode(shell_exec("python3 wordfreq.py $input"));

header("Content-Type: application/json");
echo json_encode($output);

?>