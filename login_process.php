<?php
// Start session to store login state
session_start();

// Connect to the database
$conn = mysqli_connect('localhost', 'Tafsir', '', 'gamevault');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect the form data and sanitize
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the email exists
    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Fetch user data
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user info in session and redirect to index.php
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit();
        } else {
            // Incorrect password
            echo "Incorrect password.";
        }
    } else {
        // Email not found
        echo "No account found with that email.";
    }
}

// Close the connection
mysqli_close($conn);
?>