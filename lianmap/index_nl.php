<!DOCTYPE html>
<html>
<head>
    <title>Access Google Maps API in PHP</title>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
    </style>
<body>

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
            <h1>Your Location</h1>
            <p id="coordinates"></p>
            <h1>Nearest by locations:</h1>
            <div id="allShops"></div>

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
                                "Location: " + locationName;
                        } else {
                            document.getElementById("coordinates").innerHTML =
                                "Unable to retrieve your location name.";
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
                        details: "\n" + "Owner: " + shop.shop_owner  + "\n" +
                                 "Contact: " + shop.contact  + "\n" +
                                 "Distance: " + shop.distance.toFixed(2) + " km"  + "\n" +
                                 "Address: " + (shop.address ? shop.address : "No address provided")
                    };
                    motorShops.push(shopDetails);

                    allShopsHTML += "<span style='display: inline-block;width:20px;height:20px;border-radius:100px;border: solid 2px;" + "background-color:" + shop.marker_color + ";'></span> <strong style='font-size:16px;'>" + shop.shop_name + "</strong>" +
                                    "<br>Owner: " + shop.shop_owner + 
                                    "<br>Shop ID: " + shop.id +
                                    "<br>Contact: " + shop.contact + 
                                    "<br>Distance: " + shop.distance.toFixed(2) + " km" + 
                                    "<br>Address: " + (shop.address ? shop.address : "No address provided") + 
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
            </script>
        </div>
    </div>

</body>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARR2m90UPtv_f9weMTg21q4OYuB6h4eBQ&callback=loadMap">
</script>
</html>
