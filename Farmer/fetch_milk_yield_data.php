<?php
// fetch_milk_yield_data.php
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


// Get logged-in user's ID
$user_id = $_SESSION['user_id']; // Assuming session contains the user ID

// SQL query to get average yield per cow grouped by month
$sql = "SELECT cow_id, MONTH(date) AS month, AVG(yield) AS average_yield 
        FROM milk_yield 
        WHERE user_id = ?
        GROUP BY cow_id, MONTH(date)
        ORDER BY cow_id, MONTH(date)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);

$stmt->close();
$conn->close();
