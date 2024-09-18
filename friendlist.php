<?php
include 'db_connect.php';


$current_user_id = $_SESSION['user_id']; 


// Prepare the query to fetch friends
$query = "
    SELECT u.id, u.username, u.profile_image 
    FROM users u
    JOIN friends f ON (
        (u.id = f.friend_id AND f.user_id = ?)
        OR (u.id = f.user_id AND f.friend_id = ?)
    )
    WHERE u.id != ?
    GROUP BY u.id"; // Use GROUP BY to remove duplicates

$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $current_user_id, $current_user_id, $current_user_id);
$stmt->execute();
$friends_list = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend List</title>
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
    </style>
</head>
<body>
<?php include "header.php"; ?>

<h2>My Friends</h2>

<!-- Display Friend List -->
<?php if ($friends_list->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Profile Image</th>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $friends_list->fetch_assoc()): ?>
                <tr>
                    <td><img src="Assets/user_image/<?php echo htmlspecialchars($row['profile_image']); ?>" alt="Profile Image" class="profile-image"></td>
                    <td><a href="user_info_display.php?user_id=<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['username']); ?></a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>You don't have any friends yet.</p>
<?php endif; ?>

<?php include "footer.php"; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
