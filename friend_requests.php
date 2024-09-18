<?php
include "db_connect.php"; 

$current_user_id = $_SESSION['user_id'];

// pending request
$query = "SELECT users.username, users.profile_image, friends_requests.id, friends_requests.sender_id
          FROM friends_requests
          JOIN users ON friends_requests.sender_id = users.id
          WHERE friends_requests.receiver_id = ? AND friends_requests.status = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend Requests</title>
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
        .accept-btn {
            background-color: #4CAF50;
            color: white;
        }
        .reject-btn {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>

<?php include "header.php"?>

<h2>Friend Requests</h2>

<table>
    <thead>
        <tr>
            <th>Profile Image</th>
            <th>Username</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="Assets/user_image/<?php echo $row['profile_image']; ?>" alt="Profile" class="profile-image"></td>
                <td><?php echo $row['username']; ?></td>
                <td>
                    <form action="process_friend_request.php" method="POST">
                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="sender_id" value="<?php echo $row['sender_id']; ?>">
                        <button type="submit" name="action" value="accept" class="action-btn accept-btn">Accept</button>
                        <button type="submit" name="action" value="reject" class="action-btn reject-btn">Reject</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include "footer.php"?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
