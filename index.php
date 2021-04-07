<?php

use App\Lib\Client;
use App\Lib\Department;
use App\Lib\Help_topic;
use App\Lib\User;

include 'includes/session.inc.php';
include 'includes/header.inc.php';

$objUser = new User();
$objClient = new Client();
$objHelpTopic = new Help_topic();
$objDepartmet = new Department();

$sql = "SELECT * FROM staff";
$usersCount = $objUser->get_by_query_count($sql);
$sql = "SELECT * FROM client";
$ClientsCount = $objClient->get_by_query_count($sql);
$sql = "SELECT * FROM help_topic";
$helpTopicCount = $objHelpTopic->get_by_query_count($sql);
$sql = "SELECT * FROM department";
$DepartmentCount = $objDepartmet->get_by_query_count($sql);

?>

    <!-- start main -->
<?php
if (isset($_SESSION['isadmin'])) {
    if ($_SESSION['isadmin'] == 1) {
        ?>
        <div class="col-md-12 row d-flex justify-content-around ml-1 mt-5">
            <div class="card control-color fb-card col-md-5">
                <div class="card-header">
                    <i class="icofont icofont-social-facebook"></i>
                    <div class="d-inline-block">
                        <h5>Users</h5>
                        <span></span>
                    </div>
                </div>
                <div class="card-block text-center">
                    <div class="row col-md-12">
                        <div class="text-center col-md-12">
                            <br>
                            <h2><?php echo $usersCount ?></h2>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card control-color fb-card col-md-5">
                <div class="card-header">
                    <i class="icofont icofont-social-facebook"></i>
                    <div class="d-inline-block">
                        <h5>Clients</h5>
                        <span></span>
                    </div>
                </div>
                <div class="card-block text-center">
                    <div class="row col-md-12">
                        <div class="text-center col-md-12">
                            <br>
                            <h2><?php echo $ClientsCount ?></h2>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 row justify-content-around ml-1 mt-5">
            <div class="card control-color fb-card col-md-5">
                <div class="card-header">
                    <i class="icofont icofont-social-facebook"></i>
                    <div class="d-inline-block">
                        <h5>Help Topic</h5>
                        <span></span>
                    </div>
                </div>
                <div class="card-block text-center">
                    <div class="row col-md-12">
                        <div class="text-center col-md-12">
                            <br>
                            <h2><?php echo $helpTopicCount ?></h2>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card control-color fb-card col-md-5">
                <div class="card-header">
                    <i class="icofont icofont-social-facebook"></i>
                    <div class="d-inline-block">
                        <h5>Departments</h5>
                        <span></span>
                    </div>
                </div>
                <div class="card-block text-center">
                    <div class="row col-md-12">
                        <div class="text-center col-md-12">
                            <br>
                            <h2><?php echo $DepartmentCount ?></h2>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php
    }
}
?>
    <!-- end main -->
<?php include 'includes/footer.inc.php' ?>