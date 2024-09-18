<?php

include "db_connect.php"; // Database connection

$search_results = [];

if (isset($_POST['search'])) {
    $search_username = $_POST['username'];
    $current_user_id = $_SESSION['user_id'];

    // username search marbo and friend kina already check korbo
    $query = "
        SELECT u.id, u.username, u.profile_image 
        FROM users u
        WHERE u.username LIKE ? 
        AND u.id != ? 
        AND u.id NOT IN (
            SELECT friend_id FROM friends WHERE user_id = ? 
            UNION 
            SELECT user_id FROM friends WHERE friend_id = ?
        )
    ";

    $stmt = $conn->prepare($query);
    $search_term = "%" . $search_username . "%"; // jekono kisu dile jate similar result pai
    $stmt->bind_param("siii", $search_term, $current_user_id, $current_user_id, $current_user_id);
    $stmt->execute();
    $search_results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Friends</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .profile-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .action-btn {
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .send-request-btn {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
<?php include "header.php"?>

<h2>Find Friends</h2>


<form method="POST" action="">
    <label for="username">Search by Username:</label>
    <input type="text" name="username" id="username" placeholder="Enter username" required>
    <button type="submit" name="search">Search</button>
</form>


<?php if (!empty($search_results)): ?>
    <h3>Search Results</h3>
    <table>
        <thead>
            <tr>
                <th>Profile Image</th>
                <th>Username</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $search_results->fetch_assoc()): ?>
                <tr>
                    <td><img src="Assets/user_image/<?php echo $row['profile_image']; ?>" alt="Profile" class="profile-image"></td>
                    <td><?php echo $row['username']; ?></td>
                    <td>
                        <form action="send_friend_request.php" method="POST">
                            <input type="hidden" name="receiver_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="action-btn send-request-btn">Send Friend Request</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php elseif (isset($_POST['search'])): ?>
    <p>No results found for "<?php echo htmlspecialchars($search_username); ?>"</p>
<?php endif; ?>

<?php include "footer.php"?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
