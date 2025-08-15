<?php 


if($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../database/dbcon.php';

    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $upass = $_POST['upass'];
    $ucpass = $_POST['ucpass'];

    if($upass === $ucpass) {
        $hashed_password = password_hash($upass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (uname, uemail, upass) VALUES ('$uname', '$email', '$hashed_password')";

        if(mysqli_query($conn, $sql)) {
            echo "<script>alert('Signup successful! You can now login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Signup - PG Management System</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="../assets/css/responsive.css" />
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-container">
                <a href="../index.php" class="navbar-brand">
                    <i class="fas fa-home"></i>
                    <span>PG-Rooms</span>
                </a>
                <ul class="navbar-nav">
                    <li><a href="../index.php" class="nav-link">Home</a></li>
                    <li><a href="Listing.php" class="nav-link">Rooms</a></li>
                    <li><a href="#" class="nav-link active">Signup</a></li>
                    <li><a href="login.php" class="nav-link">Login</a></li>
                    <li><a href="../admin_paages/alogin.php" class="nav-link">Admin Login</a></li>
                    
                </ul>
            </div>
        </div>
    </nav>

    

    <!-- Signup Section -->
    <main class="container">
        <section class="section">
            <div style="max-width: 400px; margin: 0 auto;">
                <div style="background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);">
                    <h2 style="text-align: center; margin-bottom: 1.5rem; color: var(--primary-color);">
                        <i class="fas fa-user-plus"></i> User Signup
                    </h2>
                    <form action="signup.php" method="post">
                        <div style="margin-bottom: 1.5rem;">
                            <label for="uname" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                <i class="fas fa-user"></i> Username
                            </label>
                            <input type="text" name="uname" id="uname" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; font-size: 1rem;" />
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" name="email" id="email" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; font-size: 1rem;" />
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label for="upass" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" name="upass" id="upass" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; font-size: 1rem;" />
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label for="ucpass" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <input type="password" name="ucpass" id="ucpass" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; font-size: 1rem;" />
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-user-plus"></i> Signup
                        </button>
                    </form>
                    <div style="text-align: center; margin-top: 1.5rem;">
                        <p style="color: var(--text-secondary);">
                            Already have an account? <a href="login.php" style="color: var(--primary-color);">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- Brand Section -->
                <div class="footer-section">
                    <h5>PG-Rooms</h5>
                    <p>Your trusted platform for finding the perfect paying guest accommodations. Experience seamless room booking and management with our advanced system.</p>
                    <div>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>

                <!-- Contact Details -->
                <div class="footer-section">
                    <h5>Contact Us</h5>
                    <p><i class="fas fa-map-marker-alt"></i> 123 PG Street, Tech City, TC 456789</p>
                    <p><i class="fas fa-phone"></i> +91 98765 43210</p>
                    <p><i class="fas fa-envelope"></i> info@pg-rooms.com</p>
                    <p><i class="fas fa-clock"></i> 24/7 Customer Support</p>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h5>Quick Links</h5>
                    <p><a href="#">About Us</a></p>
                    <p><a href="#">Privacy Policy</a></p>
                    <p><a href="#">Terms & Conditions</a></p>
                    <p><a href="#">Support</a></p>
                    <p><a href="#">FAQ</a></p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 PG-Rooms. All rights reserved. | Designed with <i class="fas fa-heart"></i> for PG Management</p>
            </div>
        </div>
    </footer>
</body>
</html>
