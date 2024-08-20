<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_donation";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the donation ID from the query string
$id = $_GET['id'];

// Delete the donation record
$sql = "DELETE FROM donations WHERE id=? AND user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo "Donation deleted successfully.";
} else {
    echo "Error deleting donation: " . $conn->error;
}

$stmt->close();
$conn->close();

header("Location: view_history.php");
exit();
?>
