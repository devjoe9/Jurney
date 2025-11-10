<?php
require_once "session_check.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Write Journal Entry</title>
  <link rel="stylesheet" href="styles.css">

  <script>
    function analyse_journal(){
      journal_title = document.getElementById("journal_title").value;
      journal_text = document.getElementById("journal_text").value;

      if (!journal_title || !journal_text){
        alert("Please fill out all fields :)")
      }
      else{
        document.getElementById("left_page_body").innerHTML = "Analysing your journal entry..."
        setTimeout(send_journal_to_php(journal_title, journal_text), 3000)
      }
    }

    function send_journal_to_php(journal_title, journal_text){
      form_data = new FormData();
      form_data.append("journal_title", journal_title);
      form_data.append("journal_text", journal_text);

      fetch("../backend/run_python_analysis.php", {
        method: "POST",
        body: form_data
      })
      .then(response => response.json())
      .then(data => {

        const neg_score = data.negative
        const pos_score = data.positive
        const comp_score = data.compound
        const emotion = data.emotion
        const title_exists = data.title_exists

        if(!title_exists){
          document.getElementById("left_page_title").innerHTML = "Your Analysis Results"
          document.getElementById("left_page_body").innerHTML = (
          "Negative sentiment score: "+ (-neg_score*100).toFixed(2) + "%<br>" +
          "Positive sentiment score: "+ (pos_score*100).toFixed(2) + "%<br>" +
          "Overall sentiment score: "+ (comp_score*100).toFixed(2) + "%<br>" +
          "Most prominent emotion: "+ emotion
          )
        }
        else{
          alert("Uh oh! You've already used this title :/ please try a different one")
          document.getElementById("left_page_body").innerHTML = "Type your thoughts and feelings and I will analyse them for you here"
        }
      })
    }

    function generate_prompt(){
      const prompts = [
        // Daily Reflection Prompts
        "What was the best part of today?",
        "What challenges did you face, and how did you handle them?",
        "What is something you learned today?",
        "How did you take care of yourself today?",
        "What emotions did you feel most strongly today?",

        // Self-Discovery Prompts
        "What are three words that describe you?",
        "If you could give your younger self one piece of advice, what would it be?",
        "What values are most important to you?",
        "What motivates you to keep going when things get tough?",
        "How do you define success in your life?",
        "If you could invite anyone to your wedding, who would it be and why is it ChatGPT?",

        // Gratitude Prompts
        "List three things you are grateful for today.",
        "Who has had a positive impact on your life recently?",
        "What is something small that brought you joy today?",
        "What past experience are you grateful for?",
        "How can you express gratitude more often?",

        // Goal-Setting & Productivity Prompts
        "What are three goals you want to accomplish this month?",
        "What is one habit you want to build?",
        "What is something you’ve been procrastinating on, and why?",
        "What steps can you take today to move closer to your long-term goals?",
        "What does your ideal daily routine look like?",

        // Mindfulness & Mental Health Prompts
        "What is something you can let go of that no longer serves you?",
        "How do you recharge when you’re feeling overwhelmed?",
        "What activities make you feel most present in the moment?",
        "What is one limiting belief you want to overcome?",
        "How does your inner voice talk to you? Can it be kinder?",

        // Creativity & Imagination Prompts
        "If you could live in any fictional world, which one would it be and why?",
        "Write about your dream day—where would you go and what would you do?",
        "Describe a character based on someone you saw today.",
        "If you wrote a book, what would it be about?",
        "What song, book, or movie has inspired you recently?",
        "If you were a fortnite location, which one would you be?",

        // Relationships & Social Life Prompts
        "What qualities do you value most in a friend?",
        "How can you improve your relationships with loved ones?",
        "What is the best advice someone has ever given you?",
        "Write about a time someone showed you kindness.",
        "What do you wish people understood about you?",

        "What are three things you’re grateful for today?",
        "Describe a moment today that made you smile.",
        "What’s a challenge you faced today, and how did you handle it?",
        "Write about someone who inspires you and why.",
        "What’s a goal you’re working towards, and what’s your next step?",
        "Describe your perfect day from morning to night.",
        "What’s something new you learned recently?",
        "How do you handle stress, and what helps you relax?",
        "Write about a time when you stepped out of your comfort zone.",
        "What’s a piece of advice you’d give to your future self?",
        "If you could master any skill instantly, what would it be and why?",
        "What’s your favorite memory from childhood?",
        "Describe a recent dream you had.",
        "What are your top three personal values?",
        "What makes you feel the most alive?",
        "If you could have a conversation with any historical figure, who would it be and why?",
        "Write about a time you helped someone or someone helped you.",
        "What do you love most about yourself?",
        "Describe your ideal home and its surroundings.",
        "What’s a book, movie, or song that deeply impacted you?",
        "Write a letter to your future self.",
        "How do you want to be remembered?",
        "What’s one habit you’d like to improve or change?",
        "What emotions have you been experiencing the most lately?",
        "Describe a place you’d love to visit and why."
      ];

      rand_prompt = prompts[Math.floor(Math.random() * prompts.length)]
      document.getElementById("journal_text").placeholder = rand_prompt
    }

    function openPopup(element) {
      let popup = document.getElementById(element);
      let overlay = document.getElementById('popupOverlay');
      overlay.style.display = 'block';
      popup.style.display = 'block';
      setTimeout(() => {
          overlay.classList.add('overlay-active-popup');
          popup.classList.add('popup-active');
      }, 10);
    }
    
    function closePopup(element) {
      let popup = document.getElementById(element);
      let overlay = document.getElementById('popupOverlay');
      popup.classList.remove('popup-active');
      overlay.classList.remove('overlay-active-popup');
      setTimeout(() => {
          popup.style.display = 'none';
          overlay.style.display = 'none';
      }, 300);
    }
  </script>
