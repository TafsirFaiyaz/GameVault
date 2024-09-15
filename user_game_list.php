<?php
include 'db_connect.php'; // Include your MySQLi connection file

 // Start the session to access session variables
$user_id = $_SESSION['user_id']; // Assuming the user is logged in

// Determine sorting option
$sort_option = isset($_POST['sort_option']) ? $_POST['sort_option'] : 'completed';

if ($sort_option === 'completed') {
    // Fetch games that the user has rated
    $query = "SELECT g.id, g.title, g.image_path, r.rating as user_rating
              FROM games g
              JOIN ratings r ON g.id = r.game_id
              WHERE r.user_id = $user_id
              GROUP BY g.id";
} else {
    // Fetch games that are planned to play and not rated
    $query = "SELECT g.id, g.title, g.image_path
              FROM games g
              LEFT JOIN user_planned_list u ON g.id = u.game_id
              WHERE u.user_id = $user_id AND g.id NOT IN (SELECT game_id FROM ratings WHERE user_id = $user_id)
              GROUP BY g.id";
}

$result = mysqli_query($conn, $query); // Execute the query

// Check for query errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch Gamevault rating (average rating for each game) only for 'completed'
$gamevault_ratings = [];
if ($sort_option === 'completed') {
    $avg_rating_query = "SELECT game_id, AVG(rating) as avg_rating FROM ratings GROUP BY game_id";
    $avg_rating_result = mysqli_query($conn, $avg_rating_query);

    while ($row = mysqli_fetch_assoc($avg_rating_result)) {
        $gamevault_ratings[$row['game_id']] = $row['avg_rating'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamevault - My Games</title>
    <style>
        /* General Styles */
        body {
    margin: 0;
    font-family: 'Arial', sans-serif;
    background-color: #121212;
    color: #f0f0f0;
}

.container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    background-color: #1e1e1e;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

h1 {
    color: #ffcc00;
    text-align: center;
    margin-bottom: 20px;
}

form {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

select {
    padding: 8px;
    background-color: #333;
    color: #fff;
    border: 1px solid #555;
    border-radius: 5px;
}

button {
    padding: 8px 15px;
    background-color: #ff4500;
    color: #fff;
    border: none;
    border-radius: 5px;
    margin-left: 10px;
    cursor: pointer;
}

button:hover {
    background-color: #ff5722;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #2b2b2b;
    color: #ffcc00;
}

td {
    background-color: #2e2e2e;
    color: white
}

.game-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

a {
    color: #ffcc00;
    text-decoration: none;
}

a:hover {
    color: #ff5722;
}

.sortedforted{
    color:white;
}

    </style>

</head>
<body>

<?php include "header.php"; ?>

    <div class="container">
        <h1>My Games</h1>
        
        <!-- Sorting Form -->
        <form method="POST" action="">
            <label for="sort_option" >Sort By:</label>
            <select name="sort_option" id="sort_option">
                <option value="completed" <?php echo $sort_option == 'completed' ? 'selected' : ''; ?>>  Completed</option>
                <option value="plan_to_play" <?php echo $sort_option == 'plan_to_play' ? 'selected' : ''; ?>>  Plan to Play</option>
            </select>
            <button type="submit">Sort</button>
        </form>
        
        <!-- Games Table -->
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <?php if ($sort_option === 'completed'): ?>
                        <th>Gamevault Rating</th>
                        <th>Your Rating</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($game = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><img src="<?php echo $game['image_path']; ?>" alt="<?php echo $game['title']; ?>" class="game-img"></td>
                        <td><a href="game_details.php?game_id=<?php echo $game['id']; ?>"><?php echo $game['title']; ?></a></td>
                        
                        <?php if ($sort_option === 'completed'): ?>
                            <td>
                                <?php 
                                    // Show the average rating if it exists
                                    echo isset($gamevault_ratings[$game['id']]) ? number_format($gamevault_ratings[$game['id']], 1) : 'No Rating'; 
                                ?>
                            </td>
                            <td><?php echo isset($game['user_rating']) ? $game['user_rating'] : 'Not Rated'; ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include "footer.php"; ?>

</body>
</html>

<?php
// Close the connection
mysqli_close($conn);
?>
