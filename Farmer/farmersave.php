<?php
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect farmer data from POST request
    $name = $_POST['name'];
    $email = $_POST['email']; // Farmer email field
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $address = $_POST['address'];
    $telephone = $_POST['telephone'];
    $farm_id = $_POST['farm_id']; // Farm registration number field
    $role = $_POST['role']; // Role will be "farmer"
    $farmer_info = $_POST['farmer_info']; // Additional farmer information

    // File uploads (profile picture)
    $profile_pic = $_FILES['profile_pic']['name'];

    // Define upload directory
    $upload_dir = 'uploads/';

    // Move uploaded profile picture to the server
    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_dir . $profile_pic);

    // Prepare and bind for inserting the farmer profile into the `users` table
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, address, telephone, farm_id, additional_info, profile_pic) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssssss", $name, $email, $password, $role, $address, $telephone, $farm_id, $farmer_info, $profile_pic);

    // Check if the profile was saved
    if ($stmt->execute()) {
        echo 'success'; // Send success message back to AJAX
    } else {
        echo 'Error: ' . $stmt->error; // Send error message back to AJAX
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
