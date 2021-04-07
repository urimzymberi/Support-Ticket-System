<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$keys = array('id', 'username', 'isactive');
foreach ($keys as $key) {
    if (!isset($_SESSION[$key])) {
        if (file_exists('login.php')) {
            header("Location: login.php");
        } else {
            header("Location: ../login.php");
        }
        exit();
    }
}
?>