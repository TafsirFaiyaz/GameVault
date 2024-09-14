<?php
// Include database connection
include 'db_connect.php';

// Handle the form submission to determine which table to display
$tableType = isset($_POST['table_type']) ? $_POST['table_type'] : 'game';

$type = isset($_GET['type']) ? $_GET['type'] : 'game';

// Fetch data based on the table type
if ($type === 'character') {
    $sql = "SELECT image_path, name, game_title, username FROM characters_request";
} else {
    $sql = "SELECT title, image_path, release_date, username FROM games_request";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Process</title>
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

form select {
    padding: 5px;
    font-size: 1rem;
}

/* Links */
a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Headings */


h2 {
    color: #333;
    border-bottom: 2px solid #333;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

    </style>
    
</head>
<body>
    <?php include "header.php"; ?>

    <div class="container">
        <h1>Request Process</h1>

        <!-- Form to select the table -->
        <form method="get" action="request_process.php">
            <label for="type">Select Table:</label>
            <select name="type" id="type" onchange="this.form.submit()">
                <option value="game" <?php if ($type === 'game') echo 'selected'; ?>>Game Requests</option>
                <option value="character" <?php if ($type === 'character') echo 'selected'; ?>>Character Requests</option>
            </select>
        </form>

        <br>

        <!-- Display the selected table -->
        <?php if ($type === "game") { ?>
            <h2>Game Requests</h2>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Release Date</th>
                        <th>Username</th>
                        <th>View Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Game Image" width="100"></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></a></td>
                                <td><?php echo htmlspecialchars($row['release_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><a href="game_request_process.php?title=<?php echo urlencode($row['title']); ?>">See More</a></td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5">No game requests found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <h2>Character Requests</h2>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Character Name</th>
                        <th>Game Title</th>
                        <th>Username</th>
                        <th>View Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Character Image" width="100"></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['game_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><a href="character_request_process.php?name=<?php echo urlencode($row['name']); ?>">See More</a></td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5">No character requests found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

    <?php include "footer.php"; ?>
</body>
</html>


<?php
// Close database connection
$conn->close();
?>
