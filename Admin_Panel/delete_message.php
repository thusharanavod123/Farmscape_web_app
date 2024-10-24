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

// Check if 'id' is provided in POST request
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $messageId = $_POST['id'];

    // Prepare and bind the delete query
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $messageId);

    // Execute the query and check success
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Message not found']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete the message']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid or missing message ID']);
}

// Close the connection
$conn->close();
