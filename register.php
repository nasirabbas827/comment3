<?php
include('config.php');

// Process the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Set the status to "pending"
    $status = "pending";

    // Example: Insert data into the database with status set to "pending"
    $query = "INSERT INTO users (first_name, last_name, username, email, password, status) VALUES ('$first_name', '$last_name', '$username', '$email', '$password', '$status')";

    if ($conn->query($query) === TRUE) {
        echo "Registration successful! Your status is pending.";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<?php include('navbar.php'); ?>
    <h2>Registration Form</h2>

    <form action="" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required><br>

        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label>
            <input type="checkbox" name="terms" required>
            I agree to the terms and conditions
        </label><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>
