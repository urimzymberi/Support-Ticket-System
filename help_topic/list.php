<?php

use App\Lib\Help_topic;

include '../includes/session.inc.php';
include '../autoloader.php';
if (!isset($_SESSION['isadmin'])) {
    header("Location: ../index.php");
}
include '../includes/header.inc.php';
// if(isset( $_SESSION["alert"]))
// {
//     $alert_array= $_SESSION["alert"];
//     echo "<script>
//             Swal.fire({
//                 position: 'top-end',
//                 icon: '$alert_array[0]',
//                 title: '$alert_array[1]',
//                 showConfirmButton: false,
//                 timer: 1500
//             })
//         </script>";
//     unset($_SESSION["alert"]);
// }
$objHelpTopic = new Help_topic();

$help_topics = $objHelpTopic->get_all();
// $query = "select * from help_topic";
// $result = mysqli_query($conn, $query);
// mysqli_close($conn);


?>
<!-- start main -->


<div class="content_page">
    <div class="h4 p-5"><strong>Help Topics</strong></div>
    <div class="card border-0">
        <div class="card-body">
            <table class="dataTable compact hover row-border">
                <thead>
                <tr class="h6">
                    <th class="d-none">Id</th>
                    <th>Topic</th>
                    <th>Date registered</th>
                    <th>Status</th>
                    <th></th>
                </thead>
                <tbody>
                <?php
                foreach ($help_topics as $help_topic) {
                    ?>
                    <tr>
                        <td class="d-none"><?php echo $help_topic['id']; ?></td>
                        <td><?php echo $help_topic['topic']; ?></td>
                        <td><?php echo $help_topic['created']; ?></td>
                        <td><?php echo $help_topic['isactive'] == 1 ? "Active" : "Passive"; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $help_topic['id']; ?>"
                               class="btn btn-sm btn-outline-warning">Edit</a>
                            <a style="color:#e55353" onclick="Delete(<?php echo $help_topic['id']; ?>)"
                               class="btn btn-sm btn-outline-danger">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- end main -->
<?php
include '../includes/footer.inc.php';
?>
<script>
    $("#help_topic_list").addClass("active");
</script>
<script>
    function Delete(id) {
        Swal.fire({
            title: 'Are you sure you want to continue?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                window.location.href = "delete.php?id=" + id;
            }
        })
    }
</script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable();
    });
</script>