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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT id, username, latitude, longitude, telephone,additional_info FROM users WHERE role = 'doctor'";
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
