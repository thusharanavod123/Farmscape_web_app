<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit();
}

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$user_id = $_SESSION['user_id'];

// Delete the product if it belongs to the logged-in user
$query = "DELETE FROM marketPlace WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $product_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error deleting product']);
}

// Close the statement and connection
$stmt->close();
$conn->close();
