<?php
// PHP code remains the same as in the previous version
// Establish database connection
include "db_connect.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize genre filters
$selected_genres = [];
if (isset($_POST['filters'])) {
    $selected_genres = $_POST['filters'];
}

// Prepare SQL query based on selected genres
$genre_conditions = [];
if (!empty($selected_genres)) {
    foreach ($selected_genres as $genre) {
        $genre_conditions[] = "$genre = 1";
    }
    $genre_query = implode(' AND ', $genre_conditions);
    $sql = "SELECT id, title, image_path, release_date, platform, developer, publisher 
            FROM games
            WHERE $genre_query";
} else {
    $sql = "SELECT id, title, image_path, release_date, platform, developer, publisher 
            FROM games";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genre Sorting</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Body Styling */
        body {
            background-color: #1e1e1e;
            color: #f0f0f0;
            font-family: 'Roboto', sans-serif;
            margin: 0;            
        }

        .content-wrapper {
            padding: 20px;
        }

        /* Form Sorting Option Styling */
        #genre-filter {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        label {
            color: #ffffff;
            font-size: 18px;
            margin-right: 10px;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        button {
            background-color: #2c2c2c;
            color: #ffffff;
            border: 1px solid #4a4a4a;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #3e3e3e;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2c2c2c;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #4a4a4a;
        }

        th {
            background-color: #3a3a3a;
            color: #ffffff;
            font-size: 18px;
            text-transform: uppercase;
        }

        td {
            color: #cccccc;
        }

        tr:nth-child(even) {
            background-color: #242424;
        }

        td img {
            max-width: 70px;
            height: auto;
            border-radius: 5px;
        }

        tr:hover {
            background-color: #3b3b3b;
        }

        /* Style for clickable game title */
        a.game-title-link {
            color: #00aaff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a.game-title-link:hover {
            color: #ffaa00;
            text-decoration: underline;
        }

        a.game-title-link:visited {
            color: #66bbff;
        }

        h2{
            color:white;
        }

        /* Responsive Table for Mobile Devices */
        @media screen and (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead {
                display: none;
            }
            tr {
                margin-bottom: 15px;
            }
            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                padding-left: 10px;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
      <?php  include "header.php"; ?>

    <div class="content-wrapper">
        <section id="genre-filter">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <h2>Filter by Genre</h2>
                <div class="filter-options">
                    <label><input type="checkbox" name="filters[]" value="action" <?php echo in_array('action', $selected_genres) ? 'checked' : ''; ?>> Action</label>
                    <label><input type="checkbox" name="filters[]" value="rpg" <?php echo in_array('rpg', $selected_genres) ? 'checked' : ''; ?>> RPG</label>
                    <label><input type="checkbox" name="filters[]" value="strategy" <?php echo in_array('strategy', $selected_genres) ? 'checked' : ''; ?>> Strategy</label>
                    <label><input type="checkbox" name="filters[]" value="sports" <?php echo in_array('sports', $selected_genres) ? 'checked' : ''; ?>> Sports</label>
                    <label><input type="checkbox" name="filters[]" value="adventure" <?php echo in_array('adventure', $selected_genres) ? 'checked' : ''; ?>> Adventure</label>
                    <label><input type="checkbox" name="filters[]" value="mystery" <?php echo in_array('mystery', $selected_genres) ? 'checked' : ''; ?>> Mystery</label>
                    <button type="submit">Apply Filters</button>
                </div>
            </form>
        </section>

        <section id="game-results">
            <h2>Filtered Games</h2>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Release Date</th>
                        <th>Platform</th>
                        <th>Developer</th>
                        <th>Publisher</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td data-label='Image'><img src='" . htmlspecialchars($row['image_path']) . "' alt='Game Image'></td>";
                            echo "<td data-label='Title'><a href='game_details.php?game_id=" . htmlspecialchars($row['id']) . "' class='game-title-link'>" . htmlspecialchars($row['title']) . "</a></td>";
                            echo "<td data-label='Release Date'>" . htmlspecialchars($row['release_date']) . "</td>";
                            echo "<td data-label='Platform'>" . htmlspecialchars($row['platform']) . "</td>";
                            echo "<td data-label='Developer'>" . htmlspecialchars($row['developer']) . "</td>";
                            echo "<td data-label='Publisher'>" . htmlspecialchars($row['publisher']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No results found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>
    </div>
    <?php  include "footer.php"; ?>
</body>
</html>