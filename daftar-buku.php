<?php 
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

$buku = select("SELECT * FROM buku");
?>

<div class="container mt-5">
    <h1>ReviewkanLe</h1>
    <a href="tambah-buku.php" class="btn btn-success "> Tambah</a>

    <table class="table table-striped table-bordered mt-3 "  id="table">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Judul</th>
                <th class="text-center">Pengarang</th>
                <th class="text-center">Tanggal Rilis</th>
                <th class="text-center">Genre</th>
                <th class="text-center">Rating</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Sampul</th>
                <th class="text-center">Aksi</th>
                <th class="text-center">Baca</th>
            </tr>
        </thead>

        <tbody>
            <?php $no = 1?>
            <?php foreach ($buku as $data_buku) :?>
            <tr>
                <td class="text-center"><?= $no++?></td>
                <td class="text-center"><?= $data_buku['judul_buku']; ?></td>
                <td class="text-center"><?= $data_buku['pengarang_buku']; ?></td>
                <td class="text-center"><?=date('d F Y', strtotime( $data_buku['tanggal'])); ?></td>
                <td class="text-center"><?= $data_buku['genre_buku']; ?></td>
                <td class="text-center"><?= $data_buku['rating_buku']; ?></td>
                <td class="text-center">Rp<?= number_format($data_buku['harga_buku'], 2, ',', '.'); ?></td>
                <td class="text-center"><img src="./foto/foto-buku/<?= $data_buku['sampul_buku']; ?>" width="150px"></td>
                <td width="15%" class="text-center">
                    <a href="edit-buku.php?id_buku=<?= $data_buku['id_buku']; ?>" class="btn btn-primary">Edit</a>
                    <a href="hapus-buku.php?id_buku=<?= $data_buku['id_buku']; ?>" class="btn btn-danger"
                        onclick="return confirm('Apakah anda yakin untuk menghapus Buku')">Hapus</a>
                </td>
                <td width="15%" class="text-center">
                    <a href="isi-buku/<?= htmlspecialchars($data_buku['isi_buku']) ?>" target="_blank" class="btn btn-success">Baca buku</a>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php 

    include 'layout/footer.php';

?>