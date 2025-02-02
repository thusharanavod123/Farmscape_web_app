<?php
session_start();
header('Content-Type: application/json');

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

$query = "SELECT id, Title, Price, Description, Image FROM marketPlace WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['Image'])) {
        // Assuming the 'Image' column contains the relative URL (e.g., 'uploads/image.jpg')
        $row['Image'] = 'http://your-domain.com/' . $row['Image'];  // Replace with your domain or base path
    }
    $products[] = $row;
}

if (empty($products)) {
    echo json_encode(['success' => true, 'products' => [], 'message' => 'No products found']);
} else {
    echo json_encode(['success' => true, 'products' => $products]);
}

$stmt->close();
$conn->close();
