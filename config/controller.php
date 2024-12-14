<?php 
    //menampilkan data dari DB
    function select($query)
    {
        global $db;
        
        $result = mysqli_query($db, $query);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }

    //menambahkan buku
    function create_buku($post)
    {
        global $db;

        $judul_buku = $post['judul_buku'];
        $pengarang_buku = $post['pengarang_buku'];
        $genre_buku = $post['genre_buku'];
        $rating_buku = $post['rating_buku'];
        $sinopsis_buku = $post['sinopsis_buku'];
        $harga_buku = $post['harga_buku'];
        $sampul_buku = upload_SampulBuku();
        $isi_buku = upload_isiBuku();

        if (!$isi_buku) {
            echo "<script>alert('Gagal mengunggah isi buku!');</script>";
            return 0; // Return error jika upload gagal
        }



        //query tambah data
        $query = "INSERT INTO buku VALUES(null, '$judul_buku', '$pengarang_buku', '$genre_buku', '$rating_buku', '$sinopsis_buku', '$sampul_buku','$isi_buku','$harga_buku', CURRENT_TIMESTAMP())";

        mysqli_query($db, $query);
        return mysqli_affected_rows($db);
    }

    function update_buku($post)
    {
        global $db;

        $id_buku = $post['id_buku'];
        $judul_buku = $post['judul_buku'];
        $pengarang_buku = $post['pengarang_buku'];
        $genre_buku = $post['genre_buku'];
        $rating_buku = $post['rating_buku'];
        $sinopsis_buku = $post['sinopsis_buku'];
        $sampul_buku_lama = $post['sampul_buku_lama'];
        $isi_buku_lama = $post['isi_buku_lama'];
        $harga_buku = $post['harga_buku'];

         //cek foto
         if ($_FILES['sampul_buku']['error'] == 4){
            $sampul_buku = $sampul_buku_lama;
        } else {
            $sampul_buku = upload_SampulBuku();
        }
        //cek isi buku
         if ($_FILES['isi_buku']['error'] == 4){
            $isi_buku = $isi_buku_lama;
        } else {
            $isi_buku = upload_isiBuku();
        }

        //query ubah data
        $query = "UPDATE buku SET judul_buku = '$judul_buku', pengarang_buku = '$pengarang_buku', genre_buku = '$genre_buku', rating_buku = '$rating_buku', sinopsis_buku = '$sinopsis_buku', sampul_buku = '$sampul_buku', isi_buku = '$isi_buku', harga_buku = '$harga_buku' WHERE id_buku = $id_buku";

        mysqli_query($db, $query);
        return mysqli_affected_rows($db);

    }

         //upload foto
    function upload_SampulBuku()
         {
             $nama_SampulBuku = $_FILES['sampul_buku']['name'];
             $ukuran_SampulBuku = $_FILES['sampul_buku']['size'];
             $error = $_FILES['sampul_buku']['error'];
             $tmpName = $_FILES['sampul_buku']['tmp_name'];
     
             //cek file yang diupload
             $extensifileValid = ['jpg', 'jpeg', 'png'];
             $extensifile= explode('.', $nama_SampulBuku);
             $extensifile= strtolower(end($extensifile));
     
             if (!in_array($extensifile, $extensifileValid)){
                 //pesan gagal
     
                 echo "<script>
                         alert('Format File Salah');
                         document.location.href = 'tambah-buku.php';
                 </script>";
                 die();
             }
     
             //mengubah nama file menjadi nama baru
             $namaFileBaru   = uniqid();
             $namaFileBaru  .= '.';
             $namaFileBaru  .= $extensifile;
     
             //pindah ke folder foto
             move_uploaded_file($tmpName, 'foto/foto-buku/'. $namaFileBaru);
             return ($namaFileBaru);
                     
         }

    function upload_isiBuku()
         {
             $nama_isiBuku = $_FILES['isi_buku']['name'];
             $ukuran_isiBuku = $_FILES['isi_buku']['size'];
             $error = $_FILES['isi_buku']['error'];
             $tmpName = $_FILES['isi_buku']['tmp_name'];
     
             //cek file yang diupload
             $extensifileValid = ['pdf'];
             $extensifile= explode('.', $nama_isiBuku);
             $extensifile= strtolower(end($extensifile));
     
             if (!in_array($extensifile, $extensifileValid)){
                 //pesan gagal
     
                 echo "<script>
                         alert('Format File Salah');
                         document.location.href = 'tambah-buku.php';
                 </script>";
                 die();
             }
     
             //mengubah nama file menjadi nama baru
             $namaBukuBaru   = uniqid();
             $namaBukuBaru  .= '.';
             $namaBukuBaru  .= $extensifile;
     
             //pindah ke folder buku
             move_uploaded_file($tmpName, 'isi-buku/'. $namaBukuBaru);
             return ($namaBukuBaru);
                     
         }

    function delete_buku($id_buku)
    {
        global $db;

        //query hapus
        $query = "DELETE FROM buku WHERE id_buku = $id_buku";

        mysqli_query($db, $query);

        return mysqli_affected_rows($db);
    }

    function register_user($post)
    {
        global $db;

        $username = mysqli_real_escape_string($db, $post['username']);
        $email    = mysqli_real_escape_string($db, $post['email']);
        $foto_profil = upload_FotoProfil();
        $password = mysqli_real_escape_string($db, $post['password']);

        //cek email apakah sudah terdaftar atau belum
        $check_user = mysqli_query($db, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_fetch_assoc($check_user)) {
        return 0; // Email sudah terdaftar
    }

    // Hash password sebelum disimpan
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    //insert user
    $query = "INSERT INTO users (username, email, foto_profil, password) VALUES ('$username', '$email', '$foto_profil', '$password_hashed')";

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
    }

    //upload foto
    function upload_FotoProfil()
    {
        $nama_FotoProfil = $_FILES['foto_Profil']['name'];
        $ukuran_FotoProfil = $_FILES['foto_Profil']['size'];
        $error = $_FILES['foto_Profil']['error'];  
        $tmpName = $_FILES['foto_Profil']['tmp_name'];

        //cek file yang diupload
        $extensifileValid = ['jpg', 'jpeg', 'png'];
        $extensifile= explode('.', $nama_FotoProfil);
        $extensifile= strtolower(end($extensifile));

        if (!in_array($extensifile, $extensifileValid)){
            //pesan gagal

            echo "<script>
                    alert('Format File Salah');
                    document.location.href = 'register.php';
            </script>";
            die();
        }

        //mengubah nama file menjadi nama baru
        $namaFileBaru   = uniqid();
        $namaFileBaru  .= '.';
        $namaFileBaru  .= $extensifile;

        //pindah ke folder foto
        move_uploaded_file($tmpName, 'foto/foto-profil/'. $namaFileBaru);
        return ($namaFileBaru);

    }
        
    function update_profil($post)
    {
    global $db;

            $id_user = $post['id_user'];
            $username  = $post['username'];
            $foto_profil_lama = $post['foto_profil_lama'];

            //cek foto
            if ($_FILES['foto_Profil']['error'] == 4){
                $foto_Profil = $foto_profil_lama;
            } else {
                $foto_Profil = upload_FotoProfil();
            }

            //query ubah data
            $query = "UPDATE users SET username = '$username', foto_Profil = '$foto_Profil' WHERE id_user = $id_user";

            mysqli_query($db, $query);

            return mysqli_affected_rows($db);
    }

    // Fungsi untuk transaksi (contohnya: pembelian buku)
    function create_transaksi($post)
    {
    global $db;

    $id_user = $post['id_user']; // ID user yang melakukan transaksi
    $id_buku = $post['id_buku']; // ID buku yang dibeli
    $jumlah  = $post['jumlah'];  // Jumlah buku yang dibeli
    $total_bayar = $post['total_bayar']; // Total harga transaksi
    $metode_bayar = $post['metode_bayar'];
    $tanggal_transaksi = date('Y-m-d H:i:s'); // Waktu transaksi

    // Query untuk memasukkan data transaksi ke tabel 'transaksi'
    $query = "INSERT INTO transaksi (id_user, id_buku, total_bayar,metode_bayar, tanggal_transaksi) 
              VALUES ('$id_user', '$id_buku', '$total_bayar','$metode_bayar', '$tanggal_transaksi')";

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
    }

// Fungsi untuk mengambil data transaksi berdasarkan user
    function get_transaksi_by_user($id_user)
    {
    global $db;

    // Query untuk mengambil data transaksi
    $query = "SELECT transaksi.*, buku.judul_buku, buku.harga_buku 
              FROM transaksi 
              JOIN buku ON transaksi.id_buku = buku.id_buku 
              WHERE transaksi.id_user = '$id_user' 
              ORDER BY transaksi.tanggal_transaksi DESC";

    $result = mysqli_query($db, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
    }

    function tambah_keranjang($post) {
        global $db;
    
        // Sanitasi input
        $id_user = mysqli_real_escape_string($db, $post['id_user']);
        $id_buku = mysqli_real_escape_string($db, $post['id_buku']);
    
        // Ambil harga buku dari tabel buku
        $result = mysqli_query($db, "SELECT harga_buku FROM buku WHERE id_buku = '$id_buku'");
        if (!$result || mysqli_num_rows($result) === 0) {
            return -1; // Jika buku tidak ditemukan, kembalikan -1
        }
    
        $buku = mysqli_fetch_assoc($result);
        $harga_buku = (int)$buku['harga_buku'];
    
        // Cek apakah buku sudah ada di keranjang
        $check_cart = mysqli_query($db, "SELECT * FROM keranjang WHERE id_user = '$id_user' AND id_buku = '$id_buku'");
    
        if ($row = mysqli_fetch_assoc($check_cart)) {
            // Jika buku sudah ada, update harga_total
            $new_harga_total = $row['harga_total'] + $harga_buku;
            $query = "UPDATE keranjang SET harga_total = '$new_harga_total' WHERE id_user = '$id_user' AND id_buku = '$id_buku'";
        } else {
            // Jika buku belum ada, tambahkan sebagai item baru
            $query = "INSERT INTO keranjang (id_user, id_buku, harga_total) VALUES ('$id_user', '$id_buku', '$harga_buku')";
        }
    
        // Eksekusi query
        mysqli_query($db, $query);
    
        // Kembalikan jumlah baris yang terpengaruh
        return mysqli_affected_rows($db);
    }
    
    
    // Menampilkan isi keranjang
    function get_cart($id_user) {
        global $db;

        $query = "SELECT keranjang.*, buku.judul_buku, buku.harga_buku, buku.sampul_buku 
                FROM keranjang 
                JOIN buku ON keranjang.id_buku = buku.id_buku 
                WHERE keranjang.id_user = '$id_user'";
        $result = mysqli_query($db, $query);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

// Menghapus item dari keranjang
    function delete_keranjang($id_keranjang) {
        global $db;

        $query = "DELETE FROM keranjang WHERE id_keranjang = $id_keranjang";
        mysqli_query($db, $query);

        return mysqli_affected_rows($db);
    }

    //menambahkan slide
    function create_slide($post)
    {
        global $db;

        $slide_atas = $post['slide_atas'];
        $slide_bawah = $post['slide_bawah'];
        $gambar_slide = upload_GambarSlide();



        //query tambah data
        $query = "INSERT INTO slide VALUES(null, '$slide_atas', '$slide_bawah', '$gambar_slide')";

        mysqli_query($db, $query);
        return mysqli_affected_rows($db);
    }

    function upload_GambarSlide()
    {
        $nama_GambarSlide = $_FILES['gambar_slide']['name'];
        $ukuran_GambarSlide = $_FILES['gambar_slide']['size'];
        $error = $_FILES['gambar_slide']['error'];
        $tmpName = $_FILES['gambar_slide']['tmp_name'];

        //cek file yang diupload
        $extensifileValid = ['jpg', 'jpeg', 'png'];
        $extensifile= explode('.', $nama_GambarSlide);
        $extensifile= strtolower(end($extensifile));

        if (!in_array($extensifile, $extensifileValid)){
            //pesan gagal

            echo "<script>
                    alert('Format File Salah');
                    document.location.href = 'tambah-slide.php';
            </script>";
            die();
        }

        //mengubah nama file menjadi nama baru
        $namaFileBaru   = uniqid();
        $namaFileBaru  .= '.';
        $namaFileBaru  .= $extensifile;

        //pindah ke folder foto
        move_uploaded_file($tmpName, 'foto/foto-slide/'. $namaFileBaru);
        return ($namaFileBaru);
                
    }

    function update_slide($post)
    {
        global $db;

        $id_slide = $post['id_slide'];
        $slide_atas = $post['slide_atas'];
        $slide_bawah = $post['slide_bawah'];
        $gambar_slide_lama = $post['gambar_slide_lama'];

         //cek foto
         if ($_FILES['gambar_slide']['error'] == 4){
            $gambar_slide = $gambar_slide_lama;
        } else {
            $gambar_slide = upload_GambarSlide();
        }

        //query ubah data
        $query = "UPDATE slide SET slide_atas = '$slide_atas', slide_bawah = '$slide_bawah', gambar_slide = '$gambar_slide' WHERE id_slide = $id_slide";

        mysqli_query($db, $query);
        return mysqli_affected_rows($db);

    }

    function delete_slide($id_slide)
    {
        global $db;

        //query hapus
        $query = "DELETE FROM slide WHERE id_slide = $id_slide";

        mysqli_query($db, $query);

        return mysqli_affected_rows($db);
    }


    ?>