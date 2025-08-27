<html>
<head>
<title>Location</title>
<style type="text/css">
body {
  font-family: Courier;
}
input {
  margin-bottom: 5px;
}
#map-canvas {
  height: 400px;
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrkSAmNSoW7g5_Sxu9Kcxa0-tx6eFA0SA&callback=myMap"></script>

<script>
var maxLat = Math.atan(Math.sinh(Math.PI)) * 180 / Math.PI;

function initialize() {

    var center = new google.maps.LatLng(0, 0);

    var mapOptions = {
        zoom: 3,
        center: center,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

    // DOM event listener for the center map form submit
    google.maps.event.addDomListener(document.getElementById('mapCenterForm'), 'submit', function(e) {

        e.preventDefault();

        // Get lat and lng values from input fields
        var lat = document.getElementById('lat').value;
        var lng = document.getElementById('lng').value;

        // Validate user input as numbers
        lat = (!isNumber(lat) ? 0 : lat);
        lng = (!isNumber(lng) ? 0 : lng);

        // Validate user input as valid lat/lng values
        lat = latRange(lat);
        lng = lngRange(lng);

        // Replace input values
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;

        // Create LatLng object
        var mapCenter = new google.maps.LatLng(lat, lng);

        new google.maps.Marker({

            position: mapCenter,
            title: 'Marker title',
            map: map
        });

        // Center map
        map.setCenter(mapCenter);
    });
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function latRange(n) {
    return Math.min(Math.max(parseInt(n), -maxLat), maxLat);
}

function lngRange(n) {
    return Math.min(Math.max(parseInt(n), -180), 180);
}

initialize();
</script>
</head>
<body>
  <form id="mapCenterForm">
      Lat: <input type="text" id="lat" />
      <br />
      Lng: <input type="text" id="lng" />
      <br />
      <input type="submit" value="Center map" />
  </form>
  <br />
  <div id="map-canvas"></div>
</body>
</html>
