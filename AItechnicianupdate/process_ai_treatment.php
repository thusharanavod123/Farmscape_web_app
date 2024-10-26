<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ai_technician') {
    header("Location: login.php");
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

// Check if the form data is set
if (isset($_POST['cow_id'], $_POST['farmer_id'], $_POST['technician_id'], $_POST['note'])) {
    $cow_id = $_POST['cow_id'];
    $farmer_id = $_POST['farmer_id'];
    $technician_id = $_POST['technician_id'];
    $note = $_POST['note'];
    $next_due_date = isset($_POST['next_due_date']) ? $_POST['next_due_date'] : null;

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO AI_management (cow_id, farmer_id, technician_id, note, next_due_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $cow_id, $farmer_id, $technician_id, $note, $next_due_date);

    if ($stmt->execute()) {
        echo "AI treatment details added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Required fields are missing.";
}

$conn->close();
