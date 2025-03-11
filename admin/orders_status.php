<?php
    include "../config/connect.php";

    $status = $_GET['status'];
    $id = $_GET['id'];

    if($status == 'success'){
        $ss = 'shipped';
    }else if($status == 'cancel'){
        $ss = 'cancelled';
    }

    $sql = "UPDATE bill
            SET status = '$ss'
            WHERE id = $id";
    $conn->query($sql);

    header("Location: index.php?page_layout=orders");
?>