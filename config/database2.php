<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "student_marks";

$conn2 = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($conn2->connect_error) {
    die("Connection Failed: " . $conn2->connect_error);
}

?>