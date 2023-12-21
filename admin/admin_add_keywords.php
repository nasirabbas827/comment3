<?php
include('config.php');

// Check if the admin is logged in
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: admin_login.php");
    exit();
}

// Process the form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_keyword = $_POST["new_keyword"];

    // Insert the keyword into the database
    $insert_query = "INSERT INTO keywords (keyword) VALUES ('$new_keyword')";

    if ($conn->query($insert_query) === TRUE) {
        echo "Keyword added successfully!";
    } else {
        echo "Error adding keyword: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Keywords</title>
    <link rel="stylesheet" href="./css/style.css">

</head>
<body>
<?php include('navbar.php'); ?>

    <h2>Add Keywords</h2>

    <form action="" method="post">
        <label for="new_keyword">New Keyword:</label>
        <input type="text" name="new_keyword" required><br>

        <input type="submit" value="Add Keyword">
    </form>

    <p><a href="admin_view_keywords.php">View Keywords</a></p>

</body>
</html>
