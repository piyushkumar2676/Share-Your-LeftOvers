<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.html");
    exit();
}

// Listing delete karna
if (isset($_GET['delete'])) {
    $listing_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM requests WHERE listing_id=$listing_id");
    mysqli_query($conn, "DELETE FROM food_listings WHERE id=$listing_id");
    echo "<script>alert('Listing deleted.'); window.location.href='manage_donations.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Donations - Share Your Leftovers</title>
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
        <h2>Monitor Donations</h2>
        <p>View all food listings posted by donors.</p>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
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
            $query = "SELECT f.*, u.name as donor_name 
                      FROM food_listings f 
                      JOIN users u ON f.donor_id = u.id 
                      ORDER BY f.created_at DESC";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['food_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['donor_name']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['pickup_time']}</td>
                        <td>{$row['status']}</td>
                        <td><a href='manage_donations.php?delete={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No donations found.</td></tr>";
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