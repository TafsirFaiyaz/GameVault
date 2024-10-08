<?php

include 'db_connect.php'; // Include your database connection

$user_id = $_SESSION['user_id']; 

$user_query = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id); // Bind user_id
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$username = $user['username'];

// Prepare and execute the query to check if the user is an admin
$admin_query = "SELECT * FROM admin WHERE user_id = ?";
$stmt = $conn->prepare($admin_query);
$stmt->bind_param("i", $user_id); // 'i' for integer (user_id)
$stmt->execute();
$admin_result = $stmt->get_result();

$is_admin = $admin_result->num_rows > 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $platform = $_POST['platform'];

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $target_dir = "Assets/Charecters/";
    $target_file = $target_dir . basename($image_name);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {

        // Prepare the INSERT query based on whether the user is an admin
        if ($is_admin) {
            $query = "INSERT INTO characters (name, description, game_title, image_path) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $title, $description, $platform, $target_file);
        } else {
            $query = "INSERT INTO characters_request (name, description, game_title, image_path, username) VALUES (?, ?, ?, ?,?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $title, $description, $platform, $target_file, $username);
        }


            if ($stmt->execute()) {
                if ($is_admin) {
                    echo "<script>alert('Character was successfully uploaded.'); window.location.href = 'index.php';</script>";
                    
                } else {
                    echo "<script>alert('Request has been sent for approval.'); window.location.href = 'index.php';</script>";
                    
                }
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Game</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1e1e1e;
            color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 50px;
            color: #e50914;
            font-size: 2.5em;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #2b2b2b;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.7);
            border: 1px solid #444;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
            font-size: 1.1em;
            color: #f0f0f0;
        }

        input[type="text"],
        input[type="date"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #444;
            border-radius: 8px;
            background-color: #1c1c1c;
            color: #f0f0f0;
            font-size: 1em;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        input[type="date"] {
            cursor: pointer;
        }

        input[type="file"] {
            padding: 10px;
            cursor: pointer;
        }

        input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #e50914;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #d40812;
        }

        /* Style the file upload button */
        input[type="file"]::-webkit-file-upload-button {
            padding: 10px;
            background-color: #e50914;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="file"]::-webkit-file-upload-button:hover {
            background-color: #d40812;
        }

        /* Add some hover effect for input fields */
        input[type="text"]:focus,
        input[type="date"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #e50914;
            outline: none;
        }

        /* Add a subtle box-shadow to the input fields */
        input[type="text"],
        input[type="date"],
        textarea,
        input[type="file"] {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 20px;
            }

            input[type="submit"] {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <h2>Upload Game</h2>
    <div class="form-container">
        <form action="upload_characters.php" method="post" enctype="multipart/form-data">
            <label for="title">Name:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>



            <label for="platform">Game:</label>
            <input type="text" id="platform" name="platform" required>


            <label for="image">Game Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <input type="submit" name="submit" value="Upload Game">
        </form>
    </div>
</body>
</html>
