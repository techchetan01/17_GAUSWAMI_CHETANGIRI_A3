<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit;
}
require_once 'db.php';

 if (isset($_POST['add_category'])) {
    $cname = trim($_POST['category_name']);
    if ($cname != '') {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $cname);
        $stmt->execute();
    }
    header("Location: manage_categories.php");
    exit;
}

 if (isset($_POST['update_category'])) {
    $cid = intval($_POST['cat_id']);
    $cname = trim($_POST['category_name']);
    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $cname, $cid);
    $stmt->execute();
    header("Location: manage_categories.php");
    exit;
}

 if (isset($_GET['delete'])) {
    $cid = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    header("Location: manage_categories.php");
    exit;
}

 $edit_row = null;
if (isset($_GET['edit'])) {
    $cid = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM categories WHERE id = $cid");
    $edit_row = $res->fetch_assoc();
}

$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Categories</title></head>
<body>
    <p><a href="admin_dashboard.php">&larr; Dashboard</a></p>
    <h2><?= $edit_row ? 'Edit Category' : 'Add Category' ?></h2>
    <form method="POST">
        <input type="hidden" name="cat_id" value="<?= $edit_row['id'] ?? '' ?>">
        <input type="text" name="category_name" required value="<?= htmlspecialchars($edit_row['name'] ?? '') ?>">
        <button type="submit" name="<?= $edit_row ? 'update_category' : 'add_category' ?>">
            <?= $edit_row ? 'Update' : 'Add' ?>
        </button>
    </form>

    <h2>All Categories</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr><th>ID</th><th>Name</th><th>Action</th></tr>
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= htmlspecialchars($cat['name']) ?></td>
                <td>
                    <a href="?edit=<?= $cat['id'] ?>">Edit</a> | 
                    <a href="?delete=<?= $cat['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>