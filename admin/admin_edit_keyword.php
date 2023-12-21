<?php
include('config.php');

// Check if the admin is logged in
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: admin_login.php");
    exit();
}

// Check if keyword ID is provided in the URL
if (!isset($_GET["id"])) {
    echo "Keyword ID not provided.";
    exit();
}

$keyword_id = $_GET["id"];

// Retrieve keyword information for pre-filling the form
$query = "SELECT * FROM keywords WHERE id='$keyword_id'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    $keyword = $result->fetch_assoc();
} else {
    echo "Keyword not found.";
    exit();
}

// Process the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_keyword = $_POST["new_keyword"];

    // Update keyword information in the database
    $update_query = "UPDATE keywords SET keyword='$new_keyword' WHERE id='$keyword_id'";

    if ($conn->query($update_query) === TRUE) {
        echo "Keyword information updated successfully!";
        // Refresh keyword information after update
        $keyword['keyword'] = $new_keyword;
    } else {
        echo "Error updating keyword information: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Keyword</title>
    <link rel="stylesheet" href="./css/style.css">

</head>
<body>
<?php include('navbar.php'); ?>

    <h2>Edit Keyword</h2>

    <form action="" method="post">
        <label for="new_keyword">New Keyword:</label>
        <input type="text" name="new_keyword" value="<?php echo $keyword['keyword']; ?>" required><br>

        <input type="submit" value="Update Keyword">
    </form>

    <p><a href="admin_view_keywords.php">Back to View Keywords</a></p>

</body>
</html>
