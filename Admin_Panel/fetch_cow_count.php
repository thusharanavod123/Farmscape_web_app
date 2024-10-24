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

// Query to get total cow count by user (farmer) and farm
$sql = "
    SELECT 
        u.farm_id,
        u.username,
        SUM(c.number_of_cows) AS total_cows
    FROM 
        cages c
    JOIN 
        users u ON c.user_id = u.id
    WHERE 
        u.role = 'farmer'
    GROUP BY 
        u.farm_id
";

$result = $conn->query($sql);

$cowData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cowData[] = $row;
    }
}

// Return JSON data
header('Content-Type: application/json');
echo json_encode(['success' => true, 'data' => $cowData]);

$conn->close();
