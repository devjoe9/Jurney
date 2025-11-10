<?php

require_once "db.php";
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the frontend
    $input = escapeshellarg($_POST["journal_text"]);

    // Execute Python script and capture output
    $neg = "neg";
    $neu = "neu";
    $pos = "pos";
    $comp = "compound";

    $negative = shell_exec("python3 analysis.py $input $neg");
    $neutral = shell_exec("python3 analysis.py $input $neu");
    $positive = shell_exec("python3 analysis.py $input $pos");
    $compound = shell_exec("python3 analysis.py $input $comp");
    $emotion_label = shell_exec("python3 emotion_analysis.py $input");
    $title_exists = false;
}

// Check if user logged in
if (isset($_SESSION["user_id"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $text = $_POST["journal_text"];
        $title = $_POST["journal_title"];
        $user_id = $_SESSION["user_id"];
        $date_created = date('Y-m-d H:i:s'); // Current date and time

        // echo $_SESSION["user_id"];

        $title_exists = true;

        // A CHECK WHETHER TITLE ALREADY EXISTS FOR THAT USER
        try {
            $sql = "SELECT title FROM journal_entries WHERE BINARY title=:journal_title";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':journal_title', $title);
            $stmt->execute();

            $has_title = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($has_title) {
                $title_exists = true;
                // FRONT-END: validation messahe 
                // echo "\nPlease enter a different title\n";
            } else {
                $title_exists = false;
                // echo "\nNo entries found.\n";
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }


        // Inserting the journal info into the DB
        // IF title exists, the journal isn't added
        if (!$title_exists) {
            try {
                // Prepare the SQL statement
                $sql = "INSERT INTO journal_entries (user_id, title, text, date_created, comp_sentiment_score, pos_score, neu_score, neg_score, emotion_label) VALUES (:uid, :title, :text, :date, :comp, :pos, :neu, :neg, :emotion)";
                $stmt = $pdo->prepare($sql);
    
                // Bind parameters
                $stmt->bindParam(':uid', $user_id);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':text', $text);
                $stmt->bindParam(':date', $date_created);
                $stmt->bindParam(':comp', $compound);
                $stmt->bindParam(':neg', $negative);
                $stmt->bindParam(':pos', $positive);
                $stmt->bindParam(':neu', $neutral);
                $stmt->bindParam(':emotion', $emotion_label);
    
                // Execute the statement
                $stmt->execute();
    
                // echo "Journal entry successfully added.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
} else {
    echo "User not logged in.";
}

header("Content-Type: application/json");
echo json_encode([
    "negative" => (float)trim($negative),
    "positive" => (float)trim($positive),
    "compound" => (float)trim($compound),
    "emotion" => (string)trim($emotion_label),
    "title_exists" => (boolean)trim($title_exists)
]);

?>