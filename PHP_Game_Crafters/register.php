<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: Implement proper registration
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Basic file storage - to be enhanced with proper validation and security
    $file = 'users.txt';
    $userData = $username . ',' . $password . PHP_EOL;
    file_put_contents($file, $userData, FILE_APPEND);
    
    // Temporary direct login after registration
    $_SESSION['user_name'] = $username;
    $_SESSION['logged_in'] = true;
    header("Location: menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
