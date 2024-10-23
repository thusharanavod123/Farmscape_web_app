<?php
session_start();

// Response array to send JSON data
$response = [];

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Send JSON error response
    $response['status'] = 'error';
    $response['message'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize the email and password inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // User-entered password

    // Prepare SQL query to fetch the user based on email
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    // If a user with the provided email is found
    if ($stmt->num_rows > 0) {
        // Bind the result to variables
        $stmt->bind_result($user_id, $username, $hashed_password, $role);
        $stmt->fetch();

        // Verify the password using password_verify()
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Prepare a success response with user info
            $response['status'] = 'success';
            $response['username'] = $username;
            $response['role'] = $role;
            echo json_encode($response);
            exit();
        } else {
            // Invalid password
            $response['status'] = 'error';
            $response['message'] = 'Invalid email or password.';
            echo json_encode($response);
            exit();
        }
    } else {
        // User not found
        $response['status'] = 'error';
        $response['message'] = 'No user found with this email.';
        echo json_encode($response);
        exit();
    }

    // Close the statement 
    $stmt->close();
}

// Close the connection
$conn->close();
