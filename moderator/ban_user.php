<?php
// Include the configuration file and establish a database connection
include('config.php');

// Check if the moderator is logged in
session_start();
if (!isset($_SESSION["moderator_username"])) {
    header("Location: moderator_login.php");
    exit();
}

// Process the ban or unban action if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && isset($_POST["user_id"])) {
        $user_id = $_POST["user_id"];

        if ($_POST["action"] == "ban") {
            $ban_duration = $_POST["ban_duration"];

            // Calculate the end date of the ban
            $banned_start_date = date("Y-m-d");
            $banned_end_date = date("Y-m-d", strtotime("+$ban_duration days"));

            // Insert the ban information into the BannedUser table
            $insert_ban_query = "INSERT INTO BannedUser (UserID, BannedStartDate, BannedEndDate) VALUES ('$user_id', '$banned_start_date', '$banned_end_date')";

            if ($conn->query($insert_ban_query) === TRUE) {
                echo "User banned successfully!";
            } else {
                echo "Error banning user: " . $conn->error;
            }
        } elseif ($_POST["action"] == "unban") {
            // Delete the ban information from the BannedUser table
            $delete_ban_query = "DELETE FROM BannedUser WHERE UserID = '$user_id'";

            if ($conn->query($delete_ban_query) === TRUE) {
                echo "User unbanned successfully!";
            } else {
                echo "Error unbanning user: " . $conn->error;
            }
        }
    }
}

// Retrieve all users along with their ban status
$query_users = "SELECT users.*, BannedUser.BannedEndDate AS BannedEndDate FROM users
                LEFT JOIN BannedUser ON users.id = BannedUser.UserID";
$result_users = $conn->query($query_users);

if ($result_users->num_rows > 0) {
    $all_users = $result_users->fetch_all(MYSQLI_ASSOC);
} else {
    echo "No users found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator View Users</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <h2>Moderator View Users</h2>

    <table border="1">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Banned Until</th>
            <th>Action</th>
        </tr>
        <?php foreach ($all_users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['BannedEndDate'] ?? 'Not Banned'; ?></td>
                <td>
                    <?php if ($user['BannedEndDate']): ?>
                        <form action="" method="post">
                            <input type="hidden" name="action" value="unban">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="submit" value="Unban">
                        </form>
                    <?php else: ?>
                        <form action="" method="post">
                            <input type="hidden" name="action" value="ban">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <label for="ban_duration">Ban Duration (days):</label>
                            <input type="number" name="ban_duration" min="1" required>
                            <input type="submit" value="Ban User">
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="moderator_dashboard.php">Back to Moderator Dashboard</a></p>

</body>
</html>
