<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proper registration
    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            $error = "Username and Password are required.";
        } else {
            // Hash the password before saving
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Store username and hashed password in users.txt
            $file = 'users.txt';

            // Check if the username already exists
            $users = file($file, FILE_IGNORE_NEW_LINES);
            foreach ($users as $user) {
                list($storedUsername, $storedPassword) = explode(',', $user);
                if ($storedUsername === $username) {
                    $error = "Username already exists.";
                    break;
                }
            }

            // If no existing username, save the new user
            if (empty($error)) {
                $userData = $username . ',' . $hashedPassword . PHP_EOL;

                // Attempt to write the data to the file
                if (file_put_contents($file, $userData, FILE_APPEND)) {
                    $_SESSION['user_name'] = htmlspecialchars($username);
                    $_SESSION['logged_in'] = true;

                    // Redirect to the main menu after successful registration
                    header("Location: menu.php");
                    exit();
                } else {
                    $error = "Failed to write data to the file.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2 class="titleDrop">Register</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
