<?php
session_start();
include '../classes/ticket.class.php';
$staff_id = $client_id = $status_id = $assign_id = 0;
$body = '';
$ticket_id = $_POST['ticket_id'];
if (isset($_SESSION["isadmin"])) {
    $staff_id = $_SESSION["id"];
} else {
    $client_id = $_SESSION["id"];
}
if (isset($_POST["body"])) {
    $body = $_POST["body"];
}
$status_id = $_POST['ticket_status'];
$assign_id = $_POST['assign'];

$obj = new ticket();
$ticket_info = $obj->ticket_info($ticket_id);

if (($status_id != $ticket_info['status_id'] && $status_id != 0) || ($assign_id != $ticket_info['assign_id'] && $assign_id != 0) || $body != '') {
    //ticket_response($ticket_id, $staff_id, $client_id, $body, $status_id, $assign_id)
    $obj->ticket_response($ticket_id, $staff_id, $client_id, $body, $status_id, $assign_id);
} else {
    $number = $ticket_info['number'];
    $_SESSION["alert"] = array("warning", "You have not changed the ticket $number!");
    header("Location: ../tickets/open.php");
    exit();
}
?>