</head>

<body>
  <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="jurney_journal.php" class="active">Start Writing</a></li>
            <li class="dropdown">
                <a href="jurney_score_graph.php" class="dropdown_li">Your Journey So Far</a>
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
  </nav>

<div class="overlay" id="popupOverlay"></div>

<div class="popup" id="popupHelp">
  <button class="close-btn" onclick="closePopup('popupHelp')">X</button>
  <div>
    When you create a journal, we will display the following metrics:
    <br>
    <br>
    Positive sentiment score - a measure of how much positivity is in your journal, from 0% to 100%
    <br>
    <br>
    Negative sentiment score - a measure of how much negativity is in your journal, from -100% to 0%
    <br>
    <br>
    Overall sentiment score - a measure that combines positive score and negative score, from -100% to 100%.
    <br> It is NOT just a combination of the positive and negative score. To find out more see the <a href="jurney_about.php">about page</a>
    <br>
    <br>
    Most Prominent Emotion - the emotion found most within your journal entry
  </div>
</div>

  <div class="journal-container">
    <div class="book">
      <div class="page left-page">
        <div id="help-container">
          <button class="btn" onclick="openPopup('popupHelp')">?</button>
        </div>
        
        <h2 id="left_page_title">Your Jurney</h2>
        <p id="left_page_body" class="instructions">Type your thoughts and feelings and I will analyse them for you here</p>
        <button onclick="generate_prompt()" class="btn">Stuck? Generate a prompt</button>
        <a href="jurney_journal.php" class="btn">Start again</a>
      </div>

      <div class="page right-page">
        <textarea id="journal_title" class="journal_title" placeholder="Enter a title here..."></textarea>
        <textarea id="journal_text" placeholder="Start writing here..."></textarea>
        <button onclick="analyse_journal()" class="btn">DONE</button>
      </div>
    </div>
  </div>
  
  <footer class="footer">
    <p>2024/2025 University of Manchester COMP10120 CM1 Team Project</p>
  </footer>
</body>

</html>