<?php
require_once "session_check.php";
require_once "../backend/db.php";



if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $query = "SELECT email FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $email = $user['email'];
    } else {
        $email = "Email not found";
    }
} else {
    $email = "Not logged in";
}

// Store session messages in variables to use in JavaScript
$errorMessage = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : '';

// Clear session messages after they are displayed
unset($_SESSION['error']);
unset($_SESSION['success']);
?>

<script>
    // Function to show alerts for error and success
    window.onload = function() {
        var errorMessage = "<?php echo addslashes($errorMessage); ?>";
        var successMessage = "<?php echo addslashes($successMessage); ?>";

        // Show the error alert if there is an error
        if (errorMessage) {
            alert(errorMessage);
        }

        // Show the success alert if there is success
        if (successMessage) {
            alert(successMessage);
        }
    };
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
            <li><a href="jurney_memories.php">View Journals</a></li>
            <li><a href="jurney_about.php">About</a></li>
            <li>
                <a href="profile.php" class="active">
                    <div class="profile-pic"></div>
                    <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
            </li>
        </ul>
</nav>

    <h1 class="journal-title profile-title">Your Profile</h1>
    <hr class="custom-hr">

<div class="profile-page">
    <section class="profile-section">
        <h2>User Information</h2>
        <table>
            <tr>
                <td><p>Username: <?php echo htmlspecialchars($_SESSION['username']); ?></p></td>
                <td><button onclick="openPopup('popupUser')">Change Username</button> </td>
            </tr>
            <tr>
                <td><p>Email: <?php echo htmlspecialchars($email); ?></p></td>
                <td><button onclick="openPopup('popupEmail')">Change Email</button> </td>
            </tr>
            <tr>
                <td><p>Password: ********** </p></td>
                <td>
                    <!-- <form action="../backend/reset_password.php" method="post" onsubmit="return showEmailSentAlert();">  
                        <button onclick="submit">Change Password</button> 
                    </form> -->
                    <button onclick="openPopup('popupPassword')">Change Password</button> 
                </td>
            </tr>

            <script>
                function showEmailSentAlert(){
                    alert("Email sent!");
                    return true;
                }
            </script>        
        </table>
        <h2>User Data</h2>
        <table>
            <tr>
                <td><p>Number of Journals: </p></td>
                <td><p>
                <?php
                try {
                    if (isset($_SESSION['user_id'])) { // Check if user_id is set
                        $userid = $_SESSION['user_id'];
                        $sql = "SELECT COUNT(user_id) FROM journal_entries WHERE user_id = :user_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':user_id', $userid, PDO::PARAM_INT);
                        $stmt->execute();
                        $count = $stmt->fetchColumn();
                        print $count;
                    } else {
                        echo "User ID not found in session."; // Handle case where user_id is not set
                    }

                } catch (PDOException $e) {
                    error_log("Database error: " . $e->getMessage());
                    die("An error occurred while fetching your journals. Please try again later.");
                }
                ?>
                </p> </td>
            </tr>
            <tr>
                <td><p>Days Journalling: </p></td>
                <td><p>
                <?php
                try {
                    if (isset($_SESSION['user_id'])) { // Check if user_id is set
                        $userid = $_SESSION['user_id'];
                        $sql = "SELECT DATEDIFF(MAX(date_created), MIN(date_created)) AS date_difference FROM journal_entries WHERE user_id = :user_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':user_id', $userid, PDO::PARAM_INT);
                        $stmt->execute();
                        $date_difference = $stmt->fetchColumn();
                        if ($date_difference == NULL){
                            print '0';
                        }
                        else{
                            print $date_difference;
                        }
                    } else {
                        echo "User ID not found in session."; // Handle case where user_id is not set
                    }
                } catch (PDOException $e) {
                    error_log("Database error: " . $e->getMessage());
                    die("An error occurred while fetching your journals. Please try again later.");
                }
                ?>
                </p> </td>
            </tr>
        </table>
        <div class="profile-section-footer">
            <a class="logout" href="logout.php">Log Out</a>
            <a class="delaccount" onclick="openPopup('popupDelete')">Delete Account</a>
        </div>
    </section>
</div>


<div class="overlay" id="popupOverlay"></div>

<div class="popup" id="popupUser">
    <button class="close-btn" onclick="closePopup('popupUser')">X</button>
    <form action="../backend/change_user_info.php" method="post" onsubmit="return validateUsername()">
        <h2>Change Username</h2><br>
        <input id="new_username" type="text" name="new_username" placeholder="New Username"><br>
        <button class="change" type="submit">Change</button>
    </form>
</div>

<div class="popup" id="popupEmail">
    <button class="close-btn" onclick="closePopup('popupEmail')">X</button>
    <form action="../backend/change_user_info.php" method="post" onsubmit="return validateEmail()">
        <h2>Change Email</h2><br>
        <input id="new_email" type="text" name="new_email" placeholder="New Email"><br>
        <button class="change" type="submit">Change</button>
    </form>
</div>

<div class="popup" id="popupPassword">
    <button class="close-btn" onclick="closePopup('popupPassword')">X</button>
    <form action="../backend/change_user_info.php" method="post" onsubmit="return validatePassword()">
        <h2>Change Password</h2><br>
        <input id="old_pswd" type="text" name="old_pswd" placeholder="Old Password"><br><br>
        <input id="new_pswd" type="text" name="new_pswd" placeholder="New Password"><br><br>
        <input id="new_pswd_conf" type="text" name="new_pswd_conf" placeholder="Confirm New Password"><br>
        <button class="change" type="submit">Change</button>
    </form>
</div>


<div class="popup" id="popupDelete">
    <button class="close-btn" onclick="closePopup('popupDelete')">X</button><br>
        <h3>Are you sure you want to delete your account</h3>
        <span style="display: inline;">
            <form action="../backend/delete_account.php" method="post" style="display: inline;">
                <button class="change" onclick="">YES</button>
            </form>
            <button class="change" onclick="closePopup('popupDelete')">NO</button>
        </span>
</div>

    <script>

    //     document.addEventListener('DOMContentLoaded', () => {
    //         const themeToggle = document.getElementById('theme-toggle');
    //         const savedTheme = localStorage.getItem('theme');

    //         if (savedTheme === 'dark') {
    //             document.body.classList.add('dark-theme');
    //             themeToggle.checked = true;
    //         }

    //         themeToggle.addEventListener('change', () => {
    //             document.body.classList.toggle('dark-theme');
    //             const theme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
    //             localStorage.setItem('theme', theme);
    //   });
    // });


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


    function validateUsername() {
        let newUsername = document.getElementById("new_username").value.trim();

        if (newUsername === "") {
            alert("Please fill out all fields :)");
            return false; // Prevent form submission
        } else {
            return true; // Allow form submission
        }
    }

    function validateEmail() {
        let newEmail = document.getElementById("new_email").value.trim();

        if (newEmail === "") {
            alert("Please fill out all fields :)");
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }

    function validatePassword() {
        let oldPassword= document.getElementById("old_pswd").value.trim();
        let newPassword= document.getElementById("new_pswd").value.trim();
        let newPasswordConf = document.getElementById("new_pswd_conf").value.trim();

        if (oldPassword === "" || newPassword === "" || newPasswordConf === "") {
            alert("Please fill out all fields :)");
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }
    </script>


    <footer class="footer">
        <p>2024/2025 University of Manchester COMP10120 CM1 Team Project</p>
    </footer>
</body>



</html>