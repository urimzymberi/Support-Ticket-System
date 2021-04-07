<?php

use App\Lib\User;

include '../includes/session.inc.php';
include '../autoloader.php';

if (!isset($_SESSION['isadmin'])) {
    header('Location: index.php');
}

$oldpasswordError = $passwordError = $confirmpasswordError = "";
$oldpassword = $password = $confirmpassword = "";

$objUser = new User();

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $user = $objUser->get_by_id($id);
    $objUser->fill_properties($user);
} else {
    header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;

    $id = $_POST["id"];
    $oldpassword = $_POST["oldpassword"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];
    if (empty($oldpassword)) {
        $oldpasswordError = "Old Password is required";
        $check = false;
    } else {

        if (strcmp($oldpassword, $user["password"]) == 0) {
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
        } else {
            $passwordError = "New Password and Confirm New Password should be identical";
            $confirmpasswordError = "New Password and Confirm New Password should be identical";
            $check = false;
        }
    }
    if ($check) {
        $objUser->set_password($password);

        if ($objUser->update()) {
            $_SESSION['alert'] = array('success', 'Password changed successfully!');
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['alert'] = array('error', 'Password failed to change!');
            header("Location: index.php");
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
                    <input class="form-control" hidden name="id" value="<?php echo $id ?>">
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