<?php

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

// Fetch data from marketPlace table
$sql = "SELECT id, Title, Price, Description, Image FROM marketPlace";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convert the image blob to base64 for rendering
        $row['Image'] = base64_encode($row['Image']);
        $products[] = [
            'id' => $row['id'],
            'title' => $row['Title'],
            'cost' => floatval($row['Price']), // Ensure 'cost' is a float
            'description' => $row['Description'],
            'image' => 'data:image/jpeg;base64,' . $row['Image'], // Assuming image is in JPEG format
        ];
    }
}

// Output the data as JSON
header('Content-Type: application/json');
echo json_encode($products);

// Close the connection
$conn->close(); 
