

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Data</title>
    
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Your PHP logic and HTML content -->
    <?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: Farmer.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmscape";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Function to trigger SweetAlert and redirect
function triggerSweetAlert($message, $type, $redirectUrl = 'AddData.html') {
    echo "<script type='text/javascript'>
        Swal.fire({
            title: '$message',
            icon: '$type',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '$redirectUrl';
            }
        });
    </script>";
}

// Handle Feeding Management Data Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_feeding'])) {
    $cow_id = $_POST['cow_id'];
    $feed_type = $_POST['feed_type'];
    $quantity = $_POST['quantity'];
    $frequency = $_POST['frequency'];

    $sql = "INSERT INTO feeding_management (user_id, cow_id, feed_type, quantity, frequency) VALUES ('$user_id', '$cow_id', '$feed_type', '$quantity', '$frequency')";

    if ($conn->query($sql) === TRUE) {
        triggerSweetAlert('Feeding data added successfully', 'success');
    } else {
        triggerSweetAlert('Error: ' . $conn->error, 'error');
    }
}

// Handle Milk Yield Data Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_milk_yield'])) {
    $date = $_POST['milk_date'];
    $cow_id = $_POST['milk_cow_id'];
    $yield = $_POST['milk_yield'];

    $sql = "INSERT INTO milk_yield (user_id, date, cow_id, yield) VALUES ('$user_id', '$date', '$cow_id', '$yield')";

    if ($conn->query($sql) === TRUE) {
        triggerSweetAlert('Milk yield data added successfully', 'success');
    } else {
        triggerSweetAlert('Error: ' . $conn->error, 'error');
    }
}

// Handle Vaccination Schedule Data Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_vaccine'])) {
    $date = $_POST['vaccine_date'];
    $cow_id = $_POST['vaccine_cow_id'];
    $vaccine = $_POST['vaccine'];
    $next_due_date = $_POST['next_due_date'];
    $symptoms = $_POST['symptoms'];

    $sql = "INSERT INTO vaccination_schedule (user_id, date, cow_id, vaccine, next_due_date, symptoms) VALUES ('$user_id', '$date', '$cow_id', '$vaccine', '$next_due_date', '$symptoms')";

    if ($conn->query($sql) === TRUE) {
        triggerSweetAlert('Vaccination schedule data added successfully', 'success');
    } else {
        triggerSweetAlert('Error: ' . $conn->error, 'error');
    }
}


// Handle cage Data Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_cage'])) {
    $cage_number = $_POST['cage_number'];
    $number_of_cows = $_POST['number_of_cows'];
   

    $sql = "INSERT INTO cages (user_id, cage_number, number_of_cows) VALUES ('$user_id', '$cage_number', '$number_of_cows')";

    if ($conn->query($sql) === TRUE) {
        triggerSweetAlert('cage data added successfully', 'success');
    } else {
        triggerSweetAlert('Error: ' . $conn->error, 'error');
    }
}

$conn->close();
?>
    

</body>
</html>
