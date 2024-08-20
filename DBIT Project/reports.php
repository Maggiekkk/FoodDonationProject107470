<?php
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

// Fetch donation records
$donations_sql = "SELECT id, donorName, donorEmail, donorPhone, foodType, foodQuantity, expiry_date FROM donations";
$donations_result = $conn->query($donations_sql);

// Fetch food bank records
$food_banks_sql = "SELECT id, name, address, contact FROM food_banks";
$food_banks_result = $conn->query($food_banks_sql);

// Fetch service records
$services_sql = "SELECT id, service_name, description FROM services";
$services_result = $conn->query($services_sql);

// Fetch user records
$users_sql = "SELECT id, name, email, phone_number, user_type FROM users";
$users_result = $conn->query($users_sql);



// Fetch volunteer request records
$volunteer_requests_sql = "SELECT id, name, email, phone, date, activity, status FROM volunteer_requests";
$volunteer_requests_result = $conn->query($volunteer_requests_sql);

// Function to print a specific report
function printReport($reportType, $result) {
    echo "<h2>$reportType Report</h2>";
    echo "<table border='1' cellpadding='10'>";
    
    // Fetch and print column names
    $field_count = $result->field_count;
    $fields = $result->fetch_fields();
    echo "<tr>";
    foreach ($fields as $field) {
        echo "<th>" . $field->name . "</th>";
    }
    echo "</tr>";

    // Fetch and print rows
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    // Print button for the report
    echo "<button onclick='window.print()'>Print $reportType Report</button>";
    echo "<hr>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combined Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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
        table {
            width: 80%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        h2 {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        hr {
            margin: 40px 0;
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
    <?php
    // Display each report with print functionality
    if ($donations_result->num_rows > 0) {
        printReport("Donations", $donations_result);
    } else {
        echo "<p>No donation records found.</p>";
    }

    if ($food_banks_result->num_rows > 0) {
        printReport("Food Banks", $food_banks_result);
    } else {
        echo "<p>No food bank records found.</p>";
    }

    if ($services_result->num_rows > 0) {
        printReport("Services", $services_result);
    } else {
        echo "<p>No service records found.</p>";
    }

    if ($users_result->num_rows > 0) {
        printReport("Users", $users_result);
    } else {
        echo "<p>No user records found.</p>";
    }

    

    if ($volunteer_requests_result->num_rows > 0) {
        printReport("Volunteer Requests", $volunteer_requests_result);
    } else {
        echo "<p>No volunteer request records found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>
 <footer>
        <p>&copy; 2024 Feed4All. All Rights Reserved</p>
    </footer>
</body>
</html>
