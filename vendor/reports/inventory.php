<div class="content py-3">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h5 class="card-title">Inventory</h5>
        </div>
        <div class="card-body">
        <table id="inventoryTable" class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Vendor ID</th>
                <th>Category ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Date Created</th>
                <th>Date Updated</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM product_list where vendor_id = '{$_settings->userdata('id')}'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['vendor_id'] . "</td>";
                    echo "<td>" . $row['category_id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>" . ($row['status'] ? 'Active' : 'Inactive') . "</td>";
                    echo "<td>" . $row['quantity'] . "";
                    
                    if ($row['quantity'] < 5) {
                        echo "&nbsp;<i class='fas fa-exclamation-circle text-danger'></i>";
                    }
                    echo "</td>";
                    echo "<td>" . $row['date_created'] . "</td>";
                    echo "<td>" . $row['date_updated'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='12' class='text-center'>No records found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#inventoryTable').DataTable();
});
</script>
