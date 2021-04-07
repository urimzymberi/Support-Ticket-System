<?php

use App\Lib\Department;

include_once '../includes/session.inc.php';
include_once '../autoloader.php';
include_once '../includes/fill_dropdownlist.inc.php';
if (!isset($_SESSION['isadmin'])) {
    header("Location: index.php");
    exit();
}
$objDepartment = new Department();

$nameError = $managerError = $emailError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;

    $objDepartment->set_name($_POST["name"]);
    $objDepartment->set_manager_id($_POST["manager"]);
    $objDepartment->set_email($_POST["email"]);
    $objDepartment->set_ispublic(isset($_POST["ispublic"]) ? 1 : 0);
    $objDepartment->set_created(date('Y-m-d H:i:s'));
    $objDepartment->set_updated(date('Y-m-d H:i:s'));

    if (empty($objDepartment->get_name())) {
        $nameError = "Name is required";
        $check = false;
    } else {
        $nameError = "";
    }

    if ($objDepartment->get_manager_id() == 0) {
        $managerError = "Manager is required";
        $check = false;
    } else {
        $managerError = "";
    }

    if (empty($objDepartment->get_email())) {
        $emailError = "Email is required";
        $check = false;
    } else {
        $emailError = "";
    }

    if ($check) {
        if ($objDepartment->create()) {
            $_SESSION['alert'] = array('success', 'Department successful created');
            header("Location: list.php");
            exit();
        } else {
            $_SESSION['alert'] = array('success', 'Department faild created');
            header("Location: list.php");
            exit();
        }
    }
}
include '../includes/header.inc.php';
?>

<div class="content_page">
    <div class="h4 p-5"><strong>New Department</strong></div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input class="form-control" name="name" id="name" type="text">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $nameError; ?></div>
                            <div class="invalid-feedback">Required Name</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="manager">Manager:</label>
                            <select class="control-color form-control-sm form-control" name="manager" id="manager">
                                <option value="0">--Select a Staff Member--</option>
                                <?php
                                $staff_members = check_staff();
                                foreach ($staff_members as $staff_member) {
                                    echo '<option value="' . $staff_member['id'] . '">' . $staff_member['firstname'] . ' ' . $staff_member['lastname'] . '</option>';
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $managerError; ?></div>
                            <div class="invalid-feedback">Required Manager</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input class="form-control" name="email" id="email" type="text">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $emailError; ?></div>
                            <div class="invalid-feedback">Required Email</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="ispublic">Is Public:</label>
                            <input class="form-check-input row ml-3" id="ispublic" name="ispublic" value="1"
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
    </form>
</div>
<?php
include '../includes/footer.inc.php';
?>
<script>
    $("#department_new").addClass("active");
</script>
<script>
    $('#btnSave').click(function () {
        if ($('#name').val() == '') {
            $("#name").addClass("is-invalid");
            return false;
        }
        if ($('#email').val() == '') {
            $("#email").addClass("is-invalid");
            return false;
        }
        if ($('#manager').val() == 0) {
            $("#manager").addClass("is-invalid");
            return false;
        }
    });
    $("#name").keyup(function () {
        $("#name").removeClass("is-invalid");
    });
    $("#email").keyup(function () {
        $("#email").removeClass("is-invalid");
    });
    $("#manager").click(function () {
        $("#manager").removeClass("is-invalid");
    });
</script>