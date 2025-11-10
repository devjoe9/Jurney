<?php
require_once "session_check.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Journaling Web App</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
<nav class="navbar">
    <?php if (isset($_SESSION["username"])): ?>
        <ul>
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="jurney_journal.php">Start Writing</a></li>
            <li class="dropdown">
                <a href="jurney_score_graph.php">Your Journey So Far</a>
                <div class="dropdown_content">
                    <a href="jurney_score_graph.php">Sentiment scores</a>
                    <a href="jurney_wordcloud.php">Wordcloud</a>
                    <a href="jurney_emotion_radar.php">Emotions Radar</a>
                </div>
            </li>
            <li><a href="jurney_memories.php">View Journals</a></li>
            <li><a href="jurney_about.php">About</a></li>
            <li class="user-profile">
                <a href="profile.php" class="user-profile-link">
                    <div class="profile-pic"></div>
                    <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
            </li>  
        </ul>

    <?php else: ?>
        <ul>
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="jurney_login.php">Login/Register</a></li>
            <li><a href="jurney_about.php">About</a></li>
        </ul>
    <?php endif; ?>
  </nav>

  <header class="header">
    <h1>Welcome to Jurney<?php echo isset($_SESSION["username"]) ? ", " . htmlspecialchars($_SESSION["username"]) . "!" : "!"; ?></h1>
    <p>Reflect, write, and understand your emotions better.</p>
    <ul>
      <li><a href="jurney_login.php" class="btn">Start Your Jurney</a></li>
      <li><a href="jurney_about.php" class="btn">Learn more about Jurney</a></li>
    </ul>
  </header>

  <footer class="footer">
    <p>2024/2025 University of Manchester COMP10120 CM1 Team Project</p>
  </footer>

</body>

</html>