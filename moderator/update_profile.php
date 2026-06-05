<?php
// Include the configuration file and establish a database connection
include('config.php');

// Check if the moderator is logged in
session_start();
if (!isset($_SESSION["moderator_username"])) {
    header("Location: moderator_login.php");
    exit();
}

// Get moderator information
$moderator_username = $_SESSION["moderator_username"];
$query = "SELECT * FROM moderators WHERE username = '$moderator_username'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    $moderator = $result->fetch_assoc();
} else {
    echo "Error retrieving moderator information.";
    exit();
}

// Process the form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST["new_name"];
    $new_email = $_POST["new_email"];
    $new_password = $_POST["new_password"];

    // Update the moderator's profile in the database
    $update_query = "UPDATE moderators SET name = '$new_name', email = '$new_email', password = "YOUR_OWN_API_KEY" WHERE username = '$moderator_username'";

    if ($conn->query($update_query) === TRUE) {
        echo "Profile updated successfully!";
        // Refresh the page to reflect the changes
        header("Refresh:0");
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Profile Update</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <h2>Moderator Profile Update</h2>

    <form action="" method="post">
        <label for="new_name">New Name:</label>
        <input type="text" name="new_name" value="<?php echo $moderator['name']; ?>" required><br>

        <label for="new_email">New Email:</label>
        <input type="email" name="new_email" value="<?php echo $moderator['email']; ?>" required><br>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required><br>

        <input type="submit" value="Update Profile">
    </form>

    <p><a href="moderator_dashboard.php">Back to Moderator Dashboard</a></p>

</body>
</html>
