<?php
session_start();


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to registration page
    header("Location: ../Farmer/Farmer.html");
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
$user_id = $_SESSION['user_id'] ;

$cage = isset($_GET['cage']) ? intval($_GET['cage']) : 0;

if ($cage > 0) {
    // selecting cowNumber according to the provided cageId
    $query = "SELECT number_of_cows FROM cages WHERE cage_number = $cage AND  user_id = ". mysqli_real_escape_string($conn, $user_id);

    // Fetching data from the database
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Assuming one row per cage with cow number count
        $row = $result->fetch_assoc();
        $actualCowCount = $row['number_of_cows'];
    } else {
        $actualCowCount = 0;
    }
    
    // Returning JSON response
    echo json_encode(['actualCowCount' => $actualCowCount]);
} else {
    echo json_encode(['actualCowCount' => 0]);
}

// Close the connection
$conn->close();
