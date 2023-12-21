<?php
include('config.php');

// Check if the admin is logged in
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: admin_login.php");
    exit();
}

// Check if moderator ID is provided in the URL
if (!isset($_GET["id"])) {
    echo "Moderator ID not provided.";
    exit();
}

$moderator_id = $_GET["id"];

// Retrieve moderator information for pre-filling the form
$query = "SELECT * FROM moderators WHERE id='$moderator_id'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    $moderator = $result->fetch_assoc();
} else {
    echo "Moderator not found.";
    exit();
}

// Process the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST["new_name"];
    $new_username = $_POST["new_username"];
    $new_email = $_POST["new_email"];
    $new_password = $_POST["new_password"];

    // Update moderator information in the database
    $update_query = "UPDATE moderators SET name='$new_name', username='$new_username', email='$new_email', password='$new_password' WHERE id='$moderator_id'";

    if ($conn->query($update_query) === TRUE) {
        echo "Moderator information updated successfully!";
        // Refresh moderator information after update
        $moderator['name'] = $new_name;
        $moderator['username'] = $new_username;
        $moderator['email'] = $new_email;
    } else {
        echo "Error updating moderator information: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Moderator</title>
    <link rel="stylesheet" href="./css/style.css">

</head>
<body>
<?php include('navbar.php'); ?>

    <h2>Edit Moderator</h2>

    <form action="" method="post">
        <label for="new_name">New Moderator Name:</label>
        <input type="text" name="new_name" value="<?php echo $moderator['name']; ?>" required><br>

        <label for="new_username">New Moderator Username:</label>
        <input type="text" name="new_username" value="<?php echo $moderator['username']; ?>" required><br>

        <label for="new_email">New Moderator Email:</label>
        <input type="email" name="new_email" value="<?php echo $moderator['email']; ?>" required><br>

        <label for="new_password">New Moderator Password:</label>
        <input type="password" name="new_password" value="<?php echo $moderator['password']; ?>" required><br>

        <input type="submit" value="Update Moderator">
    </form>

    <p><a href="admin_view_moderators.php">Back to View Moderators</a></p>

</body>
</html>
