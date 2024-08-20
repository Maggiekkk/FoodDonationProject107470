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

// Handle adding a new service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    
    $sql = "INSERT INTO services ( service_name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $service_name, $description);

    if ($stmt->execute()) {
        echo "Service added successfully.";
    } else {
        echo "Error adding service: " . $conn->error;
    }

    $stmt->close();
}

// Handle deleting a service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_service'])) {
    $service_id = $_POST['service_id'];

    $sql = "DELETE FROM services WHERE id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
        echo "Service deleted successfully.";
    } else {
        echo "Error deleting service: " . $conn->error;
    }

    $stmt->close();
}

// Fetch the services offered by the food bank
$foodbank_id = $_SESSION['foodbank_id'];
$sql = "SELECT service_name, description FROM services WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $foodbank_id);
$stmt->execute();
$result = $stmt->get_result();
$services = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="styles.css">
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
        <h1>Manage Services</h1>
        
        <h2>Add New Service</h2>
        <form action="manage_services.php" method="POST" class="service-form">
            <label for="service_name">Service Name:</label>
            <input type="text" id="service_name" name="service_name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="3" required></textarea>

            <input type="submit" name="add_service" value="Add Service">
        </form>

        <h2>Your Services</h2>
        <?php if (count($services) > 0): ?>
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($service['description']); ?></td>
                            <td>
                                <form action="manage_services.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                    <input type="submit" name="delete_service" value="Delete" onclick="return confirm('Are you sure you want to delete this service?');">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No services found. Add a new service above.</p>
        <?php endif; ?>
    </div>
    <footer>
        <p>&copy; 2024 Feed4All. All Rights Reserved</p>
    </footer>
</body>
</html>
