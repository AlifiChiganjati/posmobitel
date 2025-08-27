const options = {
	enableHighAccuracy: true,
	timeout: 7000,
	maximumAge: 0,
};

function success(pos) {
	const crd = pos.coords;
	//console.log("Your current position is:");
	//console.log(`Latitude : ${crd.latitude}`);
	//console.log(`Longitude: ${crd.longitude}`);
	//console.log(`More or less ${crd.accuracy} meters.`);
		
	showPosition(crd);	
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
 fetch(urls)
	    .then((response) => response.json())
	    .then((data) => {  
	      const city = data.results[0].formatted_address;  
				//console.log(`Your location is ${city}.`);
	      
	      var lola = lat + "," + lang;
	      $.ajax({
				  method: "POST",
				  url: "auto_saved_location.php",
				  data: { longlat: lola, address: city }
				})
				  .done(function( msg ) {
				    console.log("Data saved " + msg);
				  });	      
	    })
	    .catch((error) => console.log(error));
} 








