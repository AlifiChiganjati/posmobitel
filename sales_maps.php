<?php
$title = "SALES MAPS";
session_start();
include("session_check.php");
include("connection_rti.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$user = $_SESSION['USER'];
$role = $_SESSION['ROLE'];
$name = $_SESSION['NAME'];

$arrUser = array("MOBI00011", "MOBI00077");
if(in_array($user, $arrUser)){
	$links = "https://lookerstudio.google.com/embed/reporting/b844f46b-f3d7-4505-af6a-1cf3d0c7432d/page/khZgB";
}else{
	$links = "";
}

?>

<style>
	.iframe-container {
	  overflow: hidden;
	  padding-top: 10%; 
	  position: relative;
	}

	.iframe-container iframe {
	   border: 0;
	   height: 100%;
	   left: 0;
	   position: absolute;
	   top: 0;
	   width: 100%;
	}
</style>

<!-- Main Start -->
<main class="main-wrap address1-page">
<div class="iframe-container">
	 <iframe id="myIframe"  width="500" height="100%" src="<?=$links; ?>" frameborder="0" style="border:0" allowfullscreen></iframe>						
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
	</div>

</main>
 
	<!-- Main End -->
<?php include("footer_alt.inc.php");?>
