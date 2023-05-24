<?php
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

// Check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create
    if (isset($_POST['create'])) {
        $name = sanitize($_POST['name']);
        $age = sanitize($_POST['age']);
        $address = sanitize($_POST['address']);
        
        $sql = "INSERT INTO students (name, age, address) VALUES ('$name', $age, '$address')";
        if (mysqli_query($conn, $sql)) {
            echo "Student record created successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
    
    // Read
    if (isset($_POST['read'])) {
        $sql = "SELECT * FROM students";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Age</th><th>Address</th></tr>";
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
                echo "<td>".$row['address']."</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "No student records found.";
        }
    }
    
    // Update
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = sanitize($_POST['name']);
        $age = sanitize($_POST['age']);
        $address = sanitize($_POST['address']);
        
        $sql = "UPDATE students SET name='$name', age=$age, address='$address' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            echo "Student record updated successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
    
    // Delete
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        
        $sql = "DELETE FROM students WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            echo "Student record deleted successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Records</title>
</head>
<body>
    <h1>Student Records</h1>
    <form method="POST">
        <input type="submit" name="read" value="Read Records">
    </form>
    <br>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>
        
        <label for="age">Age:</label>
        <input type="number" name="age" required><br>
        
        <label for="address">Address:</label>
        <input type="text" name="address" required><br>
        
        <input type="submit" name="create" value="Create Record">
    </form>
    <br>
    <form method="POST">
        <label for="id">ID:</label>
        <input type="number" name="id" required><br>
        
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>
        
        <label for="age">Age:</label>
        <input type="number" name="age" required><br>
        
        <label for="address">Address:</label>
        <input type="text" name="address" required><br>
        
        <input type="submit" name="update" value="Update Record">
        <input type="submit" name="delete" value="Delete Record">
    </form>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
