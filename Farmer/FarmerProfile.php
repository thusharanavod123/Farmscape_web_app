<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to registration page
    header("Location: Farmer.html");
    exit();
}

// Check if the user is a farmer
if ($_SESSION['role'] !== 'farmer') {
    // If not a farmer, redirect to a different page (e.g., an error page or home page)
    header("Location: Farmer.html");
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

$user_id = $_SESSION['user_id'] ;

// Fetch Feeding Management Data
$feeding_sql = "SELECT * FROM feeding_management WHERE user_id = ". mysqli_real_escape_string($conn, $user_id);

$feeding_result = mysqli_query($conn, $feeding_sql);

// Fetch Milk Yield Data
$milk_sql = "SELECT * FROM milk_yield WHERE user_id = ". mysqli_real_escape_string($conn, $user_id);
$milk_result = mysqli_query($conn, $milk_sql);

// Fetch Vaccination Schedule Data
$vaccine_sql = "SELECT * FROM vaccination_schedule WHERE user_id = ". mysqli_real_escape_string($conn, $user_id);
$vaccine_result = mysqli_query($conn, $vaccine_sql);

// Fetch Cage Details
$cage_sql = "SELECT * FROM cages WHERE user_id = " . mysqli_real_escape_string($conn, $user_id);
$cage_result = mysqli_query($conn, $cage_sql);



//getting data from DB using session variable 

// Fetch user details using username

// Get the username from session
$username1 = $_SESSION['username'];


$sql = "SELECT id,email,profile_pic FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username1);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch the user data as an associative array
    // Access other fields like email, role, address, etc.
    $email = $user['email'];
    $id = $user['id'];
    $profile_pic = $user['profile_pic'];
    // ... add more fields as required
} else {
    echo "User not found";
}

// Close the connection
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../style.css">
    <title>FARMSCAPE </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/fontawesome.min.css">
    <link rel="stylesheet" href="Farmer.css">
    <link rel="shortcut icon" href="images/fav.png" type="image/svg" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }
    </style>
</head>
<body>
    <section class="sub-header">
        <nav>
            <a href="../index.html"><img src="../images/logo1.png"></a>
            <div class="nav-links" id="navLinks">
                <i class="fa fa-times" onclick="hideMenu()"></i>
                <ul>
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="../About_Us/About.html">About Us</a></li>
                    <li><a href="../Product_Page/productpage.html">Sell & Buy</a></li>
                    <li><a href="FarmerProfile.php">Profile</a></li>
                    <li><a href="vaccination_reminder.html">Notifications</a></li>
                </ul>
            </div>
            <i class="fa fa-bars" onclick="showMenu()"></i>
        </nav>
        <h1>Welcome, <?php echo $_SESSION['username']; ?> For Your Farmer Profile</h1>
        <p>Email: <?php echo $email; ?></p>
    
    <p>User ID: <?php echo $id; ?></p></p>
    </section>
    

    <div class="abc">
        <!-- Display user profile information here -->
        <h2>Your Profile Information</h2>
        <!-- Add additional profile information retrieval and display logic -->

                        <!-- Animal Feeding Management Section -->
                        <div class="container1">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul>
                <li><a href="#" onclick="showSection('feeding-management')">Animal Feeding Management</a></li>
                <li><a href="#" onclick="showSection('milk-yield-tracking')">Milk Yield Tracking</a></li>
                <li><a href="#" onclick="showSection('vaccination-schedule')">Vaccination Schedule Management</a></li>
                <li><a href="#" onclick="showSection('cage-management')">Cage Management</a></li>
                <li><a href="AddData.html" >Add Farm Details</a></li>
                <li><a href="milk_yield_graph.html" >Show Milk Yield Perfomance</a></li>
                
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Animal Feeding Management Section -->
            <div class="section" id="feeding-management">
                <h2>Animal Feeding Management</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Cow ID</th>
                            <th>Feed Type</th>
                            <th>Quantity</th>
                            <th>Frequency</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($feeding_result) > 0) {
                            while($row = mysqli_fetch_assoc($feeding_result)) {
                                echo "<tr>";
                                echo "<td contenteditable='true'>" . $row['cow_id'] . "</td>";
                                echo "<td contenteditable='true'>" . $row['feed_type'] . "</td>";
                                echo "<td contenteditable='true'>" . $row['quantity'] . "</td>";
                                echo "<td contenteditable='true'>" . $row['frequency'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No feeding records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Milk Yield Tracking Section -->
            <div class="section" id="milk-yield-tracking">
                <h2>Milk Yield Tracking</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Cow ID</th>
                            <th>Yield (Liters)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($milk_result) > 0) {
                            while($row = mysqli_fetch_assoc($milk_result)) {
                                echo "<tr>";
                                echo "<td contenteditable='true'>" . $row['date'] . "</td>";
                                echo "<td contenteditable='true'>" . $row['cow_id'] . "</td>";
                                echo "<td contenteditable='true'>" . $row['yield'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No milk yield records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Vaccination Schedule Management Section -->
            <div class="section" id="vaccination-schedule">
                <h2>Vaccination Schedule Management</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Cow ID</th>
                            <th>Vaccine</th>
                            <th>Next Due Date</th>
                            <th>symptoms</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($vaccine_result) > 0) {
                            while($row = mysqli_fetch_assoc($vaccine_result)) {
                                echo "<tr>";
                                echo "<td contenteditable='true'>" . $row['date'] . "</td>";
                                echo "<td contenteditable='true'>" . $row['cow_id'] . "</td>";
                                echo "<td contenteditable='true'>" . $row['vaccine'] . "</td>";
                                echo "<td contenteditable='true'>" . $row['next_due_date'] . "</td>";
                                echo "<td contenteditable='true'>" . $row['symptoms'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No vaccination records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

             <!-- Cage Management Section -->
<div class="section" id="cage-management">
    <h2>Cage Management</h2>
    <table>
        <thead>
            <tr>
                <th>Cage Number</th>
                <th>Number of Cows</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($cage_result) > 0) {
                while($row = mysqli_fetch_assoc($cage_result)) {
                    echo "<tr>";
                    echo "<td contenteditable='true'>" . htmlspecialchars($row['cage_number']) . "</td>";
                    echo "<td contenteditable='true'>" . htmlspecialchars($row['number_of_cows']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No cage records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
        </div>
    </div>

   

    <script>
        function showSection(sectionId) {
            // Hide all sections
            var sections = document.querySelectorAll('.section');
            sections.forEach(function(section) {
                section.classList.remove('active');
            });

            // Show the selected section
            document.getElementById(sectionId).classList.add('active');
        }

        // Show the first section by default
        document.getElementById('feeding-management').classList.add('active');
    </script>
    </div>

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
                            <li><a href="../Farmer/FarmerProfile.php">Your Profile</a></li>
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
