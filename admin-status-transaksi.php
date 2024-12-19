<?php
// Ambil transaksi dengan status Pending
session_start();
if (!isset($_SESSION["login"])) {
    echo "<script>
                alert('Halaman tidak ditemukan');
                document.location.href = 'index.php';
              </script>";
} else {
    include 'layout/header.php';
}

if ($_SESSION['status'] != 1) {
    echo "<script>
                alert('Halaman tidak ditemukan');
                document.location.href = 'index.php';
              </script>";
    exit;
} 


function get_pending_transaksi()
{
    global $db;

    $query = "SELECT transaksi.*, users.username, buku.judul_buku, transaksi.bukti_pembayaran
              FROM transaksi 
              JOIN users ON transaksi.id_user = users.id_user 
              JOIN buku ON transaksi.id_buku = buku.id_buku 
              WHERE transaksi.status_pembayaran = 'Pending' 
              ORDER BY transaksi.tanggal_transaksi DESC";

    $result = mysqli_query($db, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// Tampilkan transaksi Pending di tabel
$pending_transaksi = get_pending_transaksi();
?>


<div class="container mt-5">
    <h1>Status Pembayaran</h1>

    <table class="table table-striped table-bordered mt-3"  id="table">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Username</th>
                <th class="text-center">Total Bayar</th>
                <th class="text-center">Metode Bayar</th>
                <th class="text-center">Tanggal Transaksi</th>
                <th class="text-center">Bukti Pembayran</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php $no = 1?>
            <?php foreach ($pending_transaksi as $transaksi) :?>
            <tr>
                <td class="text-center"><?= $no++?></td>
                <td class="text-center"><?= $transaksi['username']; ?></td>
                <td class="text-center"><?= $transaksi['total_bayar']; ?></td>
                <td class="text-center"><?= $transaksi['metode_bayar']; ?></td>
                <td class="text-center"><?=date('d F Y', strtotime( $transaksi['tanggal_transaksi'])); ?></td>>
                <td class="text-center"><img src="./foto/bukti-transaksi/<?= $transaksi['bukti_pembayaran']; ?>"  width="150px"></td>
                <td width="15%" class="text-center">
                <form method="POST" action="acc_reject_transaksi.php">
                    <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi'] ?>">
                    <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                </form>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>



<?php 

    include 'layout/footer.php';

?>