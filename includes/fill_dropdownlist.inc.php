<?php
include_once '../includes/dbh.inc.php';
function check_staff()
{
    $conn = $GLOBALS['conn'];
    $query_staff = 'SELECT id, firstname,lastname, isactive, isvisible FROM staff WHERE isactive=1 AND isvisible=1';
    return mysqli_query($conn, $query_staff);
    $conn->close();
}

function check_topic()
{
    $conn = $GLOBALS['conn'];
    $query_help_topic = 'SELECT id, topic, isactive FROM help_topic where isactive=1';
    return mysqli_query($conn, $query_help_topic);
    $conn->close();
}

function check_department()
{
    $conn = $GLOBALS['conn'];
    $query_department = 'SELECT id, name, ispublic FROM department where ispublic=1';
    return mysqli_query($conn, $query_department);
    $conn->close();
}

function client_info($client_id)
{
    $conn = $GLOBALS['conn'];
    $query = "SELECT name, email  FROM client 
        WHERE id =" . $client_id;
    $res = mysqli_query($conn, $query);
    return mysqli_fetch_array($res);
    $conn->close();
}

?>