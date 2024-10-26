<?php
// Replace 'your_password_here' with the actual password
$password = 'admin123'; 
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed Password: " . $hashed_password;
