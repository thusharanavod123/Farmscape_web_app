<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch products for the logged-in user
$query = "SELECT id, Title, Price, Description, Image FROM marketPlace WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    // Convert binary image data to base64 for display in HTML
    if (!empty($row['Image'])) {
        $row['Image'] = base64_encode($row['Image']);
    }
    $products[] = $row;
}

echo json_encode(['success' => true, 'products' => $products]);

$stmt->close();
$conn->close();
