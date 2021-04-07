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
        $sql = "SELECT ticket.id, number, IF(ticket.staff_id=0 ,client.name , CONCAT(staff.firstname,' ',staff.lastname)) AS 'name' , status_id, subject, priority_id, ticket.updated,CONCAT(s.firstname,' ',s.lastname)AS 'assign'	 
                    FROM ticket 
                    LEFT join client on ticket.client_id = client.id
                    LEFT JOIN staff ON ticket.staff_id = staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id                
                    LEFT JOIN staff s ON event.assigned = s.id 
                    WHERE status_id = 1 OR status_id = 2 
                    ORDER BY ticket.id DESC";
    } else {
        $sql = "SELECT ticket.id, number, IF(ticket.staff_id=0 ,client.name , CONCAT(staff.firstname,' ',staff.lastname)) AS 'name' , status_id, subject, priority_id, ticket.updated,CONCAT(s.firstname,' ',s.lastname)AS 'assign'	 
                    FROM ticket 
                    LEFT join client on ticket.client_id = client.id
                    LEFT JOIN staff ON ticket.staff_id = staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id                
                    LEFT JOIN staff s ON event.assigned = s.id 
                    WHERE status_id = 1 OR status_id = 2 AND (ticket.staff_id = $id OR ticket.staff_id = 0 OR s.id = $id) 
                    ORDER BY ticket.id DESC";
    }
} else {
    $sql = "SELECT ticket.id, number, IF(ticket.staff_id=0 ,client.name , CONCAT(staff.firstname,' ',staff.lastname)) AS 'name' , status_id, subject, priority_id, ticket.updated	 
                    FROM ticket 
                    LEFT join client on ticket.client_id = client.id
                    LEFT JOIN staff ON ticket.staff_id = staff.id
                    LEFT JOIN event ON ticket.id = event.ticket_id                
                    WHERE status_id != 3 AND ticket.client_id = $id 
                    
                    ORDER BY ticket.id DESC";
}//GROUP BY event.ticket_id
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


$chck_session = array();
if (isset($_POST['for-print'])) {
    if (isset($_SESSION['ticket_selected'])) {
        $chck_session = $_SESSION['ticket_selected'];
    }

    $checkboxes = isset($_POST['checkbox']) ? $_POST['checkbox'] : array();
    foreach ($checkboxes as $checkbox) {
        if (!in_array($checkbox, $chck_session)) {
            $chck_session[] = $checkbox;
        }
    }

    $_SESSION['ticket_selected'] = $chck_session;
}

if (isset($_POST['clear-selected'])) {
    unset($_SESSION['ticket_selected']);
    $chck_session = '';
}

if (isset($_POST['print']) && isset($_SESSION['ticket_selected']) && $_SESSION['ticket_selected']!=null) {
    include_once '../lib/ReportPdf.php';

    $noTicket = $_SESSION['ticket_selected'];

    $ticketsPdf = implode(' or ticket.id = ', $noTicket);

    $sql = "SELECT ticket.id, number, IF(ticket.staff_id=0 ,client.name , CONCAT(staff.firstname,' ',staff.lastname)) AS 'name' , status_id, subject, priority_id, ticket.updated,CONCAT(s.firstname,' ',s.lastname)AS 'assign'	 
                FROM ticket 
                LEFT join client on ticket.client_id = client.id
                LEFT JOIN staff ON ticket.staff_id = staff.id
                LEFT JOIN event ON ticket.id = event.ticket_id                
                LEFT JOIN staff s ON event.assigned = s.id 
                WHERE ticket.id = $ticketsPdf 
                GROUP BY event.ticket_id
                ORDER BY ticket.id DESC
                ";

    $ticketsPdf = $objTicket->get_all_by_query($sql);

    $objPdf = new ReportPdf();

    $objPdf->AliasNbPages();
    $objPdf->AddPage('L', 'A4', 0);
    $objPdf->headerTable();
    $objPdf->data($ticketsPdf);
    $objPdf->Output('Open Tickets '.date('Y-m-d H:i:s').'.pdf', 'D');
    //$objPdf->Output();
}

include '../includes/header.inc.php';
?>

