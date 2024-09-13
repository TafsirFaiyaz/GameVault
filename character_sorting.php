<?php

include "db_connect.php";
// Assuming you have the logged-in user_id
$user_id = $_SESSION['user_id']; // or however you're managing sessions

$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'top';

if ($sortOption == 'top') {
    $charactersQuery = "SELECT characters.*, AVG(character_ratings.rating) as avg_rating, COUNT(character_ratings.user_id) as total_user, 
                        ur.rating as user_rating
                        FROM characters 
                        LEFT JOIN character_ratings ON characters.id = character_ratings.character_id 
                        LEFT JOIN character_ratings as ur ON characters.id = ur.character_id AND ur.user_id = $user_id
                        GROUP BY characters.id 
                        ORDER BY avg_rating DESC LIMIT 10";
} else if ($sortOption == 'popular') {
    $charactersQuery = "SELECT characters.*, AVG(character_ratings.rating) as avg_rating, COUNT(character_ratings.user_id) as total_user, 
                        ur.rating as user_rating
                        FROM characters 
                        LEFT JOIN character_ratings ON characters.id = character_ratings.character_id 
                        LEFT JOIN character_ratings as ur ON characters.id = ur.character_id AND ur.user_id = $user_id
                        GROUP BY characters.id 
                        ORDER BY total_user DESC LIMIT 10";
}

$charactersResult = mysqli_query($conn, $charactersQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Characters</title>
    <style>
        /* Body Styling */
        body {
            background-color: #1e1e1e;
            color: #f0f0f0;
            font-family: 'Roboto', sans-serif;
            margin: 0;            
        }

        .content-wrapper {
            padding: 20px;
        }

        /* Form Sorting Option Styling */
        #sort-form {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        label {
            color: #ffffff;
            font-size: 18px;
            margin-right: 10px;
        }

        select {
            background-color: #2c2c2c;
            color: #ffffff;
            border: 1px solid #4a4a4a;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2c2c2c;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #4a4a4a;
        }

        th {
            background-color: #3a3a3a;
            color: #ffffff;
            font-size: 18px;
            text-transform: uppercase;
        }

        td {
            color: #cccccc;
        }

        tr:nth-child(even) {
            background-color: #242424;
        }

        td img {
            max-width: 70px;
            height: auto;
            border-radius: 5px;
        }

        td:hover {
            background-color: #444444;
        }

        /* Button Hover Effect */
        select:hover {
            background-color: #3e3e3e;
        }

        /* Add hover effect for rows */
        tr:hover {
            background-color: #3b3b3b;
        }

        /* Style for clickable character title */
        a.character-title-link {
            color: #00aaff; /* Light blue color for the link */
            text-decoration: none; /* Remove the underline */
            font-weight: bold; /* Make the title bold */
            transition: color 0.3s ease; /* Smooth color transition on hover */
        }

        /* Hover effect for the clickable character title */
        a.character-title-link:hover {
            color: #ffaa00; /* Change color to orange on hover */
            text-decoration: underline; /* Underline the title when hovered */
        }

        /* Active/visited state of the link */
        a.character-title-link:visited {
            color: #66bbff; /* Slightly different color for visited links */
        }

        /* Responsive Table for Mobile Devices */
        @media screen and (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead {
                display: none;
            }
            tr {
                margin-bottom: 15px;
            }
            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                padding-left: 10px;
                font-weight: bold;
            }
        }
    </style>
</head>

<body>

<?php include "header.php"?>

<div class="content-wrapper">
    <!-- Sorting Form -->
    <form method="GET" id="sort-form">
        <label for="sort-options">Sort by:</label>
        <select name="sort" id="sort-options" onchange="document.getElementById('sort-form').submit()">
            <option value="top" <?php if ($sortOption == 'top') echo 'selected'; ?>>Top Characters</option>
            <option value="popular" <?php if ($sortOption == 'popular') echo 'selected'; ?>>Most Popular</option>
        </select>
    </form>

    <!-- Character List Table -->
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Image</th>
                <th>Title</th>
                <th>Game</th> <!-- New column for Game -->
                <th>Character Rating</th>
                <th>User Rating</th>
                <th>Total Players</th>
            </tr>
        </thead>
        <tbody>
    <?php 
    $rank = 1; // Initialize rank counter
    while ($row = mysqli_fetch_assoc($charactersResult)): ?>
    <tr>
        <td data-label="Rank"><?php echo $rank++; ?></td>
        <td data-label="Image"><img src="<?php echo $row['image_path']; ?>" alt="Character Image"></td>
        <td data-label="Character Name">
            <a href="character_details.php?id=<?php echo $row['id']; ?>" class="character-title-link">
                <?php echo $row['name']; ?>
            </a>
        </td>
        <td data-label="Game"><?php echo htmlspecialchars($row['game_title']); ?></td> <!-- Display Game Title -->
        <td data-label="Character Rating"><?php echo number_format($row['avg_rating'], 1); ?></td>
        <td data-label="User Rating">
            <?php 
            if ($row['user_rating']) {
                echo number_format($row['user_rating'], 1); 
            } else {
                echo "Not Rated";
            }
            ?>
        </td>
        <td data-label="Total Players"><?php echo $row['total_user']; ?></td>
    </tr>
    <?php endwhile; ?>
</tbody>
    </table>
</div>

<?php include "footer.php"?>

</body>
</html>
