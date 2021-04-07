<?php

use App\Lib\Department;
use App\Lib\User;

include '../includes/session.inc.php';
include '../autoloader.php';
include '../includes/fill_dropdownlist.inc.php';
if (!isset($_SESSION['isadmin'])) {
    header("Location: ../index.php");
}

include '../includes/header.inc.php';

$objDepartment = new Department();
$departments = $objDepartment->get_all();
?>
<!-- start main -->
<div class="content_page mb-2">
    <div class="h4 p-5"><strong>Departments</strong></div>
    <div class="card border-0">
        <div class="card-body">
            <table class="dataTable compact hover row-border">
                <thead>
                <tr class="h6">
                    <th class="d-none">Id</th>
                    <th>Name</th>
                    <th>Manager</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($departments as $department) {
                    $objUser = new User();
                    $manager = $objUser->get_by_id($department['manager_id']);
                    ?>
                    <tr>
                        <td class="d-none"><?php echo $department['id']; ?></td>
                        <td><?php echo $department['name']; ?></td>
                        <td><?php echo $manager['firstname'] . " " . $manager['lastname']; ?></td>
                        <td><?php echo $department['email']; ?></td>
                        <td><?php echo $department['created']; ?></td>
                        <td><?php echo $department['ispublic'] == 1 ? "YES" : "NO"; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $department['id']; ?>"
                               class="btn btn-sm btn-outline-warning">Edit</a>
                            <a style="color:#e55353" onclick="Delete(<?php echo $department['id']; ?>)"
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
    $("#department_list").addClass("active");
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