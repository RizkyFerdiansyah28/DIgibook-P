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

if ($_SESSION['status'] != 1) {
    echo "<script>
                alert('Halaman tidak ditemukan');
                document.location.href = 'index.php';
              </script>";
    exit;
} 

// Ambil user_id dari session, bukan dari GET
$id_user = $_SESSION['id_user'];

// Ambil data pengguna yang login
$user = select("SELECT * FROM users WHERE id_user = $id_user")[0];

// Total buku yang dibeli oleh semua pengguna
$total_buku_dibeli = select("SELECT COUNT(*) AS total FROM transaksi")[0]['total'];

// Total transaksi oleh pengguna saat ini
$total_transaksi_user = select("SELECT COUNT(*) AS total FROM transaksi WHERE id_user = $id_user")[0]['total'];

// Total semua transaksi (jumlah total_bayar oleh semua pengguna)
$total_pembayaran_semua_user = select("SELECT SUM(total_bayar) AS total FROM transaksi")[0]['total'];

//Ambil data buku terlaris
$buku_terlaris = select("SELECT b.judul_buku, COUNT(t.id_buku) AS jumlah_terjual FROM transaksi t JOIN buku b ON t.id_buku = b.id_buku GROUP BY t.id_buku ORDER BY jumlah_terjual DESC LIMIT 5");

// Ambil data penjualan bulanan
$data_penjualan = select("SELECT MONTH(tanggal_transaksi) AS bulan, SUM(total_bayar) AS total_bulan FROM transaksi GROUP BY MONTH(tanggal_transaksi)");

// Format data untuk Chart.js
$labels = [];
$data = [];
foreach ($data_penjualan as $penjualan) {
    $labels[] = date('F', mktime(0, 0, 0, $penjualan['bulan'], 10)); // Konversi angka bulan menjadi nama bulan
    $data[] = $penjualan['total_bulan'];
}

// Format data buku terlaris untuk Chart.js
$labels_buku = [];
$data_buku = [];
foreach ($buku_terlaris as $buku) {
    $labels_buku[] = $buku['judul_buku'];
    $data_buku[] = $buku['jumlah_terjual'];
}
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
        <p class="text-center">Total Pembayaran oleh Semua Pengguna:</p>
        <h5> <strong>Rp<?= number_format($total_pembayaran_semua_user, 2, ',', '.'); ?></strong></h5>
    </div>
    
    <div class="recent-reviews">
        <h2>Grafik Penjualan</h2>
        <div id="grafik-Penjualan" style="width: 600px; height: 600px;">
            <canvas id="myChart"></canvas>
        </div>

        <h2>Buku Terlaris</h2>
        <div id="grafik-Buku" style="width: 600px; height: 600px;">
            <canvas id="chartBuku"></canvas>
        </div>

        <script>
            var ctx = document.getElementById("myChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels); ?>,
                    datasets: [
                        {
                            label: 'Pendapatan Penjualan (Rp)',
                            data: <?= json_encode($data); ?>,
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384'
                            ],
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

             // Grafik Buku Terlaris
             var ctx2 = document.getElementById("chartBuku").getContext('2d');
            var chartBuku = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels_buku); ?>,
                    datasets: [
                        {
                            label: 'Jumlah Terjual',
                            data: <?= json_encode($data_buku); ?>,
                            backgroundColor: ['#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56', '#9966FF'],
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        

    </div>
</div>

<div class="container my-3 text-end">
    <a href="edit-profil.php?id_user=<?= $user['id_user']; ?>" class="btn btn-success">Edit</a>
</div>

<?php 
include 'layout/footer.php';
?>
