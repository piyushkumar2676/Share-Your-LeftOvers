<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.html");
    exit();
}

$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$total_donors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='donor'"))['total'];
$total_receivers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='receiver'"))['total'];
$total_donations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM food_listings"))['total'];
$total_requests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests"))['total'];
$total_completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE status='completed'"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Share Your Leftovers</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <nav>
        <h1>Share Your Leftovers</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_donations.php">Donations</a></li>
            <li><a href="manage_requests.php">Requests</a></li>
            <li><a href="reports.php">Reports</a></li>
            <li><a href="../auth/logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <h2>Admin Dashboard</h2>
        <p>Monitor and manage all activity on the platform.</p>

        <div class="summary-boxes">
            <div><h3>Total Users</h3><p><?php echo $total_users; ?></p></div>
            <div><h3>Total Donors</h3><p><?php echo $total_donors; ?></p></div>
            <div><h3>Total Receivers</h3><p><?php echo $total_receivers; ?></p></div>
            <div><h3>Total Donations</h3><p><?php echo $total_donations; ?></p></div>
            <div><h3>Total Requests</h3><p><?php echo $total_requests; ?></p></div>
            <div><h3>Completed Pickups</h3><p><?php echo $total_completed; ?></p></div>
        </div>

        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <a href="manage_users.php">Manage Users</a>
            <a href="manage_donations.php">View Donations</a>
            <a href="manage_requests.php">View Requests</a>
            <a href="reports.php">View Reports</a>
        </div>
    </main>

    <footer>
        <p>Share Your Leftovers &copy; 2026 | Reducing food waste together</p>
    </footer>

</body>
</html>