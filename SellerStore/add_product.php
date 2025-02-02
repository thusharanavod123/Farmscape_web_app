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
$image_path = null;
if (isset($_FILES['product-images']) && $_FILES['product-images']['error'] == 0) {
    $upload_dir = './uploads/'; // Folder to store images
    $image_name = uniqid() . '_' . basename($_FILES['product-images']['name']); // Unique image name
    $target_file = $upload_dir . $image_name;

    if (move_uploaded_file($_FILES['product-images']['tmp_name'], $target_file)) {
        $image_path = $target_file; // Save the path in the database
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to upload image']);
        exit();
    }
}

$query = "INSERT INTO marketPlace (Title, Price, Description, Image, user_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sdssi", $title, $price, $description, $image_path, $user_id); // Save the path, not binary data

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error adding product: ' . $stmt->error]);
}

$stmt->close();
$conn->close();

