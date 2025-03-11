<?php
  session_start();
  if(!isset($_SESSION['admin_name'])){
    header("Location: login.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3><?php echo $_SESSION['admin_name'] ?></h3>
        <h3><?php echo $_SESSION['admin_role'] ?></h3>
        <ul>
            <li><a href="index.php?page_layout=dashboard">Dashboard</a></li>
            <li><a href="index.php?page_layout=category">Category</a></li>
            <li><a href="index.php?page_layout=products">Products</a></li>
            <li><a href="index.php?page_layout=orders">Orders</a></li>
            <?php
              if ($_SESSION['admin_role'] == 'admin') {
                echo "<li><a href='index.php?page_layout=users'>Users</a></li>";
              }
            ?>
            <li><a href="index.php?page_layout=slider">Slider</a></li>
            <li><a href="logout.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');">Log out</a></li>
        </ul>
    </div>
</body>
</html>
<?php
  include "../config/connect.php";
  if(!isset($_GET["page_layout"]))
  {
    include "dashboard.php";
  }
  if(isset($_GET["page_layout"]))
  {
    switch($_GET["page_layout"]){
        case "dashboard";
        include "dashboard.php";
        break;
    }
    switch($_GET["page_layout"]){
        case "products";
        include "products.php";
        break;
    }
    switch($_GET["page_layout"]){
        case "orders";
        include "orders.php";
        break;
    }
    switch($_GET["page_layout"]){
        case "category";
        include "category.php";
        break;
    }
    switch($_GET["page_layout"]){
      case "editcategory";
      include "editcategory.php";
      break;
    }
    switch($_GET["page_layout"]){
      case "newproduct";
      include "newproduct.php";
      break;
    }
    switch($_GET["page_layout"]){
      case "addproduct";
      include "addproduct.php";
      break;
    }
    switch($_GET["page_layout"]){
      case "detailproduct";
      include "detailproduct.php";
      break;
    }
    switch($_GET["page_layout"]){
      case "editproduct";
      include "editproduct.php";
      break;
    }
    switch($_GET["page_layout"]){
      case "editnewpro";
      include "editnewpro.php";
      break;
    }
    switch($_GET["page_layout"]){
      case "detailorders";
      include "detailorders.php";
      break;
    }
    switch($_GET["page_layout"]){
      case "users";
      include "users.php";
      break;
    }
  }
?>