<?php
// Include the configuration file and establish a database connection
include('config.php');

// Check if the user is logged in
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

// Retrieve all posts with usernames
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        /* Add styles for card layout */
        .post-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .post-card h4 {
            margin-bottom: 10px;
        }

        .post-card p {
            margin-bottom: 5px;
        }

        .post-card a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .post-card:hover {
            background-color: #f9f9f9;
        }
        /* Add styles for the button */
.post-card button {
    background-color: #333;
    color: #fff;
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
}

.post-card button:hover {
    background-color: #555;
}

    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <h2>Welcome to Your Dashboard, <?php echo $user['first_name']; ?>!</h2>

    <p><strong>Username:</strong> <?php echo $user['username']; ?></p>

    <!-- Display all posts as cards -->
    <h3>All Posts</h3>
    <?php foreach ($all_posts as $post): ?>
        <div class="post-card">
            <h4><?php echo $post['Title']; ?></h4>
            <p><?php echo $post['Content']; ?></p>
            <p><strong>Posted By:</strong> <?php echo $post['posted_by_username']; ?></p>
            <p><strong>Post Date:</strong> <?php echo $post['PostDate']; ?></p>
            <button onclick="window.location.href='post_details.php?post_id=<?php echo $post['PostID']; ?>'">View Post Details</button>
    </div>
<?php endforeach; ?>
</body>
</html>
