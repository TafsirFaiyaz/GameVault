<?php
// Include database connection
include 'db_connect.php';

// Handle table selections
$table = isset($_GET['table']) ? $_GET['table'] : null;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Handle game removal
if (isset($_POST['remove_game'])) {
    $game_id = $_POST['game_id'];
    $deleteGameQuery = "DELETE FROM games WHERE id = $game_id";
    mysqli_query($conn, $deleteGameQuery);
}

// Handle user removal
if (isset($_POST['remove_user'])) {
    $user_id = $_POST['user_id'];
    $deleteUserQuery = "DELETE FROM users WHERE id = $user_id";
    mysqli_query($conn, $deleteUserQuery);
}

// Handle character removal
if (isset($_POST['remove_character'])) {
    $character_id = $_POST['character_id'];
    $deleteCharacterQuery = "DELETE FROM characters WHERE id = $character_id";
    mysqli_query($conn, $deleteCharacterQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        /* Add to your styles.css file */

body {
    font-family: Arial, sans-serif;
    background-color: #2c2c2c; /* Dark background */
    color: #f1f1f1; /* Light text color */
    margin: 0;
    padding: 0;
}

h1, h2 {
    color: #f5a623; /* Gold color for headings */
    text-align: center;
}

form {
    margin: 20px auto;
    max-width: 600px;
    padding: 10px;
    background-color: #3a3a3a; /* Slightly lighter dark background for forms */
    border-radius: 8px;
}

label {
    font-weight: bold;
    color: #f5a623; /* Gold color for labels */
}

select, input[type="text"], button {
    font-size: 16px;
    padding: 8px;
    margin: 5px 0;
    border: 1px solid #444; /* Dark border for form elements */
    border-radius: 4px;
}

select {
    width: 100%;
    background-color: #3a3a3a; /* Match form background */
    color: #f1f1f1; /* Light text color */
}

input[type="text"] {
    width: calc(100% - 18px); /* Adjust width to fit button */
    background-color: #3a3a3a;
    color: #f1f1f1;
}

button {
    background-color: #f5a623; /* Gold button */
    color: #fff; /* White text on button */
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #d89f1b; /* Darker gold on hover */
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table, th, td {
    border: 1px solid #444; /* Dark border for table */
    
}

th, td {
    padding: 10px;
    text-align: center;
    color: white;
}

th {
    background-color: #3a3a3a; /* Dark background for table headers */
    color: #f5a623; /* Gold text color for headers */
}

tr:nth-child(even) {
    background-color: #3a3a3a; /* Alternating row colors */
}

tr:nth-child(odd) {
    background-color: #2c2c2c;
}

img {
    max-width: 100px; /* Size for images in tables */
    border-radius: 4px;
}



    </style>

</head>
<body>
<?php include "header.php"; ?>

    <h1>Admin Panel</h1>

    <!-- Show Table Option -->
    <form method="GET" action="admin_panel.php">
        <label for="table">Select Table:</label>
        <select name="table" id="table">
            <option value="games" <?php if($table == 'games') echo 'selected'; ?>>Games</option>
            <option value="users" <?php if($table == 'users') echo 'selected'; ?>>Users</option>
            <option value="characters" <?php if($table == 'characters') echo 'selected'; ?>>Characters</option>
        </select>
        <input type="text" name="search" placeholder="Search..." value="<?php echo $searchTerm; ?>">
        <button type="submit">Show</button>
    </form>

    <!-- Table Display -->
    <?php if ($table == 'games'): ?>
        <h2>Games Table</h2>
        <table>
            <thead>
                <tr>
                    <th>Game Image</th>
                    <th>Title</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT id, image_path, title FROM games WHERE title LIKE '%$searchTerm%'";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><img src="<?php echo $row['image_path']; ?>" alt="Game Image" width="50"></td>
                        <td><?php echo $row['title']; ?></td>
                        <td>
                            <form method="POST" action="admin_panel.php?table=games">
                                <input type="hidden" name="game_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="remove_game">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($table == 'users'): ?>
        <h2>Users Table</h2>
        <table>
            <thead>
                <tr>
                    <th>Profile Image</th>
                    <th>Username</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT id, profile_image, username FROM users WHERE username LIKE '%$searchTerm%'";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><img src="Assets/user_image/<?php echo $row['profile_image']; ?>" alt="User Image" width="50"></td>
                        <td><?php echo $row['username']; ?></td>
                        <td>
                            <form method="POST" action="admin_panel.php?table=users">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="remove_user">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($table == 'characters'): ?>
        <h2>Characters Table</h2>
        <table>
            <thead>
                <tr>
                    <th>Character Image</th>
                    <th>Name</th>
                    <th>Game Title</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT characters.id, characters.image_path, characters.name, characters.game_title AS game_title 
                          FROM characters 
                          WHERE characters.name LIKE '%$searchTerm%'";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><img src="<?php echo $row['image_path']; ?>" alt="Character Image" width="50"></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['game_title']; ?></td>
                        <td>
                            <form method="POST" action="admin_panel.php?table=characters">
                                <input type="hidden" name="character_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="remove_character">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <?php include "footer.php"; ?>
</body>
</html>
