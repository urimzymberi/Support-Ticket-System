<?php
// $includeConn ="";
// if (file_exists("dbh.inc.php") ) {
//     $includeConn ="dbh.inc.php";
// }else{
//     $includeConn ="../dbh.inc.php";
// }
// include $includeConn;
include "../includes/dbh.inc.php";

class ticket
{
    // public function ticket_info($ticket_id)
    // {
    //     $conn = $GLOBALS['conn'];
    //     $query_ticket = "SELECT t.number, t.status_id, t.priority_id, t.created, t.updated, ev.assigned as 'assign_id', evs.firstname, evs.lastname, c.name, c.email, ht.topic, d.name as department 
    //             FROM ticket t LEFT JOIN event ev ON t.id = ev.ticket_id /*merr assigned*/ 
    //             LEFT JOIN staff evs ON ev.assigned = evs.id /*merr assigned*/ 
    //             LEFT JOIN client c ON t.client_id = c.id 
    //             LEFT JOIN help_topic ht ON t.topic_id = ht.id 
    //             LEFT JOIN department d ON t.dept_id = d.id
    //             WHERE t.id =" . $ticket_id . " AND ev.id =(select MAX(id) from event WHERE id =" . $ticket_id . ")";

    //     $ticket = mysqli_fetch_array($conn->query($query_ticket));

    //     return $ticket;
    // }

    // public function get_staff()
    // {
    //     $conn = $GLOBALS['conn'];

    //     $query = "SELECT `id`,`firstname`, `lastname` 
    //         FROM `staff`
    //         WHERE isactive=1 and isvisible=1";

    //     $res_staff = ($conn->query($query));

    //     return $res_staff;
    // }

    // public function ticket_content($ticket_id)
    // {
    //     $conn = $GLOBALS['conn'];

    //     $query_content_ticket = "SELECT  en.staff_id AS 'id_staff', en.client_id AS 'id_client', en.poster, en.body
    //         FROM ticket t
    //         INNER JOIN entry en ON t.id = en.ticket_id
    //         WHERE t.id=" . $ticket_id;

    //     $ticket_content = ($conn->query($query_content_ticket));

    //     return $ticket_content;
    // }

    public function ticket_response($ticket_id, $staff_id, $client_id, $body, $status_id, $assign_id)
    {
        $conn = $GLOBALS['conn'];

        // $poster = "";
        // if (isset($_SESSION['isadmin'])) {
        //     $query_staff = "SELECT s.firstname, s.lastname
        //     FROM staff s
        //     WHERE s.id=" . $staff_id;
        //     $staff_row = mysqli_fetch_array($conn->query($query_staff));
        //     $poster = $staff_row['firstname'] . ' ' . $staff_row['lastname'];
        // } else {

        //     $client_id;
        //     $query_client = "SELECT name
        //         FROM client
        //         WHERE id=" . $client_id;
        //     $client_row = mysqli_fetch_array($conn->query($query_client));
        //     $poster = $client_row['name'];
        //}

        $query_event = "SELECT `id`, `ticket_id`, `staff_id`, `client_id`, `topic_id`, `state`, `assigned` 
            FROM `event` 
            WHERE ticket_id=" . $ticket_id . " AND id=(SELECT MAX(id) FROM event WHERE ticket_id =" . $ticket_id . ")";

        $event_row = mysqli_fetch_array($conn->query($query_event));  //rreshti me id maksimale per me marr eventin e fundit te nje tikete

        // if ($body != '') {
        //     $query_insert_response = $conn->prepare("INSERT INTO `entry`(`ticket_id`, `staff_id`, `client_id`, `type`, `poster`, `body`, `created`, `updated`)
        //         VALUES (?,?,?,?,?,?,?,?)");
        //     $query_insert_response->bind_param('iiisssss', $ticket_id, $staff_id, $client_id, $type1, $poster1, $body, $created, $updated);

        //     $ticket_id1 = $ticket_id;
        //     $staff_id1 = $staff_id;
        //     $client_id1 = $client_id;
        //     $type1 = 'Post Reply';
        //     $poster1 = $poster;
        //     $body1 = $body;

        //     $created = date("Y-m-d H:i:s");
        //     $updated = date("Y-m-d H:i:s");
        //     $query_insert_response->execute();
        //     $query_insert_response->close();
        // }

        if (isset($_SESSION['isadmin'])) {
            if (($event_row['assigned'] != $assign_id || $event_row['state'] != $status_id)) {
                $query_insert_event = $conn->prepare("INSERT INTO `event`(`ticket_id`, `staff_id`, `client_id`, `topic_id`, `state`, `assigned`) 
                    VALUES (?,?,?,?,?,?)");
                $query_insert_event->bind_param('iiiiii', $ticket_id, $staff_id, $client_id, $event_row['topic_id'], $status_id, $assign_id);

                $query_insert_event->execute();
                $query_insert_event->close();


                //.$ticket_id.",".$staff_id.",".$client_id.",".$event_row['topic_id'].",".$status_id.",".$assign_id.")";
                //$conn->query($query_insert_event);
            }

            if ($event_row['assigned'] != $assign_id || $event_row['state'] != $status_id || $body != '') {
                $query_update_ticket = "UPDATE `ticket` SET `status_id`=" . $status_id . ",`answered`=1,`updated`= now() WHERE ticket_id=" . $ticket_id;
                $conn->query($query_update_ticket);
            }
        }
        $_SESSION['alert'] = array('success', 'Ticket successfully updated!');
        header("Location: ../tickets/open.php");
        exit();
    }
}

?>