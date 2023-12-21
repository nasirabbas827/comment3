<?php
include('config.php');

// Process the admin login form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_username = $_POST["admin_username"];
    $admin_password = $_POST["admin_password"];

    // Validate admin login credentials (you should use secure authentication mechanisms)
    $query = "SELECT * FROM admin WHERE username='$admin_username' AND password='$admin_password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // Admin login successful, set session and redirect to admin dashboard
        session_start();
        $_SESSION["admin_username"] = $admin_username;
        header("Location: ./admin/admin_dashboard.php");
        exit();
    } else {
        echo "Invalid admin login credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>


    <h2>Admin Login</h2>

    <form action="" method="post">
        <label for="admin_username">Admin Username:</label>
        <input type="text" name="admin_username" required><br>

        <label for="admin_password">Admin Password:</label>
        <input type="password" name="admin_password" required><br>

        <input type="submit" value="Login">
    </form>

</body>
</html>
