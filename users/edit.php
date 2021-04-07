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

$objUser = new User();


$firstnameError = $lastnameError = $usernameError = $emailError = $phoneError = "";
$firstname = $lastname = $username = $email = $phone = $isActive = $isAdmin = $isVisible = $dept_name = $dept_id = "";
if (isset($_GET['id'])) {
    $user = $objUser->get_by_id($_GET['id']);
    $objUser->fill_properties($user);
} else {
    header("Location: ../index.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;
    $id = $_POST["id"];
    $objUser->set_firstname($_POST["firstname"]);
    $objUser->set_lastname($_POST["lastname"]);
    $objUser->set_username($_POST["username"]);
    $objUser->set_email($_POST["email"]);
    $objUser->set_phone($_POST["phone"]);
    $objUser->set_dept_id($_POST["department"]);

    if (empty($_POST['firstname'])) {
        $firstnameError = "Firstname is required";
        $check = false;
    } else {
        $firstnameError = "";
        if (!preg_match("/^[a-zA-Z ]*$/", $_POST['firstname'])) {
            $firstnameError = "Only letters allowed";
            $check = false;
        }
    }
    if (empty($_POST['lastname'])) {
        $lastnameError = "Lastname is required";
        $check = false;
    } else {
        $lastnameError = "";
        if (!preg_match("/^[a-zA-Z ]*$/", $_POST['lastname'])) {
            $lastnameError = "Only letters allowed";
            $check = false;
        }
    }
    if (empty($_POST['username'])) {
        $usernameError = "Username is required";
        $check = false;
    } else {
        $validate = ValidationForUniqueUsername($_GET['id'], $_POST['username']);
        if ($validate > 0) {
            $usernameError = "Username is already taken";
            $check = false;
        } else {
            $usernameError = "";
        }
    }
    if (empty($_POST['email'])) {
        $emailError = "Email is required";
        $check = false;
    } else {
        $emailError = "";
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $emailError = "Email is invalid";
            $check = false;
        }
    }
    if (empty($_POST['phone'])) {
        $phoneError = "Phone is required";
        $check = false;
    } else {
        $phoneError = "";
        if (!preg_match('/^[0-9]+$/', $_POST['phone'])) {
            $phoneError = "Only numbers allowed";
            $check = false;
        }
        $objUser->set_isactive(isset($_POST["isActive"]) ? 1 : 0);
        $objUser->set_isadmin(isset($_POST["isAdmin"]) ? 1 : 0);
        $objUser->set_isvisible(isset($_POST["isVisible"]) ? 1 : 0);
    }
    if ($check) {

        if ($objUser->update()) {
            $_SESSION['alert'] = array('success', 'User successfully updated!');
            header("Location: list.php");
            exit();
        } else {
            $_SESSION['alert'] = array('error', 'User failed to update!');
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

<!-- start main -->

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <div class="content_page">
        <div class="h4 p-5"><strong>Edit User</strong></div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <input class="form-control" hidden name="id" value="<?php echo $objUser->get_id(); ?>">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Firstname</label>
                            <input class="form-control" id="firstname" name="firstname" type="text"
                                   value="<?php echo $objUser->get_firstname(); ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $firstnameError; ?></div>
                            <div class="invalid-feedback"> Required Firstname</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Last Name</label>
                            <input class="form-control" id="lastname" name="lastname" type="text"
                                   value="<?php echo $objUser->get_lastname(); ?>">
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
                            <input class="form-control" id="username" name="username" type="text"
                                   value="<?php echo $objUser->get_username(); ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $usernameError; ?></div>
                            <div class="invalid-feedback"> Required Username</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input class="form-control" id="email" name="email" type="text"
                                   value="<?php echo $objUser->get_email(); ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $emailError; ?></div>
                            <div class="invalid-feedback"> Required Email</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Phone</label>
                            <input class="form-control" id="phone" name="phone" type="text"
                                   value="<?php echo $objUser->get_phone(); ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $phoneError; ?></div>
                            <div class="invalid-feedback"> Required Phone</div>
                        </div>
                    </div>
                    <div class="col-sm-6 row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="isActive">Is Active</label>
                                <input class="form-check-input row ml-3" id="isActive" name="isActive"
                                       type="checkbox" <?php echo $objUser->get_isactive() == 1 ? "checked" : "" ?>>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="isAdmin">Is Admin</label>
                                <input class="form-check-input row ml-3" id="isAdmin" name="isAdmin"
                                       type="checkbox" <?php echo $objUser->get_isadmin() == 1 ? "checked" : "" ?>>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="isVisible">Is Visible</label>
                                <input class="form-check-input row ml-3" id="isVisible" name="isVisible"
                                       type="checkbox" <?php echo $objUser->get_isvisible() == 1 ? "checked" : "" ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select class="control-color form-control department" id="department" name="department">
                                <?php
                                if ($dept_id == 0) {
                                    echo '<option value="0">--Select  Dpartment--</option>';
                                } else {
                                    echo '<option value="' . $dept_id . '">' . $dept_name . '</option>';
                                }
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

<?php include '../includes/footer.inc.php'; ?>

<script>
    $("#user_list").addClass("active");
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