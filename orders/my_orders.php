<div class="content py-3">
    <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
            <h5 class="card-title"><b>My Orders</b></h5>
        </div>

        <div class="card-body">
            <div class="w-100 overflow-auto">
            <table class="table table-bordered table-striped">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="20%">
                    <col width="20%">
                    <col width="20%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="p1 text-center">#</th>
                        <th class="p1 text-center">Date Ordered</th>
                        <th class="p1 text-center">Ref. Code</th>
                        <th class="p1 text-center">Product</th>
                        <th class="p1 text-center">Total Amount</th>
                        <th class="p1 text-center">Payment Ref #</th>
                        <th class="p1 text-center">Status</th>
                        <th class="p1 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $orders = $conn->query("
                        SELECT ol.*, oi.product_id, pl.name AS product_name 
                        FROM `order_list` ol 
                        JOIN `order_items` oi ON ol.id = oi.order_id 
                        JOIN `product_list` pl ON oi.product_id = pl.id 
                        WHERE ol.client_id = '{$_settings->userdata('id')}' 
                        ORDER BY ol.status ASC, UNIX_TIMESTAMP(ol.date_created) DESC
                    ");

                    while($row = $orders->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="px-2 py-1 align-middle text-center"><?= $i++; ?></td>
                        <td class="px-2 py-1 align-middle"><?= date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                        <td class="px-2 py-1 align-middle"><?= $row['code'] ?></td>
                        <td class="px-2 py-1 align-middle"><?= $row['product_name'] ?></td>
                        <td class="px-2 py-1 align-middle text-right"><?= format_num($row['total_amount']) ?></td>
                        <td class="px-2 py-1 align-middle text-right"><?= $row['reference_no'] ?></td>
                        <td class="px-2 py-1 align-middle text-center">
                            <?php 
                                switch($row['status']){
                                    case 0:
                                        echo '<span class="badge badge-secondary bg-gradient-secondary px-3 rounded-pill">Pending</span>';
                                        break;
                                    case 1:
                                        echo '<span class="badge badge-primary bg-gradient-primary px-3 rounded-pill">Confirmed</span>';
                                        break;
                                    case 2:
                                        echo '<span class="badge badge-info bg-gradient-info px-3 rounded-pill">Packed</span>';
                                        break;
                                    case 3:
                                        echo '<span class="badge badge-warning bg-gradient-warning px-3 rounded-pill">Out for Delivery</span>';
                                        break;
                                    case 4:
                                        echo '<span class="badge badge-success bg-gradient-success px-3 rounded-pill">Delivered</span>';
                                        break;
                                    case 5:
                                        echo '<span class="badge badge-danger bg-gradient-danger px-3 rounded-pill">Cancelled</span>';
                                        break;
                                    case 6:
                                        echo '<span class="badge badge-primary bg-gradient-primary px-3 rounded-pill">Received</span>';
                                        break;
                                    default:
                                        echo '<span class="badge badge-light bg-gradient-light border px-3 rounded-pill">N/A</span>';
                                        break;
                                }
                            ?>
                        </td>
                        <td class="px-2 py-1 align-middle text-center">
                        <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?= $row['id'] ?>" data-code="<?= $row['code'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                           
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.view_data').click(function(){
            uni_modal("View Order Details - <b>"+($(this).attr('data-code'))+"</b>","orders/view_order.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('table').dataTable();
    })
</script>