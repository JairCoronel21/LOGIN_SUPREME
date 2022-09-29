<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: index.php");




exit;
?>

<?php


session_start();
session_destroy();
header("Location: index.php");



?>