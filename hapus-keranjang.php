<?php 
include 'config/app.php';

$id_keranjang = (int)$_GET['id_keranjang'];

if(delete_keranjang($id_keranjang) > 0){
    echo "<script>
        alert ('Data berhasil dihapus');
        document.location.href = 'keranjang.php';
   </script>";
}else{
    echo "<script>
        alert ('Data gagal dihapus');
        document.location.href = 'keranjang.php';
   </script>";
}

?>