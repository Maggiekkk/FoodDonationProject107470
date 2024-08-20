<?php
// Start session to manage user login state (if required)
session_start();

// Database connection settings
$servername = "localhost";  // Change this to your database server
$username = "root";         // Change this to your database username
$password = "";             // Change this to your database password
$dbname = "food_donation";  // Your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$new_username = $_POST['username'];
$new_email = $_POST['email'];
$new_password = $_POST['password'];
$new_phone = $_POST['phone'];
$new_user_type = $_POST['user_type'];

// Get the currently logged-in user's ID
// You might store this in a session after login
$user_id = $_SESSION['user_id'];

// Prepare and bind the SQL statement to update user details
$stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, phone=?, user_type=? WHERE id=?");
$stmt->bind_param("sssssi", $new_username, $new_email, $new_password, $new_phone, $new_user_type, $user_id);

// Execute the update statement
if ($stmt->execute()) {
    echo "Account details updated successfully!";
} else {
    echo "Error updating account: " . $conn->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();

