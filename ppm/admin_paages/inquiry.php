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
        <h1>Inquires page</h1>
        
        <div class="inquiry-list">
            <?php
            require_once '../database/dbcon.php';
            
            // Get admin ID from session
            $admin_id = $_SESSION['admin_id'];
            
            // Fetch inquiries for the logged-in admin
            $sql = "SELECT i.*, u.uname, u.uemail, l.title, l.price 
                    FROM inquire i 
                    JOIN users u ON i.user_id = u.sno 
                    JOIN listing l ON i.listing_id = l.lid 
                    WHERE i.admin_id = $admin_id
                    ORDER BY i.created DESC";
            
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                echo '<div class="inquiry-table">';
                echo '<table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Listing</th>
                                <th>Price</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                            <td>' . $row['iid'] . '</td>
                            <td>' . htmlspecialchars($row['uname']) . '</td>
                            <td>' . htmlspecialchars($row['uemail']) . '</td>
                            <td>' . htmlspecialchars($row['title']) . '</td>
                            <td>₹' . $row['price'] . '</td>
                            <td>' . htmlspecialchars($row['subject']) . '</td>
                            <td>' . htmlspecialchars(substr($row['message'], 0, 50)) . '...</td>
                            <td>' . date('M j, Y H:i', strtotime($row['created'])) . '</td>
                            <td><span class="status status-' . strtolower($row['status']) . '">' . $row['status'] . '</span></td>
                            <td>
                                <a href="view_inquiry.php?id=' . $row['iid'] . '" class="btn-view">View</a>
                            </td>
                        </tr>';
                }
                
                echo '</tbody></table></div>';
            } else {
                echo '<div class="no-inquiries">
                        <i class="fas fa-inbox"></i>
                        <h3>No inquiries found</h3>
                        <p>There are no inquiries at the moment.</p>
                    </div>';
            }
            
            mysqli_close($conn);
            ?>
        </div>
    </div>

    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .inquiry-list {
            margin-top: 30px;
        }

        .inquiry-table {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-resolved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn-view {
            background-color: #007bff;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
        }

        .btn-view:hover {
            background-color: #0056b3;
        }

        .no-inquiries {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-inquiries i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ddd;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }
    </style>
</body>
</html>
