<?php
session_start();

// Cek apakah pengguna sudah login, jika tidak alihkan ke halaman login
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }

// Hapus semua sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Alihkan ke halaman login setelah logout
header("Location: index.php");
exit;
?>