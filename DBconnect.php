<?php
//Connection Credentials
$servername = "10.0.0.188";
$username = "root";
$password = "pass@123";
$dbname = "instagramnew";

//Establish Connection
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
