</div>
<div id="footer" class="d-flex mt-auto justify-content-end pr-3 py-3">
    <div class="">Support Ticket&nbsp;|&nbsp;2020</div>
</div>
</div>
</div>
</body>

</html>

<script src='<?php echo $other ?>js/hide_show_sidenac.js'></script>
<script src='<?php echo $other ?>js/dark_light_mode.js'></script>
<script>
    $("#logout").click(function () {
        $.get("includes/logout.inc.php");
    });
</script>
<script>
    $('#fullnameUser').text("<?php
        if (isset($_SESSION["name"])) {
            echo $_SESSION["name"];
        } else {
            echo "rrrrrrrrr";
        }
        ?>");
</script>
<?php
if (!isset($_SESSION["isadmin"])) {
    echo "<script>
    $('#users').addClass('d-none');
    $('#clients').addClass('d-none');
    $('#help_topics').addClass('d-none');  
    $('#department').addClass('d-none');  
    </script>";
} else {
    if ($_SESSION['isadmin'] != 1) {
        echo "<script>
        $('#users').addClass('d-none');
        </script>";
    }
}
?>
<!-- <script>
    $(window).on('load', function () {
        $("#dataTable_length label").hide();//replaceWith('<label>Show <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select></label>');
        $("#dataTable_filter label").addClass("float-right");
    });
</script> -->




