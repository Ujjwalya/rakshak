<?php
session_start();
if (isset($_POST['submit'])) {
    // Check if the email is provided
    if (empty($_POST['email'])) {
        echo "Please provide an email address.";
        exit();
    }

    // Check if the email is valid
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please provide a valid email address.";
        exit();
    }

    // Establish a connection to the database
    $mysqli = new mysqli('localhost', 'username', 'password', 'database_name');

    // Check for errors
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
    }

    // Prepare the SQL statement to check if the email exists
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the email exists
    if ($result->num_rows == 0) {
        echo "No account found with that email address.";
        exit();
    }

    // Generate a random token for the password reset
    $token = bin2hex(random_bytes(16));

    // Store the token and the email in the database
    $stmt = $mysqli->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    // Send an email with the reset link
    $to = $email;
    $subject = "Password Reset";
    $message = "Please click on the following link to reset your password:\n\n";
    $message .= "http://yourwebsite.com/reset_password.php?email=" . urlencode($email) . "&token=" . urlencode($token);
    $headers = "From: yourname@yourwebsite.com";
    mail($to, $subject, $message, $headers);

    // Redirect to the login page
    header("Location: login.php");
    exit();
}
?>

<!-- HTML form to input email for password reset -->
<form action="reset_password.php" method="POST">
    <div class="input-field">
        <input type="email" class="input" placeholder="Email" name="email" required>
        <i class='bx bx-mail-send'></i>
    </div>
    <div class="input-field">
        <input type="submit" class="submit" value="Reset Password" name="submit">
    </div>
</form>
