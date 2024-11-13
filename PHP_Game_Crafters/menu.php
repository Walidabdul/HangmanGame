<?php
session_start();
// Add authentication check
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <title class="titleGlow">Main Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="titleDrop">Main Menu</h1>
        <a href="game.php" class="menu-link">Start Game</a>
        <a href="leaderboard.php" class="menu-link">Leaderboard</a>
        <a href="rules.php" class="menu-link">Rules</a>
         <form method="post" action="logout.php">
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>
