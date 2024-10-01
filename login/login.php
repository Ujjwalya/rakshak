<?php
    ob_start();

// Establish a connection to the database
$mysqli = new mysqli('localhost', 'root', '', 'login');

// Check for errors
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}
// Check if the login form has been submitted
if (isset($_POST['submit'])) {

    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {
        // User exists, so redirect to the dashboard
        header("Location: index.html");
        exit();
    } else {
        // User doesn't exist, so display an error message
        echo "Invalid username or password";
    }

    // Close the statement and database connection
    $stmt->close();
    $mysqli->close();
}
ob_end_flush();
?>
