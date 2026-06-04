<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'receiver') {
    header("Location: ../auth/login.html");
    exit();
}

$receiver_id = $_SESSION['user_id'];

// Request send karna
if (isset($_GET['request'])) {
    $listing_id = $_GET['request'];

    // Check already requested
    $check = mysqli_query($conn, "SELECT id FROM requests WHERE listing_id=$listing_id AND receiver_id=$receiver_id");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('You have already requested this food.'); window.location.href='browse_food.php';</script>";
        exit();
    }

    mysqli_query($conn, "INSERT INTO requests (listing_id, receiver_id) VALUES ($listing_id, $receiver_id)");
    mysqli_query($conn, "UPDATE food_listings SET status='requested' WHERE id=$listing_id");
    echo "<script>alert('Request sent successfully!'); window.location.href='browse_food.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Food - Share Your Leftovers</title>
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
        <h2>Available Food Listings</h2>
        <p>Browse and request food available near you.</p>

        <table>
            <thead>
                <tr>
                    <th>Food Name</th>
                    <th>Quantity</th>
                    <th>Location</th>
                    <th>Pickup Time</th>
                    <th>Donor</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead> 
            <tbody>
            <?php
            $query = "SELECT f.*, u.name as donor_name 
                      FROM food_listings f 
                      JOIN users u ON f.donor_id = u.id 
                      WHERE f.status = 'available' 
                      ORDER BY f.created_at DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['food_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['pickup_time']}</td>
                        <td>{$row['donor_name']}</td>
                        <td>{$row['status']}</td>
                        <td><a href='browse_food.php?request={$row['id']}'>Request</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No food available at the moment.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>Share Your Leftovers &copy; 2026 | Reducing food waste together</p>
    </footer>

</body>
</html>