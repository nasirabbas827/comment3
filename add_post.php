<?php
include('config.php');

// Check if the user is logged in, if not, redirect to login page
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information for the dashboard
$username = $_SESSION["username"];
$query_user = "SELECT * FROM users WHERE username='$username'";
$result_user = $conn->query($query_user);

if ($result_user->num_rows == 1) {
    $user = $result_user->fetch_assoc();
    $user_id = $user['id']; // Assuming 'id' is the actual column name for UserID

    // Check if the user is banned
    $query_banned = "SELECT * FROM BannedUser WHERE UserID='$user_id' AND BannedEndDate >= CURDATE()";
    $result_banned = $conn->query($query_banned);

    if ($result_banned->num_rows > 0) {
        // User is banned, redirect or show a message
        echo "You are currently banned and cannot add a post.";
        exit();
    }
} else {
    echo "Error retrieving user information.";
    exit();
}

// Process the form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];

    // Filter inappropriate words from the post using keywords table
    $keywords_query = "SELECT keyword FROM keywords";
    $keywords_result = $conn->query($keywords_query);

    if ($keywords_result->num_rows > 0) {
        while ($row = $keywords_result->fetch_assoc()) {
            $inappropriate_word = $row["keyword"];
            $title = str_ireplace($inappropriate_word, "", $title);
            $content = str_ireplace($inappropriate_word, "", $content);
        }
    }

    // Insert the filtered post into the database
    $insert_query = "INSERT INTO posts (UserID, Title, Content) VALUES ('$user_id', '$title', '$content')";

    if ($conn->query($insert_query) === TRUE) {
        echo "Post added successfully!";
    } else {
        echo "Error adding post: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <h3>Add a Post</h3>
    <form action="" method="post">
        <label for="title">Title:</label>
        <input type="text" name="title" required><br>

        <label for="content">Content:</label>
        <textarea name="content" required></textarea><br>

        <input type="submit" value="Add Post">
        <a href="view_post.php">View Post</a>
    </form>
</body>
</html>
