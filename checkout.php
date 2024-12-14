<?php
session_start();
if (!isset($_SESSION["login"])) {
    echo "<script>
                alert('Harap Login terlebih dahulu');
                document.location.href = 'index.php';
              </script>";
    exit;
}

include 'layout/header.php';

if ($_SESSION['status'] == 1) {
  echo "<script>
              alert('Atmint tidak bisa membeli item ini');
              document.location.href = 'index.php';
            </script>";
  exit;
} 

// Tangkap ID user dari session
$id_user = $_SESSION['id_user'];

// Ambil data keranjang berdasarkan ID user
$keranjang = get_cart($id_user);

// Jika keranjang kosong
if (empty($keranjang)) {
    echo "<script>
            alert('Keranjang Anda kosong. Tambahkan buku terlebih dahulu.');
            document.location.href = 'index.php';
          </script>";
    exit;
}

// Hitung total pembayaran
$total_bayar = array_reduce($keranjang, function ($total, $item) {
    return $total + $item['harga_total'];
}, 0);

// Proses form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $metode_bayar = $_POST['metode_bayar'];

    if (empty($metode_bayar)) {
        echo "<script>
                alert('Pilih metode pembayaran terlebih dahulu');
              </script>";
    } else {
        // Mulai transaksi database
        mysqli_begin_transaction($db);

        try {
            // Proses setiap item dalam keranjang
            foreach ($keranjang as $item) {
                $id_buku = $item['id_buku'];
                $harga_total = $item['harga_total'];

                // Simpan transaksi ke tabel
                $stmt = $db->prepare("INSERT INTO transaksi (id_user, id_buku, total_bayar, metode_bayar, tanggal_transaksi) 
                                      VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("iids", $id_user, $id_buku, $harga_total, $metode_bayar);

                if (!$stmt->execute()) {
                    throw new Exception("Gagal menyimpan transaksi.");
                }
                $stmt->close();
            }

            // Kosongkan keranjang
            $stmt = $db->prepare("DELETE FROM keranjang WHERE id_user = ?");
            $stmt->bind_param("i", $id_user);
            if (!$stmt->execute()) {
                throw new Exception("Gagal mengosongkan keranjang.");
            }
            $stmt->close();

            // Commit transaksi
            mysqli_commit($db);

            echo "<script>
                  // Membuat modal untuk menampilkan gambar dan teks
                  var modal = document.createElement('div');
                  modal.style.position = 'fixed';
                  modal.style.top = '0';
                  modal.style.left = '0';
                  modal.style.width = '100%';
                  modal.style.height = '100%';
                  modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                  modal.style.display = 'flex';
                  modal.style.justifyContent = 'center';
                  modal.style.alignItems = 'center';
                  modal.style.zIndex = '1000';
                  modal.style.opacity = '0'; // Awalnya modal transparan
      
                  // Membuat elemen form
                  var form = document.createElement('div');
                  form.style.backgroundColor = 'white';
                  form.style.padding = '20px';
                  form.style.borderRadius = '8px';
                  form.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.2)';
                  form.style.textAlign = 'center';
      
                  // Membuat elemen teks
                  var text = document.createElement('div');
                  text.innerHTML = 'Silahkan bayar'; // Menambahkan teks
                  text.style.color = 'black'; // Warna teks
                  text.style.fontSize = '24px'; // Ukuran font
                  text.style.marginBottom = '20px'; // Jarak teks ke gambar
                  text.style.fontWeight = 'bold'; // Teks tebal
      
                  // Membuat gambar
                  var img = document.createElement('img');
                  img.src = './foto/qris/qris.jpg'; // Ganti dengan URL atau path gambar yang sesuai
                  img.alt = 'QR Code';
                  img.style.maxWidth = '2000px'; // Menyesuaikan ukuran gambar
                  img.style.marginBottom = '20px'; // Jarak gambar ke teks
                  img.style.transition = 'transform 0.5s ease, opacity 1s ease'; // Efek transisi untuk gambar
      
                  // Menambahkan teks dan gambar ke form
                  form.appendChild(text);
                  form.appendChild(img);
      
                  // Menambahkan form ke modal
                  modal.appendChild(form);
                  document.body.appendChild(modal);
      
                  // Menambahkan animasi muncul dengan fade-in dan zoom-in
                  setTimeout(function() {
                      modal.style.opacity = '1'; // Mengubah opacity menjadi 1 (fade-in)
                      img.style.transform = 'scale(1.1)'; // Efek zoom-in pada gambar
                  }, 10); // Menunggu sedikit agar transisi mulai bekerja
      
                  // Menutup modal setelah 5 detik
                  setTimeout(function() {
                      modal.remove();
                      alert('Transaksi berhasil!');
                      document.location.href = 'profil.php'; // Arahkan ke profil.php setelah konfirmasi
                  }, 5000); // 5000 ms = 5 detik
                </script>";
        } catch (Exception $e) {
            mysqli_rollback($db); // Rollback transaksi jika terjadi kesalahan
            echo "<script>
                    alert('Terjadi kesalahan. Coba lagi.');
                  </script>";
        }
    }
}
?>

<div class="container">
  <main>
    <div class="py-5 text-center">
      <h2>Checkout</h2>
      <p class="lead">Pastikan data Anda sudah benar sebelum melanjutkan pembayaran.</p>
    </div>

    <div class="row g-5">
      <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Total Pembayaran Anda</span>
        </h4>
        <ul class="list-group mb-3">
          <?php foreach ($keranjang as $item) : ?>
            <li class="list-group-item d-flex justify-content-between lh-sm">
              <div>
                <h6 class="my-0"><?= htmlspecialchars($item['judul_buku']); ?></h6>
              </div>
              <span class="text-body-secondary">Rp <?= number_format($item['harga_total'], 2, ',', '.'); ?></span>
            </li>
          <?php endforeach; ?>
          <li class="list-group-item d-flex justify-content-between">
            <span>Total (Rp)</span>
            <strong>Rp <?= number_format($total_bayar, 2, ',', '.'); ?></strong>
          </li>
        </ul>
      </div>
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Alamat Penagihan</h4>
        <form method="POST" action="" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="metode_bayar" class="form-label">Metode Pembayaran</label>
            <select class="form-control" id="metode_bayar" name="metode_bayar" required>
                <option value="" disabled selected>Pilih Metode Pembayaran</option>
                <?php 
                $metode_bayar = ['Credit card', 'Debit card', 'Gopay', 'Dana', 'Ovo'];
                foreach ($metode_bayar as $bayar) {
                    echo "<option value=\"" . htmlspecialchars($bayar) . "\">" . htmlspecialchars($bayar) . "</option>";
                }
                ?>
            </select>
          </div>
          <button class="w-100 btn btn-primary btn-lg" type="submit">Lanjutkan Pembayaran</button>
        </form>
      </div>
    </div>
  </main>
</div>

<?php 
include 'layout/footer.php';
?>
