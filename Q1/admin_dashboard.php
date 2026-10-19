<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title></head>
<body>
    <h1>Welcome, Admin</h1>
    <nav>
        <a href="manage_categories.php">Manage Categories</a> | 
        <a href="manage_products.php">Manage Products</a> | 
        <a href="logout.php">Logout</a>
    </nav>
</body>
</html>