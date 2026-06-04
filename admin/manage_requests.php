<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.html");
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_donations.php">Donations</a></li>
            <li><a href="manage_requests.php">Requests</a></li>
            <li><a href="reports.php">Reports</a></li>
            <li><a href="../auth/logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <h2>Monitor Requests</h2>
        <p>View all food requests made by receivers.</p>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Food Name</th>
                    <th>Donor</th>
                    <th>Receiver</th>
                    <th>Requested On</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT r.*, f.food_name, u1.name as donor_name, u2.name as receiver_name
                      FROM requests r
                      JOIN food_listings f ON r.listing_id = f.id
                      JOIN users u1 ON f.donor_id = u1.id
                      JOIN users u2 ON r.receiver_id = u2.id
                      ORDER BY r.created_at DESC";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['food_name']}</td>
                        <td>{$row['donor_name']}</td>
                        <td>{$row['receiver_name']}</td>
                        <td>{$row['created_at']}</td>
                        <td>{$row['status']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No requests found.</td></tr>";
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