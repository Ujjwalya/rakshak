<?php
// Establish a connection to the database
$mysqli = new mysqli('localhost', 'root', '', 'login');

// Check for errors
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

// Check if the create account form has been submitted
if (isset($_POST['submit'])) {

    // Get the username, password, name, and email from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Prepare the SQL statement
    $stmt = $mysqli->prepare("INSERT INTO users (username, password, name, email) VALUES (?, ?, ?, ?)");

    // Bind the parameters
    $stmt->bind_param("ssss", $username, $password, $name, $email);

    // Execute the statement
    $stmt->execute();

    // Close the statement and database connection
    $stmt->close();
    $mysqli->close();

    // Redirect to the login page
    header("Location: login.html");
    exit();
}
?>