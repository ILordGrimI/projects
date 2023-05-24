<?php
// Check if the user is already logged in
session_start();
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'students';

// Create database connection
$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to sanitize user inputs
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($data)));
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);
    
    // Validate username and password
    // You can replace this with your own validation logic
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>
