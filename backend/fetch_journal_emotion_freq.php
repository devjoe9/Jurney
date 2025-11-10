<?php 
session_start();
require_once "db.php";

function get_journal_emotions($user_id, $pdo){
    try{
        $sql = "SELECT emotion_label FROM journal_entries WHERE user_id = :user_id AND emotion_label IS NOT NULL";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e){
        echo "Error: " . $e->getMessage();
    }
}

$user_id = $_SESSION["user_id"];
$journal_emotions = get_journal_emotions($user_id, $pdo);
$separated_emotions = array_column($journal_emotions, "emotion_label");
$combined_emotions = implode(" ", $separated_emotions);

$input = escapeshellarg($combined_emotions);
$output = json_decode(shell_exec("python3 wordfreq.py $input"));

header("Content-Type: application/json");
echo json_encode($output);
?>