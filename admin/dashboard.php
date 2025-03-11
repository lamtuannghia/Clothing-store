<!-- Content -->
<div class="content">
    <h2>Dashboard</h2>
    <select id="filter">
        <option value="month">Tháng này</option>
        <option value="week">Tuần này</option>
        <option value="year">Năm nay</option>
    </select>
    <!-- Stats Boxes -->
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <h4 id="orders"></h4>
                <p>Total Orders</p>
            </div>
        </div>
        <!-- <div class="col-md-4">
            <div class="card p-3">
                <h4></h4>
                <p>Total Customers</p>
            </div>
        </div> -->
        <div class="col-md-4">
            <div class="card p-3">
                <h4 id="total"></h4>
                <p>Total Sale</p>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <canvas id="salesChart" style="max-height: 300px; margin-top: 20px;"></canvas>
</div>
<script>
    let salesChart;

    function fetchSalesData() {
        let filter = document.getElementById("filter").value;

        fetch("get_data.php?filter=" + filter)
            .then(response => response.json())
            .then(data => {
                let labels = data.sales_data.map(item => item.date);
                let sales = data.sales_data.map(item => item.total_sales);
                let orders = data.sales_data.map(item => item.total_orders);

                let totalSales = sales.reduce((sum, value) => sum + value, 0);
                let totalOrders = orders.reduce((sum, value) => sum + value, 0);

                document.getElementById("total").innerText = totalSales.toLocaleString('vi-VN') + " VND";
                document.getElementById("orders").innerText = totalOrders ;
                
                if (salesChart) salesChart.destroy();

                let ctx = document.getElementById("salesChart").getContext("2d");
                salesChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: "Doanh số",
                                data: sales,
                                backgroundColor: "rgba(54, 162, 235, 0.5)",
                                borderColor: "rgba(54, 162, 235, 1)",
                                borderWidth: 1,
                            }
                        ]
                    }
                });
            })
            .catch(error => console.error("Lỗi:", error));
    }

    document.getElementById("filter").addEventListener("change", fetchSalesData);
    fetchSalesData();
</script>