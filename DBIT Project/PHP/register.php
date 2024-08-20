<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_donation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $user_type = $_POST['user_type'];

    // Input validation
    if (empty($name) || empty($email) || empty($password) ||empty($phone) || empty($user_type)) {
        echo "All fields are required.";
        exit;
    }

    // Sanitize email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Users (name, email, password,phone_number, user_type) VALUES (?, ?, ?, ?,?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss", $name, $email, $hashed_password,$phone, $user_type);

    if ($stmt->execute() === TRUE) {
        echo "Registration successful. <a href='login.html'>Login</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

