<?php
    session_start(); // Ensure session is started
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your emotions</title>
    <link rel="stylesheet" href="styles.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.0/dist/chartjs-plugin-annotation.min.js"></script>

    <script>
        function draw_emotion_radar(){
            const ctx2 = document.getElementById('emotion_radar').getContext("2d");
            
            fetch("../backend/fetch_journal_emotion_freq.php")
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    const emotions = Object.keys(data)
                    const frequencies = Object.values(data)
                    console.log(emotions)
                    console.log(frequencies)

                    if (!(emotions.includes("sadness"))){
                        emotions.push("sadness")
                        frequencies.push(0)
                    }
                    if (!(emotions.includes("anger"))){
                        emotions.push("anger")
                        frequencies.push(0)
                    }
                    if (!(emotions.includes("fear"))){
                        emotions.push("fear")
                        frequencies.push(0)
                    }
                    if (!(emotions.includes("disgust"))){
                        emotions.push("disgust")
                        frequencies.push(0)
                    }
                    if (!(emotions.includes("joy"))){
                        emotions.push("joy")
                        frequencies.push(0)
                    }

                    new Chart(ctx2, {
                        type: 'radar',
                        data: {
                            labels: emotions,
                            datasets: [
                            {
                                label: "Your most prominent emotions across your journal entires",
                                data: frequencies,
                            },
                            ],
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: true
                                }
                            },
                            elements: {
                                line: {
                                    borderWidth: 3
                                }
                            },
                            scales: {
                                r: {
                                    beginAtZero: true
                                    // min: -1
                                }
                            }
                        }
                    })
                })
            .catch(error => console.error("Error occurred when fetching data:", error));
        };

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.style.width === '250px') {
                sidebar.style.width = '0';
            } else {
                sidebar.style.width = '250px';
            }
        }
    </script>

    <body onload="draw_emotion_radar()">
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
                    <a href="jurney_wordcloud.php">Wordcloud</a>
                    <a href="jurney_emotion_radar.php" class="active">Emotions Radar</a>
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

    <!-- <h1 class="graph_title">Your 50 most frequently used words</h1> -->
    
    <div class="emotion_radar">
        <canvas id="emotion_radar"></canvas>
    </div>

    <footer class="footer">
        <p>2024/2025 University of Manchester COMP10120 CM1 Team Project</p>
    </footer>
</body>
</html>