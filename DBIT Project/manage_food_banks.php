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

// Handle adding a new food bank
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_food_bank'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $sql = "INSERT INTO food_banks (name, address, contact) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $address, $contact);

    if ($stmt->execute()) {
        echo "Food bank added successfully.";
    } else {
        echo "Error adding food bank: " . $conn->error;
    }

    $stmt->close();
}

// Handle deleting a food bank
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_food_bank'])) {
    $food_bank_id = $_POST['food_bank_id'];

    $sql = "DELETE FROM food_banks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $food_bank_id);

    if ($stmt->execute()) {
        echo "Food bank deleted successfully.";
    } else {
        echo "Error deleting food bank: " . $conn->error;
    }

    $stmt->close();
}

// Fetch all food banks
$sql = "SELECT id, name, address, contact FROM food_banks";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Food Banks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
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
        .form-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 1000px;
            margin: auto;
        }
        .form-container h1 {
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 10px;
        }
        .form-container input[type="text"],
        .form-container input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-container input[type="submit"] {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .delete-button {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 8px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .delete-button:hover {
            background-color: #c82333;
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
                <li><a href="manage_users.php">Account</a></li>
                <!-- <li><a href="view_donations.php">View donation</a></li> -->
                <li><a href="manage_food_banks.php">Food banks</a></li>
                <li><a href="manage_requests.php">Volunteer Request</a></li>
                <li><a href="administrator_dashboard.html">Dashboard</a></li>
            </ul>
        </nav>
    </header>
    <div class="form-container">
        <h1>Add Food Bank</h1>
        <form action="manage_food_banks.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="contact">Contact Number:</label>
            <input type="text" id="contact" name="contact" required>

            <input type="submit" name="add_food_bank" value="Add Food Bank">
        </form>
    </div>

    <div class="form-container">
        <h1>Manage Existing Food Banks</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Contact Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                        <td>
                            <form action="manage_food_banks.php" method="POST" style="display:inline;">
                                <input type="hidden" name="food_bank_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" name="delete_food_bank" class="delete-button">Delete</button>
                            </form>
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
