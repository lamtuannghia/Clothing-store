<?php
    include ('config/connect.php');
    ob_start();
    session_start();
    $stmt = $conn->prepare("
        SELECT c.id, c.name, GROUP_CONCAT(ca.name ORDER BY ca.name SEPARATOR ', ') AS subcategories
        FROM cate c
        LEFT JOIN categories ca ON c.id = ca.cate_id
        GROUP BY c.id
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'subcategories' => explode(', ', $row['subcategories']) 
        ];
    }

    // $data = file_get_contents("http://localhost/ttap2/user/cart.php");
    // $num = json_decode($data, true);
?>
<div class="top-bar" id="myDiv"></div>
    <div class="container" id="myNav">
        <div class="box-logo">
            <a><img src="assets/image/logo.jpg" class="logo"></a>
        </div>
        <div class="box-nav">
            <nav>
                <ul>
                    <li><a href="index.php?page_layout=main">Trang chủ</a></li>
                    <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="index.php?page_layout=product&cate_id=<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                        <?php if (!empty($category['subcategories'][0])): ?>
                            <div class="submenu">
                                <?php foreach ($category['subcategories'] as $subcategory): ?>
                                    <a href="index.php?page_layout=product&subcate=<?php echo urlencode($subcategory); ?>"><?php echo htmlspecialchars($subcategory); ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                    <li><a href="index.php?page_layout=product">Quà Tặng</a></li>
                    <!-- <li><a href="best-seller.html">BEST SELLER</a></li>
                    <li><a href="hang-moi-ve.html">Hàng Mới Về</a></li>
                    <li><a href="xu-huong.html">XU HƯỚNG</a></li> -->
                    <li><a href="lien-he.html">LIÊN HỆ</a></li>
                </ul>
            </nav>
        </div>
        <div class="box-icon">
            
            <span class="icon-login" title="Đăng nhập">
                <?php
                    if(isset($_SESSION['user_name'])){
                        echo '<a aria-label="search" href="index.php?page_layout=account">';
                    }else{
                        echo '<a aria-label="search" href="index.php?page_layout=login">';
                    }
                ?>
                
                    <span class="account-menu" aria-hidden="true">
                        <img src="assets/image/icon-header-user.png" class="image-user">
                    </span>
                </a>
            </span>
            <span class="icon-cart" title="Giỏ hàng">
                <a aria-label="search" href="index.php?page_layout=cart">
                    <span class="cart-menu" aria-hidden="true">
                        <img src="assets/image/icon-header-cart.png" class="image-cart">
                    </span>
                </a>
            </span>
            <span class="count-holder">
                <span class="count">0</span>
            </span>
        </div>
    </div>
    <script>
            document.addEventListener("DOMContentLoaded", function () {
            fetch("user/count_cart.php") // Gọi file PHP lấy số lượng
                .then(response => response.json())
                .then(data => {
                    document.querySelector(".count").textContent = data.count; // Cập nhật số lượng
                })
                .catch(error => console.error("Lỗi khi lấy số lượng giỏ hàng:", error));
        });
        </script>
    <?php
    include "config/connect.php";
    if(!isset($_GET["page_layout"]))
    {
        include "user/main.php";
    }
    if(isset($_GET["page_layout"]))
    {
        switch($_GET["page_layout"]){
            case "main";
            include "user/main.php";
            break;
        }
        switch($_GET["page_layout"]){
            case "login";
            include "user/login.php";
            break;
        }
        switch($_GET["page_layout"]){
            case "signin";
            include "user/signin.php";
            break;
        }
        switch($_GET["page_layout"]){
            case "product";
            include "user/product.php";
            break;
        }
        switch($_GET["page_layout"]){
            case "detail";
            include "user/detail.php";
            break;
        }
        switch($_GET["page_layout"]){
            case "test";
            include "user/test.php";
            break;
        }
        switch($_GET["page_layout"]){
            case "quick-view";
            include "user/quick-view.php";
            break;
        }
        switch($_GET["page_layout"]){
            case "cart";
            include "user/cart.php";
            break;
        }
        switch($_GET["page_layout"]){
            case "cartguest";
            include "user/cartguest.php";
            break;
        }
        switch($_GET["page_layout"]){
            case "account";
            include "user/account.php";
            break;
        }
        switch($_GET["page_layout"]){
            case "edit_profile";
            include "user/edit_profile.php";
            break;
        }
    }
    // session_write_close();
    ob_end_flush();
?>