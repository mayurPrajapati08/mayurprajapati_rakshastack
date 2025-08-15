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

if($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../database/dbcon.php';

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
    $target_file = '';

    // Handle file upload
    if (isset($_FILES['limage']) && $_FILES['limage']['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["limage"]["name"]);
        move_uploaded_file($_FILES["limage"]["tmp_name"], $target_file);
    } else {
        echo "<script>alert('Error uploading image. Please try again.');</script>";
        exit();
    }

    // Insert into database
    $sql = "INSERT INTO `listing` ( `title`, `price`, `ladd`, `stype`, `wifi`, `ac`, `food`, `parking`, `gym`, `securitry`, `limage`, `ldesc`, `aid`) VALUES ('$ltitle', '$lprice', '$laddress', '$ltype', '$lfeature1', '$lfeature2', '$lfeature3', '$lfeature4', '$lfeature5', '$lfeature6', '$target_file', '$ldescription', '$_SESSION[admin_id]')";

    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Listing added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pg - Admin Page</title>
    
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
                    <li><a href="#" class="nav-link">Add Listing</a></li>
                    <li><a href="inquiry.php" class="nav-link">Inquires </a></li>
                    <li><a href="addata.php" class="nav-link">Your Listing</a></li>
                     <li><a href="change_password.php" class="nav-link"> Change Password</a></li>
                    <li><a href="alogout.php" class="nav-link">LogOut</a></li>
                    
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
                    <li><a href="#" class="nav-link">Add Listing</a></li>
                    <li><a href="inquiry.php" class="nav-link">Inquires </a></li>
                    <li><a href="addata.php" class="nav-link">Your Listing</a></li>
                     <li><a href="change_password.php" class="nav-link"> Change Password</a></li>
                    <li><a href="alogout.php" class="nav-link">LogOut</a></li>

                </ul>
            </div>
        </div>
    </nav>';
    }
    ?>


    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Add New Listing</h1>
            <p class="admin-subtitle">Fill in the details to add a new PG listing</p>
        </div>
        
        <form action="adlisting.php" method="post" enctype="multipart/form-data" class="admin-form">
            <div class="form-section">
                <h3 class="form-section-title">Basic Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="ltitle">Title *</label>
                        <input type="text" class="form-control" name="ltitle" id="ltitle" placeholder="Enter Your PG Title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="lprice">Price (₹) *</label>
                        <input type="number" class="form-control" name="lprice" id="lprice" placeholder="Enter Your PG Price" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="laddress">Address *</label>
                    <input type="text" class="form-control" name="laddress" id="laddress" placeholder="Enter Your PG Address" required>
                </div>
                
                <div class="form-group">
                    <label for="ltype">Sharing Type *</label>
                    <select class="form-control" name="ltype" id="ltype" required>
                        <option value="">Select Sharing Type</option>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
                        <option value="Triple">Triple</option>
                        <option value="Four">Four Sharing</option>
                    </select>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title">Property Features</h3>
                <div class="form-group">
                    <label>Available Amenities</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature1" value="WiFi" id="wifi">
                            <label for="wifi">WiFi</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature2" value="AC" id="ac">
                            <label for="ac">Air Conditioning</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature3" value="Food" id="food">
                            <label for="food">Food Service</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature4" value="Parking" id="parking">
                            <label for="parking">Parking</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature5" value="Gym" id="gym">
                            <label for="gym">Gym</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="lfeature6" value="Security" id="security">
                            <label for="security">24/7 Security</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title">Property Details</h3>
                <div class="form-group">
                    <label for="limage">Property Images *</label>
                    <input type="file" class="form-control" name="limage" id="limage" accept="image/*" required>
                    <small class="form-text text-muted">Upload high-quality images of your property</small>
                </div>
                
                <div class="form-group">
                    <label for="ldescription">Description *</label>
                    <textarea class="form-control" name="ldescription" id="ldescription" placeholder="Enter detailed description about your PG - include room details, nearby facilities, rules, etc." required></textarea>
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Add Listing</button>
                <button type="reset" class="btn btn-secondary">Clear Form</button>
            </div>
        </form>
    </div>
</body>
</html>