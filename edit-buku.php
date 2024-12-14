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

// Check if id_review is set in the URL
if (isset($_GET['id_buku'])) {
    $id_buku = (int)$_GET['id_buku'];
} else {
    echo "<script>
            alert('ID buku tidak ditemukan');
            document.location.href = 'index.php';
          </script>";
    exit;
}

// Fetch the review data for the given id_review
$buku = select("SELECT * FROM buku WHERE id_buku = $id_buku")[0];

// Check if review data is found
if (!$buku) {
    echo "<script>
            alert('Data buku tidak ditemukan');
            document.location.href = 'index.php';
          </script>";
    exit;
}

// Check if form is submitted
if (isset($_POST['ubah'])) {
    // Call the function to update the review
    if (update_buku($_POST, $id_buku) > 0) {
        echo "<script>
                alert('Berhasil diubah');
                document.location.href = 'daftar-buku.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal diubah');
                document.location.href = 'daftar-buku.php';
              </script>";
    }
}
?>

<div class="container">
    <h1 class="mt-5">Edit Buku</h1>
    <hr>

    <h2 class="mt-5">Detail Buku</h2>
    <form action="" method="post" class="mt-5" enctype="multipart/form-data">
    <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">
    <input type="hidden" name="sampul_buku_lama" value="<?= $buku['sampul_buku']; ?>">
    <input type="hidden" name="isi_buku_lama" value="<?= $buku['isi_buku']; ?>">
        <div class="mb-3">
            <label for="judul" class="form-label">Judul Buku</label>
            <input type="text" class="form-control" id="judul_buku" name="judul_buku" placeholder="Tambahkan judul" value="<?= $buku['judul_buku']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Pengarang</label>
            <input type="text" class="form-control" id="pengarang_buku" name="pengarang_buku" placeholder="Tambahkan Pengarang" value="<?= $buku['pengarang_buku']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="genre_buku" class="form-label">Genre</label>
            <select class="form-control" id="genre_buku" name="genre_buku" required>
                <option value="" disabled selected>Pilih Genre</option>
                <?php 
                // Daftar genre yang tersedia
                $genres = ['buku fiksi', 'novel', 'komik', 'biografi', 'edukasi'];

                foreach ($genres as $genre) {
                    // Tentukan apakah genre ini yang dipilih
                    $selected = ($genre == $buku['genre_buku']) ? 'selected' : '';
                    echo "<option value=\"$genre\" $selected>$genre</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="rating" class="form-label">Rating</label>
            <select class="form-control" id="rating_buku" name="rating_buku" required>
                <option value="" disabled selected>Pilih rating</option>
                <?php 
                // Generate options for rating from 1 to 10
                for ($i = 1; $i <= 10; $i++) {
                    $selected = ($i == $buku['rating_buku']) ? 'selected' : ' ';
                    echo "<option value=\"$i\" $selected>$i</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="ulasan" class="form-label">Tulis Sinopsis</label>
            <textarea class="form-control" id="sinopsis_buku" name="sinopsis_buku" rows="4" placeholder="Tambahkan Sinopsis" required><?= isset($buku['sinopsis_buku']) ? htmlspecialchars($buku['sinopsis_buku']) : ''; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="ulasan" class="form-label">Masukkan harga</label>
            <input type="number" class="form-control" id="harga_buku" name="harga_buku" placeholder="Tambahkan Harga" value="<?= $buku['harga_buku']; ?>" required>
        </div>


        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control" id="sampul_buku" name="sampul_buku" placeholder="Tambahkan Foto..." onchange="previewImg()"
                >

            <img src="./foto/foto-buku/<?= $buku['sampul_buku']; ?>" class="img-thumbnail img-preview" alt="" width="500px">
        </div>

        <div class="mb-3">
            <label for="isi_buku" class="form-label">PDF Isi Buku</label>
            <input type="file" class="form-control" id="isi_buku" name="isi_buku" accept=".pdf">

            <a href="isi-buku/<?= htmlspecialchars($buku['isi_buku']) ?>" target="_blank" class="btn btn-success">Cek isi buku</a>
        </div>

        <button type="submit" name="ubah" id="ubah" class="btn btn-success my-5" style="float: right">Kirim
            </button>
    </form>
</div>

<script>
    function previewImg() {
        const sampul_buku = document.querySelector('#sampul_buku');
        const imgPreview = document.querySelector('.img-preview');

        const fileSampulBuku = new FileReader();
        fileSampulBuku.readAsDataURL(sampul_buku.files[0]);

        fileSampulBuku.onload = function(e){
            imgPreview.src = e.target.result;
        }
    }
    </script>

