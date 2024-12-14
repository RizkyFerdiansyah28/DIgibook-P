<?php 
include 'config/app.php';

$id_slide = (int)$_GET['id_slide'];

if(delete_slide($id_slide) > 0){
    echo "<script>
        alert ('Data berhasil dihapus');
        document.location.href = 'daftar-slide.php';
   </script>";
}else{
    echo "<script>
        alert ('Data gagal dihapus');
        document.location.href = 'daftar-slide.php';
   </script>";
}

?>