<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "farmscape"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the message from the request
$message = $_POST['message'];

if (!empty($message)) {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO messages (message) VALUES (?)");
    $stmt->bind_param("s", $message);
    
    // Execute and check success
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save the message']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Message cannot be empty']);
}

// Close connection
$conn->close();
