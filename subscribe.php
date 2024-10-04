<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password
$dbname = "newsletter_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the email from the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['subscriber_email'], FILTER_SANITIZE_EMAIL);

    // Check if the email contains '@'
    if (strpos($email, '@') !== false) {
        // Insert the email into the database
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            // If subscription is successful, redirect to the homepage
            $stmt->close();
            $conn->close();
            header("Location: Home Page.html"); // Redirect to the homepage
            exit(); // Ensure no further code is executed
        } else {
            // If database insertion fails, show an error message
            $error_message = "Error: Could not complete subscription.";
        }

        $stmt->close();
    } else {
        // If email does not contain '@', show an error message
        $error_message = "Invalid email: must contain '@'.";
    }
}