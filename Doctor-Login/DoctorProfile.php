<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: doctorlogin.html");
    exit();
}

// Check if the user is an AI technician
if ($_SESSION['role'] !== 'doctor') {
    header("Location: doctorlogin.html");
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
    <title>DOCTOR PROFILE</title>

    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #333;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 20px;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #575757;
        }

        /* Main content Styles */
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        h2.form-title {
            margin-top: 0;
        }

        /* Form Styles */
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        input[type="submit"],
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover,
        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .result-display {
            margin-top: 20px;
            padding: 15px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style> 

    <link rel="stylesheet" href="doctorlogin.css">
    
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/fontawesome.min.css">
    <link
    rel="shortcut icon"
    href="images/fav.png"
    type="image/svg">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  />
</head>
<body>
    <section class="sub-header">

        <nav>
            <a herf="../index.html"><img src="../images/logo1.png"></a>
             <div class="nav-links" id="navLinks">
                <i class="fa fa-times" onclick="hideMenu()"></i>
                      <ul>
                        <li><a href="../index.html">Home</a></li>
                        <li><a href="../About_Us/About.html">About Us</a></li>
                        <li><a href="../Product_Page/productpage.html">Sell & Buy</a></li>
                        
                     
                      </ul>
             </div>
             <i class="fa fa-bars" onclick="showMenu()" ></i>
        </nav>
        <h1>Welcome Doctor  <?php echo $_SESSION['username']; ?>, to Your Profile</h1>
        <p>Email: <?php echo $email; ?></p>
        <p>User ID: <?php echo $id; ?></p>
         </section>
<!---------------------------------------------------------------------------------------------------------------------------------------->
<body>
    
<div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Doctor Panel</h2>
            <ul>
                <li><a href="javascript:void(0)" onclick="showSection('search-vaccine')">Search Vaccination</a></li>
                <li><a href="javascript:void(0)" onclick="showSection('add-vaccine')">Add Animal Health Data</a></li>
                <li><a href="javascript:void(0)" onclick="showSection('search-health')">Search Animal Health</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Search Vaccination Details -->
            <section id="search-vaccine" class="form-container">
                <h2 class="form-title">Search Vaccination Details</h2>
                <form id="vaccinationForm" onsubmit="fetchVaccinationDetails(event)">
                    <table>
                        <tr>
                            <th>Cow ID:</th>
                            <td><input type="text" id="cow_id" name="cow_id" required></td>
                        </tr>
                        <tr>
                            <th>Farm ID:</th>
                            <td><input type="text" id="farm_id" name="farm_id" required></td>
                        </tr>
                    </table>
                    <input type="submit" value="Search">
                </form>
                <div id="result" class="result-display"></div>
            </section>

            <section id="add-vaccine" class="form-container">
        <h2 class="form-title">Add Animal Health Data</h2>
        <form method="POST" action="add_animal_health.php"> <!-- Change action to point to your PHP file -->
            <table>
                <tr>
                    <th>Date:</th>
                    <td><input type="date" name="health_date" required></td>
                </tr>
                <tr>
                    <th>Cow ID:</th>
                    <td><input type="text" name="cow_id" required></td>
                </tr>
                <tr>
                    <th>Farmer ID:</th>
                    <td><input type="text" name="farmer_id" required></td> <!-- Farmer ID input -->
                </tr>
                <tr>
                    <th>Doctor ID:</th>
                    <td><input type="text" name="doctor_id" value="<?php echo $_SESSION['user_id']; ?>" readonly></td>
                </tr>
                <tr>
                    <th>Medicine:</th>
                    <td><input type="text" name="medicine" required></td>
                </tr>
                <tr>
                    <th>Note:</th>
                    <td><textarea name="note" rows="4" required></textarea></td>
                </tr>
            </table>
            <button type="submit" name="add_animal_health">Add Animal Health Data</button>
        </form>
    </section>

 <!-- Search animal health Details -->

    <section id="search-health" class="form-container">
                <h2 class="form-title">Search Health Records</h2>
                <form id="vaccinationForm" onsubmit="fetchVaccinationDetails(event)">
                    <table>
                        <tr>
                            <th>Cow ID:</th>
                            <td><input type="text" id="cow_id" name="cow_id" required></td>
                        </tr>
                        <tr>
                            <th>Farmer id:</th>
                            <td><input type="text" id="farmer_id" name="farmer_id" required></td>
                        </tr>
                    </table>
                    <input type="submit" value="Search">
                </form>
                <div id="result" class="result-display"></div>
            </section>


      <!-- Search animal health Details -->
      <section id="search-health" class="form-container">
      <h1>Search Health Records</h1>
    <form id="searchForm">
        <label for="cow_id">Cow ID:</label>
        <input type="number" id="cow_id" required><br><br>

        <label for="farmer_id">Farmer ID:</label>
        <input type="number" id="farmer_id" required><br><br>

        <button type="submit">Search</button>
    </form>

    <div id="results"></div>

            </section>
        </div>
    </div>     
     <script>

document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    const cow_id = document.getElementById('cow_id').value;
    const farmer_id = document.getElementById('farmer_id').value;

    // Basic validation to ensure fields are filled
    if (!cow_id || !farmer_id) {
        alert("Please enter both Cow ID and Farmer ID.");
        return;
    }

    // Make an AJAX request to the PHP script
    fetch('search_health_records.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cow_id=${cow_id}&farmer_id=${farmer_id}`
    })
    .then(response => response.json()) // Parse the response as JSON
    .then(data => {
        const resultsDiv = document.getElementById('results');
        resultsDiv.innerHTML = ''; // Clear previous results

        if (data.error) {
            resultsDiv.innerHTML = `<p>${data.error}</p>`; // Show error message if any
        } else {
            // Loop through the data array to display health records
            data.forEach(record => {
                const recordDiv = document.createElement('div');
                recordDiv.className = 'result';
                recordDiv.innerHTML = `
                    <strong>Health Record ID:</strong> ${record.id}<br>
                    <strong>Cow ID:</strong> ${record.cow_id}<br>
                    <strong>Farmer ID:</strong> ${record.farmer_id}<br>
                    <strong>Doctor:</strong> ${record.doctor_name}<br>
                    <strong>Medicine:</strong> ${record.medicine}<br>
                    <strong>Note:</strong> ${record.note}<br>
                    <strong>Created At:</strong> ${record.created_at}<br>
                `;
                resultsDiv.appendChild(recordDiv); // Append the result div to the results container
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred while fetching the data.");
    });
});



       // Show only the selected section
       function showSection(sectionId) {
            const sections = document.querySelectorAll('.form-container');
            sections.forEach(section => {
                section.style.display = 'none'; // Hide all sections
            });

            document.getElementById(sectionId).style.display = 'block'; // Show the selected section
        }
        
        // Function to handle form validation and fetch details
        function fetchVaccinationDetails(event) {
            event.preventDefault(); // Prevent form submission

            let cowId = document.getElementById("cow_id").value;
            let farmId = document.getElementById("farm_id").value;

            // Simple validation
            if (cowId === "" || farmId === "") {
                alert("Please fill out both Cow ID and Farm ID fields.");
                return;
            }

            // Fetch API to send request to the server
            fetch("search_vaccination.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `cow_id=${cowId}&farm_id=${farmId}`,
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById("result").innerHTML = data;
            })
            .catch(error => console.error("Error fetching data:", error));
        }

        // Initially hide all sections
        document.addEventListener("DOMContentLoaded", function() {
            showSection('search-vaccine'); // Optionally display a default section
        });
    </script>
    
      <!-- SweetAlert2 JavaScript -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <!-- jQuery for AJAX (optional, can be replaced with vanilla JS) -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
     
</body>
</html>
<!-------------------------------------------------------------------------------footer-------------------------------------------->

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
                        <li><a href="../About_Us/About.html">about us</a></li>
                        <li><a href="../Contact_Us/ContactUs.html">Contact Us</a></li>
                        <li><a href="../privacypolicy/privacypolicy.html">privacy policy</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Get help</h4>
                    <ul>
                        <li><a href="../Find-Doctor/finddoctor.html">Find Doctors</a></li>
                        <li><a href="../Farmer/Farmer.html">Your Profile</a></li>
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