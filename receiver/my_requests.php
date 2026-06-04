<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'receiver') {
    header("Location: ../auth/login.html");
    exit();
}

$receiver_id = $_SESSION['user_id'];

// Pickup confirm karna
if (isset($_GET['complete'])) {
    $request_id = $_GET['complete'];
    mysqli_query($conn, "UPDATE requests SET status='completed' WHERE id=$request_id");
    mysqli_query($conn, "UPDATE food_listings SET status='completed' WHERE id=(SELECT listing_id FROM requests WHERE id=$request_id)");
    echo "<script>alert('Pickup confirmed!'); window.location.href='my_requests.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - Share Your Leftovers</title>
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
        <h2>My Requests</h2>
        <p>Track the status of your food requests here.</p>

        <table>
            <thead>
                <tr>
                    <th>Food Name</th>
                    <th>Quantity</th>
                    <th>Donor</th>
                    <th>Location</th>
                    <th>Pickup Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT r.*, f.food_name, f.quantity, f.location, f.pickup_time, u.name as donor_name 
                      FROM requests r 
                      JOIN food_listings f ON r.listing_id = f.id 
                      JOIN users u ON f.donor_id = u.id 
                      WHERE r.receiver_id = $receiver_id 
                      ORDER BY r.created_at DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['food_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['donor_name']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['pickup_time']}</td>
                        <td>{$row['status']}</td>
                        <td>";
                    if ($row['status'] == 'approved') {
                        echo "<a href='my_requests.php?complete={$row['id']}'>Confirm Pickup</a>";
                    } else {
                        echo $row['status'];
                    }
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No requests yet.</td></tr>";
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