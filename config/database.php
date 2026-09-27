<?php
// portal_psp/config/database.php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "127.0.0.1";
$username = "root";
$password = ""; // KOSONGKAN UNTUK XAMPP BIASA
$database = "psp_portal";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Sambungan pangkalan data gagal: " . $conn->connect_error);
}
?>