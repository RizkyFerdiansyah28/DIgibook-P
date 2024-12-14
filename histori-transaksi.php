<?php 
session_start();
include 'layout/header.php';

// Periksa apakah pengguna sudah login
if(!isset($_SESSION["login"])){
    echo "<script>alert('Anda belum login');
    document.location.href = 'login.php';
    </script>";
    exit;
}

// Ambil user_id dari session
$id_user = $_SESSION['id_user'];

// Ambil data pengguna yang login
$user = select("SELECT * FROM users WHERE id_user = $id_user")[0];

$buku_dibeli = select("
    SELECT b.judul_buku, b.sampul_buku, b.harga_buku, b.rating_buku ,b.sinopsis_buku, t.total_bayar, t.tanggal_transaksi 
    FROM transaksi t
    JOIN buku b ON t.id_buku = b.id_buku
    WHERE t.id_user = $id_user
    ORDER BY t.tanggal_transaksi DESC
");
?>

<style>
    body {
        background-color: #f9f9f9;
        font-family: Arial, sans-serif;
    }
    .transaction-card {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background: #fff;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .transaction-card img {
        width: 100px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 16px;
    }
    .transaction-details {
        flex: 1;
    }
    .transaction-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .transaction-meta {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    .transaction-price {
        font-weight: bold;
        font-size: 1rem;
        color: #28a745;
    }
    .no-transaction {
        text-align: center;
        font-size: 1.2rem;
        color: #666;
        margin-top: 50px;
    }
</style>

<div class="container mt-5">
    <h1 class="mb-4">Riwayat Transaksi</h1>

    <?php if (!empty($buku_dibeli)) : ?>
        <?php foreach ($buku_dibeli as $buku) : ?>
            <div class="transaction-card">
                <img src="./foto/foto-buku/<?= $buku['sampul_buku']; ?>" alt="<?= $buku['judul_buku']; ?>">
                <div class="transaction-details">
                    <h5 class="transaction-title"><?= $buku['judul_buku']; ?></h5>
                    <p class="transaction-meta">Tanggal: <?= $buku['tanggal_transaksi']; ?></p>
                    <p class="transaction-price">Rp <?= number_format($buku['total_bayar'], 0, ',', '.'); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="no-transaction">Anda belum melakukan transaksi.</p>
    <?php endif; ?>
</div>

<?php include 'layout/footer.php'; ?>
