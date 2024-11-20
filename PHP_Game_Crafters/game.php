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
        <!-- Difficulty selection form only if the game state is not set -->
        <?php if (!isset($_SESSION['game_state'])): ?>
        <form method="post">
            <label for="difficulty">Select Difficulty: </label>
            <select name="difficulty" id="difficulty">
                <option value="easy" <?= isset($_POST['difficulty']) && $_POST['difficulty'] == 'easy' ? 'selected' : ''; ?>>Easy</option>
                <option value="medium" <?= isset($_POST['difficulty']) && $_POST['difficulty'] == 'medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="hard" <?= isset($_POST['difficulty']) && $_POST['difficulty'] == 'hard' ? 'selected' : ''; ?>>Hard</option>
            </select>
            <button type="submit">Start Game</button>
        </form>
        <?php else: ?>
             <!-- Hangman drawing based on lives remaining -->
        <div class="hangman-drawing">
            <?php
            $lives = $_SESSION['game_state']['lives'];
            echo "<pre>  O</pre>"; // Head
            if ($lives <= 5) echo "<pre>  |</pre>"; // Body
            if ($lives <= 4) echo "<pre> /|</pre>"; // Left arm
            if ($lives <= 3) echo "<pre> /|\\</pre>"; // Right arm
            if ($lives <= 2) echo "<pre> /</pre>"; // Left leg
            if ($lives <= 1) echo "<pre> / \\</pre>"; // Right leg
            ?>
        </div>
        <!-- Display the word with blanks for unrevealed letters -->
        <div class="word-display">
            <?php foreach ($_SESSION['game_state']['revealed'] as $index => $revealed): ?>
                <span class="letter">
                    <?php echo $revealed ? $_SESSION['game_state']['word'][$index] : '_'; ?>
                </span>
            <?php endforeach; ?>
        </div>
        <form method="post">
            <?php foreach (range('A', 'Z') as $letter): ?>
                <button type="submit" name="guess" value="<?php echo $letter; ?>" class="key" <?php echo in_array($letter, $_SESSION['game_state']['guessed']) ? 'disabled' : ''; ?>>
                    <?php echo $letter; ?>
                </button>
            <?php endforeach; ?>
        </form>
        <!-- Display lives and score -->
        <p class="game-stats">Lives: <?php echo $_SESSION['game_state']['lives']; ?></p>
        <p class="game-stats">Score: <?php echo $_SESSION['game_state']['score']; ?></p>

        <!-- Display game over message -->
        <?php if ($_SESSION['game_state']['game_over']): ?>
            <p>Game Over! The word was: <?php echo $_SESSION['game_state']['word']; ?></p>
            <form method="post">
                 <button type="submit" name="new_game">Play Again</button>
            </form>
            <a href="menu.php">Back to Menu</a>
        <?php elseif ($_SESSION['game_state']['won']): ?>
            <p>Congratulations, you won!</p>
            <form method="post">
               <button type="submit" name="new_game">Play Again</button>
            </form>
            <a href="menu.php">Back to Menu</a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</body>
</html>
