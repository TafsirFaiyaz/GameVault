<?php

include 'db_connect.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $platform = $_POST['platform'];

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $target_dir = "Assets/Charecters/";
    $target_file = $target_dir . basename($image_name);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insert game data into the database
        $query = "INSERT INTO characters (name, description, game_title,image_path)
                  VALUES ('$title', '$description','$platform', '$target_file')";

        if (mysqli_query($conn, $query)) {
            if ($_SESSION['role'] == 'admin') {
                echo "<script>alert('Game was successfully uploaded.'); window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Request has been sent for approval.'); window.location.href = 'index.php';</script>";
            }
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading the image.";
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
