<?php

use App\Lib\Client;

include '../includes/session.inc.php';
include '../autoloader.php';

$oldpasswordError = $passwordError = $confirmpasswordError = "";
$oldpassword = $password = $confirmpassword = "";

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else {
    header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;
    $objClient = new Client();
    $Client = $objClient->get_by_id($_SESSION['id']);
    $objClient->fill_properties($Client);

    $oldpassword = $_POST["oldpassword"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];

    if (empty($oldpassword)) {
        $oldpasswordError = "Old Password is required";
        $check = false;
    } else {
        if (strcmp($oldpassword, $objClient->get_password()) == 0) {
            $oldpasswordError = "";
        } else {
            $oldpasswordError = "Old Password is not valid";
            $check = false;
        }
    }
    if (empty($password)) {
        $passwordError = "New Password is required";
        $check = false;
    } else {
        $passwordError = "";
    }

    if (empty($confirmpassword)) {
        $confirmpasswordError = "Confirm New Password is required";
        $check = false;
    } else {
        $confirmpasswordError = "";
    }
    if (!empty($confirmpassword) && !empty ($password)) {
        if (strcmp($password, $confirmpassword) == 0) {
            $passwordError = "";
            $confirmpasswordError = "";
            $objClient->set_password($password);

        } else {
            $passwordError = "New Password and Confirm New Password should be identical";
            $confirmpasswordError = "New Password and Confirm New Password should be identical";
            $check = false;
        }
    }
    if ($check) {
        if ($objClient->update()) {
            $_SESSION['alert'] = array('success', 'Password changed successfully!');
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['alert'] = array('error', 'Password failed to change!');
            header("Location: ../index.php");
            exit();
        }
    }
}
include '../includes/header.inc.php';
?>
    <!-- start main -->
    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
        <div class="content_page">
            <div class="h4 p-5"><strong>Change Password</strong></div>
            <div class="card">
                <div class="card-body">
                    <input class="form-control" hidden name="id" value="<?php ?>">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name">Old Password</label>
                                <input class="control-color form-control" id="oldpassword" name="oldpassword"
                                       type="password">
                                <div class="invalid-feedback"
                                     style="display:block !important;"><?php echo $oldpasswordError; ?></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name">New Password</label>
                                <input class="control-color form-control" id="password" name="password" type="password"
                                       autocomplete="off">
                                <div class="invalid-feedback"
                                     style="display:block !important;"><?php echo $passwordError; ?></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name">Confirm New Password</label>
                                <input class="control-color form-control" id="confirmpassword" name="confirmpassword"
                                       type="password">
                                <div class="invalid-feedback"
                                     style="display:block !important;"><?php echo $confirmpasswordError; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="m-3">
                        <div class="float-right">
                            <button class="btn btn-success" type="submit"> Save</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
    </div>
    <!-- end main -->

<?php
include '../includes/footer.inc.php';
?>