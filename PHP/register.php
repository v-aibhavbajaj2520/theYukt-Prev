<?php
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set the value of param username
            $param_username = trim($_POST['username']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken"; 
                }
                else{
                    $username = trim($_POST['username']);
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);


// Check for password
if(empty(trim($_POST['password']))){
    $password_err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5){
    $password_err = "Password cannot be less than 5 characters";
}
else{
    $password = trim($_POST['password']);
}

// Check for confirm password field
if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
    $password_err = "Passwords should match";
}


// If there were no errors, go ahead and insert into the database
if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
{
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        // Set these parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Try to execute the query
        if (mysqli_stmt_execute($stmt))
        {
            header("location: login.php");
        }
        else{
            echo "Something went wrong... cannot redirect!";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
}

?>




<!DOCTYPE html>
<html>
<head>
<title>theYukt/Sign In Page</title>
<style>
/* Styles for navigation bar */
.navbar {
  overflow: hidden;
  background-color: #333;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
}

.navbar img.logo, .navbar img.translate {
  height: 50px;
  padding: 5px 20px;
}

.navbar img.logo {
  float: left;
}

.navbar img.translate {
  float: right;
}

/* Push down content since navbar is fixed */
body {
  padding-top: 60px;
  font-family: Arial, sans-serif;
}

/* Existing styles */
.social-login {
  margin-bottom: 20px;
}

.social-button {
  padding: 10px;
  margin-right: 10px;
  border: none;
  cursor: pointer;
}

.social-button img {
  width: 20px;
  height: 20px;
  vertical-align: middle;
}

#otp-section {
  display: none;
}

#otp-message {
  color: green;
  margin-top: 5px;
}
</style>
<script>
function validateEmail() {
  var emailInput = document.getElementById('email');
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple email pattern check
  var otpSection = document.getElementById('otp-section');
  var otpMessage = document.getElementById('otp-message');

  if (!emailPattern.test(emailInput.value)) {
    otpMessage.textContent = 'Please use a proper email ID.';
    otpSection.style.display = 'none';
  } else {
    otpSection.style.display = 'block';
    otpMessage.textContent = 'Please check your email for OTP.';
  }
}

function verifyOTP() {
  // Add your OTP verification logic here
}

function translatePage() {
  // Add your translation logic here
}

// Function to move to the next input field on Enter key press
function handleEnterPress(event, nextFieldId) {
  if(event.key === 'Enter') {
    event.preventDefault(); // Prevent the form from being submitted
    document.getElementById(nextFieldId).focus(); // Move focus to the next field
  }
}

// Function to validate all fields before submitting
function validateForm() {
  var inputs = document.querySelectorAll('input[type=text], input[type=email], input[type=tel], input[type=password]');
  for(var i = 0; i < inputs.length; i++) {
    if(!inputs[i].value) {
      alert('Please fill all the fields before submitting.');
      inputs[i].focus();
      return false;
    }
  }
  return true;
}
</script>
</head>
<body>

<!-- Navigation bar -->
<div class="navbar">
  <img src="images/Logo.png" alt="Logo" class="logo">
  <a href="javascript:void(0);" onclick="translatePage()">
    <img src="images/translate.png" alt="Translate" class="translate">
  </a>
</div>

<!-- Your existing content -->
<h2>Sign In</h2>
<div class="social-login">
  <button class="social-button" onclick="location.href='/auth/google'">
    <img src="images/google-logo.png" alt="Google"> 
  </button>
  <button class="social-button" onclick="location.href='/auth/linkedin'">
    <img src="images/linkedin-logo.png" alt="LinkedIn"> 
  </button>
  <button class="social-button" onclick="location.href='/auth/github'">
    <img src="images/github-logo.png" alt="GitHub"> 
  </button>
</div>

<h3>OR</h3>

<form action="/submit-your-form" method="post" onsubmit="return validateForm()">
  <label for="name">Name:</label><br>
  <input type="text" id="name" name="name" pattern="[A-Za-z\s]+" title="Alphabets only" onkeypress="handleEnterPress(event, 'email')" required><br><br>
  
  <label for="email">Email:</label><br>
  <input type="email" id="email" name="email" onblur="validateEmail()" required><br>
  <span id="otp-message"></span><br>
  
  <div id="otp-section">
    <label for="otp">OTP:</label><br>
    <input type="text" id="otp" name="otp" pattern="\d{6}" title="6-digit number" required><br>
    <button type="button" onclick="verifyOTP()">Verify</button><br><br>
  </div>
  
  <label for="phone">Phone Number:</label><br>
  <input type="tel" id="phone" name="phone" pattern="\d{10}" title="10-digit number" onkeypress="handleEnterPress(event, 'userid')" required><br><br>
  
  <label for="userid">User ID:</label><br>
  <input type="text" id="userid" name="userid" onkeypress="handleEnterPress(event, 'password')" required><br><br>
  
  <label for="password">Password:</label><br>
  <input type="password" id="password" name="password" required><br><br>

  <label for="confirm_password">Confirm Password:</label><br>
  <input type="password" id="confirm_password" name="confirm_password" required><br><br>
  
  <input type="submit" value="Submit">
</form>

</body>
</html>