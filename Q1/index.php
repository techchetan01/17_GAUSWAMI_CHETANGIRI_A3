<?php
session_start();
require_once 'db.php';

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .layout-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .layout-table td { border: 1px solid #000; padding: 15px; vertical-align: top; }
        .cat-sidebar { width: 25%; }
        .product-area { width: 75%; }
        ul { list-style-type: none; padding-left: 0; }
        ul li { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Shop Online</h1>
        <div>
            <?php if (isset($_SESSION['user_logged'])): ?>
                Logged in as <b><?= htmlspecialchars($_SESSION['user_name']) ?></b> | <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="user_login.php">Login</a> | <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>

     <table class="layout-table">
        <tr>
            <td class="cat-sidebar">
                <h3>Categories</h3>
                <ul>
                    <?php while ($c = $categories->fetch_assoc()): ?>
                        <li>
                            <a href="javascript:void(0);" onclick="loadProducts(<?= $c['id'] ?>, 1)">
                                <?= htmlspecialchars($c['name']) ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </td>
            <td class="product-area">
                <div id="product-container">
                    <i>Click on any category on the left to view products.</i>
                </div>
            </td>
        </tr>
    </table>

    <script>
        function loadProducts(catId, page) {
            const container = document.getElementById('product-container');
            container.innerHTML = 'Loading products...';

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_products.php?cat_id=' + catId + '&page=' + page, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    container.innerHTML = this.responseText;
                } else {
                    container.innerHTML = 'Failed to load products.';
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>