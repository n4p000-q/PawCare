<?php
session_start();
session_unset();
session_destroy();

// Clear session cookie
setcookie(session_name(), '', time() - 3600, '/');

header("Location: ../index.php");
exit();
?>