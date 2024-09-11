<?php

include "db_connect.php";

// Fetching games
$sql_games = "SELECT g.id, g.title, g.image_path FROM Games g";
$result_games = $conn->query($sql_games);

// Fetching characters
$sql_characters = "SELECT c.name, c.image_path FROM Characters c";
$result_characters = $conn->query($sql_characters);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamevault - Discover & Rate Your Favorite Games</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <section class="hero">
        <div class="hero-content">
            <h2>Discover & Rate Your Favorite Games</h2>
            <p>Join a community of gamers, share your thoughts, and find the next big hit.</p>
            <a href="#sign-up" class="cta-button">Get Started</a>
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
                    echo "<img src='" . $row['image_path'] . "' alt='" . $row['name'] . "'>";
                    echo "<p>" . $row['name'] . "</p>";
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
            <div class="genre-item">Action</div>
            <div class="genre-item">Adventure</div>
            <div class="genre-item">RPG</div>
            <div class="genre-item">Strategy</div>
            <!-- Add more genres as needed -->
        </div>
    </section>

    <section id="community" class="community">
        <h2>Join Our Community</h2>
        <p>Engage with like-minded gamers, share your favorite moments, and stay updated on the latest news.</p>
        <a href="#sign-up" class="cta-button">Join Now</a>
    </section>

    <?php include 'footer.php'; ?>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
