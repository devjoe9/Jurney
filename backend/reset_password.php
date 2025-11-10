<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; // Add the Exception namespace

echo("<script>console.log('Hello');</script>");

require_once "db.php";

function get_email_by_userid($user_id, $pdo)
{
    try {
        $sql = "SELECT * FROM users WHERE $user_id = :user_id";
        $statement = $pdo->prepare($sql);
        $statement->execute([":user_id" => $user_id]);

        $result = $statement->fetch();

        if ($result) {
            return $result;
        } else {
            header("Location: ../frontend/profile.php");
        }

        // return $statement->fetch();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
}



$user_id = $_SESSION["user_id"];
$email = get_email_by_userid($user_id, $pdo);

$token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

$stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE id = ?");
$stmt->bind_param("ssi", $token, $expires, $user_id);

if (!$stmt->execute()) {
    echo "Error updating token in the database.";
    exit;
}

require 'vendor/autoload.php';

$reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token; // Replace with your actual domain

$mail = new PHPMailer(true);

try {
    // Server settings (Use MailHog for local development)
    $mail->isSMTP();
    $mail->Host = 'localhost'; // Or 127.0.0.1
    $mail->Port = 1025; // MailHog port
    $mail->SMTPAuth = false; // MailHog doesn't require authentication
    $mail->SMTPSecure = false; // MailHog doesn't use encryption

    // Recipients
    $mail->setFrom('no-reply@yourwebsite.com', 'Jurney'); // Replace with your "from" address
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset';
    $mail->Body = 'Click this link to reset your password: <a href="' . $reset_link . '">' . $reset_link . '</a>';
    $mail->AltBody = 'Click this link to reset your password: ' . $reset_link;

    $mail->send();
    echo 'Password reset link sent to your email.';

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
}

// Clean up
$stmt->close();
$conn->close();

?>