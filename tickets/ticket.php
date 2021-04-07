<?php

use App\Lib\Client;
use App\Lib\Entry;
use App\Lib\Event;
use App\Lib\Ticket;
use App\Lib\User;

if (isset($_POST['btnReply'])) {
    header("Location: open.php");
}
include '../includes/session.inc.php';
include '../classes/ticket.class.php';
include '../includes/header.inc.php';

if (isset($_GET['id'])) {
    $ticket_id = $_GET['id'];
}

$objTicket = new Ticket();
$objUser = new User();

$sql = "SELECT t.number, t.status_id, t.priority_id, t.created, t.updated, ev.assigned as 'assign_id', evs.firstname, evs.lastname, c.name, c.email, ht.topic, d.name as department 
                FROM ticket t 
                LEFT JOIN event ev ON t.id = ev.ticket_id /*merr assigned*/ 
                LEFT JOIN staff evs ON ev.assigned = evs.id /*merr assigned*/ 
                LEFT JOIN client c ON t.client_id = c.id 
                LEFT JOIN help_topic ht ON t.topic_id = ht.id 
                LEFT JOIN department d ON t.dept_id = d.id
                WHERE t.id =" . $ticket_id . " AND ev.id =(select MAX(id) from event WHERE ticket_id =" . $ticket_id . ")";

$ticket = $objTicket->get_by_query($sql);
$users = $objUser->get_all();

$priority;
switch ($ticket['priority_id']) {
    case 1:
        $priority = 'Low';
        break;
    case 2:
        $priority = 'Normal';
        break;
    case 3:
        $priority = 'High';
        break;
    case 4:
        $priority = 'Emergency';
        break;
}
$ticketStatus;
switch ($ticket['status_id']) {
    case 1:
        $ticketStatus = 'Open';
        break;
    case 2:
        $ticketStatus = 'Resloved';
        break;
    case 3:
        $ticketStatus = 'Close';
        break;
}

if (isset($_POST['btnReply'])) {
    $check_is_updated = true;

    if ($_POST['body'] != '') {
        $objEntry = new Entry();

        $objEntry->set_ticket_id($_POST['ticket_id']);
        if (isset($_SESSION["isadmin"])) {
            $objEntry->set_staff_id($_SESSION['id']);
            $objEntry->set_client_id(0);
        } else {
            $objEntry->set_staff_id(0);
            $objEntry->set_client_id($_SESSION['id']);
        }
        if (isset($_POST["body"])) {
            $objEntry->set_body($_POST["body"]);
        }
        $objEntry->set_type('Post Reply');
        if (isset($_SESSION['isadmin'])) {
            $user = $objUser->get_by_id($_SESSION['id']);
            $objEntry->set_poster($user['firstname'] . ' ' . $user['lastname']);
        } else {
            $objClient = new Client();
            $client = $objClient->get_by_id($_SESSION['id']);
            $objEntry->set_poster($client['name']);
        }
        $objEntry->set_created(date('Y-m-d H:i:s'));
        $objEntry->set_updated(date('Y-m-d H:i:s'));

        //$objEntry->create();
        if (!$objEntry->create()) {
            $check_is_updated = false;
        }
    }

    $state = $assign = '';
    if (isset($_SESSION['isadmin'])) {
        $objEvent = new Event();
        $sql = "SELECT `id`, `ticket_id`, `staff_id`, `client_id`, `topic_id`, `state`, `assigned` 
                FROM `event` 
                WHERE ticket_id=" . $ticket_id . " AND id=(SELECT MAX(id) FROM event WHERE ticket_id =" . $ticket_id . ")";
        $event = $objEvent->get_by_query($sql);
        $objEvent->fill_properties($event);

        $state = $objEvent->get_state();
        $assign = $objEvent->get_assigned();

        $objEvent->set_state($_POST['ticket_status']);
        $objEvent->set_assigned($_POST['assign']);

        if ($assign != $objEvent->get_state() || $state != $objEvent->get_state()) {
            // $objEvent->update();
            if (!$objEvent->update()) {
                $check_is_updated = false;
            }
        }
    }
    if ($assign != $_POST['assign'] || $state != $_POST['ticket_status'] || $_POST["body"] != '') {
        $ticket = $objTicket->get_by_id($_POST['ticket_id']);
        $objTicket->fill_properties($ticket);
        $objTicket->set_status_id($objEvent->get_state());
        $objTicket->set_updated(date('Y-m-d H:i:s'));
        $objTicket->set_answered(1);

        if (!$objTicket->update()) {
            $check_is_updated = false;
        }
    } else {
        $_SESSION['alert'] = array('warning', 'Ticket failed updated!');
        exit();
    }

    if ($check_is_updated) {
        $_SESSION['alert'] = array('success', 'Ticket successfully updated!');
        exit();
    } else {
        $_SESSION['alert'] = array('warning', 'Ticket failed updated!');
        exit();
    }
}
?>
<!-- start main -->

<form action="" method="POST">

    <div class="content_page">
        <div class="d-flex justify-content-between px-5 pt-5">
            <div>
                <div><strong class="h4"><?php echo $_GET['number']; ?></strong></div>
                <label class="h5 mt-3"><?php echo $_GET['subject']; ?></label>
            </div>
            <div class="form-group col-md-3">
                <label class="is_a_client"><p>Assign:</p></label>
                <select class="control-color form-control form-control-sm is_a_client" name="assign" id="assign">
                    <option value="<?php echo $ticket['assign_id'] ?>"><?php echo $ticket['firstname'] . ' ' . $ticket['lastname']; ?></option>
                    <?php
                    foreach ($users as $user) {
                        if ($user['isactive'] == 1 && $user['isvisible'] == 1) {
                            echo '<option value="' . $user['id'] . '">' . $user['firstname'] . ' ' . $user['lastname'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="card">
            <div class="c-body">
                <main class="c-main">
                    <div class="container-fluid">
                        <div id="ui-view">
                            <div class="row card-body">
                                <div class="col-sm-6">
                                    <div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="ststus">Ststus:</label>
                                                    <input class="form-control form-control-sm" disabled=""
                                                           name="ststus" id="ststus" type="text"
                                                           placeholder="<?php echo $ticketStatus; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="priority">Priority:</label>
                                                    <input class="form-control form-control-sm" disabled=""
                                                           name="priority" id="priority" type="text"
                                                           placeholder="<?php echo $priority; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="create-date">Create Date:</label>
                                                    <input class="form-control form-control-sm" disabled=""
                                                           name="create-date" id="create-date" type="text"
                                                           placeholder="<?php echo $ticket['created'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="assigned-to">Assigned To:</label>
                                                    <input class="form-control form-control-sm" disabled=""
                                                           name="assigned-to" id="assigned-to" type="text"
                                                           placeholder="<?php echo $ticket['firstname'] . ' ' . $ticket['lastname']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="department">Department:</label>
                                                    <input class="form-control form-control-sm" disabled=""
                                                           name="department" id="department" type="text"
                                                           placeholder="<?php echo $ticket['department']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="client">Client:</label>
                                                    <input class="form-control form-control-sm" disabled=""
                                                           name="client" id="client" type="text"
                                                           placeholder="<?php echo $ticket['name']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="client-email">Client Email:</label>
                                                    <input class="form-control form-control-sm" disabled=""
                                                           name="client-email" id="client-email" type="text"
                                                           placeholder="<?php echo $ticket['email']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="help-topic">Help Topic:</label>
                                                    <input class="form-control form-control-sm" disabled=""
                                                           name="help-topic" id="help-topic" type="text"
                                                           placeholder="<?php echo $ticket['topic']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="last-response">Last Response:</label>
                                                    <input class="form-control form-control-sm" disabled=""
                                                           name="last-response" id="last-response" type="text"
                                                           placeholder="<?php echo $ticket['updated']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="">
                                <div class="card-body pt-3">
                                    <?php
                                    $objEntry = new Entry();

                                    $ticket_content = $objEntry->get_all_by_field('ticket_id', $ticket_id);

                                    foreach ($ticket_content as $t_content) {
                                        if ($t_content['staff_id'] != 0) {
                                            echo '
                                                  <div class="col-md-12">
                                                  <div class="ml-5 my-2 card border  border-warning">
                                                  <div class="card-header d-flex justify-content-end ">' . $t_content["poster"] . '</div>
                                                  <div class="card-body">' . $t_content["body"] . '</div>
                                                  </div>
                                                  </div>';
                                        } else {
                                            echo '<div class="col-md-12">
                                                      <div class="mr-5 my-2 card border border-primary">
                                                      <div class="card-header ">' . $t_content["poster"] . '</div>
                                                      <div class="card-body">' . $t_content["body"] . '</div>
                                                      </div>
                                                  </div>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="card-header border-0  mt-4"><strong>Post Reply:</strong></div>
                                <div class="card-body">
                                    <textarea class="control-color form-control form-control-sm" id="body" name="body"
                                              rows="5"
                                              placeholder="Content.."></textarea>
                                    <div class="col-sm-12 row">
                                        <div class="form-group col-sm-10">
                                            <label class="is_a_client" for="ticket_status">Ticket Status:</label>
                                            <select class="control-color form-control form-control-sm is_a_client"
                                                    name="ticket_status" id="ticket_status">
                                                <option value="<?php echo $ticket['status_id'] ?>"><?php echo $ticketStatus ?></option>
                                                <option value="1">Open</option>
                                                <option value="2">Resloved</option>
                                                <option value="3">Close</option>
                                            </select>
                                        </div>
                                        <input class="form-control-sm form-control col-sm-2 invisible" name="ticket_id"
                                               type="text" value="<?php echo $ticket_id ?>">
                                    </div>
                                    <?php $_POST['ticket_id'] = $ticket_id; ?>
                                </div>
                            </div>
                            <div class="">
                                <div class="float-right mb-5">
                                    <button class="btn btn-primary" name="btnReply" type="submit"> Post Reply</button>
                                    <button class="btn btn-danger" name="btnReset" type="reset"> Reset</button>
                                </div>
                            </div>
                        </div>
                </main>
            </div>
        </div>
    </div>
</form>
<!-- end main -->
<?php
include '../includes/footer.inc.php';
if (!isset($_SESSION['isadmin'])) {
    echo "<script>
            $('.is_a_client').addClass('d-none');
            </script>";
}
?>