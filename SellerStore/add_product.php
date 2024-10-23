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

$title = isset($_POST['product-title']) ? mysqli_real_escape_string($conn, $_POST['product-title']) : '';
$price = isset($_POST['product-price']) ? floatval($_POST['product-price']) : 0;
$description = isset($_POST['product-description']) ? mysqli_real_escape_string($conn, $_POST['product-description']) : '';

// Handle image uploads
$image = null;
if (isset($_FILES['product-images']) && $_FILES['product-images']['error'] == 0) {
    $imageData = file_get_contents($_FILES['product-images']['tmp_name']);
    $image = $conn->real_escape_string($imageData); // Escape the binary data
}

$query = "INSERT INTO marketPlace (Title, Price, Description, Image, user_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sdssi", $title, $price, $description, $image, $user_id); // Image should be 'b' for blob

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error adding product: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
