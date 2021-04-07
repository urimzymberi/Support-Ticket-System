<?php
define("DBHOST", "localhost");
define("DBNAME", "s-ticket");
define("DBUSER", "root");
define("DBPASS", "");
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>