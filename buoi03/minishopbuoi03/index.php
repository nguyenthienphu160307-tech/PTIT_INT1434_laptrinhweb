<?php
include 'config/db.php';

// lấy dữ liệu từ url
$cat = 0;
if (isset($_GET['category'])) {
    $cat = (int)$_GET['category'];
}

$q = "";
if (isset($_GET['q'])) {
    $q = trim($_GET['q']);
}

$sort = "";
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
}

// tạo câu sql
$sql = "SELECT * FROM products WHERE 1=1";

if ($cat > 0) {
    $sql .= " AND category_id = $cat";
}

if ($q != "") {
    $q_sql = mysqli_real_escape_string($conn, $q);
    $sql .= " AND name LIKE '%$q_sql%'";
}

if ($sort == "price_asc") {
    $sql .= " ORDER BY price ASC";
} else if ($sort == "price_desc") {
    $sql .= " ORDER BY price DESC";
} else {
    $sql .= " ORDER BY id";
}

$products = mysqli_query($conn, $sql);
$count = mysqli_num_rows($products);
$categories = mysqli_query($conn, "SELECT * FROM categories");

$title = "Trang chủ";
include 'includes/header.php';
?>

<h2>Sản phẩm</h2>

<form action="index.php" method="get" class="search-form">
    <input type="search" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Tìm sản phẩm...">
    <?php if ($cat > 0) { ?>
        <input type="hidden" name="category" value="<?php echo $cat; ?>">
    <?php } ?>

    <select name="sort">
        <option value="">Sắp xếp mặc định</option>
        <option value="price_asc" <?php if ($sort == "price_asc") echo "selected"; ?>>Giá tăng dần</option>
        <option value="price_desc" <?php if ($sort == "price_desc") echo "selected"; ?>>Giá giảm dần</option>
    </select>

    <button type="submit" class="btn">Tìm</button>
</form>

<p class="category-bar">
    <a href="index.php" class="<?php if ($cat == 0) echo 'active'; ?>">Tất cả</a>
    <?php while ($c = mysqli_fetch_assoc($categories)) { ?>
        <a href="index.php?category=<?php echo $c['id']; ?>" class="<?php if ($cat == $c['id']) echo 'active'; ?>"><?php echo $c['name']; ?></a>
    <?php } ?>
</p>

<p><strong>Tìm thấy <?php echo $count; ?> sản phẩm</strong></p>

<?php if ($count == 0) { ?>
    <p>Không tìm thấy sản phẩm nào.</p>
<?php } ?>

<div class="product-list">
    <?php while ($row = mysqli_fetch_assoc($products)) { ?>
        <div class="product-card">
            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h3><?php echo $row['name']; ?></h3>
            <p class="price"><?php echo number_format($row['price'], 0, ',', '.'); ?> đ</p>
            <a href="product.php?id=<?php echo $row['id']; ?>" class="btn">Xem chi tiết</a>
            <button type="button" class="btn btn-accent add-to-cart" data-id="<?php echo $row['id']; ?>">Thêm vào giỏ</button>
        </div>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
