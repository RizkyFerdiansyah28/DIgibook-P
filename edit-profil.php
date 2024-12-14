<?php 
session_start();

include 'layout/header.php';

// Check if user_id is set in the URL
if (isset($_GET['id_user'])) {
    $id_user = (int)$_GET['id_user'];
} else {
    echo "<script>
            alert('ID pengguna tidak ditemukan');
            document.location.href = 'profil.php';
          </script>";
    exit;
}

// Fetch the user data for the given user_id
$user = select("SELECT * FROM users WHERE id_user = $id_user")[0];

// Check if user data is found
if (!$user) {
    echo "<script>
            alert('Data pengguna tidak ditemukan');
            document.location.href = 'index.php';
          </script>";
    exit;
}

// Check if form is submitted
if (isset($_POST['update_profil'])) {
    // Call the function to update the profile
    if (update_profil($_POST) > 0) {
        echo "<script>
                alert('Berhasil diubah');
                document.location.href = 'profil.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal diubah');
                document.location.href = 'profil.php';
              </script>";
    }
}

?>

<style>
    .profile-picture {
            width: 350px;
            height: 350px;
            border-radius: 50%;
            margin-bottom: 1rem;
            object-fit: cover;
        }
</style>

<div class="container">
    <h1 class="mt-5">Edit Profil</h1>
    <hr>

    <form action="" method="post" class="mt-5" enctype="multipart/form-data">
        <input type="hidden" name="id_user" value="<?= $user['id_user']; ?>">
        <input type="hidden" name="foto_profil_lama" value="<?= $user['foto_Profil']; ?>">
        
        <div class="mb-3">
            <label for="username" class="form-label">Edit Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Tambahkan Username Baru" value="<?= $user['username']; ?>" required>
        </div>
        

        <div class="mb-3">
            <label for="foto_Profil" class="form-label">Foto Profil</label>
            <input type="file" class="form-control" id="foto_Profil" name="foto_Profil" placeholder="Tambahkan Foto..." onchange="previewImg()">

            <img src="./foto/foto-profil/<?= $user['foto_Profil']; ?>" class="img-thumbnail img-preview profile-picture" alt="" width="500px">
        </div>
        
        <button type="submit" name="update_profil" id="update_profil" class="btn btn-success" style="float: right">Ubah Profil</button>
    </form>
</div>

<script>
    function previewImg() {
        const foto_profil = document.querySelector('#foto_Profil');
        const imgPreview = document.querySelector('.img-preview');

        const fileFotoProfil = new FileReader();
        fileFotoProfil.readAsDataURL(foto_profil.files[0]);

        fileFotoProfil.onload = function(e){
            imgPreview.src = e.target.result;
        }
    }
</script>

<?php 

    include 'layout/footer.php';

?>