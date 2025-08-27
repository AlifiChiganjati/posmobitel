<html>
  <head>
    <title>Reverse Geocoding</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="module" src="index.js"></script>
    <script>

    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.watchPosition(showPosition);
      } else {
       //x.innerHTML = "Geolocation is not supported by this browser.";
       document.getElementById("message").innerHTML = "Geolocation is not supported by this browser.";
      }
    }

    function showPosition(position) {
    	/*
        x.innerHTML="Latitude: " + position.coords.latitude +
        "<br>Longitude: " + position.coords.longitude;
    	*/
    	//document.getElementById("la").value = position.coords.latitude;
    	//document.getElementById("lo").value = position.coords.longitude;

      document.getElementById("latlng").value = position.coords.latitude + "," + position.coords.longitude;
    }

      window.onload=function(){
        getLocation();
        var form = document.getElementById("submit");
		    form.click();
      }
    </script>
  </head>
  <body>
    <div id="floating-panel">
      <input id="latlngxxxx" type="hidden" value="-8.6366693,115.2520797" />
      <input id="latlng" type="text" value="" />
      <input id="submit" type="button" value="Reverse Geocode" />
    </div>
    <div id="message"></div>
    <div id="map"></div>

    <!--
      The `defer` attribute causes the callback to execute after the full HTML
      document has been parsed. For non-blocking uses, avoiding race conditions,
      and consistent behavior across browsers, consider loading using Promises.
      See https://developers.google.com/maps/documentation/javascript/load-maps-js-api
      for more information.
      -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQTXN3zG4OFmkqlpK1iTN9datk2lssc08&callback=initMap&v=weekly"
      defer
    ></script>
  </body>
</html>
