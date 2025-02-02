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
  farm_id INT UNIQUE DEFAULT NULL, -- Farm ID for farmers only
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
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique primary key
    cow_id INT NOT NULL, 
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
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique primary key
    cow_id INT NOT NULL,
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
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique primary key
    cow_id INT NOT NULL ,
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
  cage_number VARCHAR(50) NOT NULL , 
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
    Image VARCHAR(255),
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if(mysqli_query($conn,$sql6)){
  echo"Table 'marketPlace' created successfully or already exists<br>";
}else{
  
  echo "Error creating table 'marketPlace': " . mysqli_error($conn) . "<br>";

}

$sql7 = "CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if(mysqli_query($conn,$sql7)){
echo"Table 'messages' created successfully or already exists<br>";
}else{

echo "Error creating table 'messages': " . mysqli_error($conn) . "<br>";

}


$sql8 = "CREATE TABLE IF NOT EXISTS AI_management (
  id INT AUTO_INCREMENT PRIMARY KEY,          -- Unique primary key
  cow_id INT NOT NULL,                        -- Cow ID related to AI management
  farmer_id INT NOT NULL,                     -- Farmer ID
  technician_id INT NOT NULL,                 -- AI technician ID
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the entry is created
  note TEXT,                                  -- Any relevant notes
  next_due_date DATE,                         -- Date for the next AI-related task
  FOREIGN KEY (farmer_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,    -- Foreign key for farmer
  FOREIGN KEY (technician_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE -- Foreign key for AI technician
)";

if (mysqli_query($conn, $sql8)) {
echo "Table 'AI_management' created successfully or already exists<br>";
} else {
echo "Error creating table 'AI_management': " . mysqli_error($conn) . "<br>";
}

$sql9 = "CREATE TABLE IF NOT EXISTS Animal_health (
  id INT AUTO_INCREMENT PRIMARY KEY,           -- Unique primary key
  cow_id INT NOT NULL,                          -- Cow ID
  farmer_id INT NOT NULL,                       -- Farmer ID
  doctor_id INT NOT NULL,                       -- Doctor ID (person who is adding data)
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp when the entry is created
  medicine VARCHAR(225),                        -- Medicine name
  note TEXT,                                    -- Any relevant notes

  FOREIGN KEY (farmer_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE, -- Foreign key for farmer
  FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE  -- Foreign key for doctor
)";

if (mysqli_query($conn, $sql9)) {
  echo "Table 'Animal_health' created successfully or already exists<br>";
} else {
  echo "Error creating table 'Animal_health': " . mysqli_error($conn) . "<br>";
}


// Close connection
mysqli_close($conn);