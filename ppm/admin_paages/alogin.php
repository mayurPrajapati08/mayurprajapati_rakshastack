<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../database/dbcon.php';
    
    $alname = $_POST['aname'];
    $alpass = $_POST['apass'];

    $sql = "SELECT * FROM admin WHERE aname = '$alname'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if($num == 1) {
        while($row = mysqli_fetch_assoc($result)){
            if(password_verify($alpass,$row['apass'])){    
                session_start(); 
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_name'] = $alname;
                $_SESSION['admin_id'] = $row['aid'];
                $_SESSION['admin_pass'] = $alpass;
                if($_SESSION['admin_name'] == 'mayur'){
                    header("Location: admaster.php");
                }else{
                    header("Location: admin.php");
                }
            }else{
                echo "<script>alert('Invalid Password. Please try again.');</script>";        
            }
        }
    } else {
        echo "<script>alert('Invalid Username!!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login - PG Management System</title>
    
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
                    <li><a href="../pages/Listing.php" class="nav-link">Rooms</a></li>
                    <li><a href="../pages/signup.php" class="nav-link">Signup</a></li>
                    <li><a href="../pages/login.php" class="nav-link">Login</a></li>
                    <li><a href="#" class="nav-link active">Admin Login</a></li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <!-- Admin Login Section -->
    <main class="container">
        <section class="section">
            <div style="max-width: 400px; margin: 0 auto;">
                <div style="background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);">
                    <h2 style="text-align: center; margin-bottom: 1.5rem; color: var(--primary-color);">
                        <i class="fas fa-user-shield"></i> Admin Login
                    </h2>
                    <form action="alogin.php" method="post">
                        <div style="margin-bottom: 1.5rem;">
                            <label for="aname" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                <i class="fas fa-user"></i> Username/Email
                            </label>
                            <input type="text" name="aname" id="aname" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; font-size: 1rem;" />
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label for="apass" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" name="apass" id="apass" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.375rem; font-size: 1rem;" />
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-sign-in-alt"></i> Admin Login
                        </button>
                    </form>
                    <div style="text-align: center; margin-top: 1.5rem;">
                        <p style="color: var(--text-secondary);">
                            Not an admin? <a href="login.php" style="color: var(--primary-color);">User Login</a>
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
                <div class="footer-section">
                    <h5>Contact Us</h5>
                    <p><i class="fas fa-map-marker-alt"></i> 123 PG Street, Tech City, TC 456789</p>
                    <p><i class="fas fa-phone"></i> +91 98765 43210</p>
                    <p><i class="fas fa-envelope"></i> info@pg-rooms.com</p>
                    <p><i class="fas fa-clock"></i> 24/7 Customer Support</p>
                </div>
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
