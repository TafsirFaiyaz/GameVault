<?php

include "db_connect.php";

// Fetching games
$sql_games = "    SELECT g.id, g.title, g.image_path, AVG(r.rating) AS avg_rating
    FROM Games g
    LEFT JOIN ratings r ON g.id = r.game_id
    GROUP BY g.id, g.title, g.image_path
    ORDER BY avg_rating DESC
    LIMIT 4";
$result_games = $conn->query($sql_games);

// Fetching characters
$sql_characters = "SELECT c.id, c.name, c.image_path, AVG(r.rating) AS avg_rating
    FROM characters c
    LEFT JOIN character_ratings r ON c.id = r.character_id
    GROUP BY c.id, c.name, c.image_path
    ORDER BY avg_rating DESC
    LIMIT 4";

$result_characters = $conn->query($sql_characters);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamevault - Discover & Rate Your Favorite Games</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="https://img.icons8.com/?size=100&id=69446&format=png&color=000000">
</head>
<body>

    <?php include 'header.php'; ?>

    <section class="hero">
        <div class="hero-content">
            <h2>Discover & Rate Your Favorite Games</h2>
            <p>Join a community of gamers, share your thoughts, and find the next big hit.</p>
            <a href="game_sorted.php" class="cta-button">Get Started</a>
        </div>
    </section>

    <section id="features" class="features">
        <h2>Why Choose Gamevault?</h2>
        <div class="feature-cards">
            <div class="card">
                <h3>Rate & Review</h3>
                <p>Share your opinion on games and read reviews from others.</p>
            </div>
            <div class="card">
                <h3>Top Rated Games</h3>
                <p>Explore the highest-rated games in various genres.</p>
            </div>
            <div class="card">
                <h3>Join Communities</h3>
                <p>Connect with other gamers and participate in discussions.</p>
            </div>
        </div>
    </section>

    <!-- Top Rated Games Section -->
    <section id="top-rated" class="top-rated">
    <h2>Top Rated Games</h2>
    <div class="games-container">
        <?php
        if ($result_games->num_rows > 0) {
            while ($row = $result_games->fetch_assoc()) {
                echo "<div class='game-item'>";
                echo "<a href='game_details.php?game_id=" . $row['id'] . "'>"; // Link to game details page
                echo "<img src='" . $row['image_path'] . "' alt='" . $row['title'] . "'>";
                echo "<p>" . $row['title'] . "</p>";
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No games available.</p>";
        }
        ?>
    </div>
</section>

<!-- Top Rated Characters Section -->
<section id="top-char" class="top-rated">
    <h2>Top Rated Characters</h2>
    <div class="games-container">
        <?php
        if ($result_characters->num_rows > 0) {
            while ($row = $result_characters->fetch_assoc()) {
                echo "<div class='game-item'>";
                // Correct the URL for character details page
                echo "<a href='character_details.php?id=" . $row['id'] . "'>";
                echo "<img src='" . $row['image_path'] . "' alt='" . $row['name'] . "'>";
                echo "<p>" . $row['name'] . "</p>";
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No characters available.</p>";
        }
        ?>
    </div>
</section>


<section id="genres" class="genres">
    <h2>Explore by Genre</h2>
    <div class="genre-list">
        <!-- Make each genre item a clickable link with query parameters -->
        <a href="genre_sorting.php" class="genre-item">Action</a>
        <a href="genre_sorting.php" class="genre-item">Adventure</a>
        <a href="genre_sorting.php" class="genre-item">RPG</a>
        <a href="genre_sorting.php" class="genre-item">Sports</a>
        <!-- Add more genres as needed -->
    </div>
</section>



    <?php include 'footer.php'; ?>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
