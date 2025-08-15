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

// Check if listing ID is provided
if (!isset($_GET['id'])) {
    header('Location: addata.php');
    exit();
}

$listing_id = $_GET['id'];
require_once '../database/dbcon.php';

// Fetch the listing details
$sql = "SELECT * FROM listing WHERE lid = $listing_id AND aid = '$_SESSION[admin_id]'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header('Location: addata.php');
    exit();
}

$listing = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ltitle = $_POST['ltitle'];
    $lprice = $_POST['lprice'];
    $laddress = $_POST['laddress'];
    $ltype = $_POST['ltype'];
    $lfeature1 = isset($_POST['lfeature1']) ? $_POST['lfeature1'] : '';
    $lfeature2 = isset($_POST['lfeature2']) ? $_POST['lfeature2'] : '';
    $lfeature3 = isset($_POST['lfeature3']) ? $_POST['lfeature3'] : '';
    $lfeature4 = isset($_POST['lfeature4']) ? $_POST['lfeature4'] : '';
    $lfeature5 = isset($_POST['lfeature5']) ? $_POST['lfeature5'] : '';
    $lfeature6 = isset($_POST['lfeature6']) ? $_POST['lfeature6'] : '';
    $ldescription = $_POST['ldescription'];
    
    // Handle file upload if new image is provided
    $image_path = $listing['limage']; // Keep existing image by default
    
    if (isset($_FILES['limage']) && $_FILES['limage']['error'] == 0) {
        // Delete old image if it exists
        if (file_exists($listing['limage'])) {
            unlink($listing['limage']);
        }
        
        $target_dir = "../uploads/";
        $image_path = $target_dir . basename($_FILES["limage"]["name"]);
        move_uploaded_file($_FILES["limage"]["tmp_name"], $image_path);
    }
    
    // Update the listing
    $update_sql = "UPDATE listing SET 
        title = '$ltitle',
        price = '$lprice',
        ladd = '$laddress',
        stype = '$ltype',
        wifi = '$lfeature1',
        ac = '$lfeature2',
        food = '$lfeature3',
        parking = '$lfeature4',
        gym = '$lfeature5',
        securitry = '$lfeature6',
        limage = '$image_path',
        ldesc = '$ldescription'
        WHERE lid = $listing_id AND aid = '$_SESSION[admin_id]'";
    
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Listing updated successfully!'); window.location.href='addata.php';</script>";
    } else {
        echo "<script>alert('Error updating listing: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Listing - PG Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
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
                    <li><a href="alogout.php" class="nav-link"><i class="fas fa-sign-out-alt">LogOut</a></li>

                </ul>
            </div>
        </div>
    </nav>';
    }
    ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Edit Listing</h1>
            <p class="admin-subtitle">Update your PG listing details</p>
        </div>
        
        <form action="edit_listing.php?id=<?php echo $listing_id; ?>" method="post" enctype="multipart/form-data" class="admin-form">
            <div class="form-section">
                <h3 class="form-section-title">Basic Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="ltitle">Title *</label>
                        <input type="text" class="form-control" name="ltitle" id="ltitle" value="<?php echo htmlspecialchars($listing['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="lprice">Price (₹) *</label>
                        <input type="number" class="form-control" name="lprice" id="lprice" value="<?php echo $listing['price']; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="laddress">Address *</label>
                    <input type="text" class="form-control" name="laddress" id="laddress" value="<?php echo htmlspecialchars($listing['ladd']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="ltype">Sharing Type *</label>
                    <select class="form-control" name="ltype" id="ltype" required>
                        <option value="">Select Sharing Type</option>
                        <option value="Single" <?php echo $listing['stype'] == 'Single' ? 'selected' : ''; ?>>Single</option>
                        <option value="Double" <?php echo $listing['stype'] == 'Double' ? 'selected' : ''; ?>>Double</option>
                        <option value="Triple" <?php echo $listing['stype'] == 'Triple' ? 'selected' : ''; ?>>Triple</option>
                        <option value="Four" <?php echo $listing['stype'] == 'Four' ? 'selected' : ''; ?>>Four Sharing</option>
                    </select>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title">Property Features</h3>
                <div class="form-group">
                    <label>Available Amenities</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature1" value="WiFi" id="wifi" <?php echo $listing['wifi'] == 'WiFi' ? 'checked' : ''; ?>>
                            <label for="wifi">WiFi</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature2" value="AC" id="ac" <?php echo $listing['ac'] == 'AC' ? 'checked' : ''; ?>>
                            <label for="ac">Air Conditioning</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature3" value="Food" id="food" <?php echo $listing['food'] == 'Food' ? 'checked' : ''; ?>>
                            <label for="food">Food Service</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature4" value="Parking" id="parking" <?php echo $listing['parking'] == 'Parking' ? 'checked' : ''; ?>>
                            <label for="parking">Parking</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature5" value="Gym" id="gym" <?php echo $listing['gym'] == 'Gym' ? 'checked' : ''; ?>>
                            <label for="gym">Gym</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature6" value="Security" id="security" <?php echo $listing['securitry'] == 'Security' ? 'checked' : ''; ?>>
                            <label for="security">24/7 Security</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title">Property Details</h3>
                <div class="form-group">
                    <label for="limage">Property Images</label>
                    <input type="file" class="form-control" name="limage" id="limage" accept="image/*">
                    <small class="form-text text-muted">Leave empty to keep current image</small>
                    <?php if($listing['limage']): ?>
                        <div class="current-image">
                            <p>Current Image:</p>
                            <img src="<?php echo $listing['limage']; ?>" alt="Current listing image" style="max-width: 200px; max-height: 150px;">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="ldescription">Description *</label>
                    <textarea class="form-control" name="ldescription" id="ldescription" required><?php echo htmlspecialchars($listing['ldesc']); ?></textarea>
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Update Listing</button>
                <a href="addata.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
