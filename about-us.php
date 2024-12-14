<?php
    session_start();
    if (!isset($_SESSION["login"])) {
        include 'layout/header-guest.php';
    } else {
        include 'layout/header.php';
    }
    $buku = select("SELECT * FROM buku");
?>

    <style>
        .hero {
            background: url(bacabuku.jpeg) no-repeat center center/cover;
            color: white;
            text-align: center;
            padding: 100px 20px;
        }

    </style>


    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>About Us</h1>
            <p>Learn more about who we are and what we do.</p>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h2>Our Mission</h2>
                    <p>Untuk merevolusi cara orang mengakses dan menikmati buku dengan menyediakan platform tanpa batas untuk membaca dan membeli buku digital, menumbuhkan kecintaan pada literatur dalam format yang berkelanjutan dan mudah diakses.</p>
                </div>
                <div class="col-md-6 mb-4">
                    <h2>Our Vision</h2>
                    <p>Menjadi platform online terdepan untuk buku digital, menciptakan komunitas pembaca global yang merangkul kenyamanan dan inovasi literatur digital.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            <p>&copy; 2024 Digibook. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<?php 

    include 'layout/footer.php';

?>