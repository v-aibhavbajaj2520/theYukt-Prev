<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
    $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $otp = filter_input(INPUT_POST, 'otp', FILTER_SANITIZE_NUMBER_INT);

    // Here you can write the data to a database or a file
    // For example, writing to a file named 'form-data.txt'
    $file = fopen("form-data.txt", "a") or die("Unable to open file!");
    $txt = "Name: $name\nEmail: $email\nPhone: $phone\nUserID: $userid\nPassword: $password\nOTP: $otp\n";
    fwrite($file, $txt);
    fclose($file);

    // Redirect to a new page or display a success message
    echo "Form submitted successfully!";
}
?>
