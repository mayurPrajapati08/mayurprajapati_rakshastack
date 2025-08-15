<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require_once '../database/dbcon.php';

// Get user information
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE uname = '$username'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Handle password change
$password_changed = false;
$error_message = '';

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    if(password_verify($current_password, $user['upass'])) {
        if($new_password === $confirm_password) {
            if(strlen($new_password) >= 6) {
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET upass = '$hashed_new_password' WHERE uname = '$username'";
                
                if(mysqli_query($conn, $update_sql)) {
                    $password_changed = true;
                    // Update session password
                    $_SESSION['upass'] = $new_password;
                } else {
                    $error_message = "Error updating password. Please try again.";
                }
            } else {
                $error_message = "Password must be at least 6 characters long.";
            }
        } else {
            $error_message = "New passwords do not match.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}

// Fetch user inquiries with admin response
$user_id = $user['sno'];
$inquiries_sql = "SELECT i.*, l.title, l.price, l.ladd, a.aname as admin_name
                  FROM inquire i 
                  JOIN listing l ON i.listing_id = l.lid 
                  LEFT JOIN admin a ON i.admin_id = a.aid
                  WHERE i.user_id = '$user_id' 
                  ORDER BY i.created DESC";
$inquiries_result = mysqli_query($conn, $inquiries_sql);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - PG Management System</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .dashboard-card h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin-right: 1rem;
        }
        
        .user-details p {
            margin: 0.5rem 0;
            font-size: 1.1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            font-size: 1rem;
        }
        
        .btn-change-password {
            padding: 0.75rem 1.5rem;
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 0.375rem;
            font-size: 1rem;
            width: 30%;
        }
        
        .btn-change-password:hover {
            background: var(--primary-color);
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 0.375rem;
            transition: background 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--primary-color);
            color: white;
        }
        
        
        @media (max-width: 768px) {
            .dashboard-grid {
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
                    <li><a href="dashboard.php" class="nav-link active">Dashboard</a></li>
                    <li><a href="logout.php" class="nav-link">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <h1 style="text-align: center; margin-bottom: 1rem; color: var(--primary-color);">
            <i class="fas fa-tachometer-alt"></i> User Dashboard
        </h1>
        <p style="text-align: center; color: var(--text-secondary); margin-bottom: 2rem;">
            Welcome back, <?php echo htmlspecialchars($user['uname']); ?>!
        </p>

        <div class="dashboard-grid">
            <!-- Sidebar -->
            <div>
                <div class="dashboard-card">
                    <h3><i class="fas fa-bars"></i> Menu</h3>
                    <ul class="sidebar-menu">
                        <li><a href="dashboard.php" class="active"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a href="#inquiries"><i class="fas fa-envelope"></i> My Inquiries</a></li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div>
                <!-- User Information Card -->
                <div class="dashboard-card">
                    <h3><i class="fas fa-user-circle"></i> Profile Information</h3>
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['uname']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['uemail']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Change Password Card -->
                <div class="dashboard-card">
                    <h3><i class="fas fa-key"></i> Change Password</h3>
                    
                    <?php if($password_changed): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Password changed successfully!
                        </div>
                    <?php endif; ?>
                    
                    <?php if($error_message): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="dashboard.php">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" required minlength="6">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                        </div>
                        
                        <button type="submit" name="change_password" class="btn-change-password">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- My Inquiries Section -->
        <div class="dashboard-container" id="inquiries">
            <h2 style="text-align: center; margin: 3rem 0 2rem; color: var(--primary-color);">
                <i class="fas fa-envelope"></i> My Inquiries
            </h2>

            <div class="dashboard-card">
                <h3><i class="fas fa-list"></i> Inquiry History</h3>
                
                <?php if(mysqli_num_rows($inquiries_result) > 0): ?>
                    <div class="inquiries-list">
                        <?php while($inquiry = mysqli_fetch_assoc($inquiries_result)): ?>
                            <div class="inquiry-item" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; background: #fafafa;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                    <div>
                                        <h4 style="color: var(--primary-color); margin: 0 0 0.5rem;">
                                            <?php echo htmlspecialchars($inquiry['subject']); ?>
                                        </h4>
                                        <span class="status status-<?php echo strtolower($inquiry['status']); ?>" 
                                              style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500;
                                                     <?php echo $inquiry['status'] == 'Pending' ? 'background: #fff3cd; color: #856404;' : 
                                                               ($inquiry['status'] == 'Completed' ? 'background: #d4edda; color: #155724;' : 
                                                               'background: #f8d7da; color: #721c24;'); ?>">
                                            <?php echo ucfirst($inquiry['status']); ?>
                                        </span>
                                    </div>
                                    <div style="text-align: right;">
                                        <small style="color: #666;">
                                            <?php echo date('M j, Y H:i', strtotime($inquiry['created'])); ?>
                                        </small>
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 1rem;">
                                    <strong>Listing:</strong> <?php echo htmlspecialchars($inquiry['title']); ?>
                                    <br>
                                    <strong>Price:</strong> ₹<?php echo htmlspecialchars($inquiry['price']); ?>
                                    <br>
                                    <strong>Address:</strong> <?php echo htmlspecialchars($inquiry['ladd']); ?>
                                </div>
                                
                                <div style="background: white; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                                    <strong>Message:</strong>
                                    <p style="margin: 0.5rem 0 0; white-space: pre-wrap;"><?php echo htmlspecialchars($inquiry['message']); ?></p>
                                </div>
                                
                                <div style="display: flex; gap: 0.5rem;">
                                    <button class="btn-view" onclick="viewInquiry(<?php echo $inquiry['iid']; ?>)"
                                            style="background: #007bff; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">
                                        <i class="fas fa-eye"></i> View Details
                                    </button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 2rem; color: #666;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 1rem; color: #ddd;"></i>
                        <h4>No inquiries found</h4>
                        <p>You haven't submitted any inquiries yet.</p>
                        <a href="Listing.php" style="color: var(--primary-color); text-decoration: none;">
                            <i class="fas fa-search"></i> Browse Listings
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script>
        function viewInquiry(inquiryId) {
            // Find the inquiry data
            const inquiries = <?php 
                $inquiries_data = [];
                mysqli_data_seek($inquiries_result, 0);
                while($row = mysqli_fetch_assoc($inquiries_result)) {
                    $inquiries_data[] = $row;
                }
                echo json_encode($inquiries_data);
            ?>;
            
            const inquiry = inquiries.find(i => i.iid == inquiryId);
            if (inquiry) {
                // Create modal
                const modal = document.createElement('div');
                modal.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 1000;
                `;
                
                modal.innerHTML = `
                    <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 600px; max-height: 80vh; overflow-y: auto; margin: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h3 style="color: var(--primary-color); margin: 0;">Inquiry Details</h3>
                            <button onclick="this.closest('.modal').remove()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <strong>Inquiry ID:</strong> ${inquiry.iid}
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <strong>Subject:</strong> ${inquiry.subject}
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <strong>Status:</strong> 
                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; ${
                                inquiry.status === 'Pending' ? 'background: #fff3cd; color: #856404;' :
                                inquiry.status === 'Completed' ? 'background: #d4edda; color: #155724;' :
                                'background: #f8d7da; color: #721c24;'
                            }">${inquiry.status.toUpperCase()}</span>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <strong>Listing Details:</strong><br>
                            <strong>Title:</strong> ${inquiry.title}<br>
                            <strong>Price:</strong> ₹${inquiry.price}<br>
                            <strong>Address:</strong> ${inquiry.ladd}
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <strong>Submitted on:</strong> ${new Date(inquiry.created).toLocaleString()}
                        </div>

                         <div style="margin-bottom: 1rem;">
                            <strong>Responsed on:</strong> ${new Date(inquiry.resolved_at).toLocaleString()}
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <strong>Message:</strong>
                            <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; margin-top: 0.5rem; ">
                                ${inquiry.message}
                            </div>
                        </div>

                         <div style="margin-bottom: 1rem;">
                            <strong>Admin Response:</strong>
                            <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px; margin-top: 0.5rem;">
                                ${inquiry.a_respone}
                            </div>
                        </div>
                        
                        <div style="text-align: right;">
                            <button onclick="this.closest('.modal').remove()" 
                                    style="background: var(--primary-color); color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">
                                Close
                            </button>
                        </div>
                    </div>
                `;
                
                modal.className = 'modal';
                document.body.appendChild(modal);
                
                // Close on outside click
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) modal.remove();
                });
            }
        }
    </script>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h5>PG-Rooms</h5>
                    <p>Your trusted platform for finding the perfect paying guest accommodations.</p>
                </div>
                <div class="footer-section">
                    <h5>Contact</h5>
                    <p><i class="fas fa-envelope"></i> info@pg-rooms.com</p>
                    <p><i class="fas fa-phone"></i> +91 98765 43210</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 PG-Rooms. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
