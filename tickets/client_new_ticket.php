<?php
include '../includes/fill_dropdownlist.inc.php';
include '../includes/session.inc.php';
include '../includes/header.inc.php';
$client_info = client_info($_SESSION["id"]);
?>
<!-- start main -->
<div class="page_header_content">
    <div class="h4 p-5"><strong>New Ticket</strong></div>
</div>
<form action="../includes/new_ticket.inc.php" method="POST">

    <div class="content_page mb-2">
        <div class="card">
            <div class="c-body">
                <main class="c-main">
                    <div class="container-fluid">
                        <div id="ui-view">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="client_email">Client Email:</label>
                                                    <input class="form-control-sm form-control" disabled=""
                                                           name="client_email" id="client_email" type="text"
                                                           value="<?php echo $client_info['email']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="help-topic">Help Topic:</label>
                                            <select class="control-color form-control-sm form-control" name="help_topic"
                                                    id="help_topic">
                                                <option value="0" id="help_topic">--Select Help Topic--</option>
                                                <?php
                                                $help_topics = check_topic();
                                                foreach ($help_topics as $help_topic) {
                                                    echo '<option value="' . $help_topic['id'] . '">' . $help_topic['topic'] . '</option>';
                                                }
                                                echo '</select>';
                                                ?>
                                                <div class="invalid-feedback"> Required Help Topic</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="full_name">Full Name:</label>
                                                    <input class="form-control-sm form-control" disabled=""
                                                           name="full_name" id="full_name" type="text"
                                                           placeholder="<?php echo $client_info['name'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="priority">Priority Level:</label>
                                            <select class="control-color form-control-sm form-control" name="priority"
                                                    id="priority">
                                                <?php
                                                $array_priority = array("High" => "3", "Normal" => "2", "Emergency" => "4", "Low" => "1");
                                                asort($array_priority);
                                                if (isset($_GET['ptiority'])) {
                                                    foreach ($array_priority as $priority => $priority_id) {
                                                        if ($_GET['ptiority'] == $priority_id) {
                                                            echo '<option value="' . $priority_id . '">' . $priority . '</option>';
                                                        }
                                                    }
                                                    foreach ($array_priority as $priority => $priority_id) {
                                                        if ($_GET['ptiority'] != $priority_id) {
                                                            echo '<option value="' . $priority_id . '">' . $priority . '</option>';
                                                        }
                                                    }
                                                } else {
                                                    foreach ($array_priority as $priority => $priority_id) {
                                                        echo '<option value="' . $priority_id . '">' . $priority . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group col-sm-12">
                                            <label for="department">Department:</label>
                                            <select class="control-color control-color form-control-sm form-control"
                                                    name="department"
                                                    id="department">
                                                <option value="0">To All</option>
                                                <?php
                                                $deppartments = check_department();
                                                foreach ($deppartments as $dept) {
                                                    echo '<option value="' . $dept['dept_id'] . '">' . $dept['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="subject">Subject:</label>
                                        <?php
                                        if (isset($_GET['subject'])) {
                                            if ($_GET['subject'] != '') {
                                                echo '<input class="form-control-sm form-control" name="subject" id="subject" autocomplete="off" type="text" value="' . $_GET['subject'] . '" placeholder="">';
                                            } else {
                                                echo '<input class="form-control-sm form-control" name="subject" id="subject" autocomplete="off" type="text"  placeholder="Enter Subject">';
                                                echo '<div class="invalid-feedback" style="display:block !important;"> Required Subject</div>';
                                            }
                                        } else {
                                            echo '<input class="form-control-sm form-control" name="subject" id="subject" autocomplete="off" type="text"  placeholder="Enter Subject">';
                                        }
                                        ?>
                                        <div class="invalid-feedback"> Required Subject</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="description">Description:</label>
                                        <?php
                                        if (isset($_GET['description'])) {
                                            if ($_GET['description'] != '') {
                                                echo '<textarea class="control-color form-control-sm form-control" name="description" id="description" rows="5" placeholder="">' . $_GET['description'] . '</textarea>';
                                            } else {
                                                echo '<textarea class="control-color form-control-sm form-control" name="description" id="description" rows="5" placeholder=""></textarea>';
                                                echo '<div class="invalid-feedback" style="display:block !important;"> Required Description</div>';
                                            }
                                        } else {
                                            echo '<textarea class="control-color form-control-sm form-control" name="description" id="description" rows="5" placeholder=""></textarea>';
                                        }
                                        ?>
                                        <div class="invalid-feedback" id="desc_invalid"> Required Description</div>
                                    </div>
                                </div>
                            </div>
                            <div class="float-right my-3">
                                <button class="btn btn-primary" name="btnOpen" type="submit" id="btnOpen"> Open</button>
                                <button class="btn btn-danger" name="btnReset" type="reset"> Reset</button>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
</form>
</div>
<!-- end main -->

<?php include '../includes/footer.inc.php'; ?>
<script>
    $("#new-ticket").addClass("active");
</script>
<script>
    $('#btnOpen').click(function () {
        var error = false;
        if ($('#help_topic').val() == 0) {
            $("#help_topic").addClass("is-invalid");
            error = true;
        }
        if ($("#subject").val() == "") {
            $("#subject").addClass("is-invalid");
            error = true;
        }
        if ($("#description").val() == "") {
            $("#desc_invalid").css("display", "block");
            error = true;
        }
        if (error) {
            return false;
        } else {
            return true;
        }
    });
    $("#help_topic").click(function () {
        $("#help_topic").removeClass("is-invalid");
    });
    $("#subject").keyup(function () {
        $("#subject").removeClass("is-invalid");
    });
    $("#description").keyup(function () {
        $("#desc_invalid").css("display", "none");
    });
</script>