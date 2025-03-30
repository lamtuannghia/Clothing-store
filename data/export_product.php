<?php
    include "../config/connect.php";
    // Lấy danh sách sản phẩm
    $sql = "SELECT p.id, p.name, c.name AS category, p.price 
    FROM product p 
    JOIN categories c ON p.category_id = c.id";
    $result = $conn->query($sql);

    $products = [];

    while ($row = $result->fetch_assoc()) {
    $product_id = $row["id"];

    // Lấy danh sách biến thể màu sắc của sản phẩm
    $variant_sql = "SELECT id, color, image FROM product_inventory WHERE product_id = $product_id";
    $variant_result = $conn->query($variant_sql);

    $variants = [];
    while ($variant = $variant_result->fetch_assoc()) {
    // Lấy danh sách size và số lượng của từng biến thể màu sắc
    $size_sql = "SELECT size, quantity FROM size WHERE inven_id = ". $variant["id"]."";
    $size_result = $conn->query($size_sql);

    $sizes = [];
    while ($size = $size_result->fetch_assoc()) {
        $sizes[] = [
            "size" => $size["size"],
            "quantity" => (int) $size["quantity"]
        ];
    }

    $variants[] = [
        "color" => $variant["color"],
        "image" => $variant["image"],
        "sizes" => $sizes
    ];
    }

    $products[] = [
    "id" => $product_id,
    "name" => $row["name"],
    "category" => $row["category"],
    "price" => (int) $row["price"],
    "variants" => $variants
    ];
    }

    // Trả về JSON
    header("Content-Type: application/json");
    echo json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $conn->close();
?>