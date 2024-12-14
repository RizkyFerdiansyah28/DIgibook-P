<?php 
session_start();
include 'layout/header.php';


// Periksa apakah pengguna sudah login
if (!isset($_SESSION["login"])) {
    echo "<script>alert('Anda belum login');
    document.location.href = 'login.php';
    </script>";
    exit;
}

$id_user = $_SESSION['id_user']; // ID pengguna yang sedang login

// Tangani permintaan POST untuk menambahkan ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = (int)$_POST['id_buku'];
    $harga_buku = (int)$_POST['harga_buku'];

    // Tambahkan ke keranjang
    $query = "INSERT INTO keranjang (id_user, id_buku, harga_total) 
              VALUES ('$id_user', '$id_buku', '$harga_buku')";
    mysqli_query($db, $query);

    if (mysqli_affected_rows($db) > 0) {
        echo "<script>alert('Buku berhasil ditambahkan ke keranjang');
        document.location.href = 'keranjang.php';
        </script>";
    } else {
        echo "<script>alert('Gagal menambahkan ke keranjang');
        document.location.href = 'detail-buku.php?id_buku=$id_buku';
        </script>";
    }
}

// Ambil data dari keranjang
$keranjang = select("
    SELECT keranjang.*, buku.judul_buku, buku.sampul_buku, buku.harga_buku 
    FROM keranjang 
    JOIN buku ON keranjang.id_buku = buku.id_buku 
    WHERE keranjang.id_user = '$id_user'
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
<div class="container mt-5">
    <h1 class="mb-4">Keranjang</h1>

    <?php if (!empty($keranjang)) : ?>
        <?php foreach ($keranjang as $buku) : ?>
            <div class="transaction-card">
                <img src="./foto/foto-buku/<?= $buku['sampul_buku']; ?>" alt="<?= $buku['judul_buku']; ?>">
                <div class="transaction-details">
                    <h5 class="transaction-title"><?= $buku['judul_buku']; ?></h5>
                    <p class="transaction-price">Rp <?= number_format($buku['harga_total'], 0, ',', '.'); ?></p>
                </div>
                <a href="hapus-keranjang.php?id_keranjang=<?= $buku['id_keranjang']; ?>" class="btn btn-danger"
                     onclick="return confirm('Apakah anda yakin untuk menghapus review')">Hapus
                </a>
            </div>
                <?php endforeach; ?>
    <?php else : ?>
        <p class="no-transaction">Keranjang Anda kosong. Silakan tambahkan buku.</p>
    <?php endif; ?>
</div>

<div class="container mt-5">
    <a href="checkout.php?id_user=<?= $buku['id_user']; ?>" class="btn btn-primary mt-3">Beli Buku</a>
</div>


<?php include 'layout/footer.php'; ?>