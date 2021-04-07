<?php

use App\Lib\Department;
use App\Lib\User;

include '../includes/session.inc.php';
include '../autoloader.php';
include '../includes/fill_dropdownlist.inc.php';

if (!isset($_SESSION['isadmin'])) {
    header('Location: ../index.php');
    exit();
}
$firstnameError = $lastnameError = $usernameError = $emailError = $phoneError = "";
$firstname = $lastname = $username = $email = $phone = "";
$objUser = new User();

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $user = $objUser->get_by_id($id);
    $objUser->fill_properties($user);
} else {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;

    $objUser->set_dept_id($_POST['department']);
    $objUser->set_firstname($_POST['firstname']);
    $objUser->set_lastname($_POST['lastname']);
    $objUser->set_username($_POST['username']);
    $objUser->set_email($_POST['email']);
    $objUser->set_phone($_POST['phone']);
    $objUser->set_updated(date('Y-m-d H:i:s'));

    if (empty($objUser->get_firstname())) {
        $firstnameError = "Firstname is required";
        $check = false;
    } else {
        $firstnameError = "";
        if (!preg_match("/^[a-zA-Z ]*$/", $objUser->get_firstname())) {
            $firstnameError = "Only letters allowed";
            $check = false;
        }
    }
    if (empty($objUser->get_lastname())) {
        $lastnameError = "Lastname is required";
        $check = false;
    } else {
        $lastnameError = "";
        if (!preg_match("/^[a-zA-Z ]*$/", $objUser->get_lastname())) {
            $lastnameError = "Only letters allowed";
            $check = false;
        }
    }
    if (empty($objUser->get_username())) {
        $usernameError = "Username is required";
        $check = false;
    } else {
        $validate = ValidationForUniqueUsername($objUser->get_id(), $objUser->get_username());
        if ($validate > 0) {
            $usernameError = "Username is already taken";
            $check = false;
        } else {
            $usernameError = "";
        }
    }
    if (empty($objUser->get_email())) {
        $emailError = "Email is required";
        $check = false;
    } else {
        $emailError = "";
        if (!filter_var($objUser->get_email(), FILTER_VALIDATE_EMAIL)) {
            $emailError = "Email is invalid";
            $check = false;
        }
    }
    if (empty($objUser->get_phone())) {
        $phoneError = "Phone is required";
        $check = false;
    } else {
        $phoneError = "";
        if (!preg_match('/^[0-9]+$/', $objUser->get_phone())) {
            $phoneError = "Only numbers allowed";
            $check = false;
        }
    }
    if ($check) {
        $_SESSION["firstname"] = $objUser->get_firstname();
        $_SESSION["lastname"] = $objUser->get_lastname();
        $_SESSION["username"] = $objUser->get_username();
        if ($_SESSION["isadmin"] == 1) {
            $objUser->set_isactive(isset($_POST["isActive"]) ? 1 : 0);
            $objUser->set_isadmin(isset($_POST["isAdmin"]) ? 1 : 0);
            $objUser->set_isvisible(isset($_POST["isVisible"]) ? 1 : 0);

            $_SESSION["isactive"] = $objUser->get_isactive();
            $_SESSION["isadmin"] = $objUser->get_isadmin();
            $_SESSION["isvisible"] = $objUser->get_isvisible();
        }

        if ($objUser->update()) {
            $_SESSION['alert'] = array('success', 'Profile successfully saved!');
            header("Location: list.php");
            exit();
        } else {
            $_SESSION['alert'] = array('error', 'Profile failed to save!',);
            header("Location: list.php");
            exit();
        }
    }
}
function ValidationForUniqueUsername($id, $username)
{
    $objUser = new User();
    $sql = "SELECT id FROM staff WHERE  id != " . $id . " AND username ='" . $username . "'";
    $stmt = $objUser->connectDB()->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

include '../includes/header.inc.php';
?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <div class="content_page">
        <div class="h4 p-5"><strong>Profile</strong></div>
        <div class="card mb-2">
            <div class="card-body">
                <div class="row">
                    <input class="form-control" hidden name="id" value="<?php echo $id ?>">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">First Name</label>
                            <input class="form-control" id="firstname" name="firstname" type="text"
                                   value="<?php echo $objUser->get_firstname() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $firstnameError; ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Last Name</label>
                            <input class="form-control" id="lastname" name="lastname" type="text"
                                   value="<?php echo $objUser->get_lastname() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $lastnameError; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Username</label>
                            <input class="form-control" id="username" name="username" type="text"
                                   value="<?php echo $objUser->get_username() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $usernameError; ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input class="form-control" id="email" name="email" type="text"
                                   value="<?php echo $objUser->get_email() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $emailError; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Phone</label>
                            <input class="form-control" id="phone" name="phone" type="text"
                                   value="<?php echo $objUser->get_phone() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $phoneError; ?></div>
                        </div>
                    </div>
                    <?php if ($_SESSION["isadmin"] == 1) { ?>
                        <div class="col-md-6 row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="isActive">Is Active</label>
                                    <input class="form-check-input row ml-3" id="isActive" name="isActive"
                                           type="checkbox" <?php echo $objUser->get_isactive() == 1 ? "checked" : "" ?>>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="isAdmin">Is Admin</label>
                                    <input class="form-check-input row ml-3" id="isAdmin" name="isAdmin"
                                           type="checkbox" <?php echo $objUser->get_isadmin() == 1 ? "checked" : "" ?>>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="isVisible">Is Visible</label>
                                    <input class="form-check-input row ml-3" id="isVisible" name="isVisible"
                                           type="checkbox" <?php echo $objUser->get_isvisible() == 1 ? "checked" : "" ?>>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select class="control-color form-control department" id="department" name="department">
                                <?php
                                if ($objUser->get_dept_id() == 0) {
                                    echo '<option value="0">--Select  Dpartment--</option>';
                                } else {
                                    $objDepartment = new Department();
                                    $department = $objDepartment->get_by_id($objUser->get_dept_id());
                                    echo '<option value="' . $objDepartment->get_id() . '">' . $objDepartment->get_name() . '</option>';
                                }
                                $departments = check_department();
                                foreach ($departments as $dept) {
                                    echo '<option value="' . $dept['id'] . '">' . $dept['name'] . '</option>';
                                }
                                ?>
                            </select>
                            <!-- <div class="invalid-feedback"
                                 style="display:block !important;"><?php //echo $departmentError; ?></div>
                            <div class="invalid-feedback"> Required Phone</div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-3">
                <div class="float-right">
                    <button class="btn btn-success" type="submit"> Save</button>
                </div>
            </div>
        </div>
</form>
</div>
<!-- end main -->
<?php include '../includes/footer.inc.php'; ?>

