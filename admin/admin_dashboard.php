<?php
include('config.php');

// Check if the admin is logged in
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: admin_login.php");
    exit();
}

// Retrieve all users for display
$query = "SELECT * FROM users";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "No users found.";
    exit();
}

// Process the form data when status is updated
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($users as $user) {
        $user_id = $user['id'];
        $new_status = $_POST["status_$user_id"];

        // Update user status in the database
        $update_query = "UPDATE users SET status='$new_status' WHERE id='$user_id'";
        $conn->query($update_query);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View Users</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
<?php include('navbar.php'); ?>

   
        <h1>Admin View Users</h1>
    

    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['first_name']; ?></td>
                <td><?php echo $user['last_name']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['status']; ?></td>
                <td>
                    <form action="" method="post">
                        <select name="status_<?php echo $user['id']; ?>">
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <input type="submit" value="Update Status">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>
