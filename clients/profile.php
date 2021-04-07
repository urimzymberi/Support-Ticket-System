<?php

use App\Lib\Client;

include '../includes/session.inc.php';
include '../autoloader.php';


$nameError = $usernameError = $emailError = $phoneError = "";
$name = $username = $email = $phone = "";
$objClient = new Client();

if (isset($_SESSION['id'])) {
    $client = $objClient->get_by_id($_SESSION['id']);
    $objClient->fill_properties($client);
} else {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;

    $objClient->set_name($_POST["name"]);
    $objClient->set_username($_POST["username"]);
    $objClient->set_email($_POST["email"]);
    $objClient->set_phone($_POST["phone"]);
    $objClient->set_updated(date('Y-m-d H:i:s'));


    if (empty($objClient->get_name())) {
        $nameError = "Name is required";
        $check = false;
    } else {
        $nameError = "";
        if (!preg_match("/^[a-zA-Z ]*$/", $objClient->get_name())) {
            $nameError = "Only letters allowed";
            $check = false;
        }
    }
    if (empty($objClient->get_username())) {
        $usernameError = "Username is required";
        $check = false;
    } else {
        $validate = ValidationForUniqueUsername($objClient->get_id(), $objClient->get_username());
        if ($validate > 0) {
            $usernameError = "Username is already taken";
            $check = false;
        } else {
            $usernameError = "";
        }
    }
    if (empty($objClient->get_email())) {
        $emailError = "Email is required";
        $check = false;
    } else {
        $emailError = "";
        if (!filter_var($objClient->get_email(), FILTER_VALIDATE_EMAIL)) {
            $emailError = "Email is invalid";
            $check = false;
        }
    }
    if (empty($objClient->get_phone())) {
        $phoneError = "Phone is required";
        $check = false;
    } else {
        $phoneError = "";
        if (!preg_match('/^[0-9]+$/', $objClient->get_phone())) {
            $phoneError = "Only numbers allowed";
            $check = false;
        }
    }
    if ($check) {
        if ($objClient->update()) {
            $_SESSION['alert'] = array('success', 'Profile successfully saved!');
            header("Location:  profile.php");
            exit();
        } else {
            $_SESSION['alert'] = array('error', 'Profile failed to save!',);
            header("Location: profile.php");
            exit();
        }
    }
}

function ValidationForUniqueUsername($id, $username)
{
    $objClient = new  Client();
    $sql = "SELECT id FROM client WHERE  id != '" . $id . "' AND username ='" . $username . "'";
    $stmt = $objClient->connectDB()->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

include '../includes/header.inc.php';
?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <div class="content_page">
        <div class="h4 p-5"><strong>Profile</strong></div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- <input class="form-control" hidden name="id" value="<?php //echo $id ?>"> -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input class="form-control" id="name" name="name" type="text"
                                   value="<?php echo $objClient->get_name() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $nameError; ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Username</label>
                            <input class="form-control" id="username" name="username" type="text"
                                   value="<?php echo $objClient->get_username() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $usernameError; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input class="form-control" id="email" name="email" type="text"
                                   value="<?php echo $objClient->get_email() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $emailError; ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Phone</label>
                            <input class="form-control" id="phone" name="phone" type="text"
                                   value="<?php echo $objClient->get_phone() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $phoneError; ?></div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="isVisible">Is Visible</label>
                            <input class="form-check-input row ml-3" onclick="return false;" id="isVisible"
                                   name="isVisible" type="checkbox" <?php //echo $isVisible == 1 ? "checked" : "" ?>>
                        </div>
                    </div>
                </div> -->
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
<?php include '../includes/footer.inc.php'; ?>

