<?php
$conn = mysqli_connect('localhost', 'Tafsir', '', 'gamevault');
session_start();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

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

$avg_rating_sql = "SELECT AVG(rating) as avg_rating FROM ratings WHERE game_id = $game_id";
$avg_rating_result = $conn->query($avg_rating_sql);
$avg_rating = $avg_rating_result->fetch_assoc()['avg_rating'] ?? 'No ratings yet';

// Fetch the user's rating for the game, if it exists
$user_rating_sql = "SELECT rating FROM ratings WHERE user_id = $userid AND game_id = $game_id";
$user_rating_result = $conn->query($user_rating_sql);
$user_rating = $user_rating_result->fetch_assoc()['rating'] ?? null;
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
                

                <section class="rating-section">
                    <div class="gamevault-rating">
                        <h3>Gamevault Rating</h3>
                        <div class="rating-score"><?php echo $avg_rating ? round($avg_rating, 1) : 'N/A'; ?>/5</div>
                    </div>

                    <div class="user-rating">
                        <h3>Your Rating</h3>
                        <?php if ($user_rating): ?>
                            <p class="rating-score"><?php echo $user_rating; ?>/5</p>
                        <?php else: ?>
                            <p class="rating-score">Not Rated</p>
                        <?php endif; ?>

                        <!-- Form for submitting or updating user rating -->
                        <form action="submit_rating.php" method="POST">
                            <label for="rating">Rate this game (1-5):</label>
                            <input type="number" id="rating" name="rating" min="1" max="5" value="<?php echo $user_rating ?? ''; ?>" required>
                            <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
                            <button type="submit"><?php echo $user_rating ? 'Update Rating' : 'Submit Rating'; ?></button>
                        </form>
                    </div>
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
