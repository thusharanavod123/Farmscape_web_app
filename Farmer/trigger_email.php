<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Farmer.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Logged-in user ID

// Fetch user details (email and name) from the database
$sql = "SELECT email, username FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_email = $row['email'];
    $user_name = $row['username'];
} else {
    // Handle case where user details are not found
    echo "User details not found.";
    exit();
}

// Fetch reminders for upcoming vaccinations
$sql = "SELECT cow_id, vaccine, next_due_date 
        FROM vaccination_schedule 
        WHERE user_id = '$user_id' 
        AND next_due_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";

$result = $conn->query($sql);

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
                'next_due_date' => $next_due_date
            ]
        ]);

        // Send email
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            echo "Error sending email: " . curl_error($ch);
        } else {
            echo "Email sent for email : $user_email\n";
        }
    }
} else {
    echo "No upcoming vaccinations found.";
}

$conn->close();
