<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About us</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<nav class="navbar fixed-navbar">
    <?php if (isset($_SESSION["username"])): ?>
        <ul>
            <li><a href="index.php">Home</a></li>
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
            <li><a href="jurney_about.php" class="active">About</a></li>
            <li class="user-profile">
                <a href="profile.php" class="user-profile-link">
                    <div class="profile-pic"></div>
                    <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
            </li>
        </ul>

    <?php else: ?>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="jurney_login.php">Login/Register</a></li>
            <li><a href="jurney_about.php" class="active">About</a></li>
        </ul>
    <?php endif; ?>
</nav>



    <div class="about">
        <section class='about-section'>
        <h3 class="about-title"> About Us </h3>
        <p> Welcome to Jurney, a smart journaling platform designed to help you track your thoughts, emotions, and experiences with purpose. Our mission is to provide a seamless, insightful journaling experience that empowers you to understand your mental and emotional well-being over time.

At Jurney, we combine the art of journaling with the power of semantic analysis to give you a deeper understanding of your entries. Each journal entry you make is saved securely, and we apply advanced semantic analysis to evaluate the content, providing you with a semantic score and semantic label to help you visualize and interpret your emotional trends.
        </p>

        <h3> What We Offer:</h3>
        <p> Purposeful Journaling: Record your thoughts, emotions, and experiences in a safe and private space.
Semantic Analysis: After each entry, we use advanced semantic analysis to assess the content and generate a semantic score and label, giving you insights into your emotional landscape.
Trends and Insights: Track your mood over time and discover patterns in your journaling, helping you gain valuable insights into your mental health and emotional well-being.
        </p>

        <h3> Why Jurney? </h3>
        <p> We believe that journaling is more than just writing; it's a tool for self-reflection and growth. By integrating semantic analysis, we offer a way to track not only what you write but how your emotions and thoughts evolve. Whether you’re journaling for personal development, mental health, or simply to reflect on your day, Jurney is designed to help you journal with purpose.
        </p>
        <div class='about-line'></div>
        <h3>Policies & Legal Information</h3>
        <p> We take your privacy seriously. Below, you’ll find links to our Terms & Conditions, Privacy Policy, and other essential legal information. Please take a moment to review these before using our platform.
            <ul> 
                <li><a href='docs/Terms_Conditions.pdf' target="_blank">Terms & Conditions</a></li>
                <li><a href='docs/Privacy_Policy.pdf' target="_blank">Privacy Policy</a> </li>
                <li><a href='docs/References.pdf' target="_blank">References</a></li>
            </ul> 
        <p>We’re committed to maintaining a safe and secure environment for your personal data and ensuring that your journaling experience remains private.</p>
        </p>
        <div class='about-line'></div>
        </section>
    </div>

    <footer class="footer fixed-footer">
        <p>2024/2025 University of Manchester COMP10120 CM1 Team Project</p>
    </footer>
</body>



</html>