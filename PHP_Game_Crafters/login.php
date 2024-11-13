// login.php
<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Login functionality
    // Check if the login button was clicked
    if (isset($_POST['login'])) {
        // Trim and store username and password from the form
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Check if either username or password is empty
        if (empty($username) || empty($password)) {
            $error = "Username and Password are required."; // Set error message
        } else {
            $file = 'users.txt'; // Define the file where user data is stored
            $users = file($file, FILE_IGNORE_NEW_LINES); // Read users from file and ignore new lines

            $validUser = false; // Flag to track if user credentials are valid

            // Loop through the users to find a matching username and password
            foreach ($users as $user) {
                // Split the user data into username and password
                list($storedUsername, $storedPassword) = explode(',', $user);
                
                // Check if the username matches and if the password is correct using password_verify
                if ($storedUsername === $username && password_verify($password, $storedPassword)) {
                    $validUser = true; // Set validUser to true if a match is found
                    break; // Exit the loop once a valid user is found
                }
            }

            // If valid user, create session and optionally remember the user
            if ($validUser) {
                $_SESSION['user_name'] = htmlspecialchars($username); // Store sanitized username in session
                $_SESSION['logged_in'] = true; // Mark user as logged in

                // Check if the "remember me" option was selected
                if (isset($_POST['remember'])) {
                    // Set a cookie to remember the user for 30 days
                    setcookie('user_name', $_SESSION['user_name'], time() + (86400 * 30), "/");
                }

                header("Location: menu.php"); // Redirect to the menu page after successful login
                exit(); // Exit script to prevent further code execution
            } else {
                $error = "Invalid username or password."; // Set error message if login fails
            }
        }
    }
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
