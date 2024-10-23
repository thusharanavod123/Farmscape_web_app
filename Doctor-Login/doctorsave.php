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
    $name = $_POST['name'];
    $latitude = !empty($_POST['latitude']) ? $_POST['latitude'] : NULL;
    $longitude = !empty($_POST['longitude']) ? $_POST['longitude'] : NULL;
    $telephone = $_POST['telephone'];
    $doctorEmail = $_POST['email']; // New email field
    $doctorPassword = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role']; // New role field


   
    $doctor_info = $_POST['doctor_info'];

    // File uploads
    $profile_pic = $_FILES['profile_pic']['name'];
    $career_image1 = $_FILES['career_image1']['name'];
    $career_image2 = $_FILES['career_image2']['name'];
    $career_image3 = $_FILES['career_image3']['name'];

    // Define upload directory
    $upload_dir = 'uploads/';

    // Move uploaded files to the server
    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_dir . $profile_pic);
    move_uploaded_file($_FILES['career_image1']['tmp_name'], $upload_dir . $career_image1);
    move_uploaded_file($_FILES['career_image2']['tmp_name'], $upload_dir . $career_image2);
    move_uploaded_file($_FILES['career_image3']['tmp_name'], $upload_dir . $career_image3);

    // Prepare and bind for inserting the doctor profile
    $stmt = $conn->prepare("INSERT INTO users (username,email, password,role, latitude, longitude, telephone, additional_info, profile_pic, career_image1, career_image2, career_image3) 
                            VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ? ,?)");
    
    $stmt->bind_param("ssssssssssss", $name,  $doctorEmail,$doctorPassword,$role, $latitude, $longitude, $telephone, $doctor_info, $profile_pic, $career_image1, $career_image2, $career_image3);
// Check if the profile was saved
if ($stmt->execute()) {
    echo 'success'; // Send success message back to AJAX
} else {
    echo 'Error: ' . $stmt->error; // Send error message back to AJAX
}
    $stmt->close();
    $conn->close();
}
