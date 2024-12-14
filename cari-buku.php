<?php 
session_start();
if (!isset($_SESSION["login"])) {
    include 'layout/header-guest.php';
} else {
    include 'layout/header.php';
}
$buku = select("SELECT * FROM buku");
?>

<style>
    a {
    text-decoration: none;
}
</style>

<div class="container mt-5">
    <h1>Cari Film</h1>
    <table class="table table-striped table-bordered mt-3 "  id="table">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Sampul</th>
                <th class="text-center">Judul</th>
                <th class="text-center">Tanggal Rilis</th>
                <th class="text-center">Rating</th>
                <th class="text-center">Harga</th>
                <th class="text-center">poster</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Detail Buku</th>
            </tr>
        </thead>

        <tbody>
            <?php $no = 1?>
            <?php foreach ($buku as $data_buku) :?>
            <tr>
                <td class="text-center"><?= $no++?></td>
                <td class="text-center"><img src="./foto/foto-buku/<?= $data_buku['sampul_buku']; ?>" width="150px"></td>
                <td class="text-center"><?= $data_buku['judul_buku']; ?></td>
                <td class="text-center"><?= $data_buku['pengarang_buku']; ?></td>
                <td class="text-center"><?=date('d F Y', strtotime( $data_buku['tanggal'])); ?></td>
                <td class="text-center"><?= $data_buku['genre_buku']; ?></td>
                <td class="text-center"><?= $data_buku['rating_buku']; ?></td>
                <td class="text-center">Rp<?= number_format($data_buku['harga_buku'], 2, ',', '.'); ?></td>
                <td class="text-center"><a href="detail-buku.php?id_buku=<?= $data_buku['id_buku']; ?>" class="btn btn-sm btn-outline-primary">Baca Selengkapnya</a></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php 

    include 'layout/footer.php';

?>