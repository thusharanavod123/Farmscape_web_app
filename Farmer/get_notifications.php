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

$sql = "SELECT cow_id, vaccine, next_due_date 
        FROM vaccination_schedule 
        WHERE user_id = '$user_id' 
        AND next_due_date > NOW() 
        ORDER BY next_due_date ASC";

$result = $conn->query($sql);
$notifications = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            'cow_id' => $row['cow_id'],
            'vaccine' => $row['vaccine'],
            'next_due_date' => $row['next_due_date']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($notifications);

