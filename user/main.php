<?php
    include "config/connect.php";

    $stmt = $conn->prepare("SELECT p.id, p.name, p.price, pi.image 
            FROM product p
            JOIN product_inventory pi ON p.id = pi.product_id
            GROUP BY p.id
            ORDER BY RAND() LIMIT 4");
    
    $stmt->execute();
    $result = $stmt->get_result();
?>
<head>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<div class="slideshow-container">
    <button class="slider-btn left" onclick="prevSlide()">&#10094;</button>
    <div class="slider-track" id="slider-track">
        <img src="assets/image/banner1.jpg" alt="Slide 1">
        <img src="assets/image/banner2.jpg" alt="Slide 2">
    </div>
    <button class="slider-btn right" onclick="nextSlide()">&#10095;</button>
</div>

<div class="category-grid">
    
    <div class="category-column">
        <div class="category-item" data-category="3">
            <img src="assets/image/phukien.jpg" alt="Nón">
            <div class="overlay">Phụ kiện</div>
        </div>
        <div class="category-item" data-category="2">
            <img src="assets/image/ao.jpg" alt="Áo">
            <div class="overlay">Áo</div>
        </div>
    </div>

    <div class="accessory-category">
        <div class="category-item" data-category="1">
            <img src="assets/image/quan.png" alt="Quần">
            <div class="overlay">Quần</div>
        </div>
    </div>

</div>

<div class="product-container" id="product-list">
    <h2>SẢN PHẨM NỔI BẬT</h2>
    <div class="swiper product-slider">
        <div class="swiper-wrapper">
            <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="product-item">';
                        
                        echo '<a href="index.php?page_layout=detail&id=' . $row['id'] . '">';
                        echo '<img src="assets/uploads/' . $row["image"] . '" alt="' . htmlspecialchars($row["name"]) . '">';
                        echo '</a>';

                        // echo '<div class="product-actions">';
                        // echo '<button class="quick-view" data-id=' . $row['id'] . '>';
                        // echo    '<span>Xem nhanh <i class="fa fa-eye"></i></span>';
                        // echo '</button>';
                        // echo    '<span>|</span>';
                        // echo '<button>';
                        // echo    '<span>Mua ngay <i class="fa fa-shopping-cart"></i></span>';
                        // echo '</button>';
                        // echo '</div>';

                        echo '<a class="detail-product" href="index.php?page_layout=detail&id=' . $row['id'] . '">';
                        echo '<h4>' . htmlspecialchars($row["name"]) . '</h4>';
                        echo '<p>' . number_format($row["price"]) . 'đ</p>';
                        echo '</a>';

                        echo '</div>';
                    }   
                }
            ?>
        </div>
        <!-- Nút điều hướng -->
        <!-- <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div> -->
        
    </div>
</div>

<?php include "footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    let currentIndex = 0;
    const track = document.getElementById('slider-track');
    const totalSlides = track.children.length;

    function updateSlidePosition() {
        const percentage = -(currentIndex * 100);
        track.style.transform = `translateX(${percentage}%)`;
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateSlidePosition();
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        updateSlidePosition();
    }
    // setInterval(nextSlide, 5000);
    setInterval(() => {
        nextSlide();
    }, 6000);

    // document.addEventListener("DOMContentLoaded", function () {
    // document.querySelectorAll(".quick-view").forEach(button => {
    //     button.addEventListener("click", function () {
    //         let productId = this.dataset.id; // Lấy ID từ data-id
    //         fetch("user/quick-view.php?id=" + productId)
    //             .then(response => response.text())
    //             .then(data => {
    //                 document.getElementById("quickViewContent").innerHTML = data;
    //                 document.getElementById("overlay").style.display = "block";
    //                 document.getElementById("productInfo").style.display = "block";
    //                 document.body.style.overflow = "hidden";
    //             })
    //             .catch(error => console.error("Lỗi Fetch:", error));
    //         });
    //     });
    // });

    var swiper = new Swiper(".product-slider", {
        slidesPerView: 4, // Hiển thị 4 sản phẩm mỗi lần
        spaceBetween: 20, // Khoảng cách giữa các sản phẩm
        loop: true, // Lặp vô hạn
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            1024: { slidesPerView: 4 },
            768: { slidesPerView: 2 },
            480: { slidesPerView: 1 }
        }
    });

    document.querySelectorAll(".category-item").forEach(item => {
        item.addEventListener("click", function () {
            let category = this.dataset.category;
            window.location.href = `index.php?page_layout=product&cate_id=${category}`;
        });
    });
</script>
