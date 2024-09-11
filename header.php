<!-- header.php -->
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
    justify-content: center;      /* Center items horizontally */
    align-items: center;          /* Center items vertically */
    flex-direction: column;       /* Stack the logo and navigation vertically */
    color: #fff;
    text-align: center;           /* Ensure text is centered */
}

.logo h1 {
    margin: 0;
    font-size: 24px;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
    margin-top: 20px;             /* Add some space between logo and nav links */
}

.nav-links a {
    color: yellowgreen;
    text-decoration: yellow;
    font-weight: bold;
}

.nav-links a:hover {
    color: bisque;
    text-decoration: yellow;
    font-weight: bold;
}

.search-container {
    position: absolute;
    top: 10px; /* Adjust this value as needed */
    right: 20px; /* Adjust this value as needed */
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
                <li><a href="#top-char">Top Charecters</a></li>
                <li><a href="#genres">Genres</a></li>
                <li><a href="#community">Community</a></li>
                <li><a href="./user_profile/user_profile.php">Profile</a></li>
            </ul>
            
            <div class="search-container">
                <form action="search_result.php" method="GET">
                    <input type="text" id="search-bar" name="query" placeholder="Search for games...">
                    <button type="submit" id="search-button">Search</button>
                </form>
            </div>
        </nav>
    </header>
</body>
</html>
