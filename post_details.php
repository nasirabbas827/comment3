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
        // User is banned, they can view the post details but cannot add a comment
        echo "You are currently banned and cannot add a comment.";
        $can_comment = false;
    } else {
        $can_comment = true;
    }
} else {
    echo "Error retrieving user information.";
    exit();
}

// Check if the post ID is provided in the URL
if (!isset($_GET["post_id"])) {
    echo "Post ID not provided.";
    exit();
}

$post_id = $_GET["post_id"];

// Retrieve the post information with the username of the poster
$query_post = "SELECT p.*, u.username AS posted_by_username FROM posts p
                LEFT JOIN users u ON p.UserID = u.id
                WHERE p.PostID = '$post_id'";
$result_post = $conn->query($query_post);

if ($result_post->num_rows == 1) {
    $post = $result_post->fetch_assoc();
} else {
    echo "Post not found.";
    exit();
}

// Process the form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && $can_comment) {
    $comment_content = $_POST["comment_content"];

    // Filter inappropriate words from the comment using keywords table
    $keywords_query = "SELECT keyword FROM keywords";
    $keywords_result = $conn->query($keywords_query);

    if ($keywords_result->num_rows > 0) {
        while ($row = $keywords_result->fetch_assoc()) {
            $inappropriate_word = $row["keyword"];
            $comment_content = str_ireplace($inappropriate_word, "", $comment_content);
        }
    }

    // Insert the filtered comment into the database
    $insert_comment_query = "INSERT INTO comments (PostID, UserID, Content) VALUES ('$post_id', '$user_id', '$comment_content')";

    if ($conn->query($insert_comment_query) === TRUE) {
        echo "Comment added successfully!";
    } else {
        echo "Error adding comment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Details</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <h2>Post Details</h2>

    <p><strong>Title:</strong> <?php echo $post['Title']; ?></p>
    <p><strong>Content:</strong> <?php echo $post['Content']; ?></p>
    <p><strong>Posted By:</strong> <?php echo $post['posted_by_username']; ?></p>
    <p><strong>Post Date:</strong> <?php echo $post['PostDate']; ?></p>

    <!-- Display comments -->
    <h3>Comments</h3>
    <?php
    // Retrieve comments for the post with usernames
    $query_comments = "SELECT c.*, u.username AS comment_username FROM comments c
                        LEFT JOIN users u ON c.UserID = u.id
                        WHERE c.PostID = '$post_id'";
    $result_comments = $conn->query($query_comments);

    if ($result_comments->num_rows > 0) {
        $comments = $result_comments->fetch_all(MYSQLI_ASSOC);
        foreach ($comments as $comment) {
            echo "<p><strong>Comment By:</strong> {$comment['comment_username']}</p>";
            echo "<p>{$comment['Content']}</p>";
            echo "<hr>";
        }
    } else {
        echo "No comments found.";
    }
    ?>

    <!-- Form for adding comments -->
    <?php if ($can_comment): ?>
        <h3>Add a Comment</h3>
        <form action="" method="post">
            <label for="comment_content">Comment:</label>
            <textarea name="comment_content" required></textarea><br>
            <input type="submit" value="Add Comment">
        </form>
    <?php endif; ?>

    <p><a href="dashboard.php">Back to Dashboard</a></p>

</body>
</html>
