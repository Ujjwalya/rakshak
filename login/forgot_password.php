<?php
// Establish a connection to the database
$mysqli = new mysqli('localhost', 'username', 'password', 'database_name');

// Check for errors
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

// Check if the forgot password form has been submitted
if (isset($_POST['submit'])) {

    // Get the email or username from the form
    $email_or_username = $_POST['email_or_username'];

    // Prepare the SQL statement to check if the email or username exists in the database
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email_or_username, $email_or_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {

        // User exists, so generate a password reset token and send it to their email
        $token = bin2hex(random_bytes(16)); // Generate a random token
        $email = $result->fetch_assoc()['email']; // Get the user's email from the database

        // Update the user's record in the database with the new token
        $stmt = $mysqli->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Send the password reset email
        $to = $email;
        $subject = "Password reset request";
        $message = "Hello,\n\nPlease click on the following link to reset your password: http://example.com/reset_password.php?token=$token\n\nIf you did not request a password reset, please ignore this email.\n\nBest regards,\nThe Example Team";
        $headers = "From: example@example.com";
        mail($to, $subject, $message, $headers);

        // Redirect the user to the reset password page
        header("Location: reset_password.php?email=$email");
        exit();

    } else {

        // User doesn't exist, so display an error message
        echo "Invalid email or username";

    }

    // Close the statement and database connection
    $stmt->close();
    $mysqli->close();

}
?>
