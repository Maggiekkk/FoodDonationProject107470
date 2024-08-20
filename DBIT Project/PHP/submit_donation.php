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
$donorName = $_POST['donorName'];
$donorEmail = $_POST['donorEmail'];
$donorPhone = $_POST['donorPhone'];
$foodType = $_POST['foodType'];
$foodQuantity = $_POST['foodQuantity'];
$foodExpiryDate = $_POST['expiry_date'];

// Get the currently logged-in user's ID (assumed to be stored in session)
$user_id = $_SESSION['user_id']; // This should have been set during user login

// Prepare and bind the SQL statement to insert donation details
$stmt = $conn->prepare("INSERT INTO donations (user_id, donorName, donorEmail, donorPhone, foodType, foodQuantity, expiry_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssis", $user_id, $donorName, $donorEmail, $donorPhone, $foodType, $foodQuantity,$foodExpiryDate);

// Execute the insert statement
if ($stmt->execute()) {
    echo "Thank you for your donation!";
} else {
    echo "Error submitting donation: " . $conn->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
