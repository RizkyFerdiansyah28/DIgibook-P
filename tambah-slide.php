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

// Check if form is submitted
if (isset($_POST['tambah'])) {
    // You can validate the data here before calling create_review()
    
    // Call the function to create the review
    if (create_slide($_POST) > 0) {
        echo "<script>
                alert('Berhasil ditambahkan');
                document.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal ditambahkan');
                document.location.href = 'index.php';
              </script>";
    }
}


?>

<div class="container">
    <h1 class="mt-5">Judul Buku</h1>
    <hr>

    <h2 class="mt-5">Detail Buku</h2>
    <form action="" method="POST" class="mt-5" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" class="form-control" id="slide_atas" name="slide_atas" placeholder="Tambahkan judul" required>
        </div>

        <div class="mb-3">
            <label for="pengarang" class="form-label">Keterangan</label>
            <input type="text" class="form-control" id="slide_bawah" name="slide_bawah" placeholder="Tambahkan Keterangan" required>
        </div>


        

        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control" id="gambar_slide" name="gambar_slide" placeholder="Tambahkan Foto..." onchange="previewImg()"
                required>

            <img src="" class="img-thumbnail img-preview" alt="" width="500px">
        </div>

        <button type="submit" name="tambah" id="tambah" class="btn btn-success" style="float: right">Tambah Buku
            </button>
    </form>
</div>

<script>
    function previewImg() {
        const sampul_buku = document.querySelector('#gambar_slide');
        const imgPreview = document.querySelector('.img-preview');

        const fileGambarSlide = new FileReader();
        fileGambarSlide.readAsDataURL(gambar_slide.files[0]);

        fileGambarSlide.onload = function(e){
            imgPreview.src = e.target.result;
        }
    }
    </script>

<?php 

    include 'layout/footer.php';

?>