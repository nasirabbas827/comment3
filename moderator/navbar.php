<?php

// Check if the user is logged in
if (isset($_SESSION["moderator_username"])) {
    $logged_in = true;
    $username = $_SESSION["moderator_username"];
} else {
    $logged_in = false;
}
?>

<nav>
    <div>
        <span>Comment Filtering</span>
    </div>

    <ul>
        <?php if ($logged_in): ?>
            <!-- Display these links when the user is logged in -->
            <li><a href="moderator_dashboard.php">Home</a></li>
            <li><a href="update_profile.php">Update Profile</a></li>
            <li><a href="ban_user.php">Ban Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <!-- Display these links when the user is not logged in -->
            <li><a href="register.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="admin_login.php">Admin Login</a></li>
            <li><a href="moderator_login.php">Moderator Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
