<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: AItechnicianupdate.html");
    exit();
}

// Check if the user is an AI technician
if ($_SESSION['role'] !== 'ai_technician') {
    header("Location: AItechnicianupdate.html");
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

$user_id = $_SESSION['user_id'];
$username1 = $_SESSION['username'];
$sql = "SELECT id, email, profile_pic FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username1);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = $user['email'];
    $id = $user['id'];
    $profile_pic = $user['profile_pic'];
} else {
    echo "User not found";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Technician Profile</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/fontawesome.min.css">
    <style>
        .container1 {
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            padding: 15px;
            color: white;
            position: fixed;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px 0;
            display: flex;
            align-items: center;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            width: 100%;
        }

        .sidebar ul li a:hover {
            background-color: #444;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 0;
        }

        /* Button styles */
        .nav-button {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(45deg, #ff8a00, #e52e71);
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .nav-button:hover {
            background: linear-gradient(45deg, #e52e71, #ff8a00);
        }

        .profile-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-picture img {
            width: 100px; /* Adjust as needed */
            height: 100px; /* Adjust as needed */
            border-radius: 50%;
            margin-right: 20px;
        }

        .profile-details h3 {
            margin: 0;
        }

        .profile-details p {
            margin: 5px 0;
        }

        .button-container {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }
    </style>
</head>
<body>
    <section class="sub-header">
        <nav>
            <a href="../index.html"><img src="../images/logo1.png" alt="Farmscape Logo"></a>
            <div class="nav-links" id="navLinks">
                <i class="fa fa-times" onclick="hideMenu()"></i>
                <ul>
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="../About_Us/About.html">About Us</a></li>
                    <li><a href="../Product_Page/productpage.html">Sell & Buy</a></li>
                    <li><a href="">Growth</a></li>
                </ul>
            </div>
            <i class="fa fa-bars" onclick="showMenu()"></i>
        </nav>
        <h1>Welcome  <?php echo $_SESSION['username']; ?>, to Your AI Technician Profile</h1>
        <p>Email: <?php echo $email; ?></p>
        <p>User ID: <?php echo $id; ?></p>
    </section>

    <div class="container1">
      

       <!-- Main Content -->
<div class="main-content" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; height: 30vh;">
    <h2>AI Technician Dashboard</h2>
    <p>Manage your AI treatments and reports here.</p>

    <!-- Button Container -->
    <div class="button-container" style="display: flex; gap: 15px; justify-content: center;">
        <a href="view_ai_treatment.php" class="nav-button">View AI Reports</a>
        <a href="add_ai_treatment.php" class="nav-button">Add AI Treatment</a>
    </div>
</div>

    </div>

    <script>
        document.getElementById('ai-management').classList.add('active');
    </script>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h2>FARMSCAPE</h2>
                </div>
                <div class="footer-sections">
                    <div class="footer-col">
                        <h4>Company</h4>
                        <ul>
                            <li><a href="../About_Us/About.html">About Us</a></li>
                            <li><a href="../Contact_Us/ContactUs.html">Contact Us</a></li>
                            <li><a href="../privacypolicy/privacypolicy.html">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Get help</h4>
                        <ul>
                            <li><a href="../Find-Doctor/finddoctor.html">Find Doctors</a></li>
                            <li><a href="../Technician/TechnicianProfile.php">Your Profile</a></li>
                            <li><a href="../Showdoctor_Page/showdoctorpage.html">Doctors</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Online Shop</h4>
                        <ul>
                            <li><a href="../SellerStore/sellerstore.html">Become a Seller</a></li>
                            <li><a href="../Product_Page/productpage.html">Product Page</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 FARMSCAPE. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
