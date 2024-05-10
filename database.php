<?php
$db_server  = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "btl";

// Try to establish a database connection
$conn = @mysqli_connect($db_server, $db_user, $db_pass, $db_name);

// Check if the connection was successful
if (!$conn) {
    // The connection was not successful, throw an exception
    throw new Exception('Database connection failed: ' . mysqli_connect_error());
}