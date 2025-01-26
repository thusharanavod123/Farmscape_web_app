<?php
header('Content-Type: application/json');

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'animal_health';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Get the POST data
$cow_id = $_POST['cow_id'] ?? '';
$farmer_id = $_POST['farmer_id'] ?? '';

// Validate the inputs
if (empty($cow_id) || empty($farmer_id)) {
    echo json_encode(['error' => 'Cow ID and Farmer ID are required.']);
    exit;
}

// Prepare the SQL query
$stmt = $conn->prepare('SELECT * FROM health_records WHERE cow_id = ? AND farmer_id = ?');
$stmt->bind_param('ss', $cow_id, $farmer_id);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $records = [];

    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }

    // Return the records as JSON
    echo json_encode($records);
} else {
    echo json_encode(['error' => 'No records found.']);
}

// Close the connection
$conn->close();
