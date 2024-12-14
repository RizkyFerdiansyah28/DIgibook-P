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
if (isset($_GET['id_slide'])) {
    $id_slide = (int)$_GET['id_slide'];
} else {
    echo "<script>
            alert('ID buku tidak ditemukan');
            document.location.href = 'index.php';
          </script>";
    exit;
}

// Fetch the review data for the given id_review
$slide = select("SELECT * FROM slide WHERE id_slide = $id_slide")[0];

// Check if review data is found
if (!$slide) {
    echo "<script>
            alert('Data Slide tidak ditemukan');
            document.location.href = 'daftar-slide.php';
          </script>";
    exit;
}

// Check if form is submitted
if (isset($_POST['ubah'])) {
    // Call the function to update the review
    if (update_slide($_POST, $id_slide) > 0) {
        echo "<script>
                alert('Berhasil diubah');
                document.location.href = 'daftar-slide.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal diubah');
                document.location.href = 'daftar-slide.php';
              </script>";
    }
}
?>

<div class="container">
    <h1 class="mt-5">Edit Buku</h1>
    <hr>

    <h2 class="mt-5">Detail Buku</h2>
    <form action="" method="post" class="mt-5" enctype="multipart/form-data">
    <input type="hidden" name="id_slide" value="<?= $slide['id_slide']; ?>">
    <input type="hidden" name="gambar_slide_lama" value="<?= $slide['gambar_slide']; ?>">

        <div class="mb-3">
            <label for="judul" class="form-label">Judul Buku</label>
            <input type="text" class="form-control" id="slide_atas" name="slide_atas" placeholder="Tambahkan judul" value="<?= $slide['slide_atas']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Pengarang</label>
            <input type="text" class="form-control" id="slide_bawah" name="slide_bawah" placeholder="Tambahkan Keterangan" value="<?= $slide['slide_bawah']; ?>" required>
        </div>


        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control" id="gambar_slide" name="gambar_slide" placeholder="Tambahkan Foto..." onchange="previewImg()"
                >

            <img src="./foto/foto-slide/<?= $slide['gambar_slide']; ?>" class="img-thumbnail img-preview" alt="" width="500px">
        </div>

        <button type="submit" name="ubah" id="ubah" class="btn btn-success my-5" style="float: right">Kirim
            </button>
    </form>
</div>

<script>
    function previewImg() {
        const gambar_slide = document.querySelector('#gambar_slide');
        const imgPreview = document.querySelector('.img-preview');

        const fileGambarSlide = new FileReader();
        fileGambarSlide.readAsDataURL(gambar_slide.files[0]);

        fileGambarSlide.onload = function(e){
            imgPreview.src = e.target.result;
        }
    }
    </script>

