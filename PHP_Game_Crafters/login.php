// login.php
<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: Implement login functionality
    // Basic structure for future development
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Temporary direct login for testing
    $_SESSION['user_name'] = $username;
    $_SESSION['logged_in'] = true;
    header("Location: menu.php");
    exit();
}
?>
  
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
