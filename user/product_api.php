<?php
require '../config/connect.php'; // Kết nối database

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'get_sizes' && isset($_GET['product_id']) && isset($_GET['color'])) {
    $productId = intval($_GET['product_id']);
    $color = $_GET['color'];
    getSizes($conn, $productId, $color);
} else if($action == 'get_stock' && isset($_GET['product_id']) && isset($_GET['color']) && isset($_GET['size'])) {
    $productId = intval($_GET['product_id']);
    $color = $_GET['color'];
    $size = $_GET['size'];
    getStock($conn, $productId, $color,$size);
}else{
    echo json_encode(["error" => "Yêu cầu không hợp lệ"]);
}

function getSizes($conn, $productId, $color) {
    $sql = "SELECT s.size, s.quantity 
            FROM size s
            JOIN product_inventory pi ON s.inven_id = pi.id
            WHERE pi.product_id = ? AND pi.color = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $productId, $color);
    $stmt->execute();
    $result = $stmt->get_result();

    $sizes = [];
    while ($row = $result->fetch_assoc()) {
        $sizes[] = [
            "name" => $row["size"],
            "quantity" => $row["quantity"]
        ];
    }

    echo json_encode($sizes);
}

function getStock($conn, $productId, $color, $size) {
    $sql = "SELECT quantity 
            FROM product_inventory pi
            JOIN size s ON s.inven_id = pi.id
            WHERE pi.product_id = ? AND pi.color = ? AND s.size = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $productId, $color, $size);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo json_encode(['stock' => $row ? $row['quantity'] : 0]);

}
?>