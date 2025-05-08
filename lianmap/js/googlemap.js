var map;
var geocoder;

function loadMap() {
  var pune = {lat: 14.038413, lng: 120.654508};
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 16,
    center: pune
  });

  var marker = new google.maps.Marker({
    map: map
  });

  var cdata = JSON.parse(document.getElementById('data').innerHTML);
  geocoder = new google.maps.Geocoder();
  codeAddress(cdata);

  var allData = JSON.parse(document.getElementById('allData').innerHTML);
  showAllColleges(allData);

  // Get the current location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var currentLocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      console.log("Current location: ", currentLocation);
      placeCurrentLocationMarker(currentLocation);
    }, function(error) {
      console.error("Geolocation failed: ", error);
      alert("Geolocation failed. Please allow location access.");
    });
  } else {
    alert("Geolocation is not supported by this browser.");
  }
}

function showAllColleges(allData) {
  var infoWind = new google.maps.InfoWindow;
  Array.prototype.forEach.call(allData, function(data){
    var content = document.createElement('div');
    var strong = document.createElement('strong');
    var linebreak = document.createElement("br");
    strong.setAttribute('style', 'white-space: pre;');
    content.setAttribute('style', 'font-size:15px !important;');
    data.unShop = data.population - (data.fdose + data.fully);
    strong.textContent = data.shop_name + '\r\n Owner: ' + data.shop_owner + '\r\n Services: Repair, Maintenance, Vulcanizing' + '\r\n Location: ' + data.address + '\r\n Contact #: ' + data.contact;
    content.appendChild(strong);

    var svgIcon = `
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
      <path fill="${data.marker_color}" d="M12 2C8.13 2 5 5.13 5 9c0 4.75 7 13 7 13s7-8.25 7-13c0-3.87-3.13-7-7-7z"/>
    </svg>
  `;
  
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(data.lat, data.lng),
    icon: {
      url: "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svgIcon),
      scaledSize: new google.maps.Size(40, 40)
    },
    map: map
  });

    marker.addListener('click', function(){
      infoWind.setContent(content);
      infoWind.open(map, marker);
    });

    marker.addListener('mouseover', function(){
      infoWind.setContent(content);
      infoWind.open(map, marker);
    });
  });
}

function codeAddress(cdata) {
  Array.prototype.forEach.call(cdata, function(data){
    var address = data.name + ' ' + data.address;
    geocoder.geocode({ 'address': address }, function(results, status) {
      if (status == 'OK') {
        map.setCenter(results[0].geometry.location);
        var points = {};
        points.id = data.id;
        points.lat = map.getCenter().lat();
        points.lng = map.getCenter().lng();
        updateCollegeWithLatLng(points);
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  });
}

function updateCollegeWithLatLng(points) {
  $.ajax({
    url: "action.php",
    method: "post",
    data: points,
    success: function(res) {
      console.log(res);
    }
  });
}

function placeCurrentLocationMarker(location) {
  console.log("Placing marker at: ", location); // Log the location
  var currentMarker = new google.maps.Marker({
    position: location,
    map: map,
    title: "You are here",
    icon: {
      path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
      scale: 8,
      fillColor: 'red',
      fillOpacity: 1,
      strokeColor: 'black',
      strokeWeight: 1
    }
  });
  console.log("Placed current location marker: ", currentMarker);
}
