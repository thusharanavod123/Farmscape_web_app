<?php
session_start();

// Check if AI technician is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ai_technician') {
    header("Location: login.php");
    exit();
}

$technician_id = $_SESSION['user_id']; // Get technician ID from session

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
    <title>Add AI Treatment</title>
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
        input[type="date"],
        textarea {
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
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function validateForm() {
            let cow_id = document.forms["aiForm"]["cow_id"].value;
            let farmer_id = document.forms["aiForm"]["farmer_id"].value;
            let note = document.forms["aiForm"]["note"].value;

            if (cow_id === "" || farmer_id === "" || note === "") {
                alert("Cow ID, Farmer ID, and Treatment Note are required fields.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Add AI Treatment Details</h2>
        <form name="aiForm" id="aiForm" onsubmit="return validateForm()" method="POST">
            <label for="cow_id">Cow ID:</label>
            <input type="number" name="cow_id" required>

            <label for="farmer_id">Farmer ID:</label>
            <input type="number" name="farmer_id" required>

            <label for="note">Treatment Note:</label>
            <textarea name="note" required></textarea>

            <label for="next_due_date">Next Due Date:</label>
            <input type="date" name="next_due_date">

            <button type="submit">Submit</button>
            <div id="loading" class="loading">Submitting...</div>
        </form>
    </div>

    <script>
        document.getElementById("aiForm").addEventListener("submit", function (e) {
            e.preventDefault(); // Prevent the default form submission

            let cow_id = document.forms["aiForm"]["cow_id"].value;
            let farmer_id = document.forms["aiForm"]["farmer_id"].value;
            let note = document.forms["aiForm"]["note"].value;
            let next_due_date = document.forms["aiForm"]["next_due_date"].value;
            let technician_id = "<?php echo $technician_id; ?>"; // Get technician ID from PHP session

            const loadingDiv = document.getElementById("loading");
            loadingDiv.style.display = "block"; // Show loading spinner

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "process_ai_treatment.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    loadingDiv.style.display = "none"; // Hide loading spinner
                    if (xhr.status === 200) {
                        alert("AI treatment details added successfully.");
                        document.getElementById("aiForm").reset(); // Reset the form
                    } else {
                        alert("Error: Unable to add AI treatment details.");
                    }
                }
            };

            xhr.send("cow_id=" + cow_id + "&farmer_id=" + farmer_id + "&technician_id=" + technician_id + "&note=" + note + "&next_due_date=" + next_due_date);
        });
    </script>
</body>
</html>
