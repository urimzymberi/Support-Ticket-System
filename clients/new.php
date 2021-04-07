<?php
namespace App\Lib;

include '../includes/session.inc.php';
include_once '../autoloader.php';

if (!isset($_SESSION['isadmin'])) {
    header("Location: index.php");
    exit();
}
$objClient = new Client();
$nameError = $usernameError = $emailError = $phoneError = "";
$name = $username = $email = $phone = $isActive = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;

    if (empty($_POST["name"])) {
        $nameError = "Name is required";
        $check = false;
    } else {
        $nameError = "";
        if (!preg_match("/^[a-zA-Z ]*$/", $_POST['name'])) {
            $nameError = "Only letters and white space allowed";
            $check = false;
        }
    }
    if (empty(trim($_POST['username']))) {
        $usernameError = "Username is required";
        $check = false;
    } else {
        $validate = ValidationForUniqueUsername($_POST['username']);
        if ($validate > 0) {
            $usernameError = "Username is already taken";
            $check = false;
        } else {
            $usernameError = "";
        }
    }
    if (empty(trim($_POST['email']))) {
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
        $objClient->set_name($_POST["name"]);
        $objClient->set_username($_POST["username"]);
        $objClient->set_password(randomPassword());
        $objClient->set_email($_POST['email']);
        $objClient->set_phone($_POST['phone']);
        $objClient->set_status(isset($_POST["isActive"]) ? 1 : 0);
        $objClient->set_created(date('Y-m-d H:i:s'));
        $objClient->set_updated(date('Y-m-d H:i:s'));

        if ($objClient->create()) {
            $_SESSION['alert'] = array('success', 'Client successfully saved!');
            $to = $email;
            $subject = 'Account Creation';
            $message = 'Dear ' . $name . "\r\n" .
                'An account has been created for you in Ticket App with username: "' . $validationUsername . '" and password: "' . $password . '"';
            $headers = 'From: testticketapp2020@gmail.com' . "\r\n" .
                'Reply-To: testticketapp2020@gmail.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
            header("Location: list.php");
            exit;
        } else {
            $_SESSION['alert'] = array('error', 'Client failed to save!');
            header("Location: list.php");
            exit();
        }
    }
}
function randomPassword()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

function ValidationForUniqueUsername($username)
{
    $objClient = new  Client();

    $sql = "SELECT id FROM client WHERE  username ='" . $username . "'";
    $stmt = $objClient->connectDB()->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

include '../includes/header.inc.php';
?>
<!-- start main -->

<div class="content_page">
    <div class="h4 p-5"><strong> New Client</strong></div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input class="form-control" name="name" id="name" type="text">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $nameError; ?></div>
                            <div class="invalid-feedback">Required Name</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Username</label>
                            <input class="form-control" id="username" name="username" type="text">
                            <div class="invalid-feedback" placeholder="Spaces not allowed"
                                 style="display:block !important;"><?php echo $usernameError; ?></div>
                            <div class="invalid-feedback">Required Username</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input class="form-control" id="email" name="email" type="text">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $emailError; ?></div>
                            <div class="invalid-feedback">Required Email</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Phone</label>
                            <input class="form-control" id="phone" name="phone" type="text">
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
                            <input class="form-check-input ml-3" id="isActive" name="isActive" type="checkbox">
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
    $("#client_new").addClass("active");
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
