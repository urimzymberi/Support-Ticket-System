<?php

use App\Lib\Help_topic;

include '../includes/session.inc.php';
include '../autoloader.php';

if (!isset($_SESSION['isadmin'])) {
    header("Location: ../index.php");
}
$topicError = $isActiveError = "";
$topic = $isActive = $id = "";

$objHelpTipic = new Help_topic();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $helpTopic = $objHelpTipic->get_by_id($id);
    $objHelpTipic->fill_properties($helpTopic);
} else {
    header("Location: ../index.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;

    // $objHelpTipic->set_dept_id($helpTopic['dept_id']);
    $objHelpTipic->set_isactive(isset($_POST["isActive"]) ? 1 : 0);
    $objHelpTipic->set_topic($_POST["topic"]);
    $objHelpTipic->set_updated(date('Y-m-d H:i:s'));

    if (empty($objHelpTipic->get_topic())) {
        $topicError = "Topic is required";
        $check = false;
    } else {
        $topicError = "";
    }

    if ($check) {
        if ($objHelpTipic->update()) {
            $_SESSION['alert'] = array('success', 'Help topic successful updated');
            header("Location: list.php");
            exit();
        } else {
            $_SESSION['alert'] = array('warning', 'Help topic failed updated!');
            header("Location: list.php");
            exit();
        }
    }
}
include '../includes/header.inc.php';
?>
<div class="content_page">
    <div class="h4 p-5"><strong>Help Topics</strong></div>
    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <input class="form-control" hidden name="id" value="<?php echo $id ?>">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Topic</label>
                            <input class="form-control" name="topic" id="topic" type="text"
                                   value="<?php echo $objHelpTipic->get_topic() ?>">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $topicError; ?></div>
                            <div class="invalid-feedback">Required Topic</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="isActive">Is Active</label>
                            <input class="form-check-input row ml-3" id="isActive" name="isActive" value="1"
                                   type="checkbox" <?php echo $objHelpTipic->get_isactive() == 1 ? "checked" : "" ?>>
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
    $("#help_topic_list").addClass("active");
</script>
<script>
    $('#btnSave').click(function () {
        if ($('#topic').val() == '') {
            $("#topic").addClass("is-invalid");
            return false;
        }
    });
    $("#topic").keyup(function () {
        $("#topic").removeClass("is-invalid");
    });
</script>