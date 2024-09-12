<?php

include "db_connect.php";
// For Top Games

$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'top';


if ($sortOption == 'top') {
   $gamesQuery = "SELECT games.*, AVG(ratings.rating) as avg_rating 
                  FROM games 
                  LEFT JOIN ratings ON games.id = ratings.game_id 
                  GROUP BY games.id 
                  ORDER BY avg_rating DESC LIMIT 10";

} else if ($sortOption == 'popular') {
   $gamesQuery = "SELECT games.*, AVG(ratings.rating) as avg_rating,  count(ratings.user_id) as total_user 
                  FROM games 
                  LEFT JOIN ratings ON games.id = ratings.game_id 
                  GROUP BY games.id 
                  ORDER BY total_user DESC LIMIT 10";
}

$gamesResult = mysqli_query($conn, $gamesQuery);



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Games</title>
    <style>

        /* Body Styling */
        body {
            background-color: #1e1e1e;
            color: #f0f0f0;
            font-family: 'Roboto', sans-serif;
            margin: 0;            
        }

        .content-wrapper{
            padding: 20px;
        }

        /* Form Sorting Option Styling */
        #sort-form {

            margin-top: 20px;
            margin-bottom: 20px;
        }

        label {
            color: #ffffff;
            font-size: 18px;
            margin-right: 10px;
        }

        select {
            background-color: #2c2c2c;
            color: #ffffff;
            border: 1px solid #4a4a4a;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
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

        td:hover {
            background-color: #444444;
        }

        /* Button Hover Effect */
        select:hover {
            background-color: #3e3e3e;
        }

        /* Add hover effect for rows */
        tr:hover {
            background-color: #3b3b3b;
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

<?php include "header.php"?>

<div class="content-wrapper">
    <!-- Sorting Form -->
    <form method="GET" id="sort-form">
        <label for="sort-options">Sort by:</label>
        <select name="sort" id="sort-options" onchange="document.getElementById('sort-form').submit()">
            <option value="top" <?php if ($sortOption == 'top') echo 'selected'; ?>>Top Games</option>
            <option value="popular" <?php if ($sortOption == 'popular') echo 'selected'; ?>>Most Popular</option>
        </select>
    </form>

    <!-- Game List Table -->
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Gamevault Rating</th>
                <th>User Rating</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($gamesResult)): ?>
            <tr>
                <td data-label="Image"><img src="<?php echo $row['image_path']; ?>" alt="Game Image"></td>
                <td data-label="Title"><?php echo $row['title']; ?></td>
                <td data-label="Gamevault Rating"><?php echo number_format($row['avg_rating'], 1); ?></td>
                <td data-label="User Rating"><?php // Fetch and display user rating ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"?>


</body>
</html>