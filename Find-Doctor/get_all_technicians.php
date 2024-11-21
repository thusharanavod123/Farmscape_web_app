<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, username, latitude, longitude, telephone,additional_info FROM users WHERE role = 'ai_technician' AND latitude IS NOT NULL AND longitude IS NOT NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $technicians = [];
    while ($row = $result->fetch_assoc()) {
        $technicians[] = $row;
    }
    echo json_encode($technicians);
} else {
    echo json_encode([]);
}
$conn->close();
