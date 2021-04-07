<?php

use App\Lib\Help_topic;

session_start();
include '../autoloader.php';
$id = $_GET['id'];
$objHelpTopic = new Help_topic();
if ($objHelpTopic->delete($id)) {
    $_SESSION["alert"] = array("success", "Help Topic successfully deleted!");
    header("Location: list.php");
    exit();
} else {
    $_SESSION["alert"] = array("error", "Help Topic faild deleted!");
    header("Location: list.php");
    exit();
}
?> 