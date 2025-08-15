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

// Get inquiry ID from URL
$inquiry_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

require_once '../database/dbcon.php';

// Handle form submission for response
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_response'])) {
    $response = mysqli_real_escape_string($conn, $_POST['response']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    if (empty($response)) {
        $error_message = "Please provide a response.";
    } else {
        $update_sql = "UPDATE inquire SET a_respone = '$response', status = '$status', resolved_at = NOW() WHERE iid = $inquiry_id";
        
        if (mysqli_query($conn, $update_sql)) {
            $success_message = "Response submitted successfully!";
        } else {
            $error_message = "Error submitting response. Please try again.";
        }
    }
}

// Get admin ID from session
$admin_id = $_SESSION['admin_id'];

// Fetch inquiry details
$sql = "SELECT i.*, u.uname, u.uemail, l.title, l.price, l.ladd, a.aid 
        FROM inquire i 
        JOIN users u ON i.user_id = u.sno 
        JOIN listing l ON i.listing_id = l.lid
        JOIN admin a ON i.admin_id = a.aid 
        WHERE i.iid = $inquiry_id AND i.admin_id = $admin_id";

$result = mysqli_query($conn, $sql);
$inquiry = mysqli_fetch_assoc($result);

if (!$inquiry) {
    header('Location: inquiry.php');
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inquiry - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .inquiry-details {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 20px 0;
        }

        .inquiry-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .inquiry-header h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .inquiry-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .meta-item {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .meta-item strong {
            display: block;
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .inquiry-content {
            margin-bottom: 30px;
        }

        .inquiry-content h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .message-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
        }

        .response-form {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 100px;
        }

        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-submit {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }

        .btn-back:hover {
            background-color: #545b62;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .existing-response {
            margin-top: 20px;
            padding: 15px;
            background: #e7f3ff;
            border-radius: 4px;
            border-left: 4px solid #007bff;
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

    <div class="container">
        <div class="inquiry-details">
            <div class="inquiry-header">
                <h2>Inquiry Details</h2>
                <a href="inquiry.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Inquiries</a>
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

            <div class="inquiry-meta">
                <div class="meta-item">
                    <strong>Inquiry ID</strong>
                    <span>#<?php echo $inquiry['iid']; ?></span>
                </div>
                <div class="meta-item">
                    <strong>User</strong>
                    <span><?php echo htmlspecialchars($inquiry['uname']); ?></span>
                </div>
                <div class="meta-item">
                    <strong>Email</strong>
                    <span><?php echo htmlspecialchars($inquiry['uemail']); ?></span>
                </div>
                <div class="meta-item">
                    <strong>Listing</strong>
                    <span><?php echo htmlspecialchars($inquiry['title']); ?></span>
                </div>
                <div class="meta-item">
                    <strong>Price</strong>
                    <span>₹<?php echo $inquiry['price']; ?></span>
                </div>
                <div class="meta-item">
                    <strong>Address</strong>
                    <span><?php echo htmlspecialchars($inquiry['ladd']); ?></span>
                </div>
                <div class="meta-item">
                    <strong>Date</strong>
                    <span><?php echo date('M j, Y H:i', strtotime($inquiry['created'])); ?></span>
                </div>
                <div class="meta-item">
                    <strong>Status</strong>
                    <span><?php echo $inquiry['status']; ?></span>
                </div>
            </div>

            <div class="inquiry-content">
                <h3>Subject</h3>
                <div class="message-box">
                    <?php echo htmlspecialchars($inquiry['subject']); ?>
                </div>
            </div>

            <div class="inquiry-content">
                <h3>Message</h3>
                <div class="message-box">
                    <?php echo nl2br(htmlspecialchars($inquiry['message'])); ?>
                </div>
            </div>

            <?php if (!empty($inquiry['a_respone'])): ?>
                <div class="existing-response">
                    <h3>Admin Response</h3>
                    <p><?php echo nl2br(htmlspecialchars($inquiry['a_respone'])); ?></p>
                    <?php if ($inquiry['resolved_at']): ?>
                        <small><strong>Resolved on:</strong> <?php echo date('M j, Y H:i', strtotime($inquiry['resolved_at'])); ?></small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="response-form">
                <h3>Respond to Inquiry</h3>
                
                <div class="form-group">
                    <label for="response">Response Message</label>
                    <textarea id="response" name="response" required 
                              placeholder="Enter your response to the user..."><?php echo isset($_POST['response']) ? htmlspecialchars($_POST['response']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="Pending" <?php echo $inquiry['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Cancelled" <?php echo $inquiry['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        <option value="Completed" <?php echo $inquiry['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>

                <button type="submit" name="submit_response" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Response
                </button>
            </form>
        </div>
    </div>
</body>
</html>
