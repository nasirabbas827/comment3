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

// Retrieve user's posts
$query_posts = "SELECT * FROM posts WHERE UserID = '$user_id'";
$result_posts = $conn->query($query_posts);

if ($result_posts->num_rows > 0) {
    $user_posts = $result_posts->fetch_all(MYSQLI_ASSOC);
} else {
    echo "No posts found.";
}

// Process form data for editing or deleting posts
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["edit_post"])) {
        $post_id_to_edit = $_POST["edit_post"];
        // Redirect to edit page or handle editing logic
        header("Location: edit_post.php?id=$post_id_to_edit");
        exit();
    } elseif (isset($_POST["delete_post"])) {
        $post_id_to_delete = $_POST["delete_post"];
        // Delete post from the database
        $delete_query = "DELETE FROM posts WHERE PostID = '$post_id_to_delete' AND UserID = '$user_id'";
        if ($conn->query($delete_query) === TRUE) {
            echo "Post deleted successfully!";
        } else {
            echo "Error deleting post: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Post</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<h3>Your Posts</h3>
<table border="1">
    <tr>
        <th>Title</th>
        <th>Content</th>
        <th>Action</th>
    </tr>
    <?php foreach ($user_posts as $post): ?>
        <tr>
            <td><?php echo $post['Title']; ?></td>
            <td><?php echo $post['Content']; ?></td>
            <td>
                <form action="" method="post">
                    <input type="hidden" name="edit_post" value="<?php echo $post['PostID']; ?>">
                    <input type="submit" value="Edit">
                </form>
                <form action="" method="post">
                    <input type="hidden" name="delete_post" value="<?php echo $post['PostID']; ?>">
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>