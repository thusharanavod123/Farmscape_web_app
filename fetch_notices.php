<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


// Fetch notices from the database
$sql = "SELECT message FROM messages ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$messages = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row['message'];
    }
}

// Return messages as JSON
header('Content-Type: application/json');
echo json_encode($messages);

