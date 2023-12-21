<?php
// Include the configuration file and establish a database connection
include('config.php');

// Check if the moderator is already logged in
session_start();
if (isset($_SESSION["moderator_username"])) {
    header("Location: moderator_dashboard.php");
    exit();
}

// Process the form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $moderator_username = $_POST["moderator_username"];
    $moderator_password = $_POST["moderator_password"];

    // Check the username and password against the database
    $query = "SELECT * FROM moderators WHERE username = '$moderator_username' AND password = '$moderator_password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // Moderator credentials are valid, set the session variable
        $_SESSION["moderator_username"] = $moderator_username;

        // Redirect to the moderator dashboard
        header("Location: ./moderator/moderator_dashboard.php");
        exit();
    } else {
        $login_error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Login</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <h2>Moderator Login</h2>

    <?php
    // Display login error if any
    if (isset($login_error)) {
        echo "<p style='color: red;'>$login_error</p>";
    }
    ?>

    <form action="" method="post">
        <label for="moderator_username">Moderator Username:</label>
        <input type="text" name="moderator_username" required><br>

        <label for="moderator_password">Moderator Password:</label>
        <input type="password" name="moderator_password" required><br>

        <input type="submit" value="Login">
    </form>

    <p><a href="admin_login.php">Back to Admin Login</a></p>

</body>
</html>
