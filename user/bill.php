<?php
    include "../config/connect.php";
    session_start();
    if (!isset($_SESSION['user_name'])) {
        header("Location: ../index.php?page_layout=login");
        exit();
    }

    $sql = "SELECT c.id, p.name, p.price, c.quantity, c.size, c.color, pi.image 
        FROM cart c 
        JOIN product p ON c.product_id = p.id 
        LEFT JOIN product_inventory pi ON pi.product_id = p.id 
        WHERE c.product_id = p.id AND c.color = pi.color";

    $result = $conn->query($sql);
    $tong = 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn giao hàng</title>
    <link rel="stylesheet" href="../assets/css/bill.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
<div class="bill-container">
    <form action="payment.php" class="container" method="POST">
        <h2>Thông tin giao hàng</h2>
        <div class="form-group">
            <input type="text" id="name" name="name" placeholder="Họ và tên" value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="email" id="email" name="email" placeholder="Email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="text" id="phone" name="phone" placeholder="Số điện thoại" value="<?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="text" id="address" name="address" placeholder="Địa chỉ" required>
        </div>
        <div id="map" style="width: 100%; height: 350px; margin-bottom: 15px;"></div>
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <div class="form-group">
            <textarea id="note" name="note" rows="4" placeholder="Ghi chú"></textarea>
        </div>

        <div class="payment-method">
            <h2>Phương thức thanh toán</h2>
            <label>
                <input type="radio" name="payment" value="COD" checked> Thanh toán khi nhận hàng (COD)
            </label><br>
            <label>
                <input type="radio" name="payment" value="BANK TRANSFER"> Thanh toán chuyển khoản
            </label>
        </div>

        <div class="complete-order">
            <a href="../index.php?page_layout=cart">< Quay lại giỏ hàng</a>
            <button type="submit" class="btn-danger">Hoàn tất đơn hàng</button>
        </div>
    </form>
    <div class="item-container">
        <h2>Giỏ hàng</h2>
        <table>
            <?php
                $total_invoice = 0;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $total_price = $row["price"] * $row["quantity"];
                        $tong++;
                        echo "<tr>";
                        echo "<td><img src='../assets/uploads/" . $row["image"] . "' width='50'></td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . number_format($row["price"], 0, ',', '.') . " đ</td>";
                        echo "<td>" . $row["quantity"] . "</td>";
                        // echo "<td>
                        // <button type='button' class='quantity-btn' onclick='changeQuantity(-1)'>−</button>
                        // <input type='text' name='quantity' class='quantity-input' id='quantity' value='1' readonly>
                        // <button type='button' class='quantity-btn' onclick='changeQuantity(1)'>+</button>
                        // </td>";
                        echo "<td>" . $row["size"] . "</td>";
                        echo "<td>" . $row["color"] . "</td>";
                        echo "<td><strong>" . number_format($total_price, 0, ',', '.') . " đ</strong></td>";
                        echo "</tr>";
                        $total_invoice = $total_invoice + $total_price;
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có sản phẩm trong giỏ hàng</td></tr>";
                }
            ?>
        </table>
        <div class="total">
            <span class="label">Tạm tính :</span>
            <span class="amount" id="subtotal"><?= number_format($total_invoice, 0, ',', '.') ?> đ</span><br>
            <span class="label">Phí vận chuyển :</span>
            <span class="amount">0 đ</span>
        </div>
        <div class="total">
            <span class="label">Tổng cộng :</span>
            <span class="amount" id="total"><strong><?= number_format($total_invoice, 0, ',', '.') ?> đ</strong></span>
        </div>
        <div class="note">
            <p>Chúng tôi sẽ XÁC NHẬN đơn hàng bằng EMAIL hoặc ĐIỆN THOẠI. Bạn vui lòng kiểm tra EMAIL hoặc NGHE MÁY ngay khi đặt hàng thành công và CHỜ NHẬN HÀNG.</p>
        </div>
    </div>
</div>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // // map api
    // let map, marker, geocoder;

    // function initMap() {
    //     let defaultLocation = { lat: 10.7769, lng: 106.7009 }; // Tọa độ mặc định (TPHCM)
        
    //     map = new google.maps.Map(document.getElementById("map"), {
    //         center: defaultLocation,
    //         zoom: 15,
    //     });

    //     marker = new google.maps.Marker({
    //         position: defaultLocation,
    //         map: map,
    //         draggable: true, // Cho phép kéo thả
    //     });

    //     let autocomplete = new google.maps.places.Autocomplete(document.getElementById("address"));
    //     autocomplete.bindTo("bounds", map);

    //     autocomplete.addListener("place_changed", function () {
    //         let place = autocomplete.getPlace();
    //         if (!place.geometry) return;
    //         updateMarker(place.geometry.location);
    //     });

    //     marker.addListener("dragend", function () {
    //         let position = marker.getPosition();
    //         updateAddress(position);
    //     });
    // }
    // geocoder = new google.maps.Geocoder();
    // // Cập nhật marker và input
    // function updateMarker(location) {
    //     map.setCenter(location);
    //     marker.setPosition(location);
    //     updateAddress(location);
    // }

    // // Lấy địa chỉ từ tọa độ và hiển thị vào input
    // function updateAddress(location) {
    //     geocoder.geocode({ location: location }, function (results, status) {
    //         if (status === "OK" && results[0]) {
    //             document.getElementById("address").value = results[0].formatted_address;
    //             document.getElementById("latitude").value = location.lat();
    //             document.getElementById("longitude").value = location.lng();
    //         }
    //     });
    // }

    // // // Load Google Maps API
    // // let script = document.createElement("script");
    // // script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyDBPe2dFViWg_J8p5ZNZTk3N3T5Xdw6FOM&libraries=places&callback=initMap`;
    // // script.async = true;
    // // script.defer = true;
    // // document.head.appendChild(script);

    // Khởi tạo bản đồ Leaflet
    var map = L.map('map').setView([21.028511, 105.854444], 13); // Hà Nội

    // Thêm bản đồ nền từ OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Thêm marker vào bản đồ
    var marker = L.marker([21.028511, 105.854444], { draggable: true }).addTo(map)
            .bindPopup("<b>Hà Nội</b><br>Thủ đô Việt Nam.")
            .openPopup();

    // Sự kiện click để thêm hoặc di chuyển marker
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // Nếu đã có marker, di chuyển nó thay vì tạo mới
        if (!marker) {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            // Sự kiện khi kéo thả marker
            marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                getAddress(position.lat, position.lng);
            });
        } else {
            marker.setLatLng([lat, lng]);
        }

        // Lấy địa chỉ từ tọa độ
        getAddress(lat, lng);
    });

    // Hàm lấy địa chỉ từ tọa độ bằng OpenStreetMap API
    function getAddress(lat, lng) {
        var url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                var address = data.display_name || "Không tìm thấy địa chỉ";
                document.getElementById('address').value = address;
            })
            .catch(error => console.error("Lỗi lấy địa chỉ:", error));
    }
</script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBPe2dFViWg_J8p5ZNZTk3N3T5Xdw6FOM&libraries=places&callback=initMap" async defer></script> -->
</body>
</html>