<?php

include 'db_connect.php';


$name = isset($_GET['name']) ? $_GET['name'] : '';


$sql = "SELECT * FROM characters_request WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();
$character = $result->fetch_assoc();

if (!$character) {
    echo "No character request found.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'accept') {
   
        $sql = "INSERT INTO characters (name, game_title, image_path, description)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $character['name'], $character['game_title'], $character['image_path'], $character['description']);
        $stmt->execute();


        $sql = "DELETE FROM characters_request WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();


        header("Location: request_process.php?type=character");
        exit;
    } elseif ($action === 'reject') {

        $sql = "DELETE FROM characters_request WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();

        header("Location: request_process.php?type=character");
        exit;
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Character Request Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Dark theme styles */
        body {
            background-color: #1e1e1e;
            color: #e0e0e0;
            font-family: Arial, sans-serif;
        }
        
        .container {
            background-color: #2c2c2c;
            border-radius: 8px;
            padding: 20px;
            margin: 20px;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        h1, h2 {
            color: #f0f0f0;
        }

        img {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        p {
            margin: 10px 0;
            color: white;
        }

        strong {
            color: #f0f0f0;
        }

        .btn {
            background-color: #4caf50;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        form {
            display: inline;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>

    <div class="container">
        <h1>Character Request Details</h1>

        <h2>Name:  <?php echo htmlspecialchars($character['name']); ?></h2>
        <img src="<?php echo htmlspecialchars($character['image_path']); ?>" alt="Character Image" width="200">
        <p><strong>Game Title:</strong> <?php echo htmlspecialchars($character['game_title']); ?></p>
        <p><strong>Description:    </strong> <?php echo htmlspecialchars($character['description']); ?></p>

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

