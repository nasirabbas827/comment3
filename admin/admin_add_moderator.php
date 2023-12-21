<?php
include('config.php');

// Check if the admin is logged in
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: admin_login.php");
    exit();
}

// Process the form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $moderator_name = $_POST["moderator_name"];
    $moderator_username = $_POST["moderator_username"];
    $moderator_email = $_POST["moderator_email"];
    $moderator_password = $_POST["moderator_password"];

    // Insert the moderator into the database
    $insert_query = "INSERT INTO moderators (name, username, email, password) VALUES ('$moderator_name', '$moderator_username', '$moderator_email', '$moderator_password')";

    if ($conn->query($insert_query) === TRUE) {
        echo "Moderator added successfully!";
    } else {
        echo "Error adding moderator: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Moderator</title>
    <link rel="stylesheet" href="./css/style.css">

</head>
<body>
<?php include('navbar.php'); ?>

    <h2>Add Moderator</h2>

    <form action="" method="post">
        <label for="moderator_name">Moderator Name:</label>
        <input type="text" name="moderator_name" required><br>

        <label for="moderator_username">Moderator Username:</label>
        <input type="text" name="moderator_username" required><br>

        <label for="moderator_email">Moderator Email:</label>
        <input type="email" name="moderator_email" required><br>

        <label for="moderator_password">Moderator Password:</label>
        <input type="password" name="moderator_password" required><br>

        <input type="submit" value="Add Moderator">
    <p><a href="admin_view_moderators.php">View Moderators</a></p>

    </form>


</body>
</html>
