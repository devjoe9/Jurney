<?php 

session_start();

session_unset();
session_destroy();

header("Location: jurney_login.php");
exit();

?>