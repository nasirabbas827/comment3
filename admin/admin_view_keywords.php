<?php
include('config.php');

// Check if the admin is logged in
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: admin_login.php");
    exit();
}

// Retrieve all keywords for display
$query = "SELECT * FROM keywords";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $keywords = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "No keywords found.";
    exit();
}

// Process the form data when keywords are edited or deleted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($keywords as $keyword) {
        $keyword_id = $keyword['id'];

        if (isset($_POST["edit_$keyword_id"])) {
            // Edit keyword - redirect to edit page or handle as needed
            header("Location: admin_edit_keyword.php?id=$keyword_id");
            exit();
        } elseif (isset($_POST["delete_$keyword_id"])) {
            // Delete keyword
            $delete_query = "DELETE FROM keywords WHERE id='$keyword_id'";
            $conn->query($delete_query);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View Keywords</title>
    <link rel="stylesheet" href="./css/style.css">

</head>
<body>
<?php include('navbar.php'); ?>

    <h2>Admin View Keywords</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Keyword</th>
            <th>Action</th>
        </tr>
        <?php foreach ($keywords as $keyword): ?>
            <tr>
                <td><?php echo $keyword['id']; ?></td>
                <td><?php echo $keyword['keyword']; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="submit" name="edit_<?php echo $keyword['id']; ?>" value="Edit">
                        <input type="submit" name="delete_<?php echo $keyword['id']; ?>" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="admin_dashboard.php">Back to Admin Dashboard</a></p>

</body>
</html>
