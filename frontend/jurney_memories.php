<?php
require_once "session_check.php";
require_once "../backend/db.php";

// if (!isset($_SESSION['user_id'])) {
//     header('Location: index.php');
//     exit();
// }

try {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT title, text, comp_sentiment_score, pos_score, neg_score, date_created, emotion_label FROM journal_entries WHERE user_id = :user_id ORDER BY date_created DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":user_id" => $user_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("An error occurred while fetching your journals. Please try again later.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memories</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
  <nav class="navbar">
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
        <li><a href="jurney_memories.php" class="active">View Journals</a></li>
        <li><a href="jurney_about.php">About</a></li>
        <li class="user-profile">
            <a href="profile.php" class="user-profile-link">
                <div class="profile-pic"></div>
                <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </a>
        </li>
        
       
      </ul>
    </nav>

    <h1 class="journal-title">Your Journals</h1>
    <hr class="custom-hr">
    <div class="journal-container">
        <?php if (!empty($result)): ?>
            <div class="journal-grid">
                <?php foreach ($result as $index => $journal): ?>
                    <div class="journal-tile" onclick="openModal(<?php echo $index; ?>)">
                        <div class="journal-entry-title"><?php echo htmlspecialchars($journal['title']); ?></div>
                        <?php if (isset($journal['comp_sentiment_score'])): ?>
                            <div class="sentiment-score">Overall sentiment score: <?php echo ($journal['comp_sentiment_score']*100).'%'; ?></div>
                            <div class="sentiment-score">Most prominent emotion: <?php echo $journal['emotion_label']; ?></div>
                        <?php endif; ?>
                        <div class="journal-date"><?php echo htmlspecialchars($journal['date_created']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-journals">No journals to display yet</p>
        <?php endif; ?>
    </div>

    <div id="journalModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">×</span>
            <div class="modal-left">
                <h3>Journal Analysis</h3>
                <p id="modal-sentiment"></p>
            </div>
            <div class="modal-right">
                <h2 id="modal-title"></h2>
                <p id="modal-content"></p>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>2024/2025 University of Manchester COMP10120 CM1 Team Project</p>
    </footer>

    <script>
        const journals = <?php echo json_encode($result); ?>;

        function openModal(index) {
            const journal = journals[index];
            document.getElementById('modal-title').textContent = journal.title;
            document.getElementById('modal-content').textContent = journal.text;
            document.getElementById('modal-sentiment').innerHTML = (
                "<br>Negative sentiment score: "+ (-journal.neg_score*100).toFixed(2) + "%<br><br>" +
                "Positive sentiment score: "+ (journal.pos_score*100).toFixed(2) + "%<br><br>" +
                "Overall sentiment score: "+ (journal.comp_sentiment_score*100).toFixed(2) + "%<br><br>" +
                "Most prominent emotion: "+ journal.emotion_label
                ) || 'Not available';
            document.getElementById('journalModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('journalModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('journalModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const themeToggle = document.getElementById('theme-toggle');
            const savedTheme = localStorage.getItem('theme');

            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
                themeToggle.checked = true;
            }

            themeToggle.addEventListener('change', () => {
                document.body.classList.toggle('dark-theme');
                const theme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
            });
        });
    </script>
</body>
</html>