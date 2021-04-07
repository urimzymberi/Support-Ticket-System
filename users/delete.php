<?php

use App\Lib\User;

session_start();
if ($_SESSION["isadmin"] != 1) {
    header("Location: ../index.php");
}
include '../autoloader.php';
$objUser = new User();
if ($objUser->delete($_GET['id'])) {
    $_SESSION["alert"] = array('success', 'User successful deleted!');
    header("Location: list.php");
    exit();
} else {
    $_SESSION["alert"] = array('error', 'User faild deleted!');
    header("Location: list.php");
    exit();
}
?>