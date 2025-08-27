const optionsx = {
	enableHighAccuracy: true,
	timeout: 7000,
	maximumAge: 0,
};

function success(pos) {
	const crd = pos.coords;		
	showPosition(crd);	
}

function error(err) {
	console.warn(`ERROR(${err.code}): ${err.message}`);
}

function sleep(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}


window.onload=function(){
	navigator.geolocation.getCurrentPosition(success, error, optionsx);
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
          var ixsales = document.getElementById('salesid').innerHTML;
          var ixnama = document.getElementById('salesname').innerHTML;
          var ixrole = document.getElementById('salescluster').innerHTML;
          var ixdep = document.getElementById('salesdepartment').innerHTML;
          
	      $.ajax({
				  method: "POST",
				  url: "https://nexa.my.id/maps/auto_saved_rti.php",
				  data: { longlat: lola, address: city, idsales: ixsales, nama: ixnama, role: ixrole, dept: ixdep }
				})
				  .done(function( msg ) {
				    console.log("Data saved " + msg);
				  });	      
	    })
	    .catch((error) => console.log(error));
} 








