<?php 
session_start();

include('layout/header-guest.php');

// Check if the login form is submitted
if (isset($_POST['login'])) {
    // Check if email and password are posted
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        // Escape email and password inputs to prevent SQL injection
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $password = mysqli_real_escape_string($db, $_POST['password']);
        
        // Query to check if the user exists
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($db, $query);
        
        // Check if the user is found
        if (mysqli_num_rows($result) == 1) {
            $hasil = mysqli_fetch_assoc($result);
            
            // Verify the password
            if (password_verify($password, $hasil['password'])) {
                // Set session variables on successful login
                $_SESSION['login'] = true;
                $_SESSION['id_user'] = $hasil['id_user'];
                $_SESSION['username'] = $hasil['username'];
                $_SESSION['email'] = $hasil['email'];
                $_SESSION['status'] = $hasil['status']; // Menyimpan status user
                
                // Redirect based on user role
                if ($hasil['status'] == 1) {
                    // Redirect admin ke halaman admin
                    header("Location: admin-dashboard.php");
                } else {
                    // Redirect user biasa ke halaman daftar buku
                    header("Location: index.php");
                }
                exit;
            } else {
                // Password is incorrect
                $error = "Password salah!";
            }
        } else {
            // No user found
            $error = "Email tidak ditemukan!";
        }
    } else {
        $error = "Mohon isi semua bidang!";
    }
}
?>

<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-12 col-sm-8 col-md-6 m-auto">
            <div class="card">
                <div class="card-body">
                    <h1 class="text-center">Login</h1>
                    <?php if (isset($error)) : ?>
                    <script>
                    alert('gagal')
                    </script>;
                    <?php endif; ?>
                    <form action="" method="POST">
                        <input type="email" name="email" class="form-control my-3 py-2" placeholder="Email" required />
                        <input type="password" name="password" class="form-control my-3 py-2" placeholder="Password"
                            required />
                        <div class="text-center mt-3">
                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    <a href="register.php" class="nav-link text-primary text-center text-py-10">Sign Up</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

    include 'layout/footer.php';

?>