<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to fetch user details based on email
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_type'] = $user['user_type'];
            
            // Redirect based on user_type
            switch ($user['user_type']) {
                case 'Donor':
                    header("Location: ../donors_dashboard.html");
                    break;
                case 'Volunteer':
                    header("Location: volunteer_dashboard.php");
                    break;
                case 'FoodBank':
                    header("Location: ../foodBank_dashboard.html");
                    break;
                case 'Admin':
                    header("Location: ../administrator_dashboard.html");
                    break;
                default:
                    echo "Invalid user type.";
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }

    $stmt->close();
    $conn->close();
}
?>
