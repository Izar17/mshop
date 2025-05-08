<?php require_once('./../config.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Access Google Maps API in PHP</title>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="js/googlemap.js"></script>
    <style type="text/css">
        .container {
            height: 100vh; /* Full viewport height */
            display: flex;
            flex-direction: column; /* Change direction to column for better responsiveness */
        }
        #map {
            flex: 1; /* Allow map to take up remaining space */
            width: 100%;
            border: 1px solid blue;
        }
        #data, #allData {
            display: none;
        }
        #location {
            width: 100%;
            max-height: 30%; /* Adjust the height based on your need */
            padding: 10px;
            overflow-y: auto;
        }

        @media (min-width: 768px) {
            .container {
                flex-direction: row; /* Change direction to row for wider screens */
            }
            #map {
                width: 80%;
            }
            #location {
                width: 20%;
                max-height: 100%;
            }
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: white;
            padding: 10px;
            width: 60%;
            max-height: 70vh;
            margin: 5% auto;
            border-radius: 5px;
            text-align: center;
            overflow-y: auto;
        }

        .close {
            float: right;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
<body>
<div class="modal fade rounded-0" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header rounded-0">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body rounded-0">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
    <div class="container">
        
        <?php 
            require 'education.php';
            $edu = new education;
            $coll = $edu->getCollegesBlankLatLng();
            $coll = json_encode($coll, true);
            echo '<div id="data">' . $coll . '</div>';

            $allData = $edu->getAllColleges();
            $allData = json_encode($allData, true);
            echo '<div id="allData">' . $allData . '</div>';    

        ?>
        <div id="map"></div>
        
        <div id="location">
        
        <button id="openModal">Show emergency status</button>
        <hr>
        <style>
        #openModal {
            background-color: #ff4d4d; /* Red background */
            color: white; /* White text */
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width:100%;
        }

        #openModal:hover {
            background-color: #cc0000; /* Darker red on hover */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }
        
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: grey;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Emergency Status</h2>
                <table style="width:100%;border: 1px;">
                    <thead>
                    <tr>
                    <th width="50px">#</th>
                    <th>Date</th>
                    <th>Shop</th>
                    <th>Status</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $cus_id = $_settings->userdata('id');
                    $i = 1;
                    $orders = $conn->query("SELECT e.id, e.created_date, e.status, vl.shop_name from emergency e inner join vendor_list vl on vl.id = e.vendor_id where customer_id = $cus_id");

                    while($row = $orders->fetch_assoc()):
                    ?>
                    <tr>
                    <td><?= $i++; ?></td>
                    <td><?= date("Y-m-d H:i", strtotime($row['created_date'])) ?></td>
                    <td><?= $row['shop_name'] ?></td>
                    <td>
                    <?php 
                                switch($row['status']){
                                    case 0:
                                        echo '<span class="badge badge-secondary bg-gradient-secondary px-3 rounded-pill">Pending</span>';
                                        break;
                                    case 1:
                                        echo '<span class="badge badge-primary bg-gradient-primary px-3 rounded-pill">Accepted, Mecanic is on the way!</span>';
                                        break;
                                    case 2:
                                        echo '<span class="badge badge-info bg-gradient-info px-3 rounded-pill">Declined, No available mecanic!</span>';
                                        break;
                                    case 3:
                                        echo '<span class="badge badge-warning bg-gradient-success px-3 rounded-pill">Fixed</span>';
                                        break;
                                    case 4:
                                        echo '<span class="badge badge-success bg-gradient-success px-3 rounded-pill">Cancelled</span>';
                                        break;
                                    default:
                                        echo '<span class="badge badge-light bg-gradient-light border px-3 rounded-pill">N/A</span>';
                                        break;
                                }
                            ?>
                    </td>
                    <td>
                    <button class="btn btn-default bg-gradient-green text-light btn-sm btn-flat" type="button" id="cancel_emergency" onclick="cancel_emergency(<?= $row['id'] ?>)">Cancel</button>
                    <button class="btn btn-default bg-gradient-danger text-light btn-sm btn-flat" type="button" id="fixed_emergency" onclick="fixed_emergency(<?= $row['id'] ?>)">Fixed</button>
                    </td>
                    </tr>
                    
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <span class="mx-2"><?= $_settings->userdata('lastname') ?>, <?= $_settings->userdata('firstname') ?></span>
        <input type="hidden" id="customerId" value="<?= $_settings->userdata('id') ?>"/>
        <input type="hidden" id="customerName" value="<?= $_settings->userdata('lastname') ?>, <?= $_settings->userdata('firstname') ?>"/>
        <input type="hidden" id="customerContact" value="<?= $_settings->userdata('contact') ?>"/>
            <h2>Your Location</h2>
            <p id="coordinates"></p>
            <h2>Nearest by locations:</h2>
            <div id="allShops"></div>
            <!-- jQuery (Load First) -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <!-- Bootstrap JavaScript (Load After jQuery) -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
            // Automatically detect location when the page loads
            window.onload = function() {
                getLocation();
            };

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    document.getElementById("coordinates").innerHTML = "Geolocation is not supported by this browser.";
                }
            }

            function showPosition(position) {
                let lat = position.coords.latitude;
                let lng = position.coords.longitude;

                document.getElementById("coordinates").innerHTML =
                    "Latitude: " + lat +
                    "<br>Longitude: " + lng;

                // Use Google Maps Geocoding API to get location name
                $.ajax({
                    url: `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=YOUR_API_KEY`,
                    method: 'GET',
                    success: function(response) {
                        if (response.status === 'OK') {
                            let locationName = response.results[0].formatted_address;
                            document.getElementById("coordinates").innerHTML =
                                "Location: " + locationName +
                                "<br>Lat:" + lat +
                                "<br>Lng:" + lng;
                        } else {
                            document.getElementById("coordinates").innerHTML =
                                "Unable to retrieve your location name." +
                                "<br>Lat: <input type='text' value='" + lat + "' id='lat' disabled/>" +
                                "<br>Lng: <input type='text' value='" + lng + "' id='lat' disabled/>";
                        }
                    },
                    error: function() {
                        document.getElementById("coordinates").innerHTML =
                            "Error occurred while retrieving location name.";
                    }
                });

                showAllShops(lat, lng);
            }

            function showAllShops(userLat, userLng) {
                let allData = JSON.parse(document.getElementById('allData').innerHTML);
                let shopsWithDistance = [];

                for (let i = 0; i < allData.length; i++) {
                    let shop = allData[i];
                    let distance = getDistance(userLat, userLng, shop.lat, shop.lng);
                    shopsWithDistance.push({
                        ...shop,
                        distance: distance
                    });
                }

                // Sort shops by distance
                shopsWithDistance.sort((a, b) => a.distance - b.distance);

                let allShopsHTML = '';
                let motorShops = []; // Array to store motor shops data for insertion

                for (let i = 0; i < shopsWithDistance.length; i++) {
                    let shop = shopsWithDistance[i];
                    let shopDetails = {
                        name: shop.shop_name,
                        details: "Owner: " + shop.shop_owner + "\n" +
                                 "Contact: " + shop.contact + "\n" +
                                 "Distance: " + shop.distance.toFixed(2) + " km\n" +
                                 "Address: " + (shop.address ? shop.address : "No address provided")
                    };
                    motorShops.push(shopDetails);

                    allShopsHTML += "<span style='display: inline-block;width:20px;height:20px;border-radius:100px;border: solid 2px;" + 
                                "background-color:" + shop.marker_color + ";'></span> <strong style='font-size:16px;'>" + shop.shop_name + "</strong>" +
                                "<br>Owner: " + shop.shop_owner +  
                                "<br>Shop ID: " + shop.id + 
                                "<br>Contact: " + shop.contact +  
                                "<br>Distance: " + shop.distance.toFixed(2) + " km" +  
                                "<br>Address: " + (shop.address ? shop.address : "No address provided") +  
                                `<br><a href="#" onclick="navigator.geolocation.getCurrentPosition(function(position) {
                                    askForHelp(position.coords.latitude, position.coords.longitude,` + shop.id + `); 
                                });">Ask for emergency help?</a>` +  
                                "<br><br>";
                                }

                document.getElementById("allShops").innerHTML = allShopsHTML;
                
                // Call the PHP function to insert motor shops data
                $.ajax({
                    url: 'insert_motor_shops.php',
                    method: 'POST',
                    data: { motorShops: JSON.stringify(motorShops) }, // Ensure JSON data is sent as a string
                    success: function(response) {
                        console.log('Motor shops inserted successfully');
                        console.log(response); // Log the response for debugging
                    },
                    error: function(xhr, status, error) {
                        console.error('Error inserting motor shops: ' + error);
                    }
                });
            }
            
            function getDistance(lat1, lng1, lat2, lng2) {
                const R = 6371; // Radius of the Earth in km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLng = (lng2 - lng1) * Math.PI / 180;
                const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                          Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                          Math.sin(dLng/2) * Math.sin(dLng/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                const distance = R * c;
                return distance;
            }

           
                function askForHelp(lat,lng,shopId) {
                    let userResponse = confirm("Do you need emergency help?");
                    if (userResponse) {
                    // Example data (replace with actual user input)
                    let name = document.getElementById('customerName').value;
                    let contact = document.getElementById('customerContact').value;
                    let customerId = document.getElementById('customerId').value;
                    let status = "0";
                    // Send data to the server via an AJAX request
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "insert_emergency.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                        alert("Emergency sent successfully! Please wait shop to accept. Thank you!");
                        window.location.reload(true);
                        }
                    };
                    xhr.send(`name=${name}&contact=${contact}&lat=${lat}&lng=${lng}&status=${status}&shopId=${shopId}&customerId=${customerId}`);
                    } else {
                    alert("Stay safe!");
                    }
                }

                
            const modal = document.getElementById("myModal");
            const openModalBtn = document.getElementById("openModal");
            const closeBtn = document.querySelector(".close");

            openModalBtn.onclick = function() {
                modal.style.display = "block";
            }

            closeBtn.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            }

            var _base_url_ = '<?php echo base_url ?>';
            // function 
