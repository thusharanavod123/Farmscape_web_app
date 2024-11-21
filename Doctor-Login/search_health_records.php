<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: doctorlogin.html");
    exit();
}

// Check if the user is a doctor
if ($_SESSION['role'] !== 'doctor') {
    header("Location: doctorlogin.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the POST data
$cow_id = isset($_POST['cow_id']) ? $_POST['cow_id'] : '';
$farmer_id = isset($_POST['farmer_id']) ? $_POST['farmer_id'] : '';

// Validate input
if (empty($cow_id) || empty($farmer_id)) {
    echo json_encode(['error' => 'Cow ID and Farmer ID are required']);
    exit();
}

// SQL query to search health records by cow_id and farmer_id
$sql = "SELECT h.id, h.cow_id, h.farmer_id, h.doctor_id, h.medicine, h.note, h.created_at, u.username as doctor_name
        FROM health_records h
        JOIN users u ON h.doctor_id = u.id
        WHERE h.cow_id = ? AND h.farmer_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $cow_id, $farmer_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any records are found
if ($result->num_rows > 0) {
    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    // Return results as JSON
    echo json_encode($records);
} else {
    // No records found
    echo json_encode(['error' => 'No health records found for the provided Cow ID and Farmer ID']);
}

$stmt->close();
$conn->close();
