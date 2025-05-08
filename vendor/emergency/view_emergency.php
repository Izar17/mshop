<?php
require_once('./../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from emergency where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }else{
?>
		<center>Unknown order</center>
		<style>
			#uni_modal .modal-footer{
				display:none
			}
		</style>
		<div class="text-right">
			<button class="btn btndefault bg-gradient-dark btn-flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
		</div>
		<?php
		exit;
		}
}
?>
<style>
	#uni_modal .modal-footer{
		display:none
	}
    .prod-img{
        width:calc(100%);
        height:auto;
        max-height: 10em;
        object-fit:scale-down;
        object-position:center center
    }
</style>
<div class="container-fluid">
	<div class="row">
        <div class="col-3 border bg-gradient-primary"><span class="">Date and Time</span></div>
        <div class="col-9 border"><span class="font-weight-bolder"><?= isset($created_date) ? $created_date : '' ?></span></div>
        <div class="col-3 border bg-gradient-primary"><span class="">Customer Name</span></div>
        <div class="col-9 border"><span class="font-weight-bolder"><?= isset($name) ? $name : '' ?></span></div>
        <div class="col-3 border bg-gradient-primary"><span class="">Contact Number</span></div>
        <div class="col-9 border"><span class="font-weight-bolder"><?= isset($contact) ? $contact : '' ?></span></div>
        <div class="col-3 border bg-gradient-primary"><span class="">Location</span></div>
        <div class="col-9 border"><span class="font-weight-bolder">
        <input type="hidden" id="lat" value="<?= isset($lat) ? $lat : '' ?>" disabled/>
        <input type="hidden" id="lng" value="<?= isset($lat) ? $lng : '' ?>" disabled/> 
        <div id="map"></div> 
        </span></div>
        <div class="col-3 border bg-gradient-primary"><span class="">Status</span></div>
        <div class="col-9 border"><span class="font-weight-bolder">
            <?php 
            $status = isset($status) ? $status : '';
                switch($status){
                    case 0:
                        echo '<span class="badge badge-danger bg-gradient-danger px-3 rounded-pill">Emergency</span>';
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
                    default:
                        echo '<span class="badge badge-light bg-gradient-light border px-3 rounded-pill">N/A</span>';
                        break;
                }
            ?>
        </div>
    </div>
    <div class="clear-fix mb-2"></div>
    <div id="order-list" class="row">
  
    </div>
	<div class="clear-fix mb-3"></div>
	<div class="text-right">
		    <button class="btn btn-default bg-gradient-green text-light btn-sm btn-flat" type="button" id="accept_emergency">Accept</button>
		    <button class="btn btn-default bg-gradient-danger text-light btn-sm btn-flat" type="button" id="decline_emergency">Decline</button>
		<button class="btn btn-default bg-gradient-dark text-light btn-sm btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
	</div>
</div>

  <style>
    #map {
      height: 400px;
      width: 100%;
    }
  </style>
<script>
    

    $(function(){
        $('#update_status').click(function(){
            uni_modal_second("Update Order Status - <b><?= isset($code) ? $code : '' ?></b>","orders/update_status.php?id=<?= isset($id) ? $id : '' ?>")
        })
    })
    
    function initMap() {
        const lat = parseFloat(document.getElementById("lat").value);
        const lng = parseFloat(document.getElementById("lng").value);

        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: { lat: lat, lng: lng }
        });

        new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: "Location Marker"
        });
    }



    $(function(){
        $('#accept_emergency').click(function(){
            _conf("Are you sure to accept this emergency?","accept_emergency",['<?= isset($id) ? $id : '' ?>'])
        })
    })
    function accept_emergency($id){
        start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=accept_emergency",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
    }
    $(function(){
        $('#decline_emergency').click(function(){
            _conf("Are you sure to decline this emergency?","decline_emergency",['<?= isset($id) ? $id : '' ?>'])
        })
    })
    function decline_emergency($id){
        start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=decline_emergency",
			method:"POST",
			data:{id: $id}, 
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARR2m90UPtv_f9weMTg21q4OYuB6h4eBQ&callback=initMap" async defer></script>