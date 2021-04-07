<?php
include 'session.inc.php';
include '../classes/new_ticket.class.php';
if (isset($_SESSION["isadmin"])) {
    $client_email = $topic_id = $subject = $description = '';
    $assign = $comment = $status_id = $staff_id = $priority_id = $answered = '';
    $ticket_id = $type = $poster = $body = $department = '';
    $state = $data = '';
    if (isset($_SESSION["id"])) {
        $staff_id = $_SESSION["id"];
    }
    $check = true;
    if (!empty($_POST["client_email"])) {
        $client_email = $_POST["client_email"];
    } else {
        $check = false;
    }
    if ($_POST["help_topic"] != 0) {
        $topic_id = $_POST["help_topic"];
    } else {
        $check = false;
    }
    if (!empty($_POST["subject"])) {
        $subject = $_POST["subject"];
    } else {
        $check = false;
    }
    if (!empty($_POST["description"])) {
        $description = $_POST["description"];
    } else {
        $check = false;
    }
    if ($check) {
        $comment = $_POST['comment'];
        $assign = $_POST["assign"];
        $priority_id = $_POST["priority"];
        $status_id = $_POST["ticket_status"];
        $department = $_POST["department"];
        $answered = 0;
        $array_ticket = array();
        $array_entry = array();
        $array_event = array();
        $obj_new_ticket = new new_ticket;
        $array_ticket = array("client_email" => $client_email, "dept_id" => $department, "status_id" => $status_id, "staff_id" => $staff_id, "topic_id" => $topic_id, "priority_id" => $priority_id, "subject" => $subject, "answered" => $answered);
        $obj_new_ticket->ticket($array_ticket);
        if ($comment == '') {
            $type = 'Ticket Detail';
            $body = $description;
            $array_entry = array("staff_id" => $staff_id, "client_email" => $client_email, "body" => $body, "type" => $type);
            $obj_new_ticket->entry($array_entry);
        } else {
            $type = 'Ticket Detail';
            $body = $description;
            $array_entry = array("staff_id" => $staff_id, "client_email" => $client_email, "body" => $body, "type" => $type);
            $obj_new_ticket->entry($array_entry);

            $type = 'Post Reply';
            $body = $comment;
            $array_entry = array("staff_id" => $staff_id, "client_email" => $client_email, "body" => $body, "type" => $type);
            $obj_new_ticket->entry($array_entry);
        }
        $array_event = array("staff_id" => $staff_id, "dept_id" => $department, "client_email" => $client_email, "topic_id" => $topic_id, "status_id" => $status_id, "assign" => $assign);
        $obj_new_ticket->event($array_event);
        session_start();
        $_SESSION['alert'] = array('success', 'Ticket successful created!');
        header("Location: ../tickets/open.php");
        exit();
    } else {
        session_start();
        $_SESSION['alert'] = array('warning', 'Ticket failed created!');
        header("Location: ../new_ticket.php?check_email=true&client_email=$client_email&help_topic=$topic_id&assign=$assign&subject=$subject&description=$description&ptiority=$priority_id&comment=$comment&status_id=$status_id");
        exit();
    }
} else {
    $client_email = $topic_id = $subject = $description = $priority_id = $department = '';
    $check = true;
    if ($_POST["help_topic"] != 0) {
        $topic_id = $_POST["help_topic"];
    } else {
        $check = false;
    }
    if (!empty($_POST["subject"])) {
        $subject = $_POST["subject"];
    } else {
        $check = false;
    }
    if (!empty($_POST["description"])) {
        $description = $_POST["description"];
    } else {
        $check = false;
    }
    $priority_id = $_POST["priority"];
    $department = $_POST["department"];

    if ($check == true) {
        $array_ticket = array();
        $array_entry = array();
        $array_event = array();
        $obj_new_ticket = new new_ticket;
        $array_ticket = array("client_email" => '', "status_id" => 1, "staff_id" => 0, "dept_id" => $department, "topic_id" => $topic_id, "priority_id" => $priority_id, "subject" => $subject, "answered" => 0);
        $obj_new_ticket->ticket($array_ticket);
        $array_entry = array("staff_id" => 0, "client_email" => '', "body" => $description, "type" => 'Ticket Detail');
        $obj_new_ticket->entry($array_entry);
        $array_event = array("staff_id" => 0, "client_email" => '', "dept_id" => $department, "topic_id" => $topic_id, "status_id" => 1, "assign" => 0);
        $obj_new_ticket->event($array_event);
        $_SESSION['alert'] = array('success', 'Ticket successfully created!');
        header("Location: ../tickets/open.php");
        exit();
    } else {
        $_SESSION['alert'] = array('warning', 'Ticket failed created!');
        header("Location: ../tickets/client_new_ticket.php?help_topic=$topic_id&subject=$subject&description=$description&ptiority=$priority_id");
        exit();
    }
}
?>