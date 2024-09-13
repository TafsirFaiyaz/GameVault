<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Gamevault</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your main CSS file -->
    <style>
        /* Reuse the styles from the login page to maintain consistency */
        body {
            background-color: #1c1c1e; 
            color: #f5f5f7;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background-color: #2a2a2d;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .register-container h1 {
            margin-bottom: 20px;
            color: #f5c518;
        }
        .register-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #3b3b3d;
            color: #fff;
        }
        .register-container button {
            background-color: #f5c518;
            border: none;
            color: #1c1c1e;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            width: 106%;
            transition: background-color 0.3s ease;
        }
        .register-container button:hover {
            background-color: #e5a318;
        }
        .register-container a {
            color: #f5c518;
            text-decoration: none;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Create an Account</h1>
        <form action="register_process.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="file" name="profile_image" accept="image/*">
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
