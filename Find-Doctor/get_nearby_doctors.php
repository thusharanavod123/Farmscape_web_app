<?php

session_start();

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $sql = "SELECT id, username, latitude, longitude,telephone FROM users WHERE role = 'doctor' AND latitude IS NOT NULL AND longitude IS NOT NULL";
    $result = $conn->query($sql);

    $doctors = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
    }

    echo json_encode($doctors);
}

$conn->close();

