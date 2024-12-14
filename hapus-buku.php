<?php 
include 'config/app.php';

$id_buku = (int)$_GET['id_buku'];

if(delete_buku($id_buku) > 0){
    echo "<script>
        alert ('Data berhasil dihapus');
        document.location.href = 'daftar-buku.php';
   </script>";
}else{
    echo "<script>
        alert ('Data gagal dihapus');
        document.location.href = 'daftar-buku.php';
   </script>";
}

?>