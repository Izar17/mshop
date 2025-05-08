<div class="content py-3">
    <div class="card card-outline card-primary shadow rounded-0">
        <div class="card-header">
            <div class="h5 card-title">Checkout</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <form action="" id="checkout-form">
                    <div class="form-group">
                    <?php 
                    
                    $selectedItems = isset($_GET['items']) ? explode(',', $_GET['items']) : [];
                    $selId = implode(', ', $selectedItems);
                    echo "<input type='hidden' value ='$selId' id='selId'/>";
                $gtotal = 0;
                $vendors = $conn->query("SELECT * FROM `vendor_list` where id in (SELECT vendor_id from product_list where id in (SELECT product_id FROM `cart_list` where id in ($selId) and client_id ='{$_settings->userdata('id')}')) order by `shop_name` asc");
                while($vrow=$vendors->fetch_assoc()):                
                ?>
                    <div class="col-12 border">
                        <span><b>Vendor: </b><?= $vrow['code']. " - " . $vrow['shop_name'] . " - <b>Gcash # " . $vrow['contact'] ?></b></span>
                    </div>
                    
                    <?php endwhile; ?> <br>
                        <label for="delivery_address" class="control-label">Delivery Address</label>
                        <textarea name="delivery_address" id="delivery_address" rows="4" class="form-control rounded-0" required><?= $_settings->userdata('address') ?></textarea>
                        
                        <style>
    .payment-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-family: Arial, sans-serif;
    }
    
    .payment-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        border-radius: 5px;
        transition: 0.3s ease-in-out;
    }

    .payment-option input {
        transform: scale(1.2);
    }

    .payment-option label {
        cursor: pointer;
        font-weight: bold;
    }

    /* Add background color on hover */
    .payment-option:hover {
        background-color: #f0f0f0;
    }

</style>

<div class="payment-container">
    <br>
    <label for="mode_of_payment" class="control-label"><strong>Mode of Payment</strong></label>
    
    <div class="payment-option">
        <input type="radio" name="mode_of_payment" value="Cash" id="cash">
        <label for="cash">💵 Cash</label>
    </div>

    <div class="payment-option">
        <input type="radio" name="mode_of_payment" value="Gcash" id="gcash">
        <label for="gcash">📱 E-Wallet </label>
    </div>
<!-- 
    <div class="payment-option">
        <input type="radio" name="mode_of_payment" value="Bank" id="bank">
        <label for="bank">🏦 Bank Transfer</label>
    </div> -->
</div>
                        
                        <label for="reference_no" class="control-label">Reference Number for E-Wallet</label>
                        <input type="text" id="reference_no" name="reference_no" class="form-control form-control-sm form-control-border">                 
                    </div>

                    <script>
                    document.querySelectorAll('input[name="mode_of_payment"]').forEach((elem) => {
                        elem.addEventListener("change", function(event) {
                            var referenceNo = document.getElementById('reference_no');
                            if (event.target.value === "Gcash" || event.target.value === "Bank") {
                                referenceNo.required = true;
                            } else {
                                referenceNo.required = false;
                            }
                        });
                    });
                    </script>

                        <div class="form-group text-right">
                            <button class="btn btn-flat btn-default btn-sm bg-navy">Place Order</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="row" id="summary">
                    <div class="col-12 border">
                        <h2 class="text-center"><b>Summary</b></h2>
                    </div>
                    <?php 
                    $gtotal = 0;
                    $vendors = $conn->query("SELECT * FROM `vendor_list` where id in (SELECT vendor_id from product_list where id in (SELECT product_id FROM `cart_list` where  id in ($selId) and client_id ='{$_settings->userdata('id')}')) order by `shop_name` asc");
                    while($vrow=$vendors->fetch_assoc()):    
                    $avtotal = $conn->query("SELECT sum(c.quantity * pa.amount) FROM `cart_list` c 
                    inner join product_list p on c.product_id = p.id 
                    LEFT JOIN product_attribute pa ON c.attribute_id = pa.id
                    where c.attribute_id >= 1 and c.client_id = '{$_settings->userdata('id')}' and p.vendor_id = '{$vrow['id']}' and c.id in ($selId)")->fetch_array()[0];  

                    $vvtotal = $conn->query("SELECT sum(c.quantity * p.price) FROM `cart_list` c 
                    inner join product_list p on c.product_id = p.id 
                    LEFT JOIN product_attribute pa ON c.attribute_id = pa.id
                    where c.attribute_id < 1 and c.client_id = '{$_settings->userdata('id')}' and p.vendor_id = '{$vrow['id']}' and c.id in ($selId)")->fetch_array()[0];   

                    $vtotal = $avtotal + $vvtotal;

                    $vtotal = $vtotal > 0 ? $vtotal : 0;
                    $gtotal += $vtotal;

                    ?>
                    <div class="col-12 border item">
                        <b class="text-muted"><small><?= $vrow['code']." - ".$vrow['shop_name'] ?></small></b>
                        <div class="text-right"><b><?= format_num($vtotal) ?></b></div>
                    </div>
                    <?php endwhile; ?>
                    <div class="col-12 border">
                        <b class="text-muted">Grand Total</b>
                        <div class="text-right h3" id="total"><b><?= format_num($gtotal) ?></b></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#checkout-form').submit(function(e){
        e.preventDefault()
        var _this = $(this)
        if(_this[0].checkValidity() == false){
            _this[0].reportValidity()
            return false;
        }
        if($('#summary .item').length <= 0){
            alert_toast("There is no order listed in the cart yet.",'error')
            return false;
        }
        $('.pop_msg').remove();
        var el = $('<div>')
            el.addClass("alert alert-danger pop_msg")
            el.hide()
        start_loader()

        var selId = document.getElementById('selId').value;
        $.ajax({
            url:_base_url_+'classes/Master.php?f=place_order',
            method:'POST',
            data:_this.serialize() + '&selId=' + selId,
            dataType:'json',
            error:err=>{
                console.error(err)
                alert_toast("An error occurred.",'error')
                end_loader()
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.replace('./?page=orders/my_orders')
                }else if(!!resp.msg){
                    el.text(resp.msg)
                    _this.prepend(el)
                    el.show('slow')
                    $('html,body').scrollTop(0)
                }else{
                    el.text("An error occurred.")
                    _this.prepend(el)
                    el.show('slow')
                    $('html,body').scrollTop(0)
                }
                end_loader()
            }
        })
    })
</script>