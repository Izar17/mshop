<style>
    .prod-img {
        width: calc(100%);
        height: auto;
        max-height: 10em;
        object-fit: scale-down;
        object-position: center center;
    }
</style>

<div class="content py-3">
    <div class="card card-outline card-primary rounded-0 shadow-0">
        <div class="card-header">
            <h5 class="card-title">Cart List</h5>
        </div>
        <div class="card-body">
            <div id="cart-list">
                <div class="row">
                    <!-- Select All Checkbox -->
                    <div class="col-12 border text-left">
                        <input type="checkbox" id="select-all" checked> <label for="select-all"><b>Select All</b></label>
                    </div>
                    
                    <?php 
                    $gtotal = 0;
                    $vendors = $conn->query("SELECT * FROM `vendor_list` WHERE id IN (SELECT vendor_id FROM product_list WHERE id IN (SELECT product_id FROM `cart_list` WHERE client_id ='{$_settings->userdata('id')}')) ORDER BY `shop_name` ASC");
                    while ($vrow = $vendors->fetch_assoc()):
                    ?>
                        <div class="col-12 border">
                            <span>Vendor: <b><?= $vrow['code'] . " - " . $vrow['shop_name'] . " - Gcash # " . $vrow['contact'] ?></b></span>
                        </div>
                        <div class="col-12 border p-0">
                            <?php 
                            $vtotal = 0;
                            $products = $conn->query("SELECT c.*, p.name AS `name`, p.price, p.image_path, pa.title, pa.amount FROM `cart_list` c 
                            INNER JOIN product_list p ON c.product_id = p.id 
                            LEFT JOIN product_attribute pa ON c.attribute_id = pa.id
                            WHERE c.client_id = '{$_settings->userdata('id')}' AND p.vendor_id = '{$vrow['id']}' ORDER BY p.name ASC");
                            while ($prow = $products->fetch_assoc()):
                                if($prow['attribute_id'] >= 1){
                                    $total = $prow['amount'] * $prow['quantity'];
                                    $gtotal += $total;
                                    $vtotal += $total;

                                } else {
                                    $total = $prow['price'] * $prow['quantity'];
                                    $gtotal += $total;
                                    $vtotal += $total;
                                }
                                
                            ?>
                            <div class="d-flex align-items-center border p-2">
                                <div class="col-1 text-center">
                                    <input type="checkbox" class="select-item" data-id="<?= $prow['id'] ?>" data-price="<?= $total ?>">
                                </div>
                                <div class="col-2 text-center">
                                    <a href="./?page=products/view_product&id=<?= $prow['product_id'] ?>">
                                        <img src="<?= validate_image($prow['image_path']) ?>" alt="" class="img-center prod-img border bg-gradient-gray">
                                    </a>
                                </div>
                                <div class="col-auto flex-shrink-1 flex-grow-1">
                                    <h4><b><?= $prow['name'] ?></b></h4>
                                    
                                    <h6><?= $prow['title'] ?></h6>
                                    <div class="d-flex">
                                        <div class="col-auto px-0"><small class="text-muted">Price: </small></div>
                                        <div class="col-auto px-0 flex-shrink-1 flex-grow-1">
                                        <p class="m-0 pl-3">
                                            <small class="text-primary">
                                                <?= ($prow['attribute_id'] >= 1) ? format_num($prow['amount']) : format_num($prow['price']); ?>
                                            </small>
                                        </p>    
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 text-right"><?= format_num($total) ?></div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <div class="col-12 border">
                            <div class="d-flex">
                                <div class="col-9 text-right font-weight-bold text-muted">Total</div>
                                <div class="col-3 text-right font-weight-bold"><?= format_num($vtotal) ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <div class="col-12 border">
                        <div class="d-flex">
                            <div class="col-9 h4 font-weight-bold text-right text-muted">Grand Total</div>
                            <div class="col-3 h4 font-weight-bold text-right" id="grand-total"><?= format_num($gtotal) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear-fix mb-2"></div>
    <div class="text-right">
        <button class="btn btn-flat btn-primary btn-sm" id="checkout-btn">
            <i class="fa fa-money-bill-wave"></i> Checkout
        </button>
    </div>
</div>

<script>
$(function(){
    // Set all checkboxes to checked by default
    $('.select-item').prop('checked', true);

    // Select All checkbox functionality
    $('#select-all').click(function(){
        $('.select-item').prop('checked', $(this).prop('checked'));
        updateGrandTotal(); // Update total when selecting all
    });

    // Individual checkbox selection
    $('.select-item').click(function(){
        if (!$('.select-item:checked').length) {
            $('#select-all').prop('checked', false);
        }
        updateGrandTotal(); // Update total when selecting an item
    });

    // Automatically calculate Grand Total on page load
    updateGrandTotal();

    // Checkout button click event
    $('#checkout-btn').click(function(){
        let selectedItems = [];
        $('.select-item:checked').each(function(){
            selectedItems.push($(this).data('id'));
        });

        if (selectedItems.length === 0) {
            alert('Please select at least one item to place an order.');
            return;
        }

        // Redirect to checkout page with selected items
        window.location.href = "./?page=orders/checkout&items=" + selectedItems.join(',');
    });

    // Function to update the Grand Total based on selected items
    function updateGrandTotal() {
        let grandTotal = 0;
        $('.select-item:checked').each(function(){
            grandTotal += parseFloat($(this).data('price'));
        });

        // Update the Grand Total value in the page
        $('#grand-total').text(grandTotal.toLocaleString());
    }
});
</script>