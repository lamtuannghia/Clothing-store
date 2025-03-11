<?php
require "../config/connect.php"; // Kết nối database

$where = [];
$params = [];
$types = "";

// Tìm kiếm theo từ khóa (tìm trong tên sản phẩm và mô tả)
if (!empty($_GET['query'])) {
    $query = "%" . $_GET['query'] . "%";
    $where[] = "(p.name LIKE ?)";
    $params[] = $query;
    $types .= "s";
}

// Lọc theo category ID
if (!empty($_GET['id'])) {
    $cateIds = explode(",", $_GET['id']); // Tách danh mục ID thành mảng
    $cateIdPlaceholders = implode(",", array_fill(0, count($cateIds), "?"));
    $where[] = "c.id IN ($cateIdPlaceholders)";
    $params = array_merge($params, $cateIds);
    $types .= str_repeat("i", count($cateIds)); // "i" là kiểu số nguyên
}

// // Lọc theo sub-category ID
// if (!empty($_GET['subcate'])) {
//     $subcateIds = explode(",", $_GET['subcate']);
//     $subcatePlaceholders = implode(",", array_fill(0, count($subcateIds), "?"));
//     $where[] = "ca.id IN ($subcatePlaceholders)";
//     $params = array_merge($params, $subcateIds);
//     $types .= str_repeat("i", count($subcateIds));
// }

// Lọc theo danh mục từ bảng product_inventory
if (!empty($_GET['cate'])) {
    $cate = explode(",", $_GET['cate']);
    $catePlaceholders = implode(",", array_fill(0, count($cate), "?"));
    $where[] = "ca.name IN ($catePlaceholders)";
    $params = array_merge($params, $cate);
    $types .= str_repeat("s", count($cate));
}

// Lọc theo màu sắc từ bảng product_inventory
if (!empty($_GET['colors'])) {
    $colors = explode(",", $_GET['colors']);
    $colorPlaceholders = implode(",", array_fill(0, count($colors), "?"));
    $where[] = "pi.color IN ($colorPlaceholders)";
    $params = array_merge($params, $colors);
    $types .= str_repeat("s", count($colors));
}

// Lọc theo kích thước từ bảng size
if (!empty($_GET['sizes'])) {
    $sizes = explode(",", $_GET['sizes']);
    $sizePlaceholders = implode(",", array_fill(0, count($sizes), "?"));
    $where[] = "s.size IN ($sizePlaceholders)";
    $params = array_merge($params, $sizes);
    $types .= str_repeat("s", count($sizes));
}

// Câu lệnh SQL với JOIN
$sql = "SELECT p.id, p.name, p.price, pi.image, s.quantity, pi.color, s.size
        FROM product p
        JOIN product_inventory pi ON p.id = pi.product_id
        LEFT JOIN size s ON pi.id = s.inven_id
        LEFT JOIN categories ca ON p.category_id = ca.id
        LEFT JOIN cate c ON c.id = ca.cate_id";

// Câu truy vấn mặc định nếu không có bộ lọc nào được chọn
$defaultQuery = "SELECT p.id, p.name, p.price, pi.image, p.quantity, pi.color, s.size
                 FROM product p
                 JOIN product_inventory pi ON p.id = pi.product_id
                 LEFT JOIN size s ON pi.id = s.inven_id
                 LEFT JOIN categories ca ON p.category_id = ca.id
                 LEFT JOIN cate c ON c.id = ca.cate_id
                 WHERE ca.cate_id = ?
                 GROUP BY p.id";

// Thêm điều kiện WHERE nếu có
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
} else {
    // Không có bộ lọc nào => dùng truy vấn mặc định
    $sql = $defaultQuery;
    $params = [$_GET['id']]; // Mặc định lấy cate_id = 1 nếu không có
    $types = "i";
}

// Sắp xếp theo giá nếu có yêu cầu
if (!empty($_GET['sort'])) {
    if ($_GET['sort'] == "price_asc") {
        $sql .= " ORDER BY p.price ASC";
    } elseif ($_GET['sort'] == "price_desc") {
        $sql .= " ORDER BY p.price DESC";
    }
}

// Thực thi truy vấn
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Hiển thị sản phẩm
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="product-item">';
        if ($row['quantity'] == 0) {
            echo '<span class="out-of-stock">HẾT HÀNG</span>';
        }
        
        echo '<a href="index.php?page_layout=detail&id=' . $row['id'] . '">';
        echo '<img src="assets/uploads/' . $row["image"] . '" alt="' . htmlspecialchars($row["name"]) . '">';
        echo '</a>';

        echo '<div class="product-actions">';
        echo '<button class="quick-view" data-id=' . $row['id'] . '>';
        echo    '<span>Xem nhanh <i class="fa fa-eye"></i></span>';
        echo '</button>';
        echo    '<span>|</span>';
        echo '<button class="buy" data-id=' . $row['id'] . '>';
        echo    '<span>Mua ngay <i class="fa fa-shopping-cart"></i></span>';
        echo '</button>';
        echo '</div>';

        echo '<a class="detail-product" href="index.php?page_layout=detail&id=' . $row['id'] . '">';
        echo '<h4>' . htmlspecialchars($row["name"]) 
                .' '. htmlspecialchars($row["color"]) 
                .' '. htmlspecialchars($row["size"]) . '</h4>';
        echo '<p>' . number_format($row["price"]) . 'đ</p>';
        echo '</a>';

        echo '</div>';
    }
} else {
    echo "<p>Không tìm thấy sản phẩm nào.</p>";
}

$stmt->close();
?>
