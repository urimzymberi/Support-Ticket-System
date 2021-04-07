<?php
session_start();
unset($_SESSION["id"]);
unset($_SESSION["name"]);
unset($_SESSION["firstname"]);
unset($_SESSION["lastname"]);
unset($_SESSION["username"]);
unset($_SESSION["isactive"]);
unset($_SESSION["isadmin"]);
unset($_SESSION["isvisible"]);
header("Location: login.php");
exit();
?>