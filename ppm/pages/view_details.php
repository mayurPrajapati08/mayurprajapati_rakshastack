<?php
session_start();
require_once '../database/dbcon.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Check if listing ID is provided
if (!isset($_GET['lid']) || empty($_GET['lid'])) {
    header("location: Listing.php");
    exit;
}

$listing_id = intval($_GET['lid']);

// Fetch listing details
$sql = "SELECT * FROM listing WHERE lid = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $listing_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$listing = mysqli_fetch_assoc($result);

if (!$listing) {
    header("location: Listing.php");
    exit;
}

// Get all amenities
$amenities = [];
if ($listing['wifi']) $amenities[] = ['name' => 'WiFi', 'icon' => 'fas fa-wifi'];
if ($listing['ac']) $amenities[] = ['name' => 'Air Conditioning', 'icon' => 'fas fa-snowflake'];
if ($listing['food']) $amenities[] = ['name' => 'Food Service', 'icon' => 'fas fa-utensils'];
if ($listing['parking']) $amenities[] = ['name' => 'Parking', 'icon' => 'fas fa-parking'];
if ($listing['gym']) $amenities[] = ['name' => 'Gym', 'icon' => 'fas fa-dumbbell'];
if ($listing['securitry']) $amenities[] = ['name' => 'Security', 'icon' => 'fas fa-shield-alt'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($listing['title']); ?> - PG Details</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        .pg-details-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .pg-details-header {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .pg-image-gallery {
            position: relative;
            width: 100%;
            height: auto;
            overflow: hidden;
            border-radius: 12px 12px 0 0;
        }
        
        .pg-main-image {
            width: 100%;
            height: auto;
            display: block;
            max-height: 600px;
            object-fit: contain;
            background-color: #f5f5f5;
        }
        
        .pg-details-content {
            padding: 2rem;
        }
        
        .pg-title-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .pg-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }
        
        .pg-location {
            font-size: 1.2rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .pg-price-section {
            text-align: right;
        }
        
        .pg-price {
            font-size: 2rem;
            font-weight: 700;
            color: #2563eb;
        }
        
        .pg-price-period {
            font-size: 1rem;
            color: #666;
        }
        
        .pg-details-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .pg-info-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .pg-sidebar {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1a1a1a;
        }
        
        .pg-description {
            line-height: 1.8;
            color: #4a4a4a;
            margin-bottom: 2rem;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .amenity-item:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }
        
        .amenity-icon {
            width: 24px;
            height: 24px;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .amenity-name {
            font-weight: 500;
            color: #374151;
        }
        
        .pg-specs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .spec-item {
            text-align: center;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .spec-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2563eb;
            display: block;
        }
        
        .spec-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        .contact-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .contact-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-full {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: color 0.3s ease;
        }
        
        .back-button:hover {
            color: #1d4ed8;
        }
        
        @media (max-width: 768px) {
            .pg-details-grid {
                grid-template-columns: 1fr;
            }
            
            .pg-title {
                font-size: 2rem;
            }
            
            .pg-title-section {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .pg-price-section {
                text-align: left;
            }
            
            .amenities-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
                    <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                    <li><a href="logout.php" class="nav-link">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="pg-details-container">
        <a href="Listing.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Back to Listings
        </a>

        <div class="pg-details-header">
            <div class="pg-image-gallery">
                <img src="../uploads/<?php echo htmlspecialchars($listing['limage']); ?>" 
                     alt="<?php echo htmlspecialchars($listing['title']); ?>"
                     class="pg-main-image">
            </div>
            
            <div class="pg-details-content">
                <div class="pg-title-section">
                    <div>
                        <h1 class="pg-title"><?php echo htmlspecialchars($listing['title']); ?></h1>
                        <p class="pg-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($listing['ladd']); ?>
                        </p>
                    </div>
                    <div class="pg-price-section">
                        <div class="pg-price">₹<?php echo number_format($listing['price']); ?></div>
                        <div class="pg-price-period">per month</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pg-details-grid">
            <div class="pg-info-section">
                <h2 class="section-title">About this PG</h2>
                <p class="pg-description">
                    <?php echo nl2br(htmlspecialchars($listing['ldesc'])); ?>
                </p>

                <h3 class="section-title">Room Details</h3>
                <div class="pg-specs">
                    <div class="spec-item">
                        <span class="spec-value"><?php echo htmlspecialchars($listing['stype']); ?></span>
                        <span class="spec-label">Room Type</span>
                    </div>

                <h3 class="section-title">Amenities & Features</h3>
                <div class="amenities-grid">
                    <?php foreach ($amenities as $amenity): ?>
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                <i class="<?php echo $amenity['icon']; ?>"></i>
                            </div>
                            <span class="amenity-name"><?php echo $amenity['name']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>


                <div class="action-buttons">
                    <a href="inquire.php?lid=<?php echo $listing['lid']; ?>&title=<?php echo urlencode($listing['title']); ?>&price=<?php echo $listing['price']; ?>"
                       class="btn btn-primary btn-full">
                        <i class="fas fa-phone"></i>
                        Inquire Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h5>PG-Rooms</h5>
                    <p>Your trusted platform for finding the perfect paying guest accommodations.</p>
                    <div>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h5>Contact Us</h5>
                    <p><i class="fas fa-map-marker-alt"></i> 123 PG Street, Tech City</p>
                    <p><i class="fas fa-phone"></i> +91 98765 43210</p>
                    <p><i class="fas fa-envelope"></i> info@pg-rooms.com</p>
                </div>
                <div class="footer-section">
                    <h5>Quick Links</h5>
                    <p><a href="../index.php">Home</a></p>
                    <p><a href="Listing.php">Rooms</a></p>
                    <p><a href="#">About Us</a></p>
                    <p><a href="#">Support</a></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 PG-Rooms. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function shareListing() {
            if (navigator.share) {
                navigator.share({
                    title: '<?php echo htmlspecialchars($listing['title']); ?>',
                    text: 'Check out this amazing PG accommodation!',
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link copied to clipboard!');
                });
            }
        }

        function saveListing() {
            // Here you can implement save to favorites functionality
            alert('Added to favorites!');
        }

        // Smooth scroll for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
