<?php

use App\Lib\Help_topic;

include '../includes/session.inc.php';
include '../autoloader.php';
if (!isset($_SESSION['isadmin'])) {
    header("Location: index.php");
    exit();
}
$topicError = $isActiveError = "";

$objHelpTopic = new Help_topic();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check = true;

    $objHelpTopic->set_dept_id(1);
    $objHelpTopic->set_isactive(isset($_POST["isActive"]) ? 1 : 0);
    $objHelpTopic->set_topic($_POST["topic"]);
    $objHelpTopic->set_created(date('Y-m-d H:i:s'));
    $objHelpTopic->set_updated(date('Y-m-d H:i:s'));

    if (empty($objHelpTopic->get_topic())) {
        $topicError = "Topic is required";
        $check = false;
    } else {
        $topicError = "";
    }
    if ($check) {
        if ($objHelpTopic->create()) {
            $_SESSION['alert'] = array('success', 'Help topic successful created');
            header("Location: list.php");
            exit();
        } else {
            $_SESSION['alert'] = array('success', 'Help topic faild created');
            header("Location: list.php");
            exit();
        }
    }
}
include '../includes/header.inc.php';
?>

<div class="content_page">
    <div class="h4 p-5"><strong>New Topic</strong></div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Topic</label>
                            <input class="form-control" name="topic" id="topic" type="text">
                            <div class="invalid-feedback"
                                 style="display:block !important;"><?php echo $topicError; ?></div>
                            <div class="invalid-feedback">Required Topic</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="isActive">Is Active</label>
                            <input class="form-check-input row ml-3" id="isActive" name="isActive" value="1"
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
    $("#help_topic_new").addClass("active");
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