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

// Get the current profile details
// $foodbank_id = $_SESSION['foodbank_id'];
$sql = "SELECT name, email,password, phone_number,user_type  FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $foodbank_id);
$stmt->execute();
$result = $stmt->get_result();
$foodbank = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $user_type = $_POST['user_type'];

    // Update profile information
    $sql = "UPDATE users SET name=?, email=?, password=?, phone_number=?, user_type=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email,$password, $phone,$user_type);

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
        header("Location: manage_profile.php"); // Refresh the page to show updated details
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <style>
        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        header .logo img {
            width: 100px;
            height: auto;
        }

        header nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        header nav ul li {
            margin-left: 20px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        header nav ul li a:hover {
            color: #ffeb3b;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #4CAF50;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
            box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<header>
        <div class="logo">
            <img src="Images\CompanyLogo.jpg.png" alt="Feed4All Logo" id="Logo">
        </div>
        <div>
            <h1>Feed4All</h1>
            <p><i>Feeding the nation, with every donation!</i></p>
        </div>
        <nav>
            <ul>
            <li><a href="index.html">Home</a></li>
                <li><a href="manage_profile.php">Profile</li>
                <li><a href="manage_services.php">Services</li>
                <li><a href="view_donations.php">Donations</li>
            </ul>
        </nav>
    </header>
    <div class="content-wrapper">
        <h1>Manage Profile</h1>
        <form action="manage_profile.php" method="POST" class="profile-form">
            
        <label for="username">Name:</label>
            <input type="text" id="username" name="username" placeholder="New username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="New email@example.com" required>

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>

    
            <label for="phone">Phone Number:</label>
            <input type="mobile" id="phone" name="phone" required>
    

            <input type="submit" value="Update Profile">
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Feed4All. All Rights Reserved</p>
    </footer>
</body>
</html>
