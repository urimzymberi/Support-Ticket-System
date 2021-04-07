<?php

use App\Lib\Department;

session_start();
include '../autoloader.php';
$id = $_GET['id'];
$objDepartment = new Department();
if ($objDepartment->delete($id)) {
    $_SESSION["alert"] = array("success", "Department successfully deleted!");
    header("Location: list.php");
    exit();
} else {
    $_SESSION["alert"] = array("error", "Department faild deleted!");
    header("Location: list.php");
    exit();
}
?> 