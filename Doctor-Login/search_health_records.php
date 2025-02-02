<?php
header('Content-Type: application/json');

session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
$stmt = $conn->prepare('SELECT * FROM animal_health WHERE cow_id = ? AND farmer_id = ?');
$stmt->bind_param('ii', $cow_id, $farmer_id);

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
