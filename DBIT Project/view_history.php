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

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Fetch donation records for the logged-in user
$sql = "SELECT id, donorName, donorEmail, donorPhone,foodType, foodQuantity, expiry_date FROM donations WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Donation History</title>
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
        <h1>Your Donation History</h1>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Donation ID</th>
                    <th>Donor Name</th>
                    <th>Donor Email</th>
                    <th>Donor Phone</th>
                    <th>Food Type</th>
                    <th>Quantity</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['donorName']; ?></td>
                    <td><?php echo $row['donorEmail']; ?></td>
                    <td><?php echo $row['donorPhone']; ?></td>
                    <td><?php echo $row['foodType']; ?></td>
                    <td><?php echo $row['foodQuantity']; ?></td>
                    <td><?php echo $row['expiry_date']; ?></td>
                    
                    <td>
                        <a href="edit_donation.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <!-- <a href="PHP/delete_donation.php?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this donation?');">Delete</a> -->
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>&copy; 2024 Feed4All. All Rights Reserved</p>
    </footer>
</body>
</html>

<?php
// Close the statement and connection
$stmt->close();
$conn->close();
?>
