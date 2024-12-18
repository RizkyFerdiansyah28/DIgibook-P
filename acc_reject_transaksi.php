<?php
require 'config/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        acc_pembayaran($id_transaksi);
    } elseif ($action === 'reject') {
        reject_pembayaran($id_transaksi);
    }

    header('Location: admin-status-transaksi.php');
    exit;
}
?>
