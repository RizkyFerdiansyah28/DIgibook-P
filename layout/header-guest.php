<?php 
include 'config/app.php';
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DigiBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
.container {
            max-width: 1000px;
            margin: auto;
        }

        .profile-header {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        
        .pp {
            border-radius: 50%;
            margin-bottom: 1rem;
            object-fit: cover;
        }

    </style>
</head>

<body>
    <header class="p-3 text-bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li>
                        <a href="index.php" class="nav-link px-2 text-secondary"><img src="./foto/RAY_Logo_2021.webp"
                                width="50" alt="" srcset="" /></a>
                    </li>
                    <li>
                        <a href="home.php" class="nav-link px-2 text-secondary">Home</a>
                    </li>
                    <li><a href="cari-buku.php" class="nav-link px-2 text-white">Cari Buku</a></li>
                    
                    <div class="dropdown">
                            <button class="nav-link px-2 text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Kategori
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="buku-fiksi.php">buku fiksi</a></li>
                                <li><a class="dropdown-item" href="novel.php">novel</a></li>
                                <li><a class="dropdown-item" href="komik.php">komik</a></li>
                                <li><a class="dropdown-item" href="biografi.php">biografi</a></li>
                                <li><a class="dropdown-item" href="edukasi.php">edukasi</a></li>
                            </ul>
                        </div>
                    
                </ul>

                
                

                <div class="text-end">
                <a href="login.php" class="btn btn-dark my-2">Login</a>
                <a href="register.php" class="btn btn-primary my-2">Sign Up </a>
                </div>
            </div>
        </div>
    </header>


    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

    <script>
        $(document).ready(function(){
            $('#table').DataTable();
            });
    </script>
</body>

</html>