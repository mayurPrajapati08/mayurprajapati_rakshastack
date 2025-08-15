<?php
session_start();
$nav =false;
require_once 'database/dbcon.php';

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: pages/login.php");
    exit;
}
if(isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == true){
    $nav = true;
}

$sql = "SELECT * FROM listing ORDER BY lid DESC LIMIT 6";
$result = mysqli_query($conn, $sql);
$listings = mysqli_fetch_all($result, MYSQLI_ASSOC);

$search_location = isset($_GET['location']) ? mysqli_real_escape_string($conn, $_GET['location']) : '';
$room_type = isset($_GET['room_type']) ? mysqli_real_escape_string($conn, $_GET['room_type']) : '';
$budget_range = isset($_GET['budget']) ? mysqli_real_escape_string($conn, $_GET['budget']) : '';

// Build dynamic SQL query based on search parameters
// $sql = "SELECT * FROM listing WHERE 1=1";
// $params = [];

// if (!empty($search_location)) {
//     $sql .= " AND (ladd LIKE ? OR title LIKE ?)";
//     $params[] = "%$search_location%";
//     $params[] = "%$search_location%";
// }

// if (!empty($room_type)) {
//     $sql .= " AND stype = ?";
//     $params[] = $room_type;
// }

// if (!empty($budget_range)) {
//     switch ($budget_range) {
//         case '0-5000':
//             $sql .= " AND price BETWEEN 0 AND 5000";
//             break;
//         case '5000-10000':
//             $sql .= " AND price BETWEEN 5000 AND 10000";
//             break;
//         case '10000-15000':
//             $sql .= " AND price BETWEEN 10000 AND 15000";
//             break;
//         case '15000+':
//             $sql .= " AND price >= 15000";
//             break;
//     }
// }

// $sql .= " ORDER BY lid DESC";

// // Prepare and execute statement
// $stmt = mysqli_prepare($conn, $sql);
// if (!empty($params)) {
//     $types = str_repeat('s', count($params));
//     mysqli_stmt_bind_param($stmt, $types, ...$params);
// }
// mysqli_stmt_execute($stmt);
// $result = mysqli_stmt_get_result($stmt);
// $listings = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PG Management System - Find Your Perfect PG</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php 
    
    if($nav){

        echo "<!-- Navigation -->
            <nav class='navbar'>
                <div class='container'>
                    <div class='navbar-container'>
                        <a href='#' class='navbar-brand'>
                            <i class='fas fa-home'></i>
                            <span>PG-Rooms</span>
                        </a>
        
                        <ul class='navbar-nav'>
                            <li><a href='#' class='nav-link active'>Home</a></li>
                            <li><a href='pages/Listing.php' class='nav-link'>Rooms</a></li>
                            <li><a href='pages/dashboard.php' class='nav-link'>Dashboard</a></li>
                            <li><a href='pages/logout.php' class='nav-link'>Logout</a></li> 
                        </ul>
                    </div>
                </div>
            </nav>"; 
    }
    else{
        echo "<!-- Navigation -->
        <nav class='navbar'>
            <div class='container'>
                <div class='navbar-container'>
                <a href='#' class='navbar-brand'>
                    <i class='fas fa-home'></i>
                    <span>PG-Rooms</span>
                </a>
                
                <ul class='navbar-nav'>
                    <li><a href='#' class='nav-link active'>Home</a></li>
                    <li><a href='pages/Listing.php' class='nav-link'>Rooms</a></li>
                    <li><a href='pages/signup.php' class='nav-link'>Signup</a></li>
                    <li><a href='pages/login.php' class='nav-link'>Login</a></li>
                    <li><a href='admin_paages/alogin.php' class='nav-link'>Admin Login</a></li>

                </ul>
            </div>
        </div>
        </nav>";
    }

    ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
           
                <h1>Find Your Perfect PG</h1>
                <p>Discover premium PG accommodations across the city. From budget-friendly rooms to luxury suites, we have the perfect space for your needs.</p>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <div class="container">
        <div class="search-section">
            <form class="search-form" action="index.php" method="GET">
                <input type="text" name="location" placeholder="Enter location..." value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
                <select name="room_type">
                    <option value="">Room Type</option>
                    <option value="single" <?php echo (isset($_GET['room_type']) && $_GET['room_type'] == 'single') ? 'selected' : ''; ?>>Single Room</option>
                    <option value="double" <?php echo (isset($_GET['room_type']) && $_GET['room_type'] == 'double') ? 'selected' : ''; ?>>Double Sharing</option>
                    <option value="triple" <?php echo (isset($_GET['room_type']) && $_GET['room_type'] == 'triple') ? 'selected' : ''; ?>>Triple Sharing</option>
                </select>
                <select name="budget">
                    <option value="">Budget Range</option>
                    <option value="0-5000" <?php echo (isset($_GET['budget']) && $_GET['budget'] == '0-5000') ? 'selected' : ''; ?>>₹0 - ₹5,000</option>
                    <option value="5000-10000" <?php echo (isset($_GET['budget']) && $_GET['budget'] == '5000-10000') ? 'selected' : ''; ?>>₹5,000 - ₹10,000</option>
                    <option value="10000-15000" <?php echo (isset($_GET['budget']) && $_GET['budget'] == '10000-15000') ? 'selected' : ''; ?>>₹10,000 - ₹15,000</option>
                    <option value="15000+" <?php echo (isset($_GET['budget']) && $_GET['budget'] == '15000+') ? 'selected' : ''; ?>>₹15,000+</option>
                </select>
                <button type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
    </div>

    <main class="container">
        <!-- Featured PGs Section -->
         <section class="section">
        <!-- PG Listings Section -->
            <h2>Available PG Accommodations</h2>
            <div class="pg-grid">
                <?php if (empty($listings)): ?>
                    <div class="pg-card">
                        <div class="pg-card-body">
                            <h3>No listings available at the moment</h3>
                            <p>Please check back later for new accommodations.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($listings as $listing): ?>
                        <div class="pg-card">
                            <div class="pg-card-header">
                                <img src="uploads/<?php echo htmlspecialchars($listing['limage']); ?>"
                                alt="<?php echo htmlspecialchars($listing['title']); ?>"
                                style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                            </div>
                            <div class="pg-card-body">
                                <h3 class="pg-card-title"><?php echo htmlspecialchars($listing['title']); ?></h3>
                                <p class="pg-card-location">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($listing['ladd']); ?>
                                </p>
                                <div class="pg-card-features">
                                    <?php
                                    $features = [];
                                    if (isset($listing['wifi'])) $features[] = 'WiFi';
                                    if (isset($listing['ac'])) $features[] = 'AC';
                                    if (isset($listing['food'])) $features[] = 'Food';
                                    if (isset($listing['parking'])) $features[] = 'Parking';
                                    if (isset($listing['gym'])) $features[] = 'Gym';
                                    if (isset($listing['securitry'])) $features[] = 'Security';

                                    foreach ($features as $feature): ?>
                                        <span class="feature-tag"><?php echo htmlspecialchars($feature); ?></span>
                                    <?php endforeach; ?>

                                    <?php if (empty($features)): ?>
                                        <span class="feature-tag">Basic Amenities</span>
                                    <?php endif; ?>
                                </div>
                                <div class="pg-card-price">₹<?php echo number_format($listing['price']); ?>/month</div>
                                <div class="pg-card-actions">
                                    <a href="pages/view_details.php?lid=<?php echo $listing['lid']; ?>" 
                                        class="btn btn-secondary">View Details</a>
                                    <a href="pages/inquire.php?lid=<?php echo $listing['lid']; ?>&title=<?php echo urlencode($listing['title']); ?>&price=<?php echo $listing['price']; ?>"
                                        class="btn btn-primary">Inquire Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
</body>
</html>
