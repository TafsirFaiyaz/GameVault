<?php
// Include database connection
include 'db_connect.php';

// Get the search query from the user input
$search_query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';

if ($search_query != '') {
    // SQL query to search for games by title, case-insensitive
    $query = "SELECT * FROM games WHERE LOWER(title) LIKE LOWER('%$search_query%')";
    $result = mysqli_query($conn, $query);
    $num_results = mysqli_num_rows($result);
} else {
    $num_results = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="top-rated">
    <h2>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
    <div class="games-container">
        <?php
        if ($num_results > 0) {
            while ($game = mysqli_fetch_assoc($result)) {
                echo "<div class='game-item'>";
                echo "<a href='game_details.php?game_id=" . $game['id'] . "'>"; // Link to game details page
                echo "<img src='" . $game['image_path'] . "' alt='" . $game['title'] . "'>";
                echo "<p>" . $game['title'] . "</p>";
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No results found for \"" . htmlspecialchars($search_query) . "\"</p>";
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
