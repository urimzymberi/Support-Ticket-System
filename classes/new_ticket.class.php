<?php
include '../includes/dbh.inc.php';
if (isset($_POST['query'])) {
    $request = mysqli_real_escape_string($conn, $_POST["query"]);
    $query = "SELECT email FROM client WHERE email LIKE '%" . $request . "%'";
    $result = $conn->query($query);
    $data = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row["email"];
        }
        echo json_encode($data);
    }
}
if (isset($_POST['email'])) {
    $query = "SELECT name FROM client 
        WHERE email = " . "'" . $_POST["email"] . "'";
    $result = mysqli_fetch_array($conn->query($query));
    $output = $result['name'];
    echo $output;
}

/////////////////////////////////////////////////////////////////////////////////////////////////
class new_ticket
{
    private static $max_ticket_id = 0;
    private static $client_id = 0;

    private function client_id($client_email)
    {
        if (isset($_SESSION['isadmin'])) {
            $conn = $GLOBALS['conn'];
            $query = "SELECT id FROM client 
                WHERE email = '" . $client_email . "'";
            $res_client = mysqli_fetch_array($conn->query($query));
            echo self::$client_id = $res_client['id'];
        } else {
            self::$client_id = $_SESSION['id'];
        }
    }

    private function poster($id)
    {
        if (isset($_SESSION['isadmin'])) {
            $conn = $GLOBALS['conn'];
            $query = "SELECT firstname, lastname FROM staff 
                WHERE  staff_id=" . $id;
            $res_staff = mysqli_fetch_array($conn->query($query));
            $poster = $res_staff['firstname'] . ' ' . $res_staff['lastname'];
            return $poster;
        } else {
            $client_id = $_SESSION['id'];
            $conn = $GLOBALS['conn'];
            $query = "SELECT name FROM client 
                WHERE  id = $client_id";
            $res_client = mysqli_fetch_array($conn->query($query));
            $poster = $res_client['name'];
            return $poster;
        }
    }

    private function max_ticket_id()
    {
        $conn = $GLOBALS['conn'];
        $query_max_ticket_id = "SELECT MAX(id) AS 'max_ticket_id' FROM ticket";
        $res_max_id = ($conn->query($query_max_ticket_id));
        $row_max_id = mysqli_fetch_array($res_max_id);
        $max_ticket_id = ($row_max_id[0]);
        self::$max_ticket_id = $max_ticket_id;
    }

    public function ticket($array_ticket)
    {
        $conn = $GLOBALS['conn'];
        $insert_ticket = $conn->prepare("INSERT INTO `ticket`(`client_id`, `dept_id`, `status_id`, `topic_id`, `staff_id`, `priority_id`,`subject`, `answered`, `created`, `updated`)VALUES (?,?,?,?,?,?,?,?,?,?)");
        $insert_ticket->bind_param("iiiiiisiss", $client_id, $dept_id, $status_id, $topic_id, $staff_id, $priority_id, $subject, $answered, $created, $updated);
        $this->client_id($array_ticket['client_email']);
        $status_id = $array_ticket['status_id'];
        $client_id = self::$client_id;
        $dept_id = $array_ticket['dept_id'];
        $topic_id = $array_ticket['topic_id'];
        $staff_id = $array_ticket['staff_id'];
        $priority_id = $array_ticket['priority_id'];
        $subject = $array_ticket['subject'];
        $answered = $array_ticket['answered'];
        $created = date("Y-m-d H:i:s");
        $updated = date("Y-m-d H:i:s");
        $insert_ticket->execute();
        $insert_ticket->close();
        $this->max_ticket_id();
        $number = '';
        $ticket_id = self::$max_ticket_id;
        $number = str_pad($ticket_id, 5, '0', STR_PAD_LEFT);
        $query_update_number = "UPDATE `ticket` 
            SET `number`='" . $number . "' WHERE id=" . $ticket_id;
        $conn->query($query_update_number);
    }

    public function entry($array_entry)
    {
        $conn = $GLOBALS['conn'];
        $insert_entry = $conn->prepare("INSERT INTO entry (ticket_id, staff_id, client_id, type, poster, body, created, updated) VALUES (?,?,?,?,?,?,?,?)");
        $insert_entry->bind_param('iiisssss', $ticket_id, $staff_id, $client_id, $type, $poster, $bady, $created, $updated);
        $ticket_id = self::$max_ticket_id;
        $staff_id = $array_entry['staff_id'];
        $client_id = self::$client_id;
        $type = $array_entry['type'];
        $poster = $this->poster($staff_id);
        $bady = $array_entry['body'];
        $created = date("Y-m-d H:i:s");
        $updated = date("Y-m-d H:i:s");
        $insert_entry->execute();
        $insert_entry->close();
    }

    public function event($array_event)
    {
        self::$max_ticket_id;
        $conn = $GLOBALS['conn'];
        $insert_event = $conn->prepare("INSERT INTO event(ticket_id, dept_id, staff_id, client_id, topic_id, state, assigned) VALUES (?,?,?,?,?,?,?)");
        $insert_event->bind_param('iiiiiii', $ticket_id, $dept_id, $staff_id, $client_id, $topic_id, $state, $assign);
        $ticket_id = self::$max_ticket_id;
        $dept_id = $array_event['dept_id'];
        $staff_id = $array_event['staff_id'];
        $client_id = self::$client_id;
        $topic_id = $array_event['topic_id'];
        $state = $array_event['status_id'];
        $assign = $array_event['assign'];
        $insert_event->execute();
        $insert_event->close();
        $conn->close();
    }
}

?>
