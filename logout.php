<?php
session_start();
session_unset();
session_destroy();
$_SESSION['logout_message'] = "Logout successful!";
header("location:index.php");
exit();
?>