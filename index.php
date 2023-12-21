<?php
// Include the configuration file and establish a database connection
include('config.php');

// Retrieve all posts
$query_posts = "SELECT p.*, u.username AS posted_by_username FROM posts p
                LEFT JOIN users u ON p.UserID = u.id
                ORDER BY p.PostDate DESC";
$result_posts = $conn->query($query_posts);

if ($result_posts->num_rows > 0) {
    $all_posts = $result_posts->fetch_all(MYSQLI_ASSOC);
} else {
    echo "No posts found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment Filtering</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <h2>Welcome to Comment Filtering</h2>

    <?php foreach ($all_posts as $post): ?>
        <div style="border: 1px solid #ccc; margin-bottom: 20px; padding: 10px;">
            <h3><?php echo $post['Title']; ?></h3>
            <p><?php echo $post['Content']; ?></p>
            <p><strong>Posted By:</strong> <?php echo $post['posted_by_username']; ?></p>
            <p><strong>Post Date:</strong> <?php echo $post['PostDate']; ?></p>

            <!-- Retrieve and display up to 3 comments for each post -->
            <?php
            $post_id = $post['PostID'];
            $query_comments = "SELECT c.*, u.username AS comment_username FROM comments c
                                LEFT JOIN users u ON c.UserID = u.id
                                WHERE c.PostID = '$post_id' LIMIT 3";
            $result_comments = $conn->query($query_comments);

            if ($result_comments->num_rows > 0) {
                $comments = $result_comments->fetch_all(MYSQLI_ASSOC);
                foreach ($comments as $comment) {
                    echo "<p><strong>Comment By:</strong> {$comment['comment_username']}</p>";
                    echo "<p>{$comment['Content']}</p>";
                }
                // Add a link to view all comments (redirects to login page)
                echo "<a href='login.php'>View All Comments</a>";
            } else {
                echo "No comments found.";
            }
            ?>
        </div>
    <?php endforeach; ?>

</body>
</html>
