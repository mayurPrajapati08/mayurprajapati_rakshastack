<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: alogin.php');
    exit();
}

// Check if listing ID is provided
if (!isset($_GET['id'])) {
    header('Location: addata.php');
    exit();
}

$listing_id = $_GET['id'];
require_once '../database/dbcon.php';

// First, fetch the listing details to verify ownership and get image path
$sql = "SELECT * FROM listing WHERE lid = $listing_id AND aid = '$_SESSION[admin_id]'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    // Listing not found or doesn't belong to this admin
    $_SESSION['error'] = "Listing not found or you don't have permission to delete it.";
    header('Location: addata.php');
    exit();
}

$listing = mysqli_fetch_assoc($result);

// Delete the listing image if it exists
if (!empty($listing['limage']) && file_exists($listing['limage'])) {
    unlink($listing['limage']);
}

// Delete the listing from database
$delete_sql = "DELETE FROM listing WHERE lid = $listing_id AND aid = '$_SESSION[admin_id]'";

if (mysqli_query($conn, $delete_sql)) {
    // Check if the listing was actually deleted
    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['success'] = "Listing deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete listing. Please try again.";
    }
} else {
    $_SESSION['error'] = "Error deleting listing: " . mysqli_error($conn);
}

// Redirect back to the listings page
header('Location: addata.php');
exit();
?>
