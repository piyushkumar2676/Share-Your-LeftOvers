<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'donor') {
    header("Location: ../auth/login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - Share Your Leftovers</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <nav>
        <h1>Share Your Leftovers</h1>
        <ul>
            <li><a href="../index.html">Home</a></li>
            <li><a href="dashboard.php">My Dashboard</a></li>
            <li><a href="add_food.php">Add Food</a></li>
            <li><a href="manage_requests.php">Requests</a></li>
            <li><a href="../auth/logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
        <p>Manage your food donations from here.</p>

        <?php
        $donor_id = $_SESSION['user_id'];
        $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM food_listings WHERE donor_id=$donor_id"))['total'];
        $pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests r JOIN food_listings f ON r.listing_id=f.id WHERE f.donor_id=$donor_id AND r.status='requested'"))['total'];
        $completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests r JOIN food_listings f ON r.listing_id=f.id WHERE f.donor_id=$donor_id AND r.status='completed'"))['total'];
        ?>

        <div class="summary-boxes">
            <div>
                <h3>Total Donations</h3>
                <p><?php echo $total; ?></p>
            </div>
            <div>
                <h3>Pending Requests</h3>
                <p><?php echo $pending; ?></p>
            </div>
            <div>
                <h3>Completed</h3>
                <p><?php echo $completed; ?></p>
            </div>
        </div>

        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <a href="add_food.php">Add New Food Listing</a>
            <a href="manage_requests.php">View Requests</a>
        </div>

        <div>
            <h3>Your Recent Food Listings</h3>
            <table>
                <thead>
                    <tr>
                        <th>Food Name</th>
                        <th>Quantity</th>
                        <th>Location</th>
                        <th>Pickup Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $listings = mysqli_query($conn, "SELECT * FROM food_listings WHERE donor_id=$donor_id ORDER BY created_at DESC");
                if (mysqli_num_rows($listings) > 0) {
                    while ($row = mysqli_fetch_assoc($listings)) {
                        echo "<tr>
                            <td>{$row['food_name']}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['pickup_time']}</td>
                            <td>{$row['status']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No food listings yet. Add your first listing!</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>Share Your Leftovers &copy; 2026 | Reducing food waste together</p>
    </footer>

</body>
</html>