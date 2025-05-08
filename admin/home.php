<?php

// Fetch order counts for Shop Owners with their shop names
$shopOwnerOrders = [];
$shopNames = [];
$shopOwnerQuery = $conn->query("
    SELECT v.shop_name, COUNT(o.id) as total 
    FROM order_list o 
    JOIN vendor_list v ON o.vendor_id = v.id 
    GROUP BY v.shop_name
");
while ($row = $shopOwnerQuery->fetch_assoc()) {
    $shopOwnerOrders[$row['shop_name']] = $row['total'];
}

// Fetch order counts for Customers with their first names
$customerOrders = [];
$customerNames = [];
$customerQuery = $conn->query("
    SELECT c.firstname, COUNT(o.id) as total 
    FROM order_list o 
    JOIN client_list c ON o.client_id = c.id 
    GROUP BY c.firstname
");
while ($row = $customerQuery->fetch_assoc()) {
    $customerOrders[$row['firstname']] = $row['total'];
}

// Fetch product details with quantity and price from product_list
$productData = [];
$productQuery = $conn->query("
    SELECT p.name, SUM(oi.quantity) as total_quantity, SUM(oi.price) as total_price 
    FROM order_items oi 
    JOIN product_list p ON oi.product_id = p.id 
    GROUP BY p.name
");
while ($row = $productQuery->fetch_assoc()) {
    $productData[$row['name']] = [
        'quantity' => $row['total_quantity'],
        'price' => $row['total_price']
    ];
}

// Convert data for JavaScript
$shopOwnerData = json_encode($shopOwnerOrders);
$customerData = json_encode($customerOrders);
$productDataJSON = json_encode($productData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
.chart-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    width: 100%;
    gap: 20px;
}

.chart-box {
    flex: 1 1 60%; /* Increased width for medium size */
    min-width: 400px; /* Ensures it's not too small */
    max-width: 700px; /* Sets a comfortable max */
    text-align: center;
}

canvas {
    width: 100% !important;
    height: 300px !important; /* Adjusted height */
}
    </style>
</head>
<body>
    <h1 class=""> <?php echo $_settings->info(''); ?></h1>
    <hr>

    <div class="row">
        <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-light border elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Shop Owners</span>
                    <span class="info-box-number text-right h4">
                        <?php 
                            $total = $conn->query("SELECT COUNT(id) as total FROM vendor_list WHERE delete_flag = 0")->fetch_assoc()['total'];
                            echo format_num($total);
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-maroon elevation-1"><i class="fas fa-user-friends"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Customers</span>
                    <span class="info-box-number text-right h4">
                        <?php 
                            $total = $conn->query("SELECT COUNT(id) as total FROM client_list WHERE delete_flag = 0")->fetch_assoc()['total'];
                            echo format_num($total);
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-container row">
    <div class="chart-box">
        <canvas id="shopOwnerChart"></canvas>
    </div>
    <div class="chart-box">
        <canvas id="customerChart"></canvas>
    </div>
</div>

<div class="chart-container row">
    <div class="chart-box">
        <canvas id="productChart"></canvas>
    </div>
</div>

    <script>
   document.addEventListener("DOMContentLoaded", function() {
    var shopOwnerData = <?php echo $shopOwnerData; ?>;
    var customerData = <?php echo $customerData; ?>;
    var productData = <?php echo $productDataJSON; ?>;

    function createChart(canvasId, type, data, options) {
        new Chart(document.getElementById(canvasId), {
            type: type,
            data: data,
            options: Object.assign({
                responsive: true,
                maintainAspectRatio: false
            }, options)
        });
    }

    createChart('shopOwnerChart', 'pie', {
        labels: Object.keys(shopOwnerData),
        datasets: [{ data: Object.values(shopOwnerData), backgroundColor: ['red', 'blue', 'green', 'yellow'] }]
    }, {});

    createChart('customerChart', 'bar', {
        labels: Object.keys(customerData),
        datasets: [{ label: "Orders per Customer", data: Object.values(customerData), backgroundColor: 'blue' }]
    }, { scales: { y: { beginAtZero: true } } });

    createChart('productChart', 'bar', {
        labels: Object.keys(productData),
        datasets: [
            { label: "Total Quantity", data: Object.values(productData).map(p => p.quantity), backgroundColor: 'green' },
            { label: "Total Price", data: Object.values(productData).map(p => p.price), backgroundColor: 'orange' }
        ]
    }, { scales: { y: { beginAtZero: true } } });
});
    </script>
</body>
</html>