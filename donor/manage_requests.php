<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'donor') {
    header("Location: ../auth/login.html");
    exit();
}

$donor_id = $_SESSION['user_id'];

// Approve request
if (isset($_GET['approve'])) {
    $request_id = $_GET['approve'];
    mysqli_query($conn, "UPDATE requests SET status='approved' WHERE id=$request_id");
    mysqli_query($conn, "UPDATE food_listings SET status='approved' WHERE id=(SELECT listing_id FROM requests WHERE id=$request_id)");
    echo "<script>alert('Request approved!'); window.location.href='manage_requests.php';</script>";
    exit();
}

// Reject request
if (isset($_GET['reject'])) {
    $request_id = $_GET['reject'];
    mysqli_query($conn, "UPDATE requests SET status='rejected' WHERE id=$request_id");
    mysqli_query($conn, "UPDATE food_listings SET status='available' WHERE id=(SELECT listing_id FROM requests WHERE id=$request_id)");
    echo "<script>alert('Request rejected.'); window.location.href='manage_requests.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Requests - Share Your Leftovers</title>
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
        <h2>Manage Requests</h2>
        <p>Review and approve requests from receivers.</p>

        <table>
            <thead>
                <tr>
                    <th>Receiver Name</th>
                    <th>Food Item</th>
                    <th>Quantity</th>
                    <th>Location</th>
                    <th>Requested On</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT r.*, u.name as receiver_name, f.food_name, f.quantity, f.location 
                      FROM requests r 
                      JOIN users u ON r.receiver_id = u.id 
                      JOIN food_listings f ON r.listing_id = f.id 
                      WHERE f.donor_id = $donor_id 
                      ORDER BY r.created_at DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['receiver_name']}</td>
                        <td>{$row['food_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['created_at']}</td>
                        <td>{$row['status']}</td>
                        <td>";
                    if ($row['status'] == 'requested') {
                        echo "<a href='manage_requests.php?approve={$row['id']}'>Approve</a> | 
                              <a href='manage_requests.php?reject={$row['id']}'>Reject</a>";
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