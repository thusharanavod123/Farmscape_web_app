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
    // Get form data
    $health_date = $_POST['health_date'];
    $cow_id = $_POST['cow_id'];
    $farmer_id = $_POST['farmer_id'];
    $doctor_id = $_POST['doctor_id']; // Doctor ID from session
    $medicine = $_POST['medicine'];
    $note = $_POST['note'];

    // Prepare and bind
    $sql = "INSERT INTO Animal_health (created_at, cow_id, farmer_id, doctor_id, medicine, note) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisss", $health_date, $cow_id, $farmer_id, $doctor_id, $medicine, $note);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
        // Redirect or provide a success message
        header("Location: DoctorProfile.php?success=1"); // Redirect back with success
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
