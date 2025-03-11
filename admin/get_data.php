<?php
header('Content-Type: application/json');
require '../config/connect.php'; // Kết nối MySQL

$filter = $_GET['filter'] ?? 'week';

switch ($filter) {
    case 'week':  // Hiển thị từng ngày trong tuần
        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));
        $interval = 'P1D'; // Tăng từng ngày
        $format = 'd-m-Y';
        $groupBy = "DATE(b.time_create)";
        $queryFormat = "DATE_FORMAT(b.time_create, '%d-%m-%Y')";
        break;

    case 'month':  // Hiển thị từng tuần trong tháng
        $start_date = date('Y-m-01'); // Ngày đầu tháng
        $end_date = date('Y-m-t');   // Ngày cuối tháng
        $interval = 'P1D'; // Tăng từng tuần
        $format = 'd-m';
        $groupBy = "DATE(b.time_create)";
        $queryFormat = "DATE_FORMAT(b.time_create, '%d-%m')";
        break;

    case 'year':  // Hiển thị từng tháng trong năm
        $start_date = date('Y-01-01'); // Đầu năm
        $end_date = date('Y-12-31');  // Cuối năm
        $interval = 'P1M'; // Tăng từng tháng
        $format = 'm-Y';
        $groupBy = "YEAR(b.time_create), MONTH(b.time_create)"; 
        $queryFormat = "DATE_FORMAT(b.time_create, '%m-%Y')";
        break;

    default:
        echo json_encode(["error" => "Tham số không hợp lệ"]);
        exit();
}

// Lấy dữ liệu doanh thu thực tế
$sql = "SELECT 
            $queryFormat as date, 
            COUNT(DISTINCT b.id) as total_orders, 
            SUM(o.quantity * p.price) as total_sales 
        FROM bill b
        JOIN orders o ON b.id = o.bill_id
        LEFT JOIN product p ON o.product_id = p.id
        WHERE b.time_create BETWEEN '$start_date' AND '$end_date'
        GROUP BY $groupBy 
        ORDER BY MIN(b.time_create)";

$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['date']] = [
        "total_orders" => (int)$row['total_orders'],
        "total_sales" => (int)$row['total_sales']
    ];
}


// Tạo danh sách đầy đủ ngày/tuần/tháng
$fullData = [];
$period = new DatePeriod(new DateTime($start_date), new DateInterval($interval), new DateTime($end_date . ' 23:59:59'));

foreach ($period as $date) {
    $key = $date->format($format);
    $fullData[] = [
        "date" => $key,
        "total_orders" => $data[$key]['total_orders'] ?? 0,
        "total_sales" => $data[$key]['total_sales'] ?? 0
    ];  
}

echo json_encode(["sales_data" => $fullData], JSON_PRETTY_PRINT);
?>
