<?php
include('config.php');

// Check if the admin is logged in
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: admin_login.php");
    exit();
}

// Retrieve all moderators for display
$query = "SELECT * FROM moderators";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $moderators = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "No moderators found.";
    exit();
}

// Process the form data when moderators are edited or deleted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($moderators as $moderator) {
        $moderator_id = $moderator['id'];

        if (isset($_POST["edit_$moderator_id"])) {
            // Edit moderator - redirect to edit page or handle as needed
            header("Location: admin_edit_moderator.php?id=$moderator_id");
            exit();
        } elseif (isset($_POST["delete_$moderator_id"])) {
            // Delete moderator
            $delete_query = "DELETE FROM moderators WHERE id='$moderator_id'";
            $conn->query($delete_query);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View Moderators</title>
    <link rel="stylesheet" href="./css/style.css">

</head>
<body>
<?php include('navbar.php'); ?>

    <h2>Admin View Moderators</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Moderator Name</th>
            <th>Moderator Username</th>
            <th>Moderator Email</th>
            <th>Action</th>
        </tr>
        <?php foreach ($moderators as $moderator): ?>
            <tr>
                <td><?php echo $moderator['id']; ?></td>
                <td><?php echo $moderator['name']; ?></td>
                <td><?php echo $moderator['username']; ?></td>
                <td><?php echo $moderator['email']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="submit" name="edit_<?php echo $moderator['id']; ?>" value="Edit">
                        <input type="submit" name="delete_<?php echo $moderator['id']; ?>" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="admin_dashboard.php">Back to Admin Dashboard</a></p>

</body>
</html>
