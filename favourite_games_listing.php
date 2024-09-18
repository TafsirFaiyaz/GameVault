<?php
include 'db_connect.php';

$user_id = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['remove_favorite'])) {
        $game_id = $_POST['game_id'];
        $delete_query = "DELETE FROM favourite_games WHERE user_id = $user_id AND game_id = $game_id";
        mysqli_query($conn, $delete_query);
    } else if(isset($_POST['add_favorite'])){
        $game_id = $_POST['game_id'];
        // username ar fvrt game dekhbo
        $count_query = "SELECT COUNT(*) as count FROM favourite_games WHERE user_id = $user_id";
        $count_result = mysqli_query($conn, $count_query);
        if (!$count_result) {
            die("Count query failed: " . mysqli_error($conn));
        }
        $count = mysqli_fetch_assoc($count_result)['count'];
        
        if ($count < 5) {
            $insert_query = "INSERT INTO favourite_games (user_id, game_id) VALUES ($user_id, $game_id)";
            if (!mysqli_query($conn, $insert_query)) {
                die("Insert query failed: " . mysqli_error($conn));
            }
        } else {
            $error_message = "You can only have 5 favorite games. Please remove a game before adding a new one.";
        }

    }
}


$favorite_games_query = "
    SELECT g.id, g.title, g.image_path
    FROM favourite_games fg
    JOIN games g ON fg.game_id = g.id
    WHERE fg.user_id = $user_id
";
$favorite_games_result = mysqli_query($conn, $favorite_games_query);


$favorite_game_ids = array();
while ($favorite_game = mysqli_fetch_assoc($favorite_games_result)) {
    $favorite_game_ids[] = $favorite_game['id'];
}
mysqli_data_seek($favorite_games_result, 0);


$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$all_games_query = "
    SELECT id, title, image_path
    FROM games
    WHERE title LIKE '%$search_query%' " . 
    (empty($favorite_game_ids) ? "" : "AND id NOT IN (" . implode(',', $favorite_game_ids) . ")") . "
    LIMIT 10
";
$all_games_result = mysqli_query($conn, $all_games_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Favorite Games</title>
    <link rel="stylesheet" href="user_profile_styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #e0e0e0;
            background-color: #121212;
        }
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h2, h3 {
            color: #bb86fc;
        }
        .game-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }
        .game-item {
            text-align: center;
            width: 150px;
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            padding: 10px;
            transition: transform 0.3s ease;
        }
        .game-item:hover {
            transform: translateY(-5px);
        }
        .game-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .game-item p {
            margin: 5px 0;
            font-weight: bold;
        }
        .search-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .search-form input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #333;
            border-radius: 4px;
            background-color: #2c2c2c;
            color: #e0e0e0;
        }
        .search-form button,
        .game-item button {
            padding: 10px 15px;
            background-color: #bb86fc;
            color: #121212;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-form button:hover,
        .game-item button:hover {
            background-color: #9965f4;
        }
        .game-item button[name="remove_favorite"] {
            background-color: #cf6679;
        }
        .game-item button[name="remove_favorite"]:hover {
            background-color: #b4495f;
        }
        .error-message {
            color: #cf6679;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>

    <div class="content-wrapper">
        <h2>Edit Favorite Games</h2>

        <?php
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        ?>

        <form class="search-form" action="" method="GET">
            <input type="text" name="search" placeholder="Search for games" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
        </form>

        <h3>Current Favorites (<?php echo mysqli_num_rows($favorite_games_result); ?>/5)</h3>
        <div class="game-list">
            <?php
            while ($game = mysqli_fetch_assoc($favorite_games_result)) {
                echo "<div class='game-item'>";
                echo "<img src='" . htmlspecialchars($game['image_path']) . "' alt='" . htmlspecialchars($game['title']) . "'>";
                echo "<p>" . htmlspecialchars($game['title']) . "</p>";
                echo "<form method='POST'>"; 
                echo "<input type='hidden' name='game_id' value='" . $game['id'] . "'>";
                echo "<button type='submit' name='remove_favorite'>Remove</button>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>

        <h3>Search Results</h3>
        <div class="game-list">
            <?php
            while ($game = mysqli_fetch_assoc($all_games_result)) {
                echo "<div class='game-item'>";
                echo "<img src='" . htmlspecialchars($game['image_path']) . "' alt='" . htmlspecialchars($game['title']) . "'>";
                echo "<p>" . htmlspecialchars($game['title']) . "</p>";
                echo "<form method='POST'>"; 
                echo "<input type='hidden' name='game_id' value='" . $game['id'] . "'>";
                echo "<button type='submit' name='add_favorite'" . (mysqli_num_rows($favorite_games_result) >= 5 ? " disabled" : "") . ">Add to Favorites</button>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <?php include "footer.php"; ?>
</body>
</html>
