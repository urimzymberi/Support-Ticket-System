<?php

use App\Lib\Client;

include '../includes/session.inc.php';
include_once '../autoloader.php';

if (!isset($_SESSION['isadmin'])) {
    header("Location: ../index.php");
    exit();
}
$nameError = $usernameError = $emailError = $phoneError = "";
$name = $username = $email = $phone = $isActive = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $objClient = new  Client();

    $Client = $objClient->get_by_id($id);
    $objClient->fill_properties($Client);
} else {
    header("Location: ../index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;

    $objClient->set_name($_POST["name"]);
    $objClient->set_username($_POST["username"]);
    $objClient->set_email($_POST["email"]);
    $objClient->set_phone($_POST["phone"]);
    $objClient->set_status(isset($_POST["isActive"]) ? 1 : 0);
    $objClient->set_updated(date('Y-m-d H:i:s'));


    if (empty($_POST["name"])) {
        $nameError = "Name is required";
        $check = false;
    } else {
        $nameError = "";
        if (!preg_match("/^[a-zA-Z ]*$/", $_POST["name"])) {
            $nameError = "Only letters and white space allowed";
            $check = false;
        }
    }
    if (empty($_POST['username'])) {
        $usernameError = "Username is required";
        $check = false;
    } else {
        $validate = ValidationForUniqueUsername($id, $$_POST['username']);
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
    }
    if ($check) {
        if ($objClient->update()) {
            $_SESSION['alert'] = array('success', 'Client updated successfully!');
            header("Location: list.php");
            exit();
        } else {
            $_SESSION['alert'] = array('error', 'Client failed updated!');
            header("Location: list.php");
            exit();
        }
    }
}
function ValidationForUniqueUsername($id, $username)
{
    $objClient = new  Client();

    $sql = "SELECT id FROM `client` WHERE  id != '" . $id . "' AND username ='" . $username . "'";
    $stmt = $objClient->connectDB()->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

include '../includes/header.inc.php';
?>

<!-- start main -->

<form method="post" action="">
    <div class="content_page">
        <div class="h4 p-5"><strong>Edit Client</strong></div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <input hidden class="form-control" id="id" name="id" type="text" value="<?php echo $id ?>">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input class="form-control" id="name" name="name" type="text"
                                   value="<?php echo $objClient->get_name(); ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $nameError; ?></div>
                            <div class="invalid-feedback">Required Full Name</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Username</label>
                            <input class="form-control" id="username" name="username" type="text"
                                   value="<?php echo $objClient->get_username(); ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $usernameError; ?></div>
                            <div class="invalid-feedback">Requrired Username</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input class="form-control" id="email" name="email" type="text"
                                   value="<?php echo $objClient->get_email() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $emailError; ?></div>
                            <div class="invalid-feedback">Required Email</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Phone</label>
                            <input class="form-control" id="phone" name="phone" type="text"
                                   value="<?php echo $objClient->get_phone(); ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $phoneError; ?></div>
                            <div class="invalid-feedback">Required Phone</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="isActive">Is Active</label>
                            <input class="form-check-input ml-3" id="isActive"
                                   name="isActive" <?php echo $objClient->get_status() == 1 ? "checked" : ""; ?>
                                   type="checkbox">
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
    </div>
</form>
</div>

<!-- end main -->

<?php
include '../includes/footer.inc.php';
?>
<script>
    $("#client_list").addClass("active");
</script>
<script>
    $('#btnSave').click(function () {
        var error = false;
        if ($('#name').val() == '') {
            $("#name").addClass("is-invalid");
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
    $("#name").keyup(function () {
        $("#name").removeClass("is-invalid");
    });
    $("#username").keyup(function () {
        $("#username").removeClass("is-invalid");
    });
    $("#email").keyup(function () {
        $("#email").removeClass("is-invalid");
    });
    $("#phone").keyup(function () {
        $("#phone").removeClass("is-invalid");
    });
</script>