<?php
require_once 'db.php';

$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 3; // Grid limit per page
$offset = ($page - 1) * $limit;

 $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM products WHERE category_id = ?");
$countStmt->bind_param("i", $cat_id);
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

 $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ? LIMIT ? OFFSET ?");
$stmt->bind_param("iii", $cat_id, $limit, $offset);
$stmt->execute();
$products = $stmt->get_result();
?>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <?php if ($products->num_rows > 0): ?>
        <?php while ($p = $products->fetch_assoc()): ?>
            <div style="border:1px solid #ccc; padding:10px; width:160px; text-align:center;">
                <img src="<?= htmlspecialchars($p['image']) ?>" width="140" height="140" style="object-fit:cover;"><br>
                <strong><?= htmlspecialchars($p['name']) ?></strong><br>
                <span>Price: $<?= number_format($p['price'], 2) ?></span>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products found in this category.</p>
    <?php endif; ?>
</div>

 <?php if ($totalPages > 1): ?>
    <div style="margin-top: 20px;">
        <span>Pages: </span>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="javascript:void(0);" 
               onclick="loadProducts(<?= $cat_id ?>, <?= $i ?>)" 
               style="margin-right: 5px; font-weight: <?= $i === $page ? 'bold' : 'normal' ?>;">
               [<?= $i ?>]
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>