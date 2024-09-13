<?php
// Include database connection
include 'db_connect.php';

// Start session to use session variables
; // Make sure this line is included if using session variables

// Get the character ID from the URL
$character_id = intval($_GET['id']); // Ensure the ID is an integer
$user_id = $_SESSION['user_id']; // Ensure session is started and user_id is set



// Query to fetch character details
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

// Query to get character rank
$rank_sql = "
    SELECT rank FROM (
        SELECT c.id, c.name, AVG(r.rating) AS avg_rating, 
        RANK() OVER (ORDER BY AVG(r.rating) DESC) AS rank
        FROM characters c
        LEFT JOIN character_ratings r ON c.id = r.character_id
        GROUP BY c.id
    ) ranked_characters
    WHERE id = ?";
$stmt = $conn->prepare($rank_sql);
$stmt->bind_param("i", $character_id);
$stmt->execute();
$rank_result = $stmt->get_result();
$char_rank = $rank_result->fetch_assoc()['rank'] ?? 'Not ranked';

// Query to get average rating
$avg_rating_sql = "SELECT AVG(rating) as avg_rating FROM character_ratings WHERE character_id = ?";
$stmt = $conn->prepare($avg_rating_sql);
$stmt->bind_param("i", $character_id);
$stmt->execute();
$avg_rating_result = $stmt->get_result();
$avg_rating = $avg_rating_result->fetch_assoc()['avg_rating'];

// Set a default value if there are no ratings
$avg_rating_display = $avg_rating ? round($avg_rating, 1) : 'No ratings yet';

// Fetch the user's rating for the character
$user_rating_sql = "SELECT rating FROM character_ratings WHERE user_id = ? AND character_id = ?";
$stmt = $conn->prepare($user_rating_sql);
$stmt->bind_param("ii", $user_id, $character_id);
$stmt->execute();
$user_rating_result = $stmt->get_result();
$user_rating = $user_rating_result->fetch_assoc()['rating'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($character_name); ?> - Gamevault Character</title>
    <link rel="stylesheet" href="character_details_styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="character-details">
        <div class="character-info">
            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($character_name); ?>">
            <div class="character-description">
                <h2><?php echo htmlspecialchars($character_name); ?></h2>
                <h3>Game: <?php echo htmlspecialchars($game_title); ?></h3>
                <p><?php echo htmlspecialchars($game_description); ?></p>
            </div>
        </div>
    </section>

    <section class="rating-section">
        <div class="gamevault-rating">
            <h3>Gamevault Rating</h3>
            <div class="rating-score"><?php echo htmlspecialchars($avg_rating_display); ?>/5<br><br></div>
            <h3>Ranking</h3>
            <div class="rating-score">#<?php echo htmlspecialchars($char_rank); ?></div>
        </div>

        <div class="user-rating">
            <h3>Your Rating</h3>
            <?php if ($user_rating !== null): ?>
                <p class="rating-score"><?php echo htmlspecialchars($user_rating); ?>/5</p>
            <?php else: ?>
                <p class="rating-score">Not Rated</p>
            <?php endif; ?>

            <!-- Rating Form -->
            <form action="submit_character_rating.php" method="POST" class="rating-form">
                <label for="rating">Rate this character:</label>
                <select id="rating" name="rating" required>
                    <option value="1" <?php echo ($user_rating == 1) ? 'selected' : ''; ?>>1 - Very Bad</option>
                    <option value="2" <?php echo ($user_rating == 2) ? 'selected' : ''; ?>>2 - Bad</option>
                    <option value="3" <?php echo ($user_rating == 3) ? 'selected' : ''; ?>>3 - Average</option>
                    <option value="4" <?php echo ($user_rating == 4) ? 'selected' : ''; ?>>4 - Good</option>
                    <option value="5" <?php echo ($user_rating == 5) ? 'selected' : ''; ?>>5 - Very Good</option>
                </select>
                <input type="hidden" name="character_id" value="<?php echo htmlspecialchars($character_id); ?>">
                <button type="submit" class="rating-submit"><?php echo $user_rating !== null ? 'Update Rating' : 'Submit Rating'; ?></button>
            </form>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