window.alert_toast = function($msg = 'TEST', $bg = 'success', $pos = '') {
    var Toast = Swal.mixin({
        toast: true,
        position: $pos || 'top',
        showConfirmButton: false,
        timer: 5000
    });
    Toast.fire({
        icon: $bg,
        title: $msg
    })
}

            function start_loader(){
	$('body').prepend('<div id="preloader"></div>')
}
function end_loader(){
	 $('#preloader').fadeOut('fast', function() {
        $(this).remove();
      })
}
 $(document).ready(function(){
            window._conf = function($msg='',$func='',$params = []){
                $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
                $('#confirm_modal .modal-body').html($msg)
                $('#confirm_modal').modal('show')
                }
            })
            
            // function cancel_emergency($id){
            //     _conf("Are you sure to cancel this emergency?","cancel_emergency_2",['<?= isset($id) ? $id : '' ?>'])
            //     modal.style.display = "none";
            // }
            function cancel_emergency($id){
                alert('Emergency Cancelled!');
                start_loader();
                $.ajax({
                    url:_base_url_+"classes/Master.php?f=cancel_emergency",
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
            // $(function(){
            //     $('#fixed_emergency').click(function(){
            //         _conf("Are you sure to decline this emergency?","fixed_emergency",['<?= isset($id) ? $id : '' ?>'])
            //         modal.style.display = "none";
            //     })
            // })
            function fixed_emergency($id){
                alert('Fixed');
                start_loader();
                $.ajax({
                    url:_base_url_+"classes/Master.php?f=fixed_emergency",
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
        </div>
    </div>

</body>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARR2m90UPtv_f9weMTg21q4OYuB6h4eBQ&callback=loadMap">
</script>
</html>
