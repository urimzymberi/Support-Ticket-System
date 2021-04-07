<?php
$e = explode('\\', getcwd());
$f = strtolower($e[count($e) - 1]);
$ticket = "../tickets/";
$user = "../users/";
$client = "../clients/";
$help_topic = "../help_topic/";
$department = "../department/";
$other = "../";

switch ($f) {
    case "tickets":
        $ticket = "";
        break;
    case "users":
        $user = "";
        break;
    case "clients":
        $client = "";
        break;
    case "help_topic":
        $help_topic = "";
        break;
    case "department":
        $department = "";
        break;
    default:
        $ticket = "tickets/";
        $user = "users/";
        $client = "clients/";
        $help_topic = "help_topic/";
        $department = "department/";
        $other = "";
        break;
}
if (file_exists('../autoloader.php')) {
    include_once '../autoloader.php';
} else {
    include_once 'autoloader.php';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Support Tickets</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css"/>
    <!-- <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script> -->

    <link class="style" href='<?php echo $other; ?>css/style.css' rel='stylesheet'/>
    <link class="style" rel="stylesheet" href="<?php echo $other; ?>css/lightMode.css"/>
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css"> -->

</head>

<body>
<?php
if (isset($_SESSION["alert"])) {
    $alert_array = $_SESSION["alert"];
    echo "<script>
              Swal.fire({
                  position: 'top-end',
                  icon: '$alert_array[0]',
                  title: '$alert_array[1]',
                  showConfirmButton: false,
                  timer: 1500
              })
          </script>";
    unset($_SESSION["alert"]);
}
?>
<aside id="side_nav" class="nav-fixed min-vh-100">
    <div class="sidebar-heading">
        <div class="logo">
            <img class="logowhite" src="<?php echo $other ?>images/st-logowhite.png" alt="ST"/>
            <img class="logoblack d-none" src="<?php echo $other ?>images/st-logoblack.png" alt="ST"/>
        </div>
    </div>

    <div class="sidenav">
        <div class="">
            <a id="dashboard" href="<?php echo $other ?>index.php"><i class="fa fa-tachometer" aria-hidden="true"></i>
                &nbsp
                Dashboard</a>
            <br/><br/>
            <p class="c-sidebar-nav-title ml-3">Tickets</p>
            <div class="border_top"></div>
            <a id="open" href="<?php echo $ticket ?>open.php"><i class="fa fa-table" aria-hidden="true"></i> &nbsp Open</a>
            <a id="answered" href="<?php echo $ticket ?>answered.php"><i class="fa fa-table" aria-hidden="true"></i>
                &nbsp Answered</a>
            <a id="my-tickets" href="<?php echo $ticket ?>my_tickets.php"><i class="fa fa-table" aria-hidden="true"></i>
                &nbsp My Ticket</a>
            <a id="closed" href="<?php echo $ticket ?>closed.php"><i class="fa fa-table" aria-hidden="true"></i> &nbsp
                Closed</a>
            <a id="new-ticket" href="<?php echo $ticket ?>new_ticket.php"><i class="fa fa-ticket"
                                                                             aria-hidden="true"></i> &nbsp New
                Ticket</a>
            <br/>
        </div>
        <div id="users">
            <p class="c-sidebar-nav-title ml-3">Users</p>
            <div class="border_top"></div>
            <a id="user_list" href="<?php echo $user ?>list.php"><i class="fa fa-list-alt" aria-hidden="true"></i> &nbsp
                List</a>
            <a id="user_new" href="<?php echo $user ?>new.php"><i class="fa fa-plus" aria-hidden="true"></i> &nbsp
                New</a>
            <br/>
        </div>
        <div id="clients">
            <p class="c-sidebar-nav-title ml-3">Clients</p>
            <div class="border_top"></div>
            <a id="client_list" href="<?php echo $client ?>list.php"><i class="fa fa-list-alt" aria-hidden="true"></i>
                &nbsp List</a>
            <a id="client_new" href="<?php echo $client ?>new.php"><i class="fa fa-plus" aria-hidden="true"></i> &nbsp
                New</a>
            <br/>
        </div>
        <div id="help_topics">
            <p class="c-sidebar-nav-title ml-3">Help Topic</p>
            <div class="border_top"></div>
            <a id="help_topic_list" href="<?php echo $help_topic ?>list.php"><i class="fa fa-list-alt"
                                                                                aria-hidden="true"></i> &nbspList</a>
            <a id="help_topic_new" href="<?php echo $help_topic ?>new.php"><i class="fa fa-plus" aria-hidden="true"></i>
                &nbsp New</a>
            <br/>
        </div>
        <div id="department">
            <p class="c-sidebar-nav-title ml-3">Departments</p>
            <div class="border_top"></div>
            <a id="department_list" href="<?php echo $department ?>list.php"><i class="fa fa-list-alt"
                                                                                aria-hidden="true"></i>
                &nbspList</a>
            <a id="department_new" href="<?php echo $department ?>new.php"><i class="fa fa-plus" aria-hidden="true"></i>
                &nbsp
                New</a>
            <br/>
        </div>
    </div>
    <br/>
</aside>
<div class="main">
    <div class="mmain d-flex flex-column min-vh-100">
        <div class="header d-flex">
            <div class="align-self-center">
                <button class="btn btn-link btn-sm ml-3" id="close_sidenav" href="#">
                    <i class="fa fa-bars fa-sm" aria-hidden="true"></i>
                </button>
            </div>
            <div class="logo1 mr-auto ml-0">
                <img class="logowhite" src="<?php echo $other ?>images/st-logowhite.png" alt="ST"/>
                <img class="logoblack d-none" src="<?php echo $other ?>images/st-logoblack.png" alt="ST"/>
            </div>
            <div class="align-self-center">
                <button id="btn_dark_light" class="btn btn-link btn-sm ml-3" href="#">
                    <i id="dark_light" class="fa fa-sun-o fa-sm" aria-hidden="true"></i>
                </button>
            </div>
            <ul class="nav align-self-center">
                <li id="dropleft" class="nav-item dropleft">
                    <a id="user" class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-sm"></i></a>
                    <div class="dropdown-menu" style="margin: 0px;">
                        <h6 class="text-center" id="fullnameUser"></h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php if (isset($_SESSION['isadmin'])) {
                            echo $user;
                        } else {
                            echo $client;
                        } ?>profile.php">Profile</a>
                        <a class="dropdown-item" href="<?php if (isset($_SESSION['isadmin'])) {
                            echo $user;
                        } else {
                            echo $client;
                        } ?>changepassword.php">Change Password</a>
                        <a id="logout" class="dropdown-item" href="<?php echo $other; ?>logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="main_content">