<?php
session_start();
require_once '../database/dbcon.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: alogin.php");
    exit();
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_SESSION['admin_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords do not match!";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        // Verify current password
        $sql = "SELECT apass FROM admin WHERE aid = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $admin_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($current_password, $row['apass'])) {
                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE admin SET apass = ? WHERE aid = ?";
                $update_stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($update_stmt, "si", $hashed_password, $admin_id);
                
                if (mysqli_stmt_execute($update_stmt)) {
                    $message = "Password changed successfully!";
                    // Update session password
                    $_SESSION['admin_pass'] = $new_password;
                } else {
                    $error = "Error updating password. Please try again.";
                }
            } else {
                $error = "Current password is incorrect!";
            }
        } else {
            $error = "Admin not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - PG Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .password-form {
            max-width: 500px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-color);
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            font-size: 1rem;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }
        .alert-success {
            background-color: #d1edff;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        input[type = 'password']{
            border: silver solid 1px;
        }
    </style>
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
                    <?php if ($_SESSION['admin_name'] == 'mayur'): ?>
                        <li><a href="admaster.php" class="nav-link">Dashboard</a></li>
                        <li><a href="adsignup.php" class="nav-link"> Add Admin</a></li>
                    <?php else: ?>
                        <li><a href="admin.php" class="nav-link"> Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="adlisting.php" class="nav-link"> Add Listing</a></li>
                    <li><a href="inquiry.php" class="nav-link"> Inquiries</a></li>
                    <li><a href="addata.php" class="nav-link"> Listings</a></li>
                    <li><a href="change_password.php" class="nav-link">Change Password</a></li>
                    <li><a href="alogout.php" class="nav-link"> LogOut</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <h1 class="page-title"><i class="fas fa-key"></i> Change Password</h1>
            
            <div class="password-form">
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form action="change_password.php" method="post">
                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required minlength="6">
                        <small style="color: #666;">Password must be at least 6 characters long</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Change Password
                        </button>
                        <a href="<?php echo $_SESSION['admin_name'] == 'mayur' ? 'admaster.php' : 'admin.php'; ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
