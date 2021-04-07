<?php

use App\Lib\User;

include '../includes/session.inc.php';
include '../autoloader.php';

if (isset($_SESSION["isadmin"])) {
    if ($_SESSION["isadmin"] != 1) {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}

include '../includes/header.inc.php';
?>
<!-- start main -->

<div class="content_page">
    <div class="h4 p-5"><strong>Users</strong></div>
    <div class="card border-0">
        <div class="card-body">
            <table class="dataTable compact hover row-border">
                <thead>
                <tr class="h6">
                    <th class="d-none">Id</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date registered</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php
                $objUser = new User();
                $Users = $objUser->get_all();
                foreach ($Users as $user) {
                    ?>
                    <tr>
                        <td class="d-none"><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php
                            $firstnameAndLastname = array($user['firstname'], $user['lastname']);
                            echo implode(" ", $firstnameAndLastname); ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['phone']; ?></td>
                        <td><?php echo $user['created']; ?></td>
                        <td><?php
                            $status = "";
                            if ($user['isactive'] == 1)
                                $status .= "Active ";
                            if ($user['isadmin'] == 1)
                                $status .= "Admin ";
                            if ($user['isvisible'] == 1)
                                $status .= "Visible ";
                            echo $status; ?></td>
                        <td>
                            <a href="<?php echo $user['id'] == $_SESSION['id'] ? "profile.php?id=" . $user['id'] : "edit.php?id=" . $user['id']; ?>"
                               class="btn btn-sm btn-outline-warning">Edit</a>
                            <a style="color:#e55353" onclick="Delete(<?php echo $user['id']; ?>)"
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

<?php include '../includes/footer.inc.php'; ?>

<script>
    $("#user_list").addClass("active");
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

