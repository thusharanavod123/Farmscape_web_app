<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => "Connection failed: " . $conn->connect_error]));
}

// Fetch messages from the database
$sql = "SELECT id, message, created_at FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);

$messages = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
} else {
    // No messages found or query failed
    $messages = [];
}

// Output the data as JSON
header('Content-Type: application/json');

// Ensure the JSON response is valid even if there are no messages or an empty result
echo json_encode([
    'success' => true,
    'messages' => $messages
]);

// Close connection
$conn->close();