<!-- start main -->
<div class="content_page">
    <div class="h4 p-5"><strong>Open</strong></div>
    <div class="card border-0">
        <div class="card-body">

            <form action="" method="POST">

                <table class="dataTable compact hover row-border">
                    <thead>
                    <tr>
                        <th style="width: 20px;"><input type="checkbox" class="select_all"></th>
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
                        if (isset($_SESSION['ticket_selected'])) {
                            $chck_session = $_SESSION['ticket_selected'];
                            if (in_array($ticket['id'], $chck_session)) {
                                echo '<td><input class="chck" type="checkbox" checked value=' . $ticket['id'] . ' name="checkbox[]"></td>';
                            } else {
                                echo '<td><input class="chck" type="checkbox" value=' . $ticket['id'] . ' name="checkbox[]"></td>';
                            }
                        } else {
                            echo '<td><input class="chck" type="checkbox" value=' . $ticket['id'] . ' name="checkbox[]"></td>';
                        }

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
                        <th><input type="checkbox" class="select_all"></th>
                        <th>Number</th>
                        <th>Last Update</th>
                        <th>Subject</th>
                        <th>From</th>
                        <th>Priority</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="d-flex justify-content-start col-md-3">
                        <input class="btn btn-sm btn-outline-primary h-75 mr-2" type="submit" name="for-print"
                               value="Keep Selected">
                        <input class="btn btn-sm btn-outline-primary h-75 mr-2" type="submit" name="print"
                               value="Print">
                        <input class="btn btn-sm btn-outline-primary h-75" type="submit" name="clear-selected"
                               value="Clear Selected">
                    </div>
                    <div class="col-md-9">
                        <nav class="d-flex justify-content-end" aria-label="Page navigation example">
                            <ul class="pagination">

                                <?php
                                if (isset($_GET['pagination'])) {
                                    if ($_GET['pagination'] > 1) {
                                        $previous = $_GET["pagination"] - 1;

                                        echo '<li class="page-item"><a class="page-link" href="open.php?pagination=' . $previous . '">Previous</a></li>';
                                    }
                                }
                                if (isset($_GET['pagination'])) {
                                    for ($i = 1; $i <= $noPagination; $i++) {
                                        if ($i == $_GET['pagination']) {
                                            echo '<li class="page-item active"><a class="page-link" href="open.php?pagination=' . $i . '">' . $i . '</a></li>';
                                        } else {
                                            echo '<li class="page-item"><a class="page-link" href="open.php?pagination=' . $i . '">' . $i . '</a></li>';
                                        }
                                    }
                                } else {
                                    for ($i = 1; $i <= $noPagination; $i++) {
                                        if ($i == 1) {
                                            echo '<li class="page-item active"><a class="page-link" href="open.php?pagination=' . $i . '">' . $i . '</a></li>';
                                        } else {
                                            echo '<li class="page-item"><a class="page-link" href="open.php?pagination=' . $i . '">' . $i . '</a></li>';
                                        }
                                    }
                                }
                                if (isset($_GET['pagination'])) {
                                    if ($_GET['pagination'] != $noPagination) {
                                        $next = $_GET["pagination"] + 1;
                                        echo '<li class="page-item"><a class="page-link" href="open.php?pagination=' . $next . '">Next</a></li>';
                                    }
                                } else {
                                    echo '<li class="page-item"><a class="page-link" href="open.php?pagination=2">Next</a></li>';
                                }
                                ?>

                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <p>Total <?php echo $ticketCount ?> entries</p>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end main -->

<?php include '../includes/footer.inc.php'; ?>

<script>
    $(document).ready(function () {
        $("#open").addClass("active");
    });
</script>

<script>
    $('#use-get-entries').click(function () {
        if ($('#use-get-entries').is(":checked")) {
            $('#no-entries').prop('disabled', false);
        } else {
            $('#no-entries').prop('disabled', true);
        }
    });
</script>

<script>
    $('.select_all').click(function(){
        $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
    });

    $(".chck").change(function(){
        if ($('.chck:checked').length == $('.chck').length) {
            $(".select_all").prop('checked', $(this).prop('checked'));
        } else{
            $(".select_all").prop('checked', false); 
        }
    });
    
    if ($('.chck:checked').length == $('.chck').length) {
            $(".select_all").prop('checked', $(this).prop('checked'));
        } else{
            $(".select_all").prop('checked', false); 
        }
</script>
