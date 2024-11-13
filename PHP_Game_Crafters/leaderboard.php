<?php
session_start();

// Check if the user is logged in by verifying the session
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit(); // Exit script to prevent further execution
}

// Retrieve leaderboard from session, or initialize as an empty array
$leaderboard = $_SESSION['leaderboard'] ?? [];

// Check if the game state indicates a win
if ($_SESSION['game_state']['won']) {
    $updated = false; // Flag to check if the user's score is updated

    // Loop through the leaderboard to find the player and update their score
    foreach ($leaderboard as &$entry) {
        if ($entry['player'] == $_SESSION['user_name']) {
            $entry['score'] += $_SESSION['game_state']['score']; // Add the score to the existing score
            $updated = true; // Set updated to true since the player's score was found and updated
            break; // Exit the loop once the player's score is updated
        }
    }

    // If the player's score was not found, add a new entry for the player
    if (!$updated) {
        $leaderboard[] = [
            'player' => $_SESSION['user_name'], // Add the player's name
            'score' => $_SESSION['game_state']['score'] // Add the score from the game state
        ];
    }

    // Sort the leaderboard in descending order based on the score
    usort($leaderboard, function($a, $b) {
        return $b['score'] - $a['score']; // Sort by score, highest first
    });

    // Save the top 10 players' scores to the session
    $_SESSION['leaderboard'] = array_slice($leaderboard, 0, 10); // Keep only the top 10 players
}
?>
    
<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="leaderboard-container">
        <h2>Leaderboard</h2>
        <!-- TODO: Implement leaderboard display -->
        <a href="menu.php">Back to Menu</a>
    </div>
</body>
</html>
