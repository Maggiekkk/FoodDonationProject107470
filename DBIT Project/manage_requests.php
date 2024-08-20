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

// Handle approving a request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_request'])) {
    $request_id = $_POST['request_id'];

    $sql = "UPDATE volunteer_requests SET status = 'Approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);

    if ($stmt->execute()) {
        echo "Request approved successfully.";
    } else {
        echo "Error approving request: " . $conn->error;
    }

    $stmt->close();
}

// Fetch all requests
$sql = "SELECT id, name, email, phone, date, activity, status FROM volunteer_requests";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Volunteer Requests</title>
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
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 1000px;
            margin: auto;
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
        .approve-button {
            background-color: #28a745;
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
        .approve-button:hover {
            background-color: #218838;
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
    <div class="table-container">
        <h1>Volunteer Requests</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Activity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['activity']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php if ($row['status'] == 'Pending'): ?>
                                <form action="manage_requests.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" name="approve_request" class="approve-button">Approve</button>
                                </form>
                            <?php endif; ?>
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
