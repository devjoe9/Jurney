<?php
    session_start();


    if (isset($_SESSION["user_id"])) {
        header("Location: jurney_register.php");
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
            <li><a href="jurney_about.php">About</a></li>
        </ul>
    </nav>

    <div class="journal-container">
        <form class="page front-cover" action="../backend/register.php" method="post">
            <h1>Start Your Jurney</h1>
            <input id="email" name="email" class="login_entry_box" placeholder="Email" type="email" required
                pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
            <input id="username" name="username" class="login_entry_box" placeholder="Username" type="text" required>
            <input id="password" name="password" class="login_entry_box" placeholder="Password" type="password"
                required>
            <input id="confirm-password" name="confirm-password" class="login_entry_box" placeholder="Confirm Password"
                type="password" required>
            <label for="terms" class="login_checkbox_container">
                <p id="tnc_line">I agree to the <a id="tnc_link" href="docs/Terms_Conditions.pdf">Terms & Conditions</a></p>
                <input id="terms" name="terms" type="checkbox" required>
            </label>
            <label for="terms" class="login_checkbox_container">
                <p id="tnc_line">I agree that I am above the age of 18 years</p>
                <input id="terms" name="terms" type="checkbox" required>
            </label>
            <button id="register_btn" type="submit" class="btn" name="submit">Register</button>
            <a id="or_login" href="jurney_login.php">Or click here to Log In</a>
        </form>
    </div>

    <footer class="footer">
        <p>2024/2025 University of Manchester COMP10120 CM1 Team Project</p>
    </footer>
</body>

</html>