<!-- header.php -->

<?php
$user_id = $_SESSION['user_id'];

$query2 = "SELECT username, profile_image FROM users where id = $user_id";
$result2 = mysqli_query($conn, $query2);
$data2 = mysqli_fetch_assoc($result2);

$profile_image = $data2['profile_image'];
$username = $data2['username'];

$admin_query = "SELECT * FROM admin WHERE user_id = $user_id";
$stmt = $conn->prepare($admin_query);
$stmt->execute();
$admin_result = $stmt->get_result();

$is_admin = $admin_result->num_rows > 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Common Header</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        header {
            background: #000;
            padding: 10px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: #fff;
            text-align: center;
        }

        .logo h1 {
            margin: 0;
            font-size: 24px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .nav-links a {
            color: yellowgreen;
            text-decoration: yellow;
            font-weight: bold;
        }

        .nav-links a:hover {
            color: bisque;
        }

        .search-container {
            position: absolute;
            top: 10px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #search-bar {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #fff;
            outline: none;
            font-size: 14px;
        }

        #search-button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: #ff4500;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        /* User Panel */
        .user-nav-button {
            position: absolute;
            top: 10px;
            left: 20px;
            background-color: #000;
            color: yellowgreen;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .side-nav {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            color: #fff;
        }

        .side-nav a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #f1f1f1;
            display: block;
            transition: 0.3s;
        }

        .side-nav a:hover {
            color: #ff4500;
        }

        .side-nav .close-btn {
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 36px;
        }

        .user-profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .admin-panel {
            margin-top: 20px;
            border-top: 1px solid #fff;
            padding-top: 10px;
        }
    </style>
</head>
<body>

<header>
    <nav>
        <div class="logo">
            <h1>Gamevault</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="game_sorted.php">Top Games</a></li>
            <li><a href="character_sorting.php">Top Characters</a></li>
            <li><a href="#genres">Genres</a></li>
            <li><a href="#community">Community</a></li>
            <li><a href="user_profile.php">Profile</a></li>
        </ul>

        <div class="search-container">
            <form action="search_result.php" method="GET">
                <input type="text" id="search-bar" name="query" placeholder="Search for games...">
                <button type="submit" id="search-button">Search</button>
            </form>
        </div>

        <!-- User Navigation Button -->
        <button class="user-nav-button" onclick="openNav()">☰ User Panel</button>
    </nav>
</header>

<!-- Side Navigation -->
<div id="sideNav" class="side-nav">
    <a href="javascript:void(0)" class="close-btn" onclick="closeNav()">&times;</a>
    
    <div class="user-profile">
        <!-- User Profile Image -->
        <img src="Assets/user_image/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture"">
        <h3><?php echo htmlspecialchars($username); ?></h3>

    </div>
    
    <a href="user_profile.php">Profile</a>
    <a href="upload_games.php">Add a Game</a>
    <a href="upload_characters.php">Add a Character</a>
    <a href="messages.php">Messages</a>
    <a href="friend_requests.php">Friend Requests</a>
    
    <!-- Admin Panel (only visible for admin users) -->
    <?php if ($is_admin): ?>
    <div class="admin-panel">
        <a href="admin_panel.php">Admin Panel</a>
    </div>
    <?php endif; ?>
</div>

<script>
    function openNav() {
        document.getElementById("sideNav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("sideNav").style.width = "0";
    }
</script>

</body>
</html>
