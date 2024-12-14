<?php
    session_start();
    if (!isset($_SESSION["login"])) {
        include 'layout/header-guest.php';
    } else {
        include 'layout/header.php';
    }
    $buku = select("SELECT * FROM buku");
    $slide = select ("SELECT * FROM slide")
?>

<!-- Section Carousel Full Width -->
<section id="carouselExampleIndicators" class="carousel slide text-center position-relative" data-bs-ride="carousel" data-bs-interval="2000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <!-- Slide 1 -->
         <?php foreach ($slide as $slide) :?>
        <div class="carousel-item active">
            <img src="./foto/foto-slide/<?= $slide['gambar_slide']; ?>" class="d-block w-100" alt="Gambar 1" style="filter: brightness(0.7) contrast(1.2); height: 400px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block">
                <div class="overlay-text">
                    <h1 class="fw-light"><?= $slide['slide_atas']; ?></h1>
                    <p class="lead"><?= $slide['slide_atas']; ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <!-- Slide 2 -->
        <!-- <div class="carousel-item">
            <img src="./foto/nc.jpg" class="d-block w-100" alt="Gambar 2" style="filter: brightness(0.7) contrast(1.2); height: 400px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block">
                <div class="overlay-text">
                    <h1 class="fw-light">Buku Terbaik Tahun Ini</h1>
                    <p class="lead">Mari mulai membaca</p>
                </div>
            </div>
        </div> -->
        <!-- Slide 3 -->
        <!-- <div class="carousel-item">
            <img src="./foto/tgd.jpg" class="d-block w-100" alt="Gambar 3" style="filter: brightness(0.7) contrast(1.2); height: 400px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block">
                <div class="overlay-text">
                    <h1 class="fw-light">Baca buku sekarang bisa di website ini</h1>
                    <p class="lead">Baca buku yang kamu mau</p>
                </div>
            </div>
        </div> -->
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</section>

           

<main>
    <div class="container">

        <div class="album py-3 bg-body-tertiary">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                    <?php foreach ($buku as $buku) :?>
                    <div class="col">
                        <div class="card shadow-sm h-100">
                        <img src="./foto/foto-buku/<?= $buku['sampul_buku']; ?>" class="bd-placeholder-img card-img-top" alt="" style="width: 100%; height: 225px; object-fit: contain;" />
                            <div class="card-body">
                                <h4><?= $buku['judul_buku']; ?></h4>
                                <p class="card-text">
                                <?= (strlen($buku['sinopsis_buku']) > 100) ? substr($buku['sinopsis_buku'], 0, 100) . '...' : $buku['sinopsis_buku']; ?>
                                </p>
                                <p class="card-text">
                                Rp<?= number_format($buku['harga_buku'], 2, ',', '.'); ?>
                                </p>
                                <p><?= $buku['genre_buku']; ?></p>
                                <small class="text-body-secondary">Rating: <?= $buku['rating_buku']; ?></small>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <a href="detail-buku.php?id_buku=<?= $buku['id_buku']; ?>" class="btn btn-sm btn-outline-primary">Baca Selengkapnya</a>
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

<style>
    .card-text {
        max-height: 75px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
    main {
        background-color: #14181C;
        color: white;
        padding: 20px 0;
    }
    .album {
        padding: 3rem 0;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    .btn-outline-primary {
        color: #1e7e34;
        border-color: #1e7e34;
    }
    .btn-outline-primary:hover {
        background-color: #1e7e34;
        color: white;
    }
    .card {
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: scale(1.05);
    }

    /* Animasi Fade In untuk teks */
    .overlay-text {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 1s ease-in-out, transform 1s ease-in-out;
    }

    .carousel-item.active .overlay-text {
        opacity: 1;
        transform: translateY(0);
    }

    /* Transisi lembut pada gambar */
    .carousel-item img {
        transition: filter 1s ease-in-out;
    }

    .carousel-item.active img {
        filter: brightness(1) contrast(1);
    }

</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var carouselElement = document.querySelector('#carouselExampleIndicators');
        var carouselItems = document.querySelectorAll('.carousel-item');
        
        carouselElement.addEventListener('slide.bs.carousel', function (e) {
            var nextSlide = e.relatedTarget;
            var currentSlide = e.target.querySelector('.carousel-item.active');

            // Menghapus kelas animasi dari slide saat ini
            currentSlide.querySelector('.overlay-text').classList.remove('fadeIn');
            currentSlide.querySelector('.overlay-text').classList.add('fadeOut');

            // Menambahkan kelas animasi pada slide yang akan datang
            nextSlide.querySelector('.overlay-text').classList.remove('fadeOut');
            nextSlide.querySelector('.overlay-text').classList.add('fadeIn');
        });

        // Inisialisasi awal untuk memberikan animasi pada slide pertama
        carouselItems[0].querySelector('.overlay-text').classList.add('fadeIn');
    });

    @keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOut {
    0% {
        opacity: 1;
        transform: translateY(0);
    }
    100% {
        opacity: 0;
        transform: translateY(20px);

    }
}
</script>

<?php 

    include 'layout/footer.php';

?>