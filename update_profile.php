<?php
include('config.php');

// Check if the user is logged in, if not, redirect to login page
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information for the form
$username = $_SESSION["username"];
$query = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    echo "Error retrieving user information.";
    exit();
}

// Process the form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated form data
    $new_first_name = $_POST["new_first_name"];
    $new_last_name = $_POST["new_last_name"];
    $new_email = $_POST["new_email"];

    // Update user information in the database
    $update_query = "UPDATE users SET first_name='$new_first_name', last_name='$new_last_name', email='$new_email' WHERE username='$username'";

    if ($conn->query($update_query) === TRUE) {
        echo "Profile updated successfully!";
        // Refresh user information after update
        $user['first_name'] = $new_first_name;
        $user['last_name'] = $new_last_name;
        $user['email'] = $new_email;
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
    <title>Update Profile</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

    <h2>Update Your Profile</h2>

    <form action="" method="post">
        <label for="new_first_name">New First Name:</label>
        <input type="text" name="new_first_name" value="<?php echo $user['first_name']; ?>" required><br>

        <label for="new_last_name">New Last Name:</label>
        <input type="text" name="new_last_name" value="<?php echo $user['last_name']; ?>" required><br>

        <label for="new_email">New Email:</label>
        <input type="email" name="new_email" value="<?php echo $user['email']; ?>" required><br>

        <input type="submit" value="Update Profile">
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>

</body>
</html>
