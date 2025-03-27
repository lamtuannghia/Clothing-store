<?php
include "../config/connect.php";

if (isset($_POST['cate_id'])) {
    $cate_id = intval($_POST['cate_id']);
    
    $query = "SELECT id, name FROM categories WHERE cate_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cate_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="" disabled selected>---Select category---</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
}
?>