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
$query = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $user_id = $user['id']; // Assuming 'id' is the actual column name for UserID
} else {
    echo "Error retrieving user information.";
    exit();
}

// Check if the post ID is provided in the URL
if (!isset($_GET["id"])) {
    echo "Post ID not provided.";
    exit();
}

$post_id = $_GET["id"];

// Retrieve the post information
$query_post = "SELECT * FROM posts WHERE PostID = '$post_id' AND UserID = '$user_id'";
$result_post = $conn->query($query_post);

if ($result_post->num_rows == 1) {
    $post = $result_post->fetch_assoc();
} else {
    echo "Post not found or you don't have permission to edit this post.";
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

    // Update the filtered post in the database
    $update_query = "UPDATE posts SET Title = '$title', Content = '$content' WHERE PostID = '$post_id' AND UserID = '$user_id'";

    if ($conn->query($update_query) === TRUE) {
        echo "Post updated successfully!";
    } else {
        echo "Error updating post: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <h2>Edit Your Post</h2>

    <form action="" method="post">
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?php echo $post['Title']; ?>" required><br>

        <label for="content">Content:</label>
        <textarea name="content" required><?php echo $post['Content']; ?></textarea><br>

        <input type="submit" value="Update Post">
    </form>

    <p><a href="view_post.php">Back to View Post</a></p>

</body>
</html>
