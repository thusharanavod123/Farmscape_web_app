<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "farmscape"; 

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data from marketPlace table
$query = "SELECT * FROM marketPlace ORDER BY id ASC";
$result = mysqli_query($connection, $query);


$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
       
        $products[] = [
            'id' => $row['id'],
            'title' => $row['Title'],
            'cost' => floatval($row['Price']), // Ensure 'cost' is a float
            'description' => $row['Description'],
            'image' =>  $row['Image'], 
        ];
    }
}

// Output the data as JSON
header('Content-Type: application/json');
echo json_encode($products);

// Close the connection
$connection->close(); 
