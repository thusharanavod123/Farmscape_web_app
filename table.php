<?php
// This file is for table creation only (one-time execution). There is another file to get DB connection.
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
$sql = "CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('doctor', 'farmer', 'ai_technician', 'admin') NOT NULL,
  farm_id INT DEFAULT NULL, -- Farm ID for farmers only
  address VARCHAR(255) DEFAULT NULL,
  telephone VARCHAR(15),
  additional_info TEXT,
  profile_pic VARCHAR(255),
  career_image1 VARCHAR(255),
  career_image2 VARCHAR(255),
  career_image3 VARCHAR(255),
  latitude DECIMAL(10, 8) DEFAULT NULL, -- Optional latitude
  longitude DECIMAL(11, 8) DEFAULT NULL, -- Optional longitude
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
  echo "Table 'users' created successfully or already exists<br>";
} else {
  echo "Error creating table 'users': " . mysqli_error($conn) . "<br>";
}


$sql2="CREATE TABLE IF NOT EXISTS feeding_management (
    cow_id INT UNIQUE PRIMARY KEY,
    user_id INT NOT NULL,
    feed_type VARCHAR(255),
    quantity VARCHAR(255),
    frequency VARCHAR(255),
    note TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE

)";
if (mysqli_query($conn, $sql2)) {
  echo "Table 'feeding_management' created successfully or already exists<br>";
} else {
  echo "Error creating table 'feeding_management': " . mysqli_error($conn) . "<br>";
}


$sql3="CREATE TABLE IF NOT EXISTS vaccination_schedule (
    cow_id INT UNIQUE PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE,
    vaccine VARCHAR(255),
    next_due_date DATE,
    symptoms VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
)";
if (mysqli_query($conn, $sql3)) {
echo "Table 'vaccination_schedule' created successfully or already exists<br>";
} else {
echo "Error creating table 'vaccination_schedule': " . mysqli_error($conn) . "<br>";
}

$sql4="CREATE TABLE IF NOT EXISTS milk_yield (
    cow_id INT UNIQUE PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE,
    yield DECIMAL(10,2),
   FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
)";
if (mysqli_query($conn, $sql4)) {
echo "Table 'milk_yield' created successfully or already exists<br>";
} else {
echo "Error creating table 'milk_yield': " . mysqli_error($conn) . "<br>";
}


$sql5 = "CREATE TABLE IF NOT EXISTS cages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cage_number VARCHAR(50) NOT NULL UNIQUE, -- Unique cage number
  number_of_cows INT NOT NULL, -- Number of cows in the cage
  user_id INT NOT NULL, -- Reference to the users table
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
)";

if (mysqli_query($conn, $sql5)) {
  echo "Table 'cages' created successfully or already exists<br>";
} else {
  echo "Error creating table 'cages': " . mysqli_error($conn) . "<br>";
}


$sql6 = "CREATE TABLE IF NOT EXISTS marketPlace (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    Description TEXT NOT NULL,
    Image LONGBLOB NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if(mysqli_query($conn,$sql6)){
  echo"Table 'marketPlace' created successfully or already exists<br>";
}else{
  
  echo "Error creating table 'marketPlace': " . mysqli_error($conn) . "<br>";

}

// Close connection
mysqli_close($conn);