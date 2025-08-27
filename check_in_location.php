<?php
$title = "SALES CHECK-IN";
session_start();
include("session_check.php");
include("connection_rti.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$user=$_SESSION['USER'];
$role = $_SESSION['ROLE'];
$name = $_SESSION['NAME'];

?>
<script type="module" src="maps/index.js"></script>
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
	sleep(1000).then(() => {
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
 fetch(urls)
	    .then((response) => response.json())
	    .then((data) => {  
	      const city = data.results[0].formatted_address;  
				console.log(`Your location is ${city}.`);
				document.getElementById("address").value = city;
	      //console.log(data);
	    })
	    .catch((error) => console.log(error));
} 
      
</script>

<!-- Main Start -->
<main class="main-wrap address1-page">


  <form method="post">
	<div class="map-wrap">
    <div class="top-address" style="display: none;">
			<input id="latlng" name="latlng" type="text" value="" readonly/>
			<input id="address" name="address" type="hidden" value=""/>
			<input id="submit" type="button" value="Reverse Geocode" />
    </div>
    <div class="map-section" id="map"></div>
    <span class="navgator"><i data-feather="crosshair"></i></span>
  </div>

  <section class="location-section">
       <!-- Search Box Start -->
     <div class="search-box">
        <input class="form-control" type="text" id="keterangan" name="keterangan" placeholder="Description">
        
     </div>
     <!-- Search Box End -->

     <div class="current-box">
       <div class="media">
         <span><i data-feather="send"></i></span>
         <div class="media-body"><h2 class="font-md title-color">Use current location</h2></div>
       </div>

     </div>
     <input type="submit" class="btn-solid" id="save_location" name="save_location" value="Confirm location & proceed">
   </section>
 </form>
</main>

<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQTXN3zG4OFmkqlpK1iTN9datk2lssc08&callback=initMap&v=weekly" defer>
</script>

<?php
if(isset($_POST['save_location'])){
  $latlng = isset($_POST['latlng'])? $_POST['latlng'] : "";
  $keterangan = isset($_POST['keterangan'])? $_POST['keterangan'] : "NO DATA";
  $address = isset($_POST['address'])? $_POST['address'] : "";
  if($latlng){
      $ix = mysql_query("insert into sales_check_in_log set date_in=now(), date_on=now(), id_salesman='$user', name='$name', unit_bisnis='$role', id_customer='$keterangan', description='SALES CHECK-IN', location='$latlng', address='$address'");
      if($ix){
        //$msg = "<div class='row mt-3 mx-1 p-2 rounded border'><strong>Proses CHECK-IN Berhasil</strong></div>";
        $msg ="<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
				  <div class='offcanvas-body small'>
					<div class='app-info'>
					  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
					  <div class='content'>
						<h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
						<a href='#'>Check-In Berhasil disimpan</a>
					  </div>
					</div>
				  </div>
				</div>";
      }else{
        //$msg = "<div class='row mt-3 mx-1 p-2 rounded border'><strong>Proses CHECK-IN Gagal</strong></div>";
        $msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='4000' data-bs-autohide='true'>
							<div class='toast-body'>
								<div class='content d-flex align-items-center mb-2'>
									<h6 class='mb-0'>Pesan</h6>
									<button class='btn-close btn-warning ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
								</div><span class='mb-0 d-block'>Check-In Gagal disimpan</span>
							</div>
						</div>";
      }
  }
}
?>

<?=$msg?>
	<!-- Main End -->
<?php include("footer_alt.inc.php");?>
