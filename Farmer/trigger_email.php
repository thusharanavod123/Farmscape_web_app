<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Farmer.html");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch user details (email and name) from the database
$sql = "SELECT email, username FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_email = $row['email'];
    $user_name = $row['username'];
} else {
    echo "User details not found.";
    exit();
}

// Fetch reminders for upcoming vaccinations
$sql = "SELECT cow_id, vaccine, next_due_date 
        FROM vaccination_schedule 
        WHERE user_id = '$user_id' 
        AND next_due_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
$result = $conn->query($sql);

// Check if there are upcoming vaccinations
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cow_id = $row['cow_id'];
        $vaccine = $row['vaccine'];
        $next_due_date = $row['next_due_date'];

        // Prepare EmailJS API call
        $url = "https://api.emailjs.com/api/v1.0/email/send";
        $postData = json_encode([
            'service_id' => 'service_qkts0tl',
            'template_id' => 'template_ja5vhtm',
            'user_id' => 'QGSbG28pszZSr-kQ_',
            'template_params' => [
                'to_email' => $user_email, // Farmer's email
                'user_name' => $user_name, // Farmer's name
                'cow_id' => $cow_id,
                'next_due_date' => $next_due_date,
            ]
        ]);

        // Initialize cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        // Execute cURL request
        $response = curl_exec($ch);
        $error = curl_error($ch); // Capture any cURL errors
        curl_close($ch);

        // Debugging and error handling
        if ($response === false || $error) {
            echo "Error sending email for cow ID $cow_id: $error\n";
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['error'])) {
                // Handle API-level errors
                echo "API Error for cow ID $cow_id: " . $responseData['error'] . "\n";
            } else {
                echo "Email sent successfully for cow ID $cow_id to $user_email\n";
            }
        }
    }
} else {
    echo "No upcoming vaccinations found.";
}

// Close the database connection
$conn->close();
