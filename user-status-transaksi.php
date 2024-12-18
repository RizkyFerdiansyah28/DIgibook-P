<?php
session_start();
include 'layout/header.php';

// Periksa apakah pengguna sudah login
if(!isset($_SESSION["login"])){
    echo "<script>alert('Anda belum login');
    document.location.href = 'login.php';
    </script>";
    exit;
}

// Ambil user_id dari session, bukan dari GET
$id_user = $_SESSION['id_user'];

// Fungsi untuk mengambil transaksi berdasarkan pengguna
$transaksi_user = get_transaksi_by_user($id_user);

// Ambil semua transaksi pengguna
$transaksi_user = get_transaksi_by_user($id_user);
?>

<div class="container mt-5">
    <h1>Status Pembayaran</h1>

    <table class="table table-striped table-bordered mt-3" id="table">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Judul Buku</th>
                <th class="text-center">Total Bayar</th>
                <th class="text-center">Metode Bayar</th>
                <th class="text-center">Tanggal Transaksi</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>

        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($transaksi_user as $transaksi) : ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td class="text-center"><?= $transaksi['judul_buku']; ?></td>
                <td class="text-center">Rp<?= number_format($transaksi['total_bayar'], 2, ',', '.'); ?></td>
                <td class="text-center"><?= $transaksi['metode_bayar']; ?></td>
                <td class="text-center"><?= date('d F Y', strtotime($transaksi['tanggal_transaksi'])); ?></td>
                <td class="text-center"><?= $transaksi['status_pembayaran']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php 
include 'layout/footer.php';
?>
