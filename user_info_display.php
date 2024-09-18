<?php
include 'db_connect.php';
// Get the friend's user ID from the URL parameter
if (!isset($_GET['user_id'])) {
    die("User ID parameter missing.");
}
$friend_user_id = intval($_GET['user_id']); 
// Query to fetch profile image and username
$query4 = "SELECT username, profile_image FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query4);
mysqli_stmt_bind_param($stmt, "i", $friend_user_id);
mysqli_stmt_execute($stmt);
$result4 = mysqli_stmt_get_result($stmt);


if (!$result4) {
    die("Query failed: " . mysqli_error($conn));
}
$data4 = mysqli_fetch_assoc($result4);
if (!$data4) {
    die("No user found with ID $friend_user_id.");
}
// Extract profile image and username
$profile_image2 = $data4['profile_image'];
$username2 = $data4['username'];
// Query to fetch mean rating, completed games, and planned games
$query = "
    SELECT 
        AVG(rating) as mean_rating, 
        COUNT(ratings.game_id) as completed_games,
        (SELECT COUNT(user_id) FROM user_planned_list WHERE user_id = ? AND game_id NOT IN (SELECT game_id FROM ratings WHERE user_id = ?)) as planned_games
    FROM ratings 
    WHERE ratings.user_id = ?
";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iii", $friend_user_id, $friend_user_id, $friend_user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
$data = mysqli_fetch_assoc($result);
// Extract rating data
$mean_rating = $data['mean_rating'];
$completed_games = $data['completed_games'];
$planned_games = $data['planned_games'];
// Query to fetch favorite games
$query3 = "
    SELECT g.image_path, g.title 
    FROM favourite_games fg
    JOIN games g ON fg.game_id = g.id
    WHERE fg.user_id = ?
    LIMIT 5
";
$stmt = mysqli_prepare($conn, $query3);
mysqli_stmt_bind_param($stmt, "i", $friend_user_id);
mysqli_stmt_execute($stmt);
$result3 = mysqli_stmt_get_result($stmt);
if (!$result3) {
    die("Query failed: " . mysqli_error($conn));
}
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
    <title><?php echo htmlspecialchars($username); ?>'s Profile</title>
    <link rel="stylesheet" href="user_profile_styles.css">
</head>
<body>
    
    <?php include "header.php"; ?>
 
    <div class="profile-container">
        <div class="left-section">
            <!-- Display the profile image -->
            <img src="Assets/user_image/<?php echo htmlspecialchars($profile_image2); ?>" alt="Profile Picture" class="profile-pic">
            <!-- Display the username -->
            <h2 class="username"><?php echo htmlspecialchars($username2); ?></h2>
            <button class="game-list-btn">
    <a href="user_game_list.php?user_id=<?php echo urlencode($friend_user_id); ?>">Game List</a>
</button>

        </div>
        
        <div class="right-section">
            <div class="stats-box">
                <p>Mean Rating: <span id="mean-rating"><?php echo round($mean_rating, 2); ?></span></p>
                <p>Games Completed: <span id="completed-games"><?php echo $completed_games; ?></span></p>
                <p>Plans to Play: <span id="planned-games"><?php echo $planned_games; ?></span></p>
            </div>
            
            <!-- Favourite Games Section -->
            <div class="favourite-games">
                <h3>Favourite Games</h3>
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
<?php
mysqli_close($conn);
?>
