<?php
session_start();

// Check if AI technician is logged in
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

// Initialize query variables
$cow_id = isset($_GET['cow_id']) ? intval($_GET['cow_id']) : null;
$farmer_id = isset($_GET['farmer_id']) ? intval($_GET['farmer_id']) : null;

// Build the query
$query = "SELECT * FROM AI_management WHERE 1 = 1";

if ($cow_id) {
    $query .= " AND cow_id = $cow_id";
}

if ($farmer_id) {
    $query .= " AND farmer_id = $farmer_id";
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Cow ID</th>
                <th>Farmer ID</th>
                <th>Technician ID</th>
                <th>Note</th>
                <th>Next Due Date</th>
                <th>Created At</th>
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['cow_id'] . "</td>
                <td>" . $row['farmer_id'] . "</td>
                <td>" . $row['technician_id'] . "</td>
                <td>" . $row['note'] . "</td>
                <td>" . $row['next_due_date'] . "</td>
                <td>" . $row['created_at'] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No AI treatments found.";
}

$conn->close();
