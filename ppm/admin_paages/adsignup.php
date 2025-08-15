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

    $aid = $_POST['adid'];
    $aname = $_POST['adname'];
    $aemail = $_POST['ademail'];
    $apass = $_POST['adpass'];
    $acpass = $_POST['adcpass'];

    if($apass === $acpass) {
        $hashed_password = password_hash($apass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `admin` (`aid`, `aname`, `aemail`, `apass`) VALUES ('$aid', '$aname', '$aemail', '$hashed_password')";

        if(mysqli_query($conn, $sql)) {
            echo "<script>alert('Admin ". $aname . " successful!')</script>";
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
                    <li><a href="#" class="nav-link"><i class="fas fa-user-plus"></i>Add Admin User</a></li>
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
            <h1 class="admin-title">Add New Admin User</h1>
            <p class="admin-subtitle">Create a new administrator account</p>
        </div>
        
        <form action="adsignup.php" method="post" class="admin-form">
            <div class="form-section">
                <h3 class="form-section-title">Account Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="adid">Admin ID *</label>
                        <input type="text" class="form-control" name="adid" id="adid" placeholder="Enter unique admin ID" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="adname">Admin Name *</label>
                        <input type="text" class="form-control" name="adname" id="adname" placeholder="Enter full name" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="ademail">Admin Email *</label>
                    <input type="email" class="form-control" name="ademail" id="ademail" placeholder="Enter email address" required>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="adpass">Admin Password *</label>
                        <input type="password" class="form-control" name="adpass" id="adpass" placeholder="Enter secure password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="adcpass">Confirm Password *</label>
                        <input type="password" class="form-control" name="adcpass" id="adcpass" placeholder="Re-enter password" required>
                    </div>
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" name="submit" class="btn btn-primary">Add Admin</button>
                <button type="reset" class="btn btn-secondary">Clear Form</button>
            </div>
        </form>
    </div>
</body>
</html>
