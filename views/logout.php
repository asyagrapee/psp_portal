<?php
// portal_psp/views/logout.php
session_start();

// Menghapus dan memusnahkan semua data sesi
session_unset();
session_destroy();

// Keluar dari folder views (../) untuk kembali ke login.php di luar
header("Location: ../login.php"); 
exit();
?>