<?php
namespace App\Ticket;

use App\Lib\Client;
use App\Lib\Entry;
use App\Lib\Event;
use App\Lib\Ticket;
use App\Lib\User;

include_once '../autoloader.php';
include '../includes/session.inc.php';
include '../includes/fill_dropdownlist.inc.php';

if (!isset($_SESSION['isadmin'])) {
    header("Location: client_new_ticket.php");
    exit();
}
if (isset($_POST['btnOpen'])) {
    $objClient = new Client();
    $objUser = new User();

    $dept_id = $client_id = $status_id = $staff_id = $topic_id = $priority_id = $subject = $answered = $created = $updated = '';
    $ticket_id = $state = $assigned = '';
    $type = $poster = $body = '';

    $description = $comment = '';

    if (isset($_SESSION["id"])) {
        $staff_id = $_SESSION["id"];
    }

    $check = true;
    if (!empty($_POST["client_email"])) {
        $client = $objClient->get_by_field('email', $_POST["client_email"]);
        $client_id = $client['id'];
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

    if (!empty($_POST['comment'])) {
        $answered = 1;
    } else {
        $answered = 0;
    }

    if ($check) {

        $number = 0;
        $dept_id = $_POST["department"];
        $status_id = $_POST["ticket_status"];
        $priority_id = $_POST["priority"];
        $created = date("Y-m-d H:i:s");
        $updated = date("Y-m-d H:i:s");


        $state = $_POST["ticket_status"];
        $assigned = $_POST["assign"];

        $user = $objUser->get_by_id($staff_id);
        $poster = $user['firstname'] . " " . $user['lastname'];


        $comment = $_POST['comment'];
        $objTicket = new Ticket();

        $objTicket->set_number(0);
        $objTicket->set_dept_id($dept_id);
        $objTicket->set_client_id($client_id);
        $objTicket->set_status_id($status_id);
        $objTicket->set_topic_id($topic_id);
        $objTicket->set_staff_id($staff_id);
        $objTicket->set_priority_id($priority_id);
        $objTicket->set_subject($subject);
        $objTicket->set_answered($answered);
        $objTicket->set_created($created);
        $objTicket->set_updated($updated);
        if (!$objTicket->create()) {
            $check_is_created = false;
        }

        $ticket_id = $objTicket->get_max_id()['max_ticket_id'];
        $number = str_pad($ticket_id, 5, '0', STR_PAD_LEFT);
        $objTicket->set_id($ticket_id);
        $objTicket->set_number($number);
        $objTicket->update();


        $objEntry = new Entry();
        $check_is_created = true;
        if ($comment == '') {
            $type = 'Ticket Detail';
            $body = $description;

            $objEntry->set_ticket_id($ticket_id);
            $objEntry->set_staff_id($staff_id);
            $objEntry->set_client_id(0);
            $objEntry->set_type($type);
            $objEntry->set_poster($poster);
            $objEntry->set_body($body);
            $objEntry->set_created($created);
            $objEntry->set_updated($updated);
            if (!$objEntry->create()) {
                $check_is_created = false;
            }

        } else {
            $type = 'Ticket Detail';
            $body = $description;
            $objEntry->set_ticket_id($ticket_id);
            $objEntry->set_staff_id($staff_id);
            $objEntry->set_client_id(0);
            $objEntry->set_type($type);
            $objEntry->set_poster($poster);
            $objEntry->set_body($body);
            $objEntry->set_created($created);
            $objEntry->set_updated($updated);
            if (!$objEntry->create()) {
                $check_is_created = false;
            }
            $type = 'Post Reply';
            $body = $comment;
            $objEntry->set_type($type);
            $objEntry->set_body($body);
            if (!$objEntry->create()) {
                $check_is_created = false;
            }
        }

        $objEvent = new Event();
        $objEvent->set_ticket_id($ticket_id);
        $objEvent->set_dept_id($dept_id);
        $objEvent->set_staff_id($staff_id);
        $objEvent->set_client_id($client_id);
        $objEvent->set_topic_id($topic_id);
        $objEvent->set_state($state);
        $objEvent->set_assigned($assigned);
        if (!$objEvent->create()) {
            $check_is_created = false;
        }

        if ($check_is_created) {
            //session_start();
            $_SESSION['alert'] = array('success', 'Ticket successful created!');
            header("Location: ../tickets/open.php");
            exit();
        } else {
            //session_start();
            $_SESSION['alert'] = array('warning', 'Ticket failed created!');
            header("Location: ../new_ticket.php?check_email=true&client_email=$client_email&help_topic=$topic_id&assign=$assign&subject=$subject&description=$description&ptiority=$priority_id&comment=$comment&status_id=$status_id");
            exit();
        }
    }
}

include '../includes/header.inc.php';
?>
<!-- start main -->

<form action="" method="POST">
    <div class="content_page">
        <div class="h4 p-5"><strong>New Ticket</strong></div>
        <div class="card">
            <div class="c-body">
                <main class="c-main">
                    <div class="container-fluid">
                        <div id="ui-view">
                            <div class="card-header border-0 "><strong>User and Collaborators:</strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <label for="name">Client Email:</label>
                                            <input class="form-control-sm form-control" name="client_email"
                                                   autocomplete="off" id="client_email" type="text"
                                                   placeholder="Enter client email">
                                            <div class="invalid-feedback" id="feedback-email"> Required Client Email
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <label for="name">Full Name:</label>
                                            <input class="control-color form-control-sm form-control" name="full_name"
                                                   id="full_name"
                                                   type="text" disabled="" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header border-0"><strong>Ticket Options:</strong></div>
                            <div class="card-body">
                                <div class="form-group col-sm-10">
                                    <label for="help-topic">Help Topic:</label>
                                    <select class="control-color form-control-sm form-control" name="help_topic"
                                            id="help_topic">
                                        <option value="0">--Select Help Topic--</option>
                                        <?php
                                        $help_topics = check_topic();
                                        foreach ($help_topics as $help_topic) {
                                            echo '<option value="' . $help_topic['id'] . '">' . $help_topic['topic'] . '</option>';
                                        }
                                        echo '</select>';
                                        ?>
                                        <div class="invalid-feedback"> Required Help Topic</div>
                                </div>
                                <div class="form-group col-sm-10">
                                    <label for="assign">Assign To:</label>
                                    <select class="control-color form-control-sm form-control" name="assign"
                                            id="assign">
                                        <?php
                                        echo '<option value="0">--Select a Staff Member--</option>';
                                        $staff_members = check_staff();
                                        foreach ($staff_members as $staff_member) {
                                            echo '<option value="' . $staff_member['id'] . '">' . $staff_member['firstname'] . ' ' . $staff_member['lastname'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-10">
                                    <label for="assign">Department:</label>
                                    <select class="control-color form-control-sm form-control" name="department"
                                            id="department">
                                        <option value="0">To All</option>
                                        ';
                                        <?php
                                        $departments = check_department();
                                        foreach ($departments as $dept) {
                                            echo '<option value="' . $dept['id'] . '">' . $dept['name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="card-header border-0"><strong>Ticket Details:</strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <label for="subject">Subject:</label>
                                            <input class="form-control-sm form-control" name="subject" id="subject"
                                                   autocomplete="off" type="text" placeholder="Enter Subject">
                                            <div class="invalid-feedback"> Required Subject</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Description:</label>
                                            <textarea class="control-color form-control-sm form-control"
                                                      name="description"
                                                      id="description" rows="5" placeholder=""></textarea>
                                            <div class="invalid-feedback" id="desc_invalid"> Required Description</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-10">
                                    <label for="priority">Priority Level:</label>
                                    <select class="control-color form-control-sm form-control" name="priority"
                                            id="priority">
                                        <?php
                                        $array_priority = array("High" => "3", "Normal" => "1", "Emergency" => "4", "Low" => "2");
                                        asort($array_priority);
                                        foreach ($array_priority as $priority => $priority_id) {
                                            echo '<option value="' . $priority_id . '">' . $priority . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="card-header border-0"><strong>Response:</strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="comment">Comment:</label>
                                            <textarea class="control-color form-control-sm form-control" name="comment"
                                                      id="comment"
                                                      rows="5" placeholder=""></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-10">
                                    <label for="ticket-status">Ticket Status:</label>
                                    <select class="control-color form-control-sm form-control" name="ticket_status"
                                            id="ticket-status">
                                        <?php
                                        $array_status = array("Open" => "1", "Reslove" => "2", "Close" => "3");
                                        foreach ($array_status as $status => $status_id) {
                                            echo '<option value="' . $status_id . '">' . $status . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="">
                                <div class="float-right p-3">
                                    <button class="btn btn-primary" id="btnOpen" name="btnOpen" type="submit"> Open
                                    </button>
                                    <button class="btn btn-danger" name="btnReset" type="reset"> Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
</form>
</div>
<!-- end main -->

<?php include '../includes/footer.inc.php'; ?>
<script>
    $(document).ready(function () {
        $('#client_email').typeahead(
            {
                source: function (query, result) {
                    $.ajax(
                        {
                            url: "../classes/new_ticket.class.php",
                            method: "POST",
                            data: {query: query},
                            dataType: "json",
                            success: function (data) {
                                result($.map(data, function (item) {
                                    return item;
                                }));
                            }
                        })
                }
            });
    });
    $(document).on('click', '.dropdown-item', function () {
        var email = $('#client_email').text();
        $.ajax(
            {
                url: "../classes/new_ticket.class.php",
                method: "POST",
                data: {email: email},
                success: function (data) {
                    $('#full_name').val(data);
                }
            })
    });
</script>
<script>
    $("#new-ticket").addClass("active");
</script>
<script>
    $("#btnOpen").click(function () {
        var error = false;
        if ($("#client_email").val() == "") {
            $("#client_email").addClass("is-invalid");
            error = true;
        }
        if ($("#help_topic").val() == "0") {
            $("#help_topic").addClass("is-invalid");
            error = true;
        }
        if ($("#subject").val() == "") {
            $("#subject").addClass("is-invalid");
            error = true;
        }
        if ($("#description").val() == "") {
            $("#desc_invalid").css("display", "block");
            error = true;
        }
        if (error) {
            return false;
        } else {
            return true;
        }
    });

    $("#client_email").keyup(function () {
        $("#client_email").removeClass("is-invalid");
    });
    $("#help_topic").click(function () {
        $("#help_topic").removeClass("is-invalid");
    });
    $("#subject").keyup(function () {
        $("#subject").removeClass("is-invalid");
    });
    $("#description").keyup(function () {
        $("#desc_invalid").css("display", "none");
    });
</script>


