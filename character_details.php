<?php
// Include database connection
include 'db_connect.php';

// Get the character ID from the URL
$character_id = $_GET['id'];

// Query to fetch character details along with game information
$query = "SELECT *
          FROM characters
          WHERE characters.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $character_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if a character was found
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $character_name = $row['name'];
    $image_path = $row["image_path"];
    $game_title = $row['game_title'];
    $game_description = $row['description'];
} else {
    echo "Character not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Character Details</title>
    <style>
        /* Base styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Container for the character details */
        .character-container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Character image styling */
        .character-container img {
            max-width: 100%;
            height: 450px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Title and text styling */
        h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        h2 {
            color: #555;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        /* Additional styles for responsive design */
        @media (max-width: 768px) {
            .character-container {
                padding: 20px;
            }

            h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="character-container">
        <img src="<?php echo $image_path; ?>" alt="<?php echo $character_name; ?>">
        <h1><?php echo htmlspecialchars($character_name); ?></h1>
        <h2>Game: <?php echo htmlspecialchars($game_title); ?></h2>
        <p><?php echo htmlspecialchars($game_description); ?></p>
    </div>
</body>
</html>
