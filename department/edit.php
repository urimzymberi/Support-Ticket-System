<?php

use App\Lib\Department;

include '../includes/session.inc.php';
include '../autoloader.php';
include '../includes/fill_dropdownlist.inc.php';
if (!isset($_SESSION['isadmin'])) {
    header("Location: ../index.php");
}
$objDepartment = new Department();
$nameError = $managerError = $emailError = "";
$name = $manager = $email = $ispublic = $id = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $department = $objDepartment->get_by_id($_GET['id']);
    $objDepartment->fill_properties($department);
} else {
    header("Location: ../index.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;

    $objDepartment->set_name($_POST["name"]);
    $objDepartment->set_manager_id($_POST['manager']);
    $objDepartment->set_email($_POST['email']);
    $objDepartment->set_ispublic(isset($_POST["ispublic"]) ? 1 : 0);
    $objDepartment->set_updated(date('Y-m-d H:i:s'));

    if (empty($objDepartment->get_name())) {
        $nameError = "Name is required";
        $check = false;
    } else {
        $nameError = "";
    }

    if (empty($objDepartment->get_email())) {
        $emailError = "Email is required";
        $check = false;
    } else {
        $emailError = "";
    }

    if ($check) {

        if ($objDepartment->update()) {
            $_SESSION['alert'] = array('success', 'Department successful updated');
            header("Location: list.php");
            exit();
        } else {
            $_SESSION['alert'] = array('warning', 'Department failed updated!');
            header("Location: list.php");
            exit();
        }
    }
}
include '../includes/header.inc.php';
?>
<div class="content_page">
    <div class="h4 p-5"><strong>Department</strong></div>
    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input class="form-control" name="name" id="name" type="text"
                                   value="<?php echo $objDepartment->get_name(); ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $nameError; ?></div>
                            <div class="invalid-feedback">Required Name</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="manager">Manager:</label>
                            <select class="control-color form-control-sm form-control" name="manager" id="manager">
                                <option value="<?php echo $objDepartment->get_manager_id(); ?>">
                                    <?php
                                    $staff_members = check_staff();
                                    foreach ($staff_members as $staff_member) {
                                        if ($staff_member['id'] == $objDepartment->get_manager_id()) {
                                            echo $staff_member['firstname'] . ' ' . $staff_member['lastname'];
                                        }
                                    }
                                    ?></option>
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
                            <input class="form-control" name="email" id="email" type="text"
                                   value="<?php echo $objDepartment->get_email(); ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $emailError; ?></div>
                            <div class="invalid-feedback">Required Email</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="ispublic">Is Public:</label>
                            <input class="form-check-input row ml-3" id="ispublic" name="ispublic" value="1"
                                   type="checkbox" <?php if ($objDepartment->get_ispublic() == 1) {
                                echo 'checked';
                            } ?>>
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
    $("#department_list").addClass("active");
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
    });
    $("#name").keyup(function () {
        $("#name").removeClass("is-invalid");
    });
    $("#email").keyup(function () {
        $("#email").removeClass("is-invalid");
    });
</script>