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

$slide = select("SELECT * FROM slide");
?>

<div class="container mt-5">
    <h1>ReviewkanLe</h1>
    <a href="tambah-slide.php" class="btn btn-success "> Tambah</a>

    <table class="table table-striped table-bordered mt-3 "  id="table">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Judul</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Sampul</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php $no = 1?>
            <?php foreach ($slide as $data_slide) :?>
            <tr>
                <td class="text-center"><?= $no++?></td>
                <td class="text-center"><?= $data_slide['slide_atas']; ?></td>
                <td class="text-center"><?= $data_slide['slide_bawah']; ?></td>
                <td class="text-center"><img src="./foto/foto-slide/<?= $data_slide['gambar_slide']; ?>" width="150px"></td>
                <td width="15%" class="text-center">
                    <a href="edit-slide.php?id_slide=<?= $data_slide['id_slide']; ?>" class="btn btn-primary">Edit</a>
                    <a href="hapus-slide.php?id_slide=<?= $data_slide['id_slide']; ?>" class="btn btn-danger"
                        onclick="return confirm('Apakah anda yakin untuk menghapus Slide')">Hapus</a>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php 

    include 'layout/footer.php';

?>