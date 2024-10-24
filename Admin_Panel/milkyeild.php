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

// Query to get total milk yield by user and farm
$sql = "
    SELECT 
        u.id AS user_id,
        u.username,
        u.farm_id,
        SUM(m.yield) AS total_milk_yield
    FROM 
        milk_yield m
    JOIN 
        users u ON m.user_id = u.id
    WHERE 
        u.role = 'farmer'
    GROUP BY 
        u.id, u.farm_id
";

$result = $conn->query($sql);

$milkData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $milkData[] = $row;
    }
}

// Return JSON data
header('Content-Type: application/json');
echo json_encode(['success' => true, 'data' => $milkData]);

$conn->close();
?>
