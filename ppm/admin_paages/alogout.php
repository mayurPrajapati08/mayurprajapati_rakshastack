<?php
session_start();
session_unset();
session_destroy();
header("Location: alogin.php"); // Redirect to homepage
exit;

?>