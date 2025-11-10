<?php
require_once "session_check.php";

session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php'); // Redirection to index
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="jurney_journal.php">Start Writing</a></li>
            <li><a href="jurney_jurney.php">Your Journey So Far</a></li>
            <l1><a href="jurney_memories.php">Memories</a></l1>
            <li>
                <label class="theme-switch">
                <input type="checkbox" id="theme-toggle">
                <span class="slider round"></span>
                </label>
            </li>
            <li><a href="jurney_about.php" class="active">About</a></li>
        </ul>
    </nav>
</body>
</html>