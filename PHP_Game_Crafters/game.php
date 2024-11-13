<?php
session_start();
//Game logic
// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

// Reset game state if the "Play Again" button is clicked
if (isset($_POST['new_game'])) {
    unset($_SESSION['game_state']);
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to reload the page and show difficulty selection
    exit();
}

// Initialize or reset game state when needed
if (isset($_POST['difficulty']) && !isset($_SESSION['game_state'])) {
    $difficulty = $_POST['difficulty'];
    $word = getWordForLevel($difficulty);  // Get word based on difficulty

    // Initialize game state session
    $_SESSION['game_state'] = [
        'score' => 0,
        'lives' => 6,
        'word' => $word,
        'guessed' => [],
        'revealed' => array_fill(0, strlen($word), false),
        'game_over' => false,
        'won' => false,
        'difficulty' => $difficulty
    ];

    // Redirect to itself to reload the page and show the game screen
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Function to handle guesses
function handleGuess($letter) {
    $game = &$_SESSION['game_state'];
    $word = $game['word'];
    $correct = false;

    // Make the guess case-insensitive
    $letter = strtolower($letter);

    if (!in_array($letter, $game['guessed'])) {
        $game['guessed'][] = $letter;

        for ($i = 0; $i < strlen($word); $i++) {
            if (strtolower($word[$i]) == $letter) {
                $game['revealed'][$i] = true;
                $correct = true;
            }
        }

        // Deduct lives if the guess is incorrect
        if (!$correct) {
            $game['lives']--;
            if ($game['lives'] <= 0) {
                $game['game_over'] = true;
            }
        }

        // Check if the game is won (all letters are revealed)
        if (!in_array(false, $game['revealed'])) {
            $game['won'] = true;
            $game['score'] += 100 * $game['lives']; // Score based on remaining lives
        }
    }
}

// Handle the form submission for guesses
if (isset($_POST['guess']) && !$_SESSION['game_state']['game_over'] && !$_SESSION['game_state']['won']) {
    handleGuess($_POST['guess']);
}

// Function to select word based on difficulty
function getWordForLevel($difficulty) {
    $wordBanks = [
        'easy' => ['cat', 'dog', 'hat', 'run', 'jump', 'play', 'bird', 'fish', 'ball', 'book'],
        'medium' => ['monkey', 'dragon', 'puzzle', 'guitar', 'planet', 'rainbow', 'picture', 'elephant'],
        'hard' => ['challenge', 'adventure', 'discovery', 'knowledge', 'butterfly', 'universe', 'symphony']
    ];

    return $wordBanks[$difficulty][array_rand($wordBanks[$difficulty])];
}

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
