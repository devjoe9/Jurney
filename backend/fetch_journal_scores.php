<?php

require_once "db.php";
session_start();

function get_journal_scores($user_id, $pdo){
    try{
        $sql = "SELECT date_created, comp_sentiment_score, pos_score, neg_score FROM journal_entries WHERE user_id = :user_id ORDER BY date_created";
        $statement = $pdo->prepare($sql);
        $statement->execute([":user_id" => $user_id]);
        return $statement->fetchAll(PDO::FETCH_ASSOC); // associative array
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


$user_id = $_SESSION["user_id"];
$journal_scores = get_journal_scores($user_id, $pdo);

// $datetimes = array_column($journal_scores, "date_created");
// $neg_scores = array_column($journal_scores, "neg_score");
// $pos_scores = array_column($journal_scores, "pos_score");
// $comp_scores = array_column($journal_scores, "comp_sentiment_score");
// echo "</pre>";
// print_r($datetimes);
// echo "</pre>";
header("Content-Type: application/json");
echo json_encode($journal_scores);
?>