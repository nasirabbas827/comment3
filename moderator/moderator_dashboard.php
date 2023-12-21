<?php
// Include the configuration file and establish a database connection
include('config.php');

// Check if the moderator is logged in
session_start();
if (!isset($_SESSION["moderator_username"])) {
    header("Location: moderator_login.php");
    exit();
}

// Process the form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && isset($_POST["post_id"])) {
        $post_id = $_POST["post_id"];

        // Delete comments under the post
        $delete_comments_query = "DELETE FROM comments WHERE PostID = '$post_id'";
        $conn->query($delete_comments_query);

        // Delete the post
        $delete_post_query = "DELETE FROM posts WHERE PostID = '$post_id'";

        if ($conn->query($delete_post_query) === TRUE) {
            echo "Post deleted successfully!";
        } else {
            echo "Error deleting post: " . $conn->error;
        }
    }
}

// Retrieve all posts
$query_posts = "SELECT * FROM posts";
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
    <title>Moderator View Posts</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <h2>Moderator View Posts</h2>

    <table border="1">
        <tr>
            <th>Post ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Posted By</th>
            <th>Post Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($all_posts as $post): ?>
            <tr>
                <td><?php echo $post['PostID']; ?></td>
                <td><?php echo $post['Title']; ?></td>
                <td><?php echo $post['Content']; ?></td>
                <td><?php echo $post['UserID']; // Assuming 'UserID' is the column name for the user ID ?></td>
                <td><?php echo $post['PostDate']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="post_id" value="<?php echo $post['PostID']; ?>">
                        <input type="submit" value="Delete Post">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="moderator_dashboard.php">Back to Moderator Dashboard</a></p>

</body>
</html>
