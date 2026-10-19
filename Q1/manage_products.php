<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit;
}
require_once 'db.php';

 function resizeAndSaveImage($sourcePath, $targetPath, $maxW = 300, $maxH = 300) {
    list($origW, $origH, $type) = getimagesize($sourcePath);
    $ratio = min($maxW / $origW, $maxH / $origH);
    $newW = round($origW * $ratio);
    $newH = round($origH * $ratio);

    $thumb = imagecreatetruecolor($newW, $newH);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            break;
        default:
            return false;
    }

    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
    
    if ($type == IMAGETYPE_PNG) {
        imagepng($thumb, $targetPath, 8);
    } else {
        imagejpeg($thumb, $targetPath, 85);
    }

    imagedestroy($thumb);
    imagedestroy($source);
    return true;
}

 if (isset($_POST['add_product'])) {
    $cat_id = intval($_POST['category_id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);

    if (!empty($_FILES['image']['tmp_name'])) {
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = 'uploads/' . $filename;

        if (resizeAndSaveImage($_FILES['image']['tmp_name'], $targetFile, 250, 250)) {
            $stmt = $conn->prepare("INSERT INTO products (category_id, name, price, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isds", $cat_id, $name, $price, $targetFile);
            $stmt->execute();
        }
    }
    header("Location: manage_products.php");
    exit;
}

 if (isset($_GET['delete'])) {
    $pid = intval($_GET['delete']);
    $res = $conn->query("SELECT image FROM products WHERE id = $pid");
    if ($row = $res->fetch_assoc()) {
        if (file_exists($row['image'])) unlink($row['image']);
    }
    $conn->query("DELETE FROM products WHERE id = $pid");
    header("Location: manage_products.php");
    exit;
}

$categories = $conn->query("SELECT * FROM categories");
$products = $conn->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Products</title></head>
<body>
    <p><a href="admin_dashboard.php">&larr; Dashboard</a></p>
    <h2>Add New Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Category:</label><br>
        <select name="category_id" required>
            <?php while($c = $categories->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Product Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" required><br><br>

        <label>Product Image (JPG/PNG):</label><br>
        <input type="file" name="image" accept="image/jpeg,image/png" required><br><br>

        <button type="submit" name="add_product">Save Product</button>
    </form>

    <h2>Product List</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr><th>ID</th><th>Picture</th><th>Name</th><th>Category</th><th>Price</th><th>Action</th></tr>
        <?php while($p = $products->fetch_assoc()): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><img src="<?= $p['image'] ?>" width="80" height="80" style="object-fit:cover;"></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['category_name']) ?></td>
                <td>$<?= number_format($p['price'], 2) ?></td>
                <td><a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>