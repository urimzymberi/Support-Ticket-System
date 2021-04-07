<?php
namespace App\Lib;
include_once '../autoloader.php';

use App\Lib\Client;

session_start();
$objClient = new Client();
if ($objClient->delete($_GET['id'])) {
    $_SESSION["alert"] = array("success", "Client successfully deleted!");
    header("Location: list.php");
    exit();
} else {
    $_SESSION["alert"] = array("error", "Client faild deleted!");
    header("Location: list.php");
    exit();
}
?> 