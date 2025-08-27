<!DOCTYPE html>
<html>
<body>

<h1>My First Google Map</h1>

<div id="googleMap" style="width:100%;height:400px;"></div>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQTXN3zG4OFmkqlpK1iTN9datk2lssc08&&callback=myMap"></script>
<script>
function myMap() {

var mapProp= {
  center:new google.maps.LatLng(51.508742,-0.120850),
  zoom:5,
};
var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);


//initMap();
}

/*
let map;
async function initMap() {
  const { Map } = await google.maps.importLibrary("maps");
  map = new Map(document.getElementById("googleMap"), {
    center: { lat: -34.397, lng: 150.644 },
    zoom: 8,
  });
}
initMap();

*/
</script>



</body>
</html>
