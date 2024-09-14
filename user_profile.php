<?php
include 'db_connect.php';

// Assuming user ID is stored in session
$user_id = $_SESSION['user_id']; 

// Query to fetch mean rating, completed games, planned games, profile image, and username
$query = "
    SELECT 
        AVG(rating) as mean_rating, 
        COUNT(user_id) as completed_games,
        (SELECT COUNT(user_id) FROM user_planned_list WHERE user_id = $user_id AND game_id NOT IN (SELECT game_id FROM ratings WHERE user_id = $user_id)) as planned_games,
        profile_image,
        username
    FROM ratings 
    JOIN users ON ratings.user_id = users.id
    WHERE ratings.user_id = $user_id
";

$query2 = "SELECT username, profile_image FROM users WHERE id = $user_id";
$result2 = mysqli_query($conn, $query2);
$data2 = mysqli_fetch_assoc($result2);

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

$mean_rating = $data['mean_rating'];
$completed_games = $data['completed_games'];
$planned_games = $data['planned_games'];
$profile_image = $data2['profile_image'];
$username = $data2['username'];

// Query to fetch favorite games
$query3 = "
    SELECT g.image_path, g.title 
    FROM favourite_games fg
    JOIN games g ON fg.game_id = g.id
    WHERE fg.user_id = $user_id
    LIMIT 5
";
$result3 = mysqli_query($conn, $query3);
$favourite_games = [];
while ($row = mysqli_fetch_assoc($result3)) {
    $favourite_games[] = $row;
}
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
    
    <?php include "header.php"; ?>
 
    <div class="profile-container">
        <div class="left-section">
            <!-- Display the profile image -->
            <img src="Assets/user_image/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" class="profile-pic">
            <!-- Display the username -->
            <h2 class="username"><?php echo htmlspecialchars($username); ?></h2>
            <button class="game-list-btn">Game List</button>
            <button class="friend-list-btn">Friend List</button>
        </div>
        
        <div class="right-section">
            <div class="stats-box">
                <p>Mean Rating: <span id="mean-rating"><?php echo round($mean_rating, 2); ?></span></p>
                <p>Games Completed: <span id="completed-games"><?php echo $completed_games; ?></span></p>
                <p>Plans to Play: <span id="planned-games"><?php echo $planned_games; ?></span></p>
            </div>
            
            <!-- New Section: Favourite Games -->
            <div class="favourite-games">
                <h3>Favourite Games</h3>
                <a href="favourite_games_listing.php" class="edit-btn">Edit</a> <!-- Updated button to link -->
                <div class="favourite-games-list">
                    <?php foreach ($favourite_games as $game): ?>
                        <div class="favourite-game-item">
                            <img src="<?php echo htmlspecialchars($game['image_path']); ?>" alt="Game Image" width="100">
                            <p><?php echo htmlspecialchars($game['title']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>

