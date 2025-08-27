<html>
  <head>
    <title>Reverse Geocoding</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="module" src="index.js"></script>
    <script>

    const options = {
      enableHighAccuracy: true,
      timeout: 7000,
      maximumAge: 0,
    };

    function success(pos) {
      const crd = pos.coords;

      console.log("Your current position is:");
      console.log(`Latitude : ${crd.latitude}`);
      console.log(`Longitude: ${crd.longitude}`);
      console.log(`More or less ${crd.accuracy} meters.`);

      document.getElementById("latlng").value = crd.latitude + "," + crd.longitude;
      
     	showPosition(crd);

      var form = document.getElementById("submit");

      sleep(3000).then(() => {
        form.click();
      });
      
   
    }

    function error(err) {
      console.warn(`ERROR(${err.code}): ${err.message}`);
    }

    function sleep(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    }

    window.onload=function(){
      navigator.geolocation.getCurrentPosition(success, error, options);
    }
    
     function showPosition(position) {
          var lat = position.latitude;
          var lang = position.longitude;
          var urls = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + lang + "&key=AIzaSyBQTXN3zG4OFmkqlpK1iTN9datk2lssc08";
          //var url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${longitude}&key=YOUR_API_KEY`;

         fetch(urls)
					    .then((response) => response.json())
					    .then((data) => {
					    	
					    	
					      const city = data.results[0].formatted_address;
					     

      					console.log(`Your location is ${city}.`);

					      //console.log(data);
					    })
					    .catch((error) => console.log(error));
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
    <div id="address"></div>
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
