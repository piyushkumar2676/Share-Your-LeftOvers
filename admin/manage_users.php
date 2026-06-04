<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.html");
    exit();
}

// User delete karna
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$user_id");
    echo "<script>alert('User deleted.'); window.location.href='manage_users.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Share Your Leftovers</title>
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
        <h2>Manage Users</h2>
        <p>View and manage all registered users.</p>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['role']}</td>
                        <td>{$row['created_at']}</td>
                        <td><a href='manage_users.php?delete={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found.</td></tr>";
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