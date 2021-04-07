<?php

use App\Lib\Ticket;

include '../includes/session.inc.php';
include '../autoloader.php';

$id = 0;
if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"];
}

$objTicket = new Ticket();

if (isset($_SESSION['isadmin'])) {
    //$_SESSION['isadmin']=0;
    if ($_SESSION['isadmin'] == 1) {
        $sql = "SELECT ticket.id, number, IF(ticket.staff_id=0 ,client.name , CONCAT(staff.firstname,' ',staff.lastname)) AS 'name', status_id, subject, priority_id, ticket.updated,CONCAT(s.firstname,' ',s.lastname)AS 'assign'	 
                    FROM ticket 
                    LEFT join client on ticket.client_id = client.id
                    LEFT JOIN staff ON ticket.staff_id = staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id                
                    LEFT JOIN staff s ON event.assigned = s.id
                    WHERE staff.id = $id 
                    GROUP BY event.ticket_id
                    ORDER BY ticket.id DESC";
    } else {
        $sql = "SELECT ticket.id, number, IF(ticket.staff_id=0 ,client.name , CONCAT(staff.firstname,' ',staff.lastname)) AS 'name', status_id, subject, priority_id, ticket.updated,CONCAT(s.firstname,' ',s.lastname)AS 'assign'	 
                    FROM ticket 
                    LEFT join client on ticket.client_id = client.id
                    LEFT JOIN staff ON ticket.staff_id = staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id                
                    LEFT JOIN staff s ON event.assigned = s.id
                    WHERE ticket.staff_id = $id OR s.id = $id 
                    GROUP BY event.ticket_id
                    ORDER BY ticket.id DESC";
    }
} else {
    $sql = "SELECT ticket.id, number, IF(ticket.staff_id=0 ,client.name , CONCAT(staff.firstname,' ',staff.lastname)) AS 'name', status_id, subject, priority_id, ticket.updated	 
                    FROM ticket 
                    LEFT join client on ticket.client_id = client.id
                    LEFT JOIN staff ON ticket.staff_id = staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id 
                    WHERE ticket.client_id = $id 
                    GROUP BY event.ticket_id 
                    ORDER BY ticket.id DESC";
}
$ticketCount = $objTicket->get_by_query_count($sql);

$limit = 8;
$limitStart = isset($_GET['pagination']) ? ($_GET['pagination'] * $limit) - $limit : 0;
$sql .= " LIMIT $limitStart, $limit";

$tickets = $objTicket->get_all_by_query($sql);

if ($ticketCount % $limit == 0) {
    $noPagination = $ticketCount / $limit;
} else {
    $noPagination = intval($ticketCount / $limit) + 1;
}
include '../includes/header.inc.php';
?>
<!-- start main -->
<div class="content_page">
    <div class="h4 p-5"><strong>My Tickets</strong></div>
    <div class="card border-0">
        <div class="card-body">
            <table class="dataTable compact hover row-border">
                <thead>
                <tr>
                    <th>Number</th>
                    <th>Last Update</th>
                    <th>Subject</th>
                    <th>From</th>
                    <th>Priority</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($tickets as $ticket) {
                    switch ($ticket['priority_id']) {
                        case 1:
                            $priority = '<td><h6><span class="badge badge-success mt-1 p-1 px-2">Low</span></h6></td>';
                            break;
                        case 2:
                            $priority = '<td><h6><span class="badge badge-secondary mt-1 p-1 px-2">Normal</span></h6></td>';
                            break;
                        case 3:
                            $priority = '<td><h6><span class="badge badge-warning mt-1 p-1 px-2">High</span></h6></td>';
                            break;
                        case 4:
                            $priority = '<td><h6><span class="badge badge-danger mt-1 p-1 px-2">Emergency</span></h6></td>';
                            break;
                    }
                    echo '<tr>';
                    echo '<td>' . $ticket['number'] . '</td>';
                    echo '<td>' . $ticket['updated'] . '</td>';
                    echo '<td>' . $ticket['subject'] . '</td>';
                    echo '<td>' . $ticket['name'] . '</td>';
                    echo $priority;
                    echo '<td><a href="ticket.php?id=' . $ticket['id'] . '&number=' . $ticket['number'] . '&subject=' . $ticket['subject'] . '" class="btn btn-sm btn-outline-warning" >Details</a></td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Number</th>
                    <th>Last Update</th>
                    <th>Subject</th>
                    <th>From</th>
                    <th>Priority</th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
            <nav class="d-flex justify-content-end" aria-label="Page navigation example">
                <ul class="pagination">

                    <?php
                    if (isset($_GET['pagination'])) {
                        if ($_GET['pagination'] > 1) {
                            $previous = $_GET["pagination"] - 1;
                            echo '<li class="page-item"><a class="page-link" href=" my_tickets.php?pagination=' . $previous . '">Previous</a></li>';
                        }
                    }
                    if (isset($_GET['pagination'])) {
                        for ($i = 1; $i <= $noPagination; $i++) {
                            if ($i == $_GET['pagination']) {
                                echo '<li class="page-item active"><a class="page-link" href=" my_tickets.php?pagination=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li class="page-item"><a class="page-link" href=" my_tickets.php?pagination=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    } else {
                        for ($i = 1; $i <= $noPagination; $i++) {
                            if ($i == 1) {
                                echo '<li class="page-item active"><a class="page-link" href=" my_tickets.php?pagination=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li class="page-item"><a class="page-link" href=" my_tickets.php?pagination=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                    }
                    if (isset($_GET['pagination'])) {
                        if ($_GET['pagination'] != $noPagination) {
                            $next = $_GET["pagination"] + 1;
                            echo '<li class="page-item"><a class="page-link" href=" my_tickets.php?pagination=' . $next . '">Next</a></li>';
                        }
                    } else {
                        echo '<li class="page-item"><a class="page-link" href=" my_tickets.php?pagination=2">Next</a></li>';
                    }
                    ?>

                </ul>
            </nav>
            <div class="d-flex justify-content-end">
                <p>Total <?php echo $ticketCount ?> entries</p>
            </div>
        </div>
    </div>
</div>
<!-- end main -->

<?php include '../includes/footer.inc.php'; ?>

<script>
    $("#my-tickets").addClass("active");
</script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            "order": [[0, "desc"]]
        });
    });
</script>