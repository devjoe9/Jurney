<?php
    session_start(); // Ensure session is started
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Sentiment</title>
    <link rel="stylesheet" href="styles.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.0/dist/chartjs-plugin-annotation.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-wordcloud"></script>

    <script>
        function draw_scores_chart(){
            const ctx = document.getElementById('scores_chart');

            fetch("../backend/fetch_journal_scores.php")
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    const datetimes = data.map(entry => entry.date_created);
                    const neg_scores = data.map(entry => -entry.neg_score*100);
                    const pos_scores = data.map(entry => entry.pos_score*100);
                    const comp_scores = data.map(entry => entry.comp_sentiment_score*100);
                    
                    const start_time = new Date(datetimes[0]).getTime();
                    const timestamps = datetimes.map(d => (new Date(d).getTime() - start_time) / 86400000);
                    const jitter_amount = 0.2;
                    const scat_neg_scores = timestamps.map((t, i) => ({ x: t, y: neg_scores[i] }));
                    const scat_pos_scores = timestamps.map((t, i) => ({ x: t, y: pos_scores[i] }));
                    const scat_comp_scores = timestamps.map((t, i) => ({ x: t, y: comp_scores[i] }));

                    //  + (Math.random() - 0.5) * jitter_amount
                    console.log(datetimes);

                    new Chart(ctx, {
                        type: 'scatter',
                        data: {
                            labels: datetimes,
                            datasets: [
                                {
                                    label: "Negative score",
                                    data: scat_neg_scores,
                                    borderWidth: 2,
                                    borderColor: "red",
                                    backgroundColor: "pink",
                                    hidden: true
                                    // barThickness: 30
                                },
                                {
                                    label: 'Positive score',
                                    data: scat_pos_scores,
                                    borderWidth: 2,
                                    borderColor: "green",
                                    backgroundColor: "lightgreen",
                                    hidden: true
                                },
                                {
                                    label: 'Overall score',
                                    data: scat_comp_scores,
                                    borderWidth: 2,
                                    borderColor: "blue",
                                    backgroundColor: "lightblue"
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            animation: {
                                duration: 1000, 
                                easing: 'easeOutBounce',
                                onComplete: function() {
                                    console.log("Chart animation complete!");
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: "Score /%"
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: "Number of days since first journal entry"
                                    },
                                    min: -timestamps[timestamps.length - 1] * 0.05,
                                    max: timestamps[timestamps.length - 1] * 1.05
                                    // categoryPercentage: 0.5,
                                    // barPercentage: 0.5
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: "Your Journal Sentiment Scores Over Time"
                                },
                                annotation: {
                                    annotations: {
                                        line1: {
                                            type: 'line',
                                            yMin: 0,  // Set the position of the line on the y-axis
                                            yMax: 0,  // Same as yMin to make it a horizontal line
                                            borderColor: 'black',  // Color of the line
                                            borderWidth: 2,  // Line width
                                            borderDash: [5,5],
                                            label: {
                                                enabled: true,
                                                position: 'center'
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error("Error occurred when fetching data:", error));
        }
        
    </script>

    <body onload="draw_scores_chart()">
</head>

<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="jurney_journal.php">Start Writing</a></li>
            <li class="dropdown">
                <a href="jurney_score_graph.php" class="dropdown_li active">Your Journey So Far</a>
                <div class="dropdown_content">
                    <a href="jurney_score_graph.php" class="active">Sentiment scores</a>
                    <a href="jurney_wordcloud.php">Wordcloud</a>
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
            <li>
            
            
        </ul>
    </nav>

    <div class="graph">
        <canvas id="scores_chart"></canvas>
    </div>

    <footer class="footer">
        <p>2024/2025 University of Manchester COMP10120 CM1 Team Project</p>
    </footer>
</body>
</html>