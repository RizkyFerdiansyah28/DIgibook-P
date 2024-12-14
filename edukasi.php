<?php
session_start();
if (!isset($_SESSION["login"])) {
    include 'layout/header-guest.php';
} else {
    include 'layout/header.php';
}

// Ambil data buku dengan genre "komik" saja
$buku = select("SELECT * FROM buku WHERE genre_buku = 'edukasi'");
?>

<main>
    <div class="container">
        <div class="album py-3 bg-body-tertiary">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                    <?php foreach ($buku as $buku) : ?>
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <img src="./foto/foto-buku/<?= $buku['sampul_buku']; ?>" 
                                 class="bd-placeholder-img card-img-top" 
                                 alt="" 
                                 style="width: 100%; height: 225px; object-fit: contain;" />
                            <div class="card-body">
                                <h4><?= $buku['judul_buku']; ?></h4>
                                <p class="card-text">
                                    <?= (strlen($buku['sinopsis_buku']) > 100) 
                                        ? substr($buku['sinopsis_buku'], 0, 100) . '...' 
                                        : $buku['sinopsis_buku']; ?>
                                </p>
                                <p class="card-text">
                                    Rp<?= number_format($buku['harga_buku'], 0, ',', '.'); ?>
                                </p>
                                <small class="text-body-secondary">
                                    Rating: <?= $buku['rating_buku']; ?>
                                </small>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <a href="detail-buku.php?id_buku=<?= $buku['id_buku']; ?>" 
                                       class="btn btn-sm btn-outline-primary">Baca Selengkapnya</a>
                                    <small class="text-muted">18-10-2024</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>