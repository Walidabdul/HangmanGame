<?php
session_start();

// Basic game initialization
$_SESSION['game_state'] = [
    'word' => 'test',
    'guessed' => [],
    'lives' => 6
];

// TODO: Implement game logic
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hangman Game</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Hangman Game</h2>
        <!-- Basic game interface -->
        <div class="word-display">
            <!-- Word display placeholder -->
        </div>
        <form method="post">
            <input type="text" name="guess" maxlength="1">
            <button type="submit">Guess</button>
        </form>
    </div>
</body>
</html>
