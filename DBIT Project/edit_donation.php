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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $donorName = $_POST['donorName'];
    $donorEmail = $_POST['donorEmail'];
    $donorPhone = $_POST['donorPhone'];
    $foodType = $_POST['foodType'];
    $foodQuantity = $_POST['foodQuantity'];
    $expiryDate = $_POST['expiryDate'];

    // Update the donation record
    $sql = "UPDATE donations SET donorName=?, donorEmail=?, donorPhone=?, foodType=?, foodQuantity=?, expiry_date=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisisii", $donorName, $donorEmail, $donorPhone, $foodType, $foodQuantity, $expiryDate, $id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "Donation updated successfully.";
    } else {
        echo "Error updating donation: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: view_history.php");
    exit();
} else {
    // Check if the 'id' parameter is set
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch the donation details to populate the form
        $sql = "SELECT donorName, donorEmail, donorPhone, foodType, foodQuantity, expiry_date FROM donations WHERE id=? AND user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $donation = $result->fetch_assoc();
        $stmt->close();
    } else {
        // Handle the case where 'id' is not set (redirect or show an error)
        echo "No donation ID specified.";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Donation</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
                <li><a href="view_history.php"> History</li>
                <li><a href="edit_donation.php">Update Account</a></li>
                <li><a href="make_donation.html">Donations</a></li>
                <li><a href="donors_dashboard.html"> Dashboard</a></li>
           
            </ul>
        </nav>
    </header>
    <div class="content-wrapper">
        <h1>Edit Donation</h1>
        <form action="edit_donation.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label for="donorName">Name:</label>
            <input type="text" id="donorName" name="donorName" value="<?php echo $donation['donorName']; ?>" required>

            <label for="donorEmail">Email:</label>
            <input type="email" id="donorEmail" name="donorEmail" value="<?php echo $donation['donorEmail']; ?>" required>

            <label for="donorPhone">Phone Number:</label>
            <input type="text" id="donorPhone" name="donorPhone" value="<?php echo $donation['donorPhone']; ?>" required>

            <label for="foodType">Food Type:</label>
            <input type="text" id="foodType" name="foodType" value="<?php echo $donation['foodType']; ?>" required>

            <label for="foodQuantity">Quantity:</label>
            <input type="number" id="foodQuantity" name="foodQuantity" value="<?php echo $donation['foodQuantity']; ?>" required>

            <label for="expiryDate">Expiry Date:</label>
            <input type="date" id="expiryDate" name="expiryDate" value="<?php echo $donation['expiry_date']; ?>" required>

            <input type="submit" value="Update Donation">
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Feed4All. All Rights Reserved</p>
    </footer>
</body>
</html>
