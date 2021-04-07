<?php
include '../includes/dbh.inc.php';
function tickets_by_status($status, $id)
{
    $conn = $GLOBALS['conn'];
    if (isset($_SESSION["isadmin"])) {
        if ($status == 1 || $status == 2) //Tickets me status open ose resloved
        {
            $query_ticket = "SELECT ticket.id, number,if(ticket.staff_id=0,client.name,CONCAT(staff.firstname,' ',staff.lastname))AS 'name' , status_id, subject, priority_id, ticket.updated,CONCAT(s.firstname,' ',s.lastname)AS 'assign'	 
                    FROM ticket 
                    LEFT join client on ticket.client_id=client.id
                    LEFT JOIN staff ON ticket.staff_id=staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id                
                    LEFT JOIN staff s ON event.assigned = s.id 
                    WHERE status_id = 1 OR status_id = 2 GROUP BY event.ticket_id
                    ORDER BY ticket.id DESC";
            $tickets = mysqli_query($conn, $query_ticket);
            $conn->close();
            return $tickets;
        } elseif ($status == 3)  //Tickets me status closed
        {
            $query_ticket = "SELECT ticket.id, number,if(ticket.staff_id=0,client.name,CONCAT(staff.firstname,' ',staff.lastname))AS 'name' , status_id, subject, priority_id, ticket.updated,CONCAT(s.firstname,' ',s.lastname)AS 'assign'	 
                    FROM ticket 
                    LEFT join client on ticket.client_id=client.id
                    LEFT JOIN staff ON ticket.staff_id=staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id                
                    LEFT JOIN staff s ON event.assigned = s.id 
                    WHERE status_id =3 GROUP BY event.ticket_id
                    ORDER BY ticket.id DESC";
            $tickets = mysqli_query($conn, $query_ticket);
            $conn->close();
            return $tickets;
        } elseif ($status == 4)  //tickets Answered
        {
            $query_ticket = "SELECT ticket.id, number,if(ticket.staff_id=0,client.name,CONCAT(staff.firstname,' ',staff.lastname))AS 'name' , status_id, subject, priority_id, ticket.updated,CONCAT(s.firstname,' ',s.lastname)AS 'assign'	 
                    FROM ticket 
                    LEFT join client on ticket.client_id=client.id
                    LEFT JOIN staff ON ticket.staff_id=staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id                
                    LEFT JOIN staff s ON event.assigned = s.id
                    WHERE answered =1 GROUP BY event.ticket_id
                    ORDER BY ticket.id DESC";
            $tickets = mysqli_query($conn, $query_ticket);
            $conn->close();
            return $tickets;
        } else         //My-tickets
        {
            $query_ticket = "SELECT ticket.id, number,if(ticket.staff_id=0,client.name,CONCAT(staff.firstname,' ',staff.lastname))AS 'name' , status_id, subject, priority_id, ticket.updated,CONCAT(s.firstname,' ',s.lastname)AS 'assign'	 
                    FROM ticket 
                    LEFT join client on ticket.client_id=client.id
                    LEFT JOIN staff ON ticket.staff_id=staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id                
                    LEFT JOIN staff s ON event.assigned = s.id
                    WHERE staff.id = " . $id . " GROUP BY event.ticket_id
                    ORDER BY ticket.id DESC";
            $tickets = mysqli_query($conn, $query_ticket);
            $conn->close();
            return $tickets;
        }
    } else {
        if ($status == 1 || $status == 2)//tickets me status Open ose Resloved
        {
            $query_ticket = "SELECT ticket.id, number,if(ticket.staff_id=0,client.name,CONCAT(staff.firstname,' ',staff.lastname))AS 'name' , status_id, subject, priority_id, ticket.updated	 
                    FROM ticket 
                    LEFT join client on ticket.client_id=client.id
                    LEFT JOIN staff ON ticket.staff_id=staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id                
                    WHERE status_id != 3 AND ticket.client_id=" . $id . " GROUP BY event.ticket_id ORDER BY ticket.id DESC";
            $tickets = mysqli_query($conn, $query_ticket);
            $conn->close();
            return $tickets;
        } elseif ($status == 3)      //tickets me status Close
        {
            $query_ticket = "SELECT ticket.id, number,if(ticket.staff_id=0,client.name,CONCAT(staff.firstname,' ',staff.lastname))AS 'name' , status_id, subject, priority_id, ticket.updated	 
                    FROM ticket 
                    LEFT join client on ticket.client_id=client.id
                    LEFT JOIN staff ON ticket.staff_id=staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id 
                    WHERE status_id = 3 AND ticket.client_id=" . $id . " GROUP BY event.ticket_id ORDER BY ticket.id DESC";
            $tickets = mysqli_query($conn, $query_ticket);
            $conn->close();
            return $tickets;
        } elseif ($status == 4)       //tickets Answered
        {
            $query_ticket = "SELECT ticket.id, number,if(ticket.staff_id=0,client.name,CONCAT(staff.firstname,' ',staff.lastname))AS 'name' , status_id, subject, priority_id, ticket.updated	 
                    FROM ticket 
                    LEFT join client on ticket.client_id=client.id
                    LEFT JOIN staff ON ticket.staff_id=staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id 
                    WHERE answered = 1 AND ticket.client_id=" . $id . " GROUP BY event.ticket_id ORDER BY ticket.id DESC";
            $tickets = mysqli_query($conn, $query_ticket);
            $conn->close();
            return $tickets;
        } else                    //My-tickets
        {
            $query_ticket = "SELECT ticket.id, number,if(ticket.staff_id=0,client.name,CONCAT(staff.firstname,' ',staff.lastname))AS 'name' , status_id, subject, priority_id, ticket.updated	 
                    FROM ticket 
                    LEFT join client on ticket.client_id=client.id
                    LEFT JOIN staff ON ticket.staff_id=staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id 
                    WHERE ticket.client_id=" . $id . " GROUP BY event.ticket_id ORDER BY ticket.id DESC";
            $tickets = mysqli_query($conn, $query_ticket);
            $conn->close();
            return $tickets;
        }
    }
}

?>