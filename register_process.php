<?php
// Start session to handle messages (optional)
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
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if the username or email already exists
    $check_query = "SELECT * FROM users WHERE email = '$email' OR username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($result) > 0) {
        // Fetch the existing data
        $row = mysqli_fetch_assoc($result);
        if ($row['username'] == $username) {
            echo "Username is already taken.";
        }
        if ($row['email'] == $email) {
            echo "Email is already registered.";
        }
    } else {
        // Insert the data into the users table
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        
        if (mysqli_query($conn, $sql)) {
            // Registration successful
            echo "Registration successful. <a href='login.php'>Login here</a>";
        } else {
            // Error inserting the data
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// Close the connection
mysqli_close($conn);
?>
