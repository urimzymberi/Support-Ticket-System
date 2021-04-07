<?php

use App\Lib\User;

include '../includes/session.inc.php';
include '../autoloader.php';
include '../includes/fill_dropdownlist.inc.php';
if (isset($_SESSION["isadmin"])) {
    if ($_SESSION["isadmin"] != 1) {
        header("Location: ../index.php");
    }
} else {
    header("Location: ../index.php");
}
$firstnameError = $lastnameError = $usernameError = $emailError = $passwordError = $phoneError = $confirmpasswordError = "";
$confirmpassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;
    $objUser = new User();

    $objUser->set_firstname($_POST["firstname"]);
    $objUser->set_lastname($_POST["lastname"]);
    $objUser->set_username($_POST["username"]);
    $objUser->set_email($_POST["email"]);
    $objUser->set_password($_POST["password"]);
    $objUser->set_phone($_POST["phone"]);
    $objUser->set_dept_id($_POST['department']);
    $objUser->set_isactive(isset($_POST["isActive"]) ? 1 : 0);
    $objUser->set_isadmin(isset($_POST["isAdmin"]) ? 1 : 0);
    $objUser->set_isvisible(isset($_POST["isVisible"]) ? 1 : 0);
    $objUser->set_created(date('Y-m-d H:i:s'));
    $objUser->set_updated(date('Y-m-d H:i:s'));

    $confirmpassword = $_POST["confirmpassword"];

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
        $validate = ValidationForUniqueUsername($objUser->get_username());
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

    if (empty($objUser->get_password())) {
        $passwordError = "Password is required";
        $check = false;
    } else {
        $passwordError = "";
    }

    if (empty($confirmpassword)) {
        $confirmpasswordError = "Confirm Password is required";
        $check = false;
    } else {
        $confirmpasswordError = "";
    }

    if (!empty($confirmpassword) && !empty ($objUser->get_password())) {
        if (strcmp($objUser->get_password(), $confirmpassword) == 0) {
            $passwordError = "";
            $confirmpasswordError = "";
        } else {
            $passwordError = "Password and Confirm Password should be identical";
            $confirmpasswordError = "Password and Confirm Password should be identical";
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

        if ($objUser->create()) {
            $_SESSION['alert'] = array('success', 'User successful to save!');
            header("Location: list.php");
            exit();
        } else {
            $_SESSION['alert'] = array('error', 'User failed to save!');
            header("Location: list.php");
            exit();
        }
    }
}
function ValidationForUniqueUsername($username)
{
    $objUser = new User();
    $sql = "SELECT id FROM staff WHERE username ='" . $username . "'";
    $stmt = $objUser->connectDB()->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

include '../includes/header.inc.php';
?>

<!-- start main -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="content_page">
        <div class="h4 p-5"><strong>New User</strong></div>
        <div class="card mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">First Name</label>
                            <input class="form-control" name="firstname" id="firstname" type="text">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $firstnameError; ?></div>
                            <div class="invalid-feedback"> Required Firstname</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Last Name</label>
                            <input class="form-control" id="lastname" name="lastname" type="text">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $lastnameError; ?></div>
                            <div class="invalid-feedback"> Required Lastname</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Username</label>
                            <input class="form-control" id="username" name="username" type="text" autocomplete="off">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $usernameError; ?></div>
                            <div class="invalid-feedback"> Required Username</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input class="control-color form-control" id="email" name="email" type="text">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $emailError; ?></div>
                            <div class="invalid-feedback"> Required Email</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Password</label>
                            <input class="control-color form-control" id="password" name="password" type="password"
                                   autocomplete="off">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $passwordError; ?></div>
                            <div class="invalid-feedback">Required Password</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Confirm Password</label>
                            <input class="control-color form-control" id="confirmpassword" name="confirmpassword"
                                   type="password">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $confirmpasswordError; ?></div>
                            <div class="invalid-feedback">Required Confirm Password</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Phone</label>
                            <input class="form-control" id="phone" name="phone" type="text" autocomplete="off">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $phoneError; ?></div>
                            <div class="invalid-feedback"> Required Phone</div>
                        </div>
                    </div>
                    <div class="col-sm-6 row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="isActive">Is Active</label>
                                <input class="form-check-input row ml-3" id="isActive" name="isActive" value="1"
                                       type="checkbox">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="isAdmin">Is Admin</label>
                                <input class="form-check-input row ml-3" id="isAdmin" name="isAdmin"
                                       value="1" type="checkbox">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="isVisible">Is Visible</label>
                                <input class="form-check-input row ml-3" id="isVisible"
                                       name="isVisible" value="1" type="checkbox">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select class="control-color form-control department" id="department" name="department">
                                <option value="0">--Select Dpartment--</option>
                                <?php
                                $departments = check_department();
                                foreach ($departments as $dept) {
                                    echo '<option value="' . $dept['id'] . '">' . $dept['name'] . '</option>';
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $departmentError; ?></div>
                            <div class="invalid-feedback"> Required Department</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3">
                <div class="float-right">
                    <button class="btn btn-success" type="submit" id="btnSave"> Save</button>
                </div>
            </div>
        </div>
</form>
</div>
<!-- end main -->


<?php
include '../includes/footer.inc.php';
?>
<script>
    $("#user_new").addClass("active");
</script>
<script>
    $('#btnSave').click(function () {
        var error = false;
        if ($('#firstname').val() == '') {
            $("#firstname").addClass("is-invalid");
            error = true;
        }
        if ($('#lastname').val() == '') {
            $("#lastname").addClass("is-invalid");
            error = true;
        }
        if ($('#username').val() == '') {
            $("#username").addClass("is-invalid");
            error = true;
        }
        if ($('#email').val() == '') {
            $("#email").addClass("is-invalid");
            error = true;
        }
        if ($('#password').val() == '') {
            $("#password").addClass("is-invalid");
            error = true;
        }
        if ($('#confirmpassword').val() == '') {
            $("#confirmpassword").addClass("is-invalid");
            error = true;
        }
        if ($('#phone').val() == '') {
            $("#phone").addClass("is-invalid");
            error = true;
        }
        if (error) {
            return false;
        } else {
            return true;
        }
    });
    $("#firstname").keyup(function () {
        $("#firstname").removeClass("is-invalid");
    });
    $("#lastname").keyup(function () {
        $("#lastname").removeClass("is-invalid");
    });
    $("#username").keyup(function () {
        $("#username").removeClass("is-invalid");
    });
    $("#email").keyup(function () {
        $("#email").removeClass("is-invalid");
    });
    $("#password").keyup(function () {
        $("#password").removeClass("is-invalid");
    });
    $("#confirmpassword").keyup(function () {
        $("#confirmpassword").removeClass("is-invalid");
    });
    $("#phone").keyup(function () {
        $("#phone").removeClass("is-invalid");
    });
</script>
<script>
    $("#isAdmin").change(function () {
        if (this.checked) {
            $(".department").attr('disabled', true);
            $(".department").val($(".department option:first").val());

        } else {
            $(".department").attr('disabled', false);
        }
    });
</script>