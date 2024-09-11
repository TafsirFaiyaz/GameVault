<?php

include '../db_connect.php';

$user_id = $_SESSION['user_id']; // Assuming user ID is stored in session

// Fetch mean rating, completed games, and planned games
$query = "SELECT AVG(rating) as mean_rating, COUNT(user_id) as completed_games FROM ratings WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

$mean_rating = $data['mean_rating'];
$completed_games = $data['completed_games'];

$query2 = "SELECT COUNT(user_id) as planned_games FROM user_planned_list WHERE user_id = $user_id";
$result2 = mysqli_query($conn, $query2);
$data2 = mysqli_fetch_assoc($result2);

$planned_games = $data2["planned_games"]


// Fetch favorite games
//$fav_games_query = "SELECT game_name FROM favorite_games WHERE user_id = $user_id";
//$fav_games_result = mysqli_query($conn, $fav_games_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="user_profile_styles.css">
</head>
<body>

    <?php include "../header.php"; ?>
 
    <div class="profile-container">
        <div class="left-section">
            <img src="../Assets/Charecters/arthur.webp" alt="Profile Picture" class="profile-pic">
            <h2 class="username">User Name</h2>
            <button class="game-list-btn">Game List</button>
            <button class="friend-list-btn">Friend List</button>
        </div>


<div class="right-section">
    <div class="stats-box">
        <p>Mean Rating: <span id="mean-rating"><?php echo round($mean_rating, 2); ?></span></p>
        <p>Games Completed: <span id="completed-games"><?php echo $completed_games; ?></span></p>
        <p>Plans to Play: <span id="planned-games"><?php echo $planned_games; ?></span></p>
    </div>
<!--
    <div class="favorite-games">
        <h3>Favorite Games</h3>
        <div class="game-grid">
            <?php // while ($game = mysqli_fetch_assoc($fav_games_result)) { ?>
                <div class="game-item"><?php //echo $game['game_name']; ?></div>
            <?php //} ?>
        </div>
    </div>
-->

</body>
</html>

