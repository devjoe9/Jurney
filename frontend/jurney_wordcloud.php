<?php
    session_start(); // Ensure session is started
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your wordcloud</title>
    <link rel="stylesheet" href="styles.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.0/dist/chartjs-plugin-annotation.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-wordcloud"></script>

    <script>
        function draw_wordcloud(){
            // const ctx2 = document.getElementById('wordcloud50').getContext("2d");
            const ctx3 = document.getElementById('wordcloud').getContext("2d");
            
            fetch("../backend/fetch_journal_wordfreq.php")
                .then(response => response.json())
                .then(data => {

                    const words = Object.keys(data)
                    const frequencies = Object.values(data)
                    const max_freq = Math.max(...frequencies)
                    const scaled_freqs = frequencies.map(freq => (freq / max_freq) * 125)

                    new Chart(ctx3, {
                        type: 'wordCloud',
                        data: {
                            labels: words,
                            datasets: [
                            {
                                label: "Your most frequently used words",
                                data: scaled_freqs,
                            },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            const index = tooltipItem.dataIndex;
                                            return `Frequency: ${frequencies[index]}`;
                                        }
                                    }
                                }
                            }
                        }
                    })

                })
            .catch(error => console.error("Error occurred when fetching data:", error));
        };
        
    </script>

    <body onload="draw_wordcloud()">
</head>

<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="jurney_journal.php">Start Writing</a></li>
            <li class="dropdown">
                <a href="jurney_score_graph.php" class="dropdown_li active">Your Journey So Far</a>
                <div class="dropdown_content">
                    <a href="jurney_score_graph.php">Sentiment scores</a>
                    <a href="jurney_wordcloud.php" class="active">Wordcloud</a>
                    <a href="jurney_emotion_radar.php">Emotions radar</a>
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
    </nav>

    <h1 class="graph_title">Frequencies of all your used words</h1>

    <div class="wordcloud">
        <canvas id="wordcloud"></canvas>
    </div>

    <footer class="footer">
        <p>2024/2025 University of Manchester COMP10120 CM1 Team Project</p>
    </footer>
</body>
</html>