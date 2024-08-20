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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_request'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $activity = $_POST['activity'];

    $sql = "INSERT INTO volunteer_requests (name, email, phone, date, activity) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $date, $activity);

    if ($stmt->execute()) {
        echo "Request submitted successfully.";
    } else {
        echo "Error submitting request: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feed4All</title>
    <style>
        /* Inline CSS */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
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
        .form-container input[type="email"],
        .form-container input[type="date"],
        .form-container textarea {
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

        .hero {
            background-image: url('https://i.pinimg.com/564x/24/22/80/2422806bf8be096cb998c3c555d98ee9.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 30px;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero h1, .hero p, .hero .button {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            margin: 0;
            font-size: 48px;
            color: #ffeb3b;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .hero p {
            margin: 20px 0 30px;
            font-size: 24px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffeb3b;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #ffc107;
        }

        main {
            padding: 20px;
        }

        section {
            margin-bottom: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            <img src="../Images/CompanyLogo.jpg" alt="Feed4All Logo" id="Logo">
        </div>
        <div>
            <h1>Feed4All</h1>
            <p><i>Feeding the nation, with every donation!</i></p>
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</li>
                <li><a href="directory.html">Food Banks & Shelters</a></li>
                <li><a href="donation.html">Donations</a></li>
                <li><a href="volunteersignup.html">Volunteer Sign-up</a></li>
                <li><a href="register.html">Register</a></li>
                <li><a href="login.html">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Welcome to Feed4All</h1>
            <p>Feeding the nation, with every donation!</p>
            <a href="donation.html" class="button">Donate Now</a>
        </section>
        <section>
            <div class="form-container">
                <h1>Volunteer Request Form</h1>
                <form action="volunteer_request.php" method="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
        
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
        
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required>
        
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>
        
                    <label for="activity">Activity:</label>
                    <textarea id="activity" name="activity" rows="3" required></textarea>
        
                    <input type="submit" name="submit_request" value="Submit Request">
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Feed4All. All Rights Reserved</p>
    </footer>
</body>
</html>
