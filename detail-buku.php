<?php 

session_start();
if (!isset($_SESSION["login"])) {
    include 'layout/header-guest.php';
} else {
    include 'layout/header.php';
}

if (isset($_GET['id_buku'])) {
    $id_buku = (int)$_GET['id_buku'];
} else {
    echo "<script>
            alert('ID review tidak ditemukan');
            document.location.href = 'index.php';
          </script>";
    exit;
}

$buku = select("SELECT * FROM buku WHERE id_buku = $id_buku")[0];

// Cek apakah buku sudah dibeli oleh user
$id_user = $_SESSION['id_user']; // ID user yang sedang login
$cek_pembelian = select("SELECT * FROM transaksi WHERE id_buku = $id_buku AND id_user = $id_user AND (status_pembayaran = 'Pending' OR status_pembayaran = 'Accepted' OR status_pembayaran = 'Rejected')");
$cek_keranjang = select("SELECT * FROM keranjang WHERE id_buku = $id_buku AND id_user = $id_user");

?>

<style>
/* CSS Styles */
.overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.7), rgba(0,0,0,0.2));
    z-index: 0;
}

.content {
    position: relative;
    z-index: 2;
}

.rating {
    color: yellow;
    font-size: 1.2em;
}

.book-price {
    font-size: 2rem;
    color: #008000;
}

.book-discount {
    font-size: 1.2rem;
    color: gray;
    text-decoration: line-through;
}

.detail-title {
    display: inline-block;
    border-bottom: 1px solid #fff;
    padding-bottom: 3px;
}

.detail-list {
    list-style: none;
    padding: 0;
    font-size: 1rem;
}

.detail-list li {
    margin-bottom: 0.5rem;
}

.icon-section img {
    width: 50px;
    margin-bottom: 0.5rem;
}

.icon-section p {
    font-size: 0.9rem;
    text-align: center;
}
</style>

<div class="bg-dark text-white position-relative">
    <div class="overlay"></div>

    <div class="container p-5" id="konten">
        <div class="row content">
            <div class="col-md-4">
                <img src="./foto/foto-buku/<?= $buku['sampul_buku']; ?>" class="img-fluid mb-3" alt="Poster Buku" />
            </div>
            <div class="col-md-8">
                <h2><?= $buku['judul_buku']; ?></h2>
                <small>Ditulis oleh: <?= $buku['pengarang_buku']; ?></small>
                <div class="rating mb-2">
                    <?= $buku['rating_buku']; ?>/10
                </div> 
                <div>
                    <span class="book-price">Rp<?= number_format($buku['harga_buku'], 2, ',', '.'); ?></span>
                </div>
                <hr class="border-light">
                <p class="detail-title">Detail</p>
                <p>Tipe buku: <?= $buku['genre_buku']; ?></p>
                <p><?= $buku['sinopsis_buku']; ?></p>
                
                <?php if ($_SESSION['status'] == 1): ?>
                <div>
                    <a href="edit-buku.php?id_buku=<?= $buku['id_buku']; ?>" class="btn btn-primary">Edit</a>
                </div>
                <?php endif; ?>

                <?php if (count($cek_pembelian) > 0): ?>
                    <?php 
                        // Ambil status pembayaran dari transaksi
                        $status_pembayaran = isset($cek_pembelian[0]['status_pembayaran']) ? $cek_pembelian[0]['status_pembayaran'] : '';
                    ?>
                    <?php if ($status_pembayaran === 'Pending'): ?>
                        <p class="text-warning">Pembayaran Anda sedang diproses.</p>
                    <?php elseif ($status_pembayaran === 'Accepted'): ?>
                        <p class="text-danger">Buku ini sudah dibeli.</p>
                    <?php elseif ($status_pembayaran === 'Rejected'): ?>
                        <p class="text-danger">Pembayaran Anda ditolak. Anda dapat membeli buku ini lagi.</p>
                        <form action="keranjang.php" method="POST">
                            <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">
                            <input type="hidden" name="id_user" value="<?= $_SESSION['id_user']; ?>">
                            <input type="hidden" name="harga_buku" value="<?= $buku['harga_buku']; ?>">
                            <button type="submit" class="btn btn-success">Tambahkan ke Keranjang</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Cek apakah Buku sudah ada di keranjang -->
                    <?php if (count($cek_keranjang) > 0): ?>
                        <p class="text-danger">Buku sudah ada di keranjang.</p>
                    <?php else: ?>
                        <!-- Form Tambahkan ke Keranjang -->
                        <form action="keranjang.php" method="POST">
                            <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">
                            <input type="hidden" name="id_user" value="<?= $_SESSION['id_user']; ?>">
                            <input type="hidden" name="harga_buku" value="<?= $buku['harga_buku']; ?>">
                            <button type="submit" class="btn btn-success">Tambahkan ke Keranjang</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
