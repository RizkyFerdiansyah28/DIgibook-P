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

// Ambil user_id dari session, bukan dari GET
$id_user = $_SESSION['id_user'];

// Ambil data pengguna yang login
$user = select("SELECT * FROM users WHERE id_user = $id_user")[0];

$buku_dibeli = select("
    SELECT b.judul_buku, b.sampul_buku, b.harga_buku, b.rating_buku ,b.sinopsis_buku, b.isi_buku, t.total_bayar, t.tanggal_transaksi 
    FROM transaksi t
    JOIN buku b ON t.id_buku = b.id_buku
    WHERE t.id_user = $id_user
    ORDER BY t.tanggal_transaksi DESC
");

$total_transaksi_user = select("SELECT  SUM(total_bayar) AS total FROM transaksi WHERE id_user = $id_user")[0]['total'];
?>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
        }


        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 1rem;
            object-fit: cover;
        }

        .profile-name {
            font-size: 1.8rem;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .profile-bio {
            color: #666;
            margin-bottom: 1rem;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1a1a1a;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .recent-reviews {
            margin-top: 2rem;
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .review-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .movie-poster {
            width: 100px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }

        .review-content {
            flex: 1;
        }

        .movie-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .review-text {
            color: #444;
            line-height: 1.5;
            margin-bottom: 0.5rem;
        }

        .review-meta {
            color: #666;
            font-size: 0.9rem;
        }

        .rating {
            color: #ffd700;
            font-weight: bold;
        }
    </style>


    <div class="container">
        <div class="profile-header">
            <img src="./foto/foto-profil/<?= $user['foto_Profil']; ?>" alt="Foto Profil" class="profile-picture">
            <h1 class="profile-name"><?= $user['username']; ?></h1>
            <p class="text-center">Total Pembelian Saya:</p>
            <h5> <strong>Rp<?= number_format($total_transaksi_user, 2, ',', '.'); ?></strong></h5>
        </div>
        
        <div class="recent-reviews">
            <h2>Buku Anda</h2>
            <?php if (!empty($buku_dibeli)) : ?>
            <?php foreach ($buku_dibeli  as $buku) : ?>
            <div class="review-item">
                <img src="./foto/foto-buku/<?= $buku['sampul_buku']; ?>" alt="Film Poster 1" class="movie-poster">
                <div class="review-content">
                    <h3 class="movie-title"><?= $buku['judul_buku']; ?></h3>
                    <p class="review-text"><?= (strlen($buku['sinopsis_buku']) > 300) ? substr($buku['sinopsis_buku'], 0, 100) . '...' : $buku['sinopsis_buku']; ?></p>
                    <p class="review-meta">Rating: <span class="rating"><?= $buku['rating_buku']; ?></span><br> <?= date('d F Y', strtotime($buku['tanggal_transaksi'])); ?></p>
                    <a href="isi-buku/<?= htmlspecialchars($buku['isi_buku']) ?>" target="_blank" class="btn btn-success">Baca buku</a>

                </div>
            </div>        
            <?php  endforeach; ?>
            <?php else : ?>
            <p class="text-center">Belum ada buku yang dibeli.</p>
        <?php endif; ?>
        </div>
    </div>
            
        
       
    </div>

    <div class="container my-3 text-end">
    <a href="edit-profil.php?id_user=<?= $user['id_user']; ?>" class="btn btn-success">Edit</a>
    </div>

<?php 

    include 'layout/footer.php';

?>