<?php
include('config.php');

// Process the login form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate login credentials (you should use secure authentication mechanisms)
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' AND status='approved'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // Login successful, set session and redirect to dashboard
        session_start();
        $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid login credentials or account not approved.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

    <h2>Login</h2>

    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>

</body>
</html>
