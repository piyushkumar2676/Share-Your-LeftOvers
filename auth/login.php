<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] == 'donor') {
                header("Location: ../donor/dashboard.php");
            } elseif ($user['role'] == 'receiver') {
                header("Location: ../receiver/dashboard.php");
            } elseif ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Wrong password. Try again.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please register first.'); window.location.href='login.php';</script>";
    }
}
?>