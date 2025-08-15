<?php
session_start();
require_once '../database/dbcon.php';
    
    // Get total admin count
    $sql = "SELECT COUNT(*) as admin_count FROM admin";
    $result = mysqli_query($conn, $sql);
    $admin_row = mysqli_fetch_assoc($result);
    $total_admins = $admin_row['admin_count'];
    
    // Get total listings count
    $sql = "SELECT COUNT(*) as listing_count FROM listing";
    $result = mysqli_query($conn, $sql);
    $listing_row = mysqli_fetch_assoc($result);
    $total_listings = $listing_row['listing_count'];
    
    // Get new inquiries count (assuming there's a status or read flag)
   $sql = "SELECT COUNT(*) as inquiry_count FROM inquire WHERE status = 'Pending'";
    $result = mysqli_query($conn, $sql);
    $inquiry_row = mysqli_fetch_assoc($result); 
    $new_inquiries = $inquiry_row['inquiry_count'] ?? 0;


    // Get the total user count
    $sql = "SELECT COUNT(*) as user_count FROM users";
    $result = mysqli_query($conn, $sql);
    $user_row = mysqli_fetch_assoc($result);
    $total_user = $user_row['user_count'];
    
    // Get all admin users
    $sql = "SELECT * FROM admin ORDER BY aid DESC";
    $admin_result = mysqli_query($conn, $sql);
    
    // Handle admin deletion
    if(isset($_POST['delete_admin'])) {
        $admin_id = $_POST['admin_id'];
        
        // Prevent deleting the last admin
        $check_sql = "SELECT COUNT(*) as count FROM admin";
        $check_result = mysqli_query($conn, $check_sql);
        $check_row = mysqli_fetch_assoc($check_result);
        
        if($check_row['count'] > 1) {
            $delete_sql = "DELETE FROM admin WHERE aid = ?";
            $stmt = mysqli_prepare($conn, $delete_sql);
            mysqli_stmt_bind_param($stmt, "i", $admin_id);
            
            if(mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Admin deleted successfully!";
            } else {
                $_SESSION['error'] = "Error deleting admin!";
            }
        } else {
            $_SESSION['error'] = "Cannot delete the last admin!";
        }
        
        header("Location: admaster.php");
        exit();
    }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PG - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-container">
                <a href="#" class="navbar-brand">
                    <i class="fas fa-home"></i>
                    <span>PG-Admin</span>
                </a>
                
                <ul class="navbar-nav">
                    <li><a href="adsignup.php" class="nav-link"> Add Admin User</a></li>
                    <li><a href="adlisting.php" class="nav-link"> Add Listing</a></li>
                    <li><a href="inquiry.php" class="nav-link"> Inquiries</a></li>
                    <li><a href="addata.php" class="nav-link"> Your Listings</a></li>
                    <li><a href="change_password.php" class="nav-link"> Change Password</a></li>
                    <li><a href="alogout.php" class="nav-link"> LogOut</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <h1 class="page-title">Welcome to Admin Dashboard</h1>
            
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fas fa-chart-line"></i> Dashboard Overview</h2>
                </div>
                <div class="card-body">
                    <p>Welcome to the PG Admin Panel. Use the navigation menu above to manage your listings, view inquiries, and manage admin users.</p>
                </div>
            </div>

            <div class="d-flex justify-content-between" style="gap: 2rem; flex-wrap: wrap;">
                <div class="card" style="flex: 1; min-width: 250px;">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-home"></i> Total Listings</h3>
                    </div>
                    <div class="card-body text-center">
                        <h2 style="color: var(--secondary-color); font-size: 2.5rem;"><?php echo $total_listings; ?></h2>
                        <p>Active listings</p>
                    </div>
                </div>

                <div class="card" style="flex: 1; min-width: 250px;">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-envelope"></i> New Inquiries</h3>
                    </div>
                    <div class="card-body text-center">
                        <h2 style="color: var(--success-color); font-size: 2.5rem;"><?php echo $new_inquiries; ?></h2>
                        <p>Unread messages</p>
                    </div>
                </div>

                <div class="card" style="flex: 1; min-width: 250px;">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-users"></i> Admin Users</h3>
                    </div>
                    <div class="card-body text-center">
                        <h2 style="color: var(--warning-color); font-size: 2.5rem;"><?php echo $total_admins; ?></h2>
                        <p>Active admins</p>
                    </div>
                </div>

                 <div class="card" style="flex: 1; min-width: 250px;">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-users"></i>Users</h3>
                    </div>
                    <div class="card-body text-center">
                        <h2 style="color: var(--warning-color); font-size: 2.5rem;"><?php echo $total_user; ?></h2>
                        <p>Active User</p>
                    </div>
                </div>
            </div>

            <!-- Admin Management Section -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fas fa-users-cog"></i> Admin Management</h2>
                </div>
                <div class="card-body">
                    
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($admin = mysqli_fetch_assoc($admin_result)): ?>
                                <tr>
                                    <td><?php echo $admin['aid']; ?></td>
                                    <td><?php echo htmlspecialchars($admin['aname']); ?></td>
                                    <td><?php echo htmlspecialchars($admin['aemail']); ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                            <input type="hidden" name="admin_id" value="<?php echo $admin['aid']; ?>">
                                            <button type="submit" name="delete_admin" class="btn btn-danger btn-sm" <?php echo ($total_admins <= 1) ? 'disabled' : ''; ?>>
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($total_admins <= 1): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Cannot delete the last remaining admin.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <style>
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .table th, .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .table th {
            background-color: skyblue;
            font-weight: bold;
        }
        .btn {
            padding: 0.375rem 0.75rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-danger:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            opacity: 0.65;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</body>
</html>
