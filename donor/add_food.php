<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'donor') {
    header("Location: ../auth/login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $donor_id = $_SESSION['user_id'];
    $food_name = mysqli_real_escape_string($conn, $_POST['food_name']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $pickup_time = mysqli_real_escape_string($conn, $_POST['pickup_time']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "INSERT INTO food_listings (donor_id, food_name, quantity, location, pickup_time, description) 
              VALUES ('$donor_id', '$food_name', '$quantity', '$location', '$pickup_time', '$description')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Food listing added successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Try again.'); window.location.href='add_food.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food - Share Your Leftovers</title>
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
        <h2>Add New Food Listing</h2>
        <p>Fill in the details of the food you want to share.</p>

        <form id="addFoodForm" action="add_food.php" method="POST">

            <label for="food_name">Food Name</label>
            <input type="text" id="food_name" name="food_name" placeholder="e.g. Rice, Bread, Curry">

            <label for="quantity">Quantity</label>
            <input type="text" id="quantity" name="quantity" placeholder="e.g. 2 kg, 5 plates, 1 pot">

            <label for="location">Pickup Location</label>
            <input type="text" id="location" name="location" placeholder="Enter your address">

            <label for="pickup_time">Pickup Time</label>
            <input type="datetime-local" id="pickup_time" name="pickup_time">

            <label for="description">Additional Description</label>
            <textarea id="description" name="description" placeholder="Any extra details about the food..." rows="4"></textarea>

            <button type="submit">Post Food Listing</button>
            <a href="dashboard.php">Cancel</a>

        </form>
    </main>

    <footer>
        <p>Share Your Leftovers &copy; 2026 | Reducing food waste together</p>
    </footer>

    <script src="../assets/validation.js"></script>
</body>
</html>