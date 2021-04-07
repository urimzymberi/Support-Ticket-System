<?php

use App\Lib\Client;

include '../includes/session.inc.php';
if (!isset($_SESSION['isadmin'])) {
    header("Location: index.php");
    exit();
}
include '../includes/header.inc.php';
?>

<!-- start main -->


<div class="content_page">
    <div class="h4 p-5"><strong> Clients</strong></div>
    <div class="card">
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
                $objClients = new Client();
                $clients = $objClients->get_all();
                foreach ($clients as $client) {
                    ?>

                    <tr>
                        <td class="d-none"><?php echo $client['id']; ?></td>
                        <td><?php echo $client['username']; ?></td>
                        <td><?php echo $client['name']; ?></td>
                        <td><?php echo $client['email']; ?></td>
                        <td><?php echo $client['phone']; ?></td>
                        <td><?php echo $client['created']; ?></td>
                        <td><?php echo $client['status'] == 1 ? "Active" : "Passive"; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $client['id']; ?>"
                               class="btn btn-sm btn-outline-warning">Edit</a>
                            <a style="color:#e55353" onclick="Delete(<?php echo $client['id']; ?>)"
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
    $("#client_list").addClass("active");
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
