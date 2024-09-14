<?php
// Include database connection
include 'db_connect.php';

// Get the title from the query string
$title = isset($_GET['title']) ? $_GET['title'] : '';

// Fetch the game request details based on the title
$sql = "SELECT * FROM games_request WHERE title = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $title);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    echo "No game request found.";
    exit;
}

// Determine the genres
$genres = [];
if ($game['action'] == 1) $genres[] = 'Action';
if ($game['rpg'] == 1) $genres[] = 'RPG';
if ($game['strategy'] == 1) $genres[] = 'Strategy';
if ($game['sports'] == 1) $genres[] = 'Sports';
if ($game['adventure'] == 1) $genres[] = 'Adventure';
if ($game['mystery'] == 1) $genres[] = 'Mystery';

$genres_list = implode(', ', $genres);

// Handle form submission for Accept or Reject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'accept') {
        // Insert into the games table
        $sql = "INSERT INTO games (title, description, release_date, platform, developer, publisher, image_path, action, rpg, strategy, sports, adventure, mystery)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssss", $game['title'], $game['description'], $game['release_date'], $game['platform'], $game['developer'], $game['publisher'], $game['image_path'], $game['action'], $game['rpg'], $game['strategy'], $game['sports'], $game['adventure'], $game['mystery']);
        $stmt->execute();

        // Delete from games_request table
        $sql = "DELETE FROM games_request WHERE title = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $title);
        $stmt->execute();

        // Redirect to request_process.php
        header("Location: request_process.php?type=game");
        exit;
    } elseif ($action === 'reject') {
        // Delete from games_request table
        $sql = "DELETE FROM games_request WHERE title = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $title);
        $stmt->execute();

        // Redirect to request_process.php
        header("Location: request_process.php?type=game");
        exit;
    }
}

// Close database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Request Details</title>
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    color: #333;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

header, footer {
    background-color: #222;
    color: #fff;
    text-align: center;
    padding: 1rem;
}

header a, footer a {
    color: #fff;
    text-decoration: none;
    padding: 0 15px;
}

header a:hover, footer a:hover {
    text-decoration: underline;
}

/* Container for Content */
.container {
    width: 80%;
    margin: auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table th, table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

table th {
    background-color: #333;
    color: #fff;
}

table td img {
    border-radius: 4px;
}

/* Form Styles */
form {
    margin: 20px 0;
}

form label {
    margin-right: 10px;
}

form select, form input[type="submit"] {
    padding: 5px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

form input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
    margin-right: 10px;
}

form input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Headings */


h2 {
    color: #333;
    border-bottom: 2px solid #333;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

/* Buttons */
button, .btn {
    background-color: #007bff;
    border: none;
    color: #fff;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 10px 0;
    cursor: pointer;
    border-radius: 4px;
}

button:hover, .btn:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <?php include "header.php"; ?>

    <div class="container">
        <h1>Game Request Details</h1>

        <h2><?php echo htmlspecialchars($game['title']); ?></h2>
        <img src="<?php echo htmlspecialchars($game['image_path']); ?>" alt="Game Image" width="200">
        <p><strong>Description:</strong> <?php echo htmlspecialchars($game['description']); ?></p>
        <p><strong>Release Date:</strong> <?php echo htmlspecialchars($game['release_date']); ?></p>
        <p><strong>Platform:</strong> <?php echo htmlspecialchars($game['platform']); ?></p>
        <p><strong>Developer:</strong> <?php echo htmlspecialchars($game['developer']); ?></p>
        <p><strong>Publisher:</strong> <?php echo htmlspecialchars($game['publisher']); ?></p>
        <p><strong>Genres:</strong> <?php echo htmlspecialchars($genres_list); ?></p>

        <!-- Buttons for Accept and Reject -->
        <form method="post" action="">
            <input type="hidden" name="action" value="accept">
            <button type="submit" class="btn">Accept</button>
        </form>
        <form method="post" action="">
            <input type="hidden" name="action" value="reject">
            <button type="submit" class="btn">Reject</button>
        </form>
    </div>

    <?php include "footer.php"; ?>
</body>
</html>

<?php $conn->close(); ?>