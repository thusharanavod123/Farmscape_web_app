<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to access this page.";
    exit();
}

// Check if the user is a doctor
if ($_SESSION['role'] !== 'doctor') {
    echo "Access Denied. You are not authorized to view this page.";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cow_id = $_POST['cow_id'];
    $farm_id = $_POST['farm_id'];

    // Fetch vaccination details for the cow and farm
    $sql = "SELECT vs.cow_id, vs.date, vs.vaccine, vs.next_due_date, vs.symptoms, u.farm_id 
            FROM vaccination_schedule vs
            JOIN users u ON vs.user_id = u.id 
            WHERE vs.cow_id = ? AND u.farm_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cow_id, $farm_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Vaccination Details for Cow ID: $cow_id and Farm ID: $farm_id</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Cow ID</th><th>Date</th><th>Vaccine</th><th>Next Due Date</th><th>Symptoms</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['cow_id'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['vaccine'] . "</td>";
            echo "<td>" . $row['next_due_date'] . "</td>";
            echo "<td>" . $row['symptoms'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No vaccination records found for Cow ID: $cow_id and Farm ID: $farm_id.";
    }

    $stmt->close();
}

$conn->close();
