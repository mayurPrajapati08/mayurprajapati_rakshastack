<?php
session_start();
$nav = false;
// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: alogin.php');
    exit();
}
if ($_SESSION['admin_name'] == 'mayur'){
    $nav = true;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pg - Admin Page</title>
    
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .search-section {
    background: var(--bg-primary);
    padding: 2rem;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    margin: -2rem auto 2rem;
    max-width: 800px;
}

.search-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.search-form input,
.search-form select {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    font-size: 1rem;
}

.search-form button {
    padding: 0.75rem 1.5rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-form button:hover {
    background: var(--primary-color);
}
    </style>
</head>
<body>
    <?php
    if($nav){
        echo '<!-- Navigation -->
        <nav class="navbar">
        <div class="container">
        <div class="navbar-container">
                <a href="admaster.php" class="navbar-brand">
                <i class=""></i>
                    <span>PG-Admin</span>
                    </a>
                    
                    <ul class="navbar-nav">
                    <li><a href="admaster.php" class="nav-link">Dashboard</a></li>
                    <li><a href="adsignup.php" class="nav-link">Add Admin User</a></li>
                    <li><a href="adlisting.php" class="nav-link"><i class="fas fa-plus-circle"></i>Add Listing</a></li>
                    <li><a href="inquiry.php" class="nav-link"><i class="fas fa-envelope"></i>Inquires </a></li>
                    <li><a href="addata.php" class="nav-link"><i class="fas fa-list"></i>Your Listing</a></li>
                    <li><a href="change_password.php" class="nav-link"> Change Password</a></li>
                    <li><a href="alogout.php" class="nav-link"><i class="fas fa-sign-out-alt">LogOut</a></li>
                    
                    </ul>
                    </div>
                    </div>
                    </nav>';
    } else {
       echo ' <!-- Navigation -->
        <nav class="navbar">
        <div class="container">
            <div class="navbar-container">
                <a href="admin.php" class="navbar-brand">
                    <i class="fas fa-home"></i>
                    <span>PG-Admin</span>
                </a>
                
                <ul class="navbar-nav">
                    <li><a href="admin.php" class="nav-link">Dashboard</a></li>
                    <li><a href="adlisting.php" class="nav-link"><i class="fas fa-plus-circle"></i>Add Listing</a></li>
                    <li><a href="inquiry.php" class="nav-link"><i class="fas fa-envelope"></i>Inquires </a></li>
                    <li><a href="addata.php" class="nav-link"><i class="fas fa-list"></i>Your Listing</a></li>
                     <li><a href="change_password.php" class="nav-link"> Change Password</a></li>
                    <li><a href="alogout.php" class="nav-link"><i class="fas fa-sign-out-alt">LogOut</a></li>

                </ul>
            </div>
        </div>
    </nav>';
    }
    ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Your Listings</h1>
            <p class="admin-subtitle">Manage all your PG listings in one place</p>
        </div>

        <!-- Search Section -->
        <div class="search-section" style="margin-bottom: 30px;">
            <form class="search-form" action="addata.php" method="GET">
                <input type="text" name="search" placeholder="Search by title, address..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
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
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="addata.php" class="btn btn-secondary" style="margin-left: 10px;">Clear</a>
            </form>
        </div>

        <?php
        // Display success or error messages
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <?php
        require_once '../database/dbcon.php';
        
        // Get admin ID from session
        $admin_id = $_SESSION['admin_id'];
        
        // Initialize search variables
        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
        $room_type = isset($_GET['room_type']) ? mysqli_real_escape_string($conn, $_GET['room_type']) : '';
        $budget_range = isset($_GET['budget']) ? mysqli_real_escape_string($conn, $_GET['budget']) : '';
        
        // Build dynamic SQL query based on search parameters
        $sql = "SELECT * FROM listing WHERE aid = $admin_id";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (title LIKE ? OR ladd LIKE ? OR ldesc LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($room_type)) {
            $sql .= " AND stype = ?";
            $params[] = $room_type;
        }
        
        if (!empty($budget_range)) {
            switch ($budget_range) {
                case '0-5000':
                    $sql .= " AND price BETWEEN 0 AND 5000";
                    break;
                case '5000-10000':
                    $sql .= " AND price BETWEEN 5000 AND 10000";
                    break;
                case '10000-15000':
                    $sql .= " AND price BETWEEN 10000 AND 15000";
                    break;
                case '15000+':
                    $sql .= " AND price >= 15000";
                    break;
            }
        }
        
        $sql .= " ORDER BY lid DESC";
        
        // Prepare and execute statement
        if (!empty($params)) {
            $stmt = mysqli_prepare($conn, $sql);
            $types = str_repeat('s', count($params));
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        } else {
            $result = mysqli_query($conn, $sql);
        }
        
        if(mysqli_num_rows($result) > 0) {
            echo '<div class="listings-grid">';
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="listing-card">
                    <div class="listing-image">
                        <img src="<?php echo $row['limage']; ?>" alt="<?php echo $row['title']; ?>">
                    </div>
                    <div class="listing-content">
                        <h3 class="listing-title"><?php echo $row['title']; ?></h3>
                        <p class="listing-price">₹<?php echo $row['price']; ?>/month</p>
                        <p class="listing-address"><?php echo $row['ladd']; ?></p>
                        <p class="listing-type"><?php echo $row['stype']; ?> Sharing</p>
                        
                        <div class="listing-features">
                            <?php if($row['wifi'] == 'WiFi') echo '<span class="feature-tag">WiFi</span>'; ?>
                            <?php if($row['ac'] == 'AC') echo '<span class="feature-tag">AC</span>'; ?>
                            <?php if($row['food'] == 'Food') echo '<span class="feature-tag">Food</span>'; ?>
                            <?php if($row['parking'] == 'Parking') echo '<span class="feature-tag">Parking</span>'; ?>
                            <?php if($row['gym'] == 'Gym') echo '<span class="feature-tag">Gym</span>'; ?>
                            <?php if($row['securitry'] == 'Security') echo '<span class="feature-tag">Security</span>'; ?>
                        </div>
                        
                        <div class="listing-actions">
                            <a href="edit_listing.php?id=<?php echo $row['lid']; ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_listing.php?id=<?php echo $row['lid']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this listing?')">Delete</a>
                        </div>
                    </div>
                </div>
                <?php
            }
            echo '</div>';
        } else {
            echo '<div class="no-listings">';
            echo '<h3>No listings found</h3>';
            echo '<p>You haven\'t added any listings yet. <a href="adlisting.php">Add your first listing</a></p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>

<style>
.admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.admin-header {
    text-align: center;
    margin-bottom: 40px;
}

.admin-title {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 10px;
}

.admin-subtitle {
    font-size: 1.2rem;
    color: #666;
}

.listings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.listing-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.listing-card:hover {
    transform: translateY(-5px);
}

.listing-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.listing-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.listing-content {
    padding: 20px;
}

.listing-title {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 10px;
}

.listing-price {
    font-size: 1.3rem;
    color: #ff6b6b;
    font-weight: bold;
    margin-bottom: 5px;
}

.listing-address {
    color: #666;
    margin-bottom: 5px;
}

.listing-type {
    color: #666;
    margin-bottom: 15px;
}

.listing-features {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 20px;
}

.feature-tag {
    background: #e9ecef;
    color: #495057;
    padding: 4px 8px;
    border-radius: 15px;
    font-size: 0.8rem;
}

.listing-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-edit {
    background-color: #007bff;
    color: white;
}

.btn-edit:hover {
    background-color: #0056b3;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
}

.no-listings {
    text-align: center;
    padding: 60px 20px;
}

.no-listings h3 {
    color: #666;
    margin-bottom: 10px;
}

.no-listings a {
    color: #007bff;
    text-decoration: none;
}

.no-listings a:hover {
    text-decoration: underline;
}
</style>
