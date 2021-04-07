<?php
include 'includes/dbh.inc.php';
session_start();
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (isset($_POST["chckLogin"])) {
        $query = "SELECT * FROM `staff` WHERE username ='$username'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $rowcount = mysqli_num_rows($result);
            if ($rowcount > 0) {
                $row = mysqli_fetch_assoc($result);
                if ($password == $row['password']) {
                    if ($row['isactive'] == 1) {
                        $_SESSION["id"] = $row['id'];
                        $_SESSION["name"] = $row['firstname'] . ' ' . $row['lastname'];
                        $_SESSION["username"] = $row['username'];
                        $_SESSION["isactive"] = $row['isactive'];
                        $_SESSION["isadmin"] = $row['isadmin'];
                        $_SESSION["isvisible"] = $row['isvisible'];
                        setcookie("chckIsStaff", 1, time() + (86400 * 1), "/"); // 86400 = 1 dite
                        echo "<script>
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'test',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
              </script>";
                        header("Location: index.php");
                        exit();
                    } else {
                        $_SESSION['alert'] = array('warning', 'User not active!');
                        header("Location: login.php");
                        exit();
                    }
                } else {
                    $_SESSION['alert'] = array('warning', 'User not found!');
                    header("Location: login.php");
                    exit();
                }
            } else {
                $_SESSION['alert'] = array('warning', 'User not found!');
                header("Location: login.php");
                exit();
            }
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    } else {
        $query = "SELECT * FROM `client` WHERE username ='$username'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $rowcount = mysqli_num_rows($result);
            if ($rowcount > 0) {
                $row = mysqli_fetch_assoc($result);
                if ($password == $row['password']) {
                    if ($row['status'] == 1) {
                        $_SESSION["id"] = $row['id'];
                        $_SESSION["name"] = $row['name'];
                        $_SESSION["username"] = $row['username'];
                        $_SESSION["isactive"] = $row['status'];
                        setcookie("chckIsStaff", 0, time() + (86400 * 30), "/"); // 86400 = 1 dite
                        header("Location: index.php");
                        exit();
                    } else {
                        $_SESSION['alert'] = array('warning', 'Client not active!');
                        header("Location: login.php");
                        exit();
                    }
                } else {
                    $_SESSION['alert'] = array('warning', 'Client not found!');
                    header("Location: login.php");
                    exit();
                }
            } else {
                $_SESSION['alert'] = array('warning', 'Client not found!');
                header("Location: login.php");
                exit();
            }
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Support Ticket</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css"/>
    <!-- <link href="css/style.css" rel="stylesheet"/> -->
</head>

<body class="bg-primary">
<?php
if (isset($_SESSION["alert"])) {
    $alert_array = $_SESSION["alert"];
    echo "<script>
                Swal.fire({
                    position: 'top-end',
                    icon: '$alert_array[0]',
                    title: '$alert_array[1]',
                    showConfirmButton: false,
                    timer: 1500
                })
            </script>";
    unset($_SESSION["alert"]);
}
?>
<div class="d-flex flex-column vh-100">
    <div class="mb-auto">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header">
                                <h3 class="text-center font-weight-light my-4">Login</h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label class="small mb-1" for="username">Username</label>
                                        <input class="form-control py-4" id="username" name="username" type="text"
                                               placeholder="Enter usernmae">
                                    </div>
                                    <div class="form-group">
                                        <label class="small mb-1" for="password">Password</label>
                                        <input class="form-control py-4" id="password" name="password" type="password"
                                               placeholder="Enter password">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control">
                                            <input type="checkbox" name="chckLogin" <?php
                                            if (isset($_COOKIE['chckIsStaff']) && $_COOKIE['chckIsStaff'] == 1) {
                                                echo "checked";
                                            }
                                            ?>><small class="ml-2">Login staff</small>
                                        </div>
                                    </div>
                                    <div class="form-group d-flex justify-content-end mb-0 mt-4">
                                        <button id="btnLogin" class="btn btn-primary">Login</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div class="d-flex justify-content-end pr-3 py-3 mt-2 bg-white">
        <div class="">Support Ticket&nbsp;|&nbsp;2020</div>
    </div>
</div>

</body>
</html>
<script>
    $("#btnLogin").click(function () {
        var username = $("#username").val();
        var password = $("#password").val();
        error = false;
        if (username == "" || username == null) {
            $("#username").addClass("is-invalid");
            error = true;
        } else {
            error = false;
        }
        if (password == "" || password == null) {
            $("#password").addClass("is-invalid");
            error = true;
        } else {
            error = false;
        }

        if (error) {
            return false;
        } else {
            return true;
        }
    });
    $("#username").keyup(function () {
        $("#username").removeClass("is-invalid");
    });
    $("#password").keyup(function () {
        $("#password").removeClass("is-invalid");
    });
</script>