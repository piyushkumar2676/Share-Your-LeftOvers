<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'receiver') {
    header("Location: ../auth/login.html");
    exit();
}

$receiver_id = $_SESSION['user_id'];
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE receiver_id=$receiver_id"))['total'];
$approved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE receiver_id=$receiver_id AND status='approved'"))['total'];
$completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE receiver_id=$receiver_id AND status='completed'"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receiver Dashboard - Share Your Leftovers</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <nav>
        <h1>Share Your Leftovers</h1>
        <ul>
            <li><a href="../index.html">Home</a></li>
            <li><a href="dashboard.php">My Dashboard</a></li>
            <li><a href="browse_food.php">Browse Food</a></li>
            <li><a href="my_requests.php">My Requests</a></li>
            <li><a href="../auth/logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
        <p>Find available food listings near you.</p>

        <div class="summary-boxes">
            <div>
                <h3>My Requests</h3>
                <p><?php echo $total; ?></p>
            </div>
            <div>
                <h3>Approved</h3>
                <p><?php echo $approved; ?></p>
            </div>
            <div>
                <h3>Completed Pickups</h3>
                <p><?php echo $completed; ?></p>
            </div>
        </div>

        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <a href="browse_food.php">Browse Available Food</a>
            <a href="my_requests.php">View My Requests</a>
        </div>

        <div>
            <h3>My Recent Requests</h3>
            <table>
                <thead>
                    <tr>
                        <th>Food Name</th>
                        <th>Donor</th>
                        <th>Location</th>
                        <th>Pickup Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $query = "SELECT r.*, f.food_name, f.location, f.pickup_time, u.name as donor_name 
                          FROM requests r 
                          JOIN food_listings f ON r.listing_id = f.id 
                          JOIN users u ON f.donor_id = u.id 
                          WHERE r.receiver_id = $receiver_id 
                          ORDER BY r.created_at DESC LIMIT 5";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['food_name']}</td>
                            <td>{$row['donor_name']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['pickup_time']}</td>
                            <td>{$row['status']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No requests yet.</td></tr>";
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