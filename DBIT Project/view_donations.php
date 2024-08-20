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

// Fetch all donation records
$sql = "SELECT id, donorName, donorEmail, donorPhone, foodType, foodQuantity, expiry_date FROM donations";
$result = $conn->query($sql);

// Check if the query was successful
if ($result === FALSE) {
    die("Error fetching donations: " . $conn->error);
}

// Fetch all donations as an associative array
$donations = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Donations</title>
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
        <h1>All Donations</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Donor Name</th>
                    <th>Donor Email</th>
                    <th>Donor Phone</th>
                    <th>Food Type</th>
                    <th>Food Quantity</th>
                    <th>Expiry Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donation['id']); ?></td>
                        <td><?php echo htmlspecialchars($donation['donorName']); ?></td>
                        <td><?php echo htmlspecialchars($donation['donorEmail']); ?></td>
                        <td><?php echo htmlspecialchars($donation['donorPhone']); ?></td>
                        <td><?php echo htmlspecialchars($donation['foodType']); ?></td>
                        <td><?php echo htmlspecialchars($donation['foodQuantity']); ?></td>
                        <td><?php echo htmlspecialchars($donation['expiry_date']); ?></td>
                        <td>
                        <!-- <a href="edit_donation.php?id=<?php echo $row['id']; ?>">Edit</a> -->
                        <a href="PHP/delete_donation.php?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this donation?');">Delete</a>
                    </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>&copy; 2024 Feed4All. All Rights Reserved</p>
    </footer>
</body>
</html>
