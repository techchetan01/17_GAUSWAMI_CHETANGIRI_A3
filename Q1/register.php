<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $captcha = trim($_POST['captcha'] ?? '');
    
    if (empty($captcha) || strcasecmp($captcha, $_SESSION['captcha'] ?? '') !== 0) {
        $error = "Incorrect CAPTCHA code!";
    } else {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $success = "Registration successful! <a href='user_login.php'>Login here</a>";
        } else {
            $error = "Username or Email already exists.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>User Registration</title></head>
<body>
    <h2>User Registration</h2>
    <?php 
    if ($error) echo "<p style='color:red;'>$error</p>";
    if ($success) echo "<p style='color:green;'>$success</p>";
    ?>
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>CAPTCHA:</label><br>
        <img src="captcha.php" alt="CAPTCHA" style="vertical-align:middle; margin-bottom:5px;"><br>
        <input type="text" name="captcha" placeholder="Enter CAPTCHA" required><br><br>

        <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="user_login.php">Login here</a></p>
</body>
</html>