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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View AI Treatments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="number"],
        button {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        #results {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
    </style>
    <script>
        function fetchAIDetails() {
            const cow_id = document.getElementById('cow_id').value;
            const farmer_id = document.getElementById('farmer_id').value;
            const resultsDiv = document.getElementById("results");
            const loadingDiv = document.getElementById("loading");

            resultsDiv.innerHTML = ""; // Clear previous results
            loadingDiv.style.display = "block"; // Show loading spinner

            let xhr = new XMLHttpRequest();
            xhr.open("GET", "view_ai_treatment_results.php?cow_id=" + cow_id + "&farmer_id=" + farmer_id, true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    loadingDiv.style.display = "none"; // Hide loading spinner
                    if (xhr.status === 200) {
                        resultsDiv.innerHTML = xhr.responseText;
                    } else {
                        resultsDiv.innerHTML = "<p style='color: red;'>Error fetching data. Please try again.</p>";
                    }
                }
            };

            xhr.send();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>View AI Treatments</h2>
        <label for="cow_id">Cow ID (optional):</label>
        <input type="number" id="cow_id" name="cow_id" placeholder="Enter Cow ID">

        <label for="farmer_id">Farmer ID (optional):</label>
        <input type="number" id="farmer_id" name="farmer_id" placeholder="Enter Farmer ID">

        <button onclick="fetchAIDetails()">Search</button>

        <div id="loading" class="loading">Loading...</div>
        <div id="results"></div>
    </div>
</body>
</html>
