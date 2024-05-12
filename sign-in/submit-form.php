<?php
$servername = "localhost";
$username = "root"; // default username for XAMPP
$password = ""; // default password for XAMPP is empty
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO user_details (name, email, phone, userid, password, otp) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $name, $email, $phone, $userid, $password, $otp);

// Set parameters and execute
$name = $_POST['Name:'];
$email = $_POST['Email:'];
$phone = $_POST['Phone Number:'];
$userid = $_POST['User ID:'];
$password = $_POST['Password:']; // Remember to hash passwords before storing them
$otp = $_POST['OTP:'];

$stmt->execute();

echo "New records created successfully";

$stmt->close();
$conn->close();
?>
