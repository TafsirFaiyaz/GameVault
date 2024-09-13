<?php
include 'db_connect.php';

$userid = $_SESSION['user_id']; 
$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : 0;

// Fetch the details of the selected game
$sql = "SELECT g.title, g.image_path, g.description, g.platform, g.release_date, g.publisher FROM Games g WHERE g.id = $game_id";
$result = $conn->query($sql);

// Check if the game exists
if ($result->num_rows > 0) {
    $game = $result->fetch_assoc();
} else {
    echo "<p>Game not found.</p>";
    exit;
}


// SQL query to fetch the game's rank based on its average rating
$rank_sql = "
    SELECT rank FROM (
        SELECT g.id, g.title, AVG(r.rating) AS avg_rating, 
        RANK() OVER (ORDER BY AVG(r.rating) DESC) AS rank
        FROM Games g
        LEFT JOIN ratings r ON g.id = r.game_id
        GROUP BY g.id
    ) ranked_games
    WHERE id = $game_id";
    
$rank_result = $conn->query($rank_sql);
$game_rank = $rank_result->fetch_assoc()['rank'] ?? 'Not ranked';


// Fetch the average rating for the game
$avg_rating_sql = "SELECT AVG(rating) as avg_rating FROM ratings WHERE game_id = $game_id";
$avg_rating_result = $conn->query($avg_rating_sql);
$avg_rating = $avg_rating_result->fetch_assoc()['avg_rating'];

// Set a default value if there are no ratings
$avg_rating_display = $avg_rating ? round($avg_rating, 1) : 'No ratings yet';

// Fetch the user's rating for the game, if it exists
$user_rating_sql = "SELECT rating FROM ratings WHERE user_id = $userid AND game_id = $game_id";
$user_rating_result = $conn->query($user_rating_sql);
$user_rating = $user_rating_result->fetch_assoc()['rating'] ?? null;

$check_playlist_sql = "SELECT * FROM user_planned_list WHERE user_id = $userid AND game_id = $game_id";
$playlist_result = $conn->query($check_playlist_sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $game['title']; ?> - Gamevault</title>
    <link rel="stylesheet" href="game_details_styles.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <section class="game-details">
        <div class="game-info">
            <img src="<?php echo $game['image_path']; ?>" alt="<?php echo $game['title']; ?>">
            <div class="game-description">

            <div class="playlist-section">
        <?php if ($playlist_result->num_rows > 0): ?>
            <p class="playlist-status">Already added to Playlist</p>
        <?php else: ?>
            <form action="add_to_playlist.php" method="POST">
                <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
                <button type="submit" class="playlist-button">Add to Playlist</button>
            </form>
        <?php endif; ?>
    </div>

<section class="rating-section">
    <div class="gamevault-rating">
        <h3>Gamevault Rating</h3>
        <div class="rating-score"><?php echo $avg_rating_display; ?>/5<br><br></div>
        <h3>Ranking</h3>
        <div class="rating-score">#<?php echo $game_rank; ?></div>


    </div>

    

    <div class="user-rating">
        <h3>Your Rating</h3>
        <?php if ($user_rating): ?>
            <p class="rating-score"><?php echo $user_rating; ?>/5</p>
        <?php else: ?>
            <p class="rating-score">Not Rated</p>
        <?php endif; ?>

        <!-- Rating Form -->
        <form action="submit_rating.php" method="POST" class="rating-form">
            <label for="rating">Rate this game:</label>
            <select id="rating" name="rating" required>
                <option value="1" <?php echo ($user_rating == 1) ? 'selected' : ''; ?>>1 - Very Bad</option>
                <option value="2" <?php echo ($user_rating == 2) ? 'selected' : ''; ?>>2 - Bad</option>
                <option value="3" <?php echo ($user_rating == 3) ? 'selected' : ''; ?>>3 - Average</option>
                <option value="4" <?php echo ($user_rating == 4) ? 'selected' : ''; ?>>4 - Good</option>
                <option value="5" <?php echo ($user_rating == 5) ? 'selected' : ''; ?>>5 - Very Good</option>
            </select>
            <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
            <button type="submit" class="rating-submit"><?php echo $user_rating ? 'Update Rating' : 'Submit Rating'; ?></button>
        </form>
    </div>

    <!-- Playlist Button Below Rating Section -->

</section>


            <h2><?php echo $game['title']; ?></h2>
            <p><?php echo $game['description']; ?></p>
            <div class="details">
                <div><strong>Platform:</strong> <?php echo $game['platform']; ?></div>
                <div><strong>Release Date:</strong> <?php echo $game['release_date']; ?></div>
                <div><strong>Publisher:</strong> <?php echo $game['publisher']; ?></div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
