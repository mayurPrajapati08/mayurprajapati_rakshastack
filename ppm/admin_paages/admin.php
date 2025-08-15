<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PG - Admin Page</title>
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
                    <li><a href="adlisting.php" class="nav-link"> Add Listing</a></li>
                    <li><a href="inquiry.php" class="nav-link"> Inquiries</a></li>
                    <li><a href="addata.php" class="nav-link">Your Listings</a></li>
                    <li><a href="change_password.php" class="nav-link"> Change Password</a></li>
                    <li><a href="alogout.php" class="nav-link"> LogOut</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <h1 class="page-title">Admin Dashboard</h1>
            
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fas fa-tachometer-alt"></i> Quick Actions</h2>
                </div>
                <div class="card-body">
                    <p>Welcome to your admin panel. Use the navigation above to manage your PG listings and view inquiries.</p>
                </div>
            </div>

            <div class="d-flex justify-content-between" style="gap: 2rem; flex-wrap: wrap;">
                <div class="card" style="flex: 1; min-width: 300px;">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-home"></i> Manage Listings</h3>
                    </div>
                    <div class="card-body">
                        <p>Add new PG listings or manage existing ones.</p>
                        <a href="../admin_paages/adlisting.php" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> Add New Listing
                        </a>
                    </div>
                </div>

                <div class="card" style="flex: 1; min-width: 300px;">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-envelope"></i> View Inquiries</h3>
                    </div>
                    <div class="card-body">
                        <p>Check and respond to customer inquiries.</p>
                        <a href="../admin_paages/inquiry.php" class="btn btn-success mt-3">
                            <i class="fas fa-eye"></i> View Inquiries
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
