<?php
session_start();

// Check if AI technician is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ai_technician') {
  header("Location: login.php");
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



$technician_id = $_SESSION['user_id']; // Get logged-in AI technician ID

$sql = "SELECT cow_id, farmer_id, next_due_date, note FROM AI_management WHERE technician_id = ? AND next_due_date IS NOT NULL ORDER BY next_due_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $technician_id);
$stmt->execute();
$result = $stmt->get_result();

$reminders = [];
while ($row = $result->fetch_assoc()) {
    $reminders[] = $row;
}

echo json_encode($reminders);
