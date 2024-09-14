<?php

include 'db_connect.php'; // Include your database connection
 // Start the session to access session variables

$user_id = $_SESSION['user_id'];

// Fetch the username for non-admin users
$user_query = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id); // Bind user_id
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$username = $user['username'];

// Check if the user is an admin
$admin_query = "SELECT * FROM admin WHERE user_id = ?";
$stmt = $conn->prepare($admin_query);
$stmt->bind_param("i", $user_id); // Bind user_id
$stmt->execute();
$admin_result = $stmt->get_result();

$is_admin = $admin_result->num_rows > 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date'];
    $platform = $_POST['platform'];
    $developer = $_POST['developer'];
    $publisher = $_POST['publisher'];

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $target_dir = "Assets/game_images/";
    $target_file = $target_dir . basename($image_name);

    // Handle genre checkboxes
    $genre = $_POST['genre'];
    $action = isset($genre['action']) ? 1 : 0;
    $rpg = isset($genre['rpg']) ? 1 : 0;
    $strategy = isset($genre['strategy']) ? 1 : 0;
    $adventure = isset($genre['adventure']) ? 1 : 0;
    $mystery = isset($genre['mystery']) ? 1 : 0;
    $sports = isset($genre['sports']) ? 1 : 0;

    // Check if the game already exists
    $check_query = "SELECT * FROM games WHERE title = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('A game with this title already exists.'); window.location.href = 'index.php';</script>";
    } else {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Insert the data into the appropriate table based on admin status
            if ($is_admin) {
                $query = "INSERT INTO games (title, description, release_date, platform, developer, publisher, image_path, action, rpg, strategy, adventure, mystery, sports)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssssssiiiii", $title, $description, $release_date, $platform, $developer, $publisher, $target_file, $action, $rpg, $strategy, $adventure, $mystery, $sports);
            } else {
                $query = "INSERT INTO games_request (title, description, release_date, platform, developer, publisher, image_path, action, rpg, strategy, adventure, mystery, sports, username)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssssssiiiiis", $title, $description, $release_date, $platform, $developer, $publisher, $target_file, $action, $rpg, $strategy, $adventure, $mystery, $sports, $username);
            }

            // Execute the query
            if ($stmt->execute()) {
                if ($is_admin) {
                    echo "<script>alert('Game was successfully uploaded.'); window.location.href = 'index.php';</script>";
                } else {
                    echo "<script>alert('Request has been sent for approval.'); window.location.href = 'index.php';</script>";
                }
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading the image.";
        }
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

        /* Styling for the genre section */
        #genre-options {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        #genre-options label {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            background-color: #1c1c1c;
            color: #f0f0f0;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid #444;
            font-size: 1em;
        }

        #genre-options input[type="checkbox"] {
            display: none;
        }

        /* Change background when checkbox is selected */
        #genre-options input[type="checkbox"]:checked + label {
            background-color: #e50914;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            border-color: #e50914;
        }

        /* Hover effect */
        #genre-options label:hover {
            background-color: #333;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
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


        #genre-options {
        flex-direction: column;
        }

        #genre-options label {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Upload Game</h2>
    <div class="form-container">
        <form action="upload_games.php" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="release_date">Release Date:</label>
            <input type="date" id="release_date" name="release_date" required>

            <label for="platform">Platform:</label>
            <input type="text" id="platform" name="platform" required>

            <label for="developer">Developer:</label>
            <input type="text" id="developer" name="developer" required>

            <label for="publisher">Publisher:</label>
            <input type="text" id="publisher" name="publisher" required>



            <label for="genre">Select Genre:</label>


            <div id="genre-options">
                <input type="checkbox" id="action" name="genre[action]" value="1">
                <label for="action">Action</label>

                <input type="checkbox" id="rpg" name="genre[rpg]" value="1">
                <label for="rpg">RPG</label>

                <input type="checkbox" id="strategy" name="genre[strategy]" value="1">
                <label for="strategy">Strategy</label>

                <input type="checkbox" id="adventure" name="genre[adventure]" value="1">
                <label for="adventure">Adventure</label>

                <input type="checkbox" id="mystery" name="genre[mystery]" value="1">
                <label for="mystery">Mystery</label>

                <input type="checkbox" id="sports" name="genre[sports]" value="1">
                <label for="sports">Sports</label>
            </div>


            <label for="image">Game Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <input type="submit" name="submit" value="Upload Game">
        </form>
    </div>
</body>
</html>
