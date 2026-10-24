<?php
include 'config/db.php';

$id = 0;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
}

// lấy sản phẩm theo id
$sql = "SELECT products.*, categories.name AS category_name
        FROM products
        JOIN categories ON categories.id = products.category_id
        WHERE products.id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $title = "Không tìm thấy sản phẩm";
    include 'includes/header.php';
    echo "<h2>Không tìm thấy sản phẩm</h2>";
    echo "<p><a href='index.php'>&laquo; Quay lại danh sách sản phẩm</a></p>";
    include 'includes/footer.php';
    exit;
}

$product = mysqli_fetch_assoc($result);

// sản phẩm cùng danh mục
$cat_id = $product['category_id'];
$sql2 = "SELECT * FROM products WHERE category_id = $cat_id AND id <> $id LIMIT 3";
$related = mysqli_query($conn, $sql2);

$title = $product['name'];
include 'includes/header.php';
?>

<div class="product-detail">
    <div class="product-image">
        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
    </div>

    <div class="product-info">
        <h2><?php echo $product['name']; ?></h2>
        <p class="price">Giá: <strong><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</strong></p>
        <p><?php echo nl2br($product['description']); ?></p>

        <h3>Thông tin sản phẩm</h3>
        <table>
            <tr>
                <th>Mã sản phẩm</th>
                <td><?php echo $product['id']; ?></td>
            </tr>
            <tr>
                <th>Danh mục</th>
                <td><?php echo $product['category_name']; ?></td>
            </tr>
            <tr>
                <th>Tình trạng</th>
                <td>
                    <?php
                    if ($product['stock'] > 0) {
                        echo "Còn " . $product['stock'] . " sản phẩm";
                    } else {
                        echo "Hết hàng";
                    }
                    ?>
                </td>
            </tr>
        </table>

        <p><a href="index.php">&laquo; Quay lại danh sách sản phẩm</a></p>
    </div>
</div>

<?php if (mysqli_num_rows($related) > 0) { ?>
    <div class="related">
        <h3>Sản phẩm cùng danh mục</h3>
        <div class="product-list related-list">
            <?php while ($row = mysqli_fetch_assoc($related)) { ?>
                <div class="product-card">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p class="price"><?php echo number_format($row['price'], 0, ',', '.'); ?> đ</p>
                    <a href="product.php?id=<?php echo $row['id']; ?>" class="btn">Xem chi tiết</a>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<?php include 'includes/footer.php'; ?>
