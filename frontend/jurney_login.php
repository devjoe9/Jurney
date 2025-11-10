<?php
  session_start();

  if (isset($_SESSION["user_id"])) {
    header("Location: jurney_journal.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Write Journal Entry</title>
  <link rel="stylesheet" href="styles.css">

  <script>

  </script>
</head>

<body>
  <nav class="navbar">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="jurney_login.php" class="active">Login/Register</a></li>
      <!-- <li><a href="jurney_about.php" class="active">About</a></li>
      <li>
                <label class="theme-switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider round"></span>
                </label>
            </li> -->
      <li><a href="jurney_about.php">About</a></li>
    </ul>
  </nav>

  <div class="journal-container">
    <form class="page front-cover" action="../backend/login.php" method="post">
      <h1>Continue Your Jurney</h1>
      <input id="username" name="username" class="login_entry_box" placeholder="Username" type="text" required>
      <input id="password" name="password" class="login_entry_box" placeholder="Password" type="password" required>
      <button type="submit" class="btn">Log in</button>
      <a id="or_register" href="jurney_register.php">Or click here to Register</a>
    </form>
  </div>

  <footer class="footer">
    <p>2024/2025 University of Manchester COMP10120 CM1 Team Project</p>
  </footer>
</body>

</html>