<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin details
$username2 = 'admin';
$email = 'admin@example.com';
$password2 = 'admin123'; // Plain password (Will be hashed before inserting)
$role = 'admin';
$created_at = date('Y-m-d H:i:s');

// Hash the password using bcrypt
$hashed_password = password_hash($password2, PASSWORD_BCRYPT);

// SQL query to insert admin data
$sql = "INSERT INTO users (username, email, password, role, created_at)
        VALUES (?, ?, ?, ?, ?)";

// Prepare statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", 
    $username2, $email, $hashed_password, $role, $created_at
);

// Execute statement
if ($stmt->execute()) {
    echo "Admin user added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
