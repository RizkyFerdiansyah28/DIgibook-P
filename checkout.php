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
       $bukti_pembayaran = uploadBuktiTransaksi();
        // Mulai transaksi database
        mysqli_begin_transaction($db);

        try {
            // Proses setiap item dalam keranjang
            foreach ($keranjang as $item) {
                $id_buku = $item['id_buku'];
                $harga_total = $item['harga_total'];

                // Simpan transaksi ke tabel
                $stmt = $db->prepare("INSERT INTO transaksi (id_user, id_buku, total_bayar, metode_bayar, bukti_pembayaran, tanggal_transaksi) 
                      VALUES (?, ?, ?, ?, ?, NOW())");

                // Perbaiki tipe data bind_param, tambahkan 's' untuk $bukti_pembayaran
                $stmt->bind_param("iisss", $id_user, $id_buku, $harga_total, $metode_bayar, $bukti_pembayaran);

                if (!$stmt->execute()) {
                    throw new Exception("Gagal menyimpan transaksi: " . $stmt->error);
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
                      alert('Transaksi berhasil!');
                      document.location.href = 'profil.php';
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
            <span class="text-body-secondary">SIlahkan Bayar melalui nomer rekening berikut atau lewat Qris</span>
            <span class="text-body-secondary"><strong>7389473289749828</strong></span>
            <img src="./foto/qris/qris.jpg" alt="" srcset="" class="mt-3">
        </ul>
      </div>
      <div class="col-md-7 col-lg-8">
        <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
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

          <div class="mb-3">
            <label for="bukti_pembayaran" class="form-label">Berikan Bukti Bayar</label>
            <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" placeholder="Tambahkan Foto..." onchange="previewImg()"
                required>

            <img src="" class="img-thumbnail img-preview" alt="" width="500px">
          </div>
          <button class="w-100 btn btn-primary btn-lg" type="submit">Lanjutkan Pembayaran</button>
        </form>
      </div>
    </div>
  </main>
</div>

<script>
    function previewImg() {
        const bukti_pembayaran = document.querySelector('#bukti_pembayaran');
        const imgPreview = document.querySelector('.img-preview');

        const fileBuktiTransfer = new FileReader();
        fileBuktiTransfer.readAsDataURL(bukti_pembayaran.files[0]);

        fileBuktiTransfer.onload = function(e){
            imgPreview.src = e.target.result;
        }
    }
    </script>

<?php 
include 'layout/footer.php';
?>
