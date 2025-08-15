<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require_once '../database/dbcon.php';

// Get listing ID from URL parameter
$listing_id = isset($_GET['lid']) ? intval($_GET['lid']) : 0;

// Get user information
$username = $_SESSION['username'];
$user_sql = "SELECT sno, uname, uemail FROM users WHERE uname = '$username'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// Get specific listing details
$listing_sql = "SELECT lid, title, price, ladd, aid FROM listing WHERE lid = $listing_id";
$listing_result = mysqli_query($conn, $listing_sql);
$listing = mysqli_fetch_assoc($listing_result);
$admin_id = $listing['aid'];

// Handle case when listing doesn't exist
// if(!$listing) {
//     header("Location: Listing.php");
//     exit();
// }

// Get admin ID (automatically assign first admin)
// $admin_sql = "SELECT aid FROM admin LIMIT 1";
// $admin_result = mysqli_query($conn, $admin_sql);
// $admin = mysqli_fetch_assoc($admin_result);
// $admin_id = $admin['aid'];

// Handle form submission
$success_message = '';
$error_message = '';

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_inquiry'])) {
    $user_id = $user['sno'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Validate inputs
    if(empty($subject) || empty($message)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Insert inquiry
        $insert_sql = "INSERT INTO inquire (user_id, listing_id, admin_id, subject, message) 
                       VALUES ('$user_id', '$listing_id', '$admin_id', '$subject', '$message')";
        
        if(mysqli_query($conn, $insert_sql)) {
            $success_message = "Your inquiry has been submitted successfully! We will get back to you soon.";
            // Reset form fields
            $_POST = array();
        } else {
            $error_message = "Error submitting inquiry. Please try again.";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Inquiry - PG Management System</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        .inquiry-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .inquiry-form {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            padding: 2.5rem;
            margin: 2rem 0;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .form-header h2 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .form-header p {
            color: var(--text-secondary);
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
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .btn-submit {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
            transition: background 0.3s;
        }
        
        .btn-submit:hover {
            background: var(--primary-hover);
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
        
        .user-info-display {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
        }
        
        .user-info-display p {
            margin: 0.25rem 0;
            font-size: 0.9rem;
        }
        
        .required {
            color: #e74c3c;
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

    <!-- Inquiry Form -->
    <div class="inquiry-container">
        <div class="inquiry-form">
            <div class="form-header">
                <h2><i class="fas fa-envelope"></i> Submit Inquiry</h2>
                <p>Send us your questions about this listing</p>
            </div>

            <?php if($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="user-info-display">
                <p><strong>Listing:</strong> <?php echo htmlspecialchars($listing['title']); ?></p>
                <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($listing['price']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($listing['ladd']); ?></p>
            </div>

            <form method="POST" action="inquire.php?lid=<?php echo $listing_id; ?>">
                <div class="form-group">
                    <label for="subject">Subject <span class="required">*</span></label>
                    <input type="text" id="subject" name="subject" required 
                           placeholder="Enter your inquiry subject"
                           value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="message">Message <span class="required">*</span></label>
                    <textarea id="message" name="message" required 
                              placeholder="Please describe your inquiry in detail..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                </div>

                <button type="submit" name="submit_inquiry" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Inquiry
                </button>
            </form>
        </div>
    </div>

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
