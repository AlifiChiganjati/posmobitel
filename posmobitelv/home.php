<?php
include("connection_rti.inc.php");
$qbanner = mysql_query('select * from banner where aktif=1 order by urutan asc');
while($dbanner = mysql_fetch_object($qbanner)){
	$banner .= "<div>
			<div class='banner-box'>
			<img src='https://nexa.my.id/posrti/banner/$dbanner->image' alt='banner' class='bg-img'>
			</div>
		</div>";
}
mysql_close();
session_start();
include("session_check.php");
include("connection_main.php");
include("function.inc.php");
include("header.inc.php");
header('Access-Control-Allow-Origin: *');
if(isset($_GET['pesan'])){
	$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
		  <div class='offcanvas-body small'>
			<div class='app-info'>
			  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
			  <div class='content'>
				<h3>$nox <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
				<a href='#'>Ganti PIN Berhasil!</a>
			  </div>
			</div>
		  </div>
		</div>";
}
//$rex1 = isset($_GET['rex1']) ? $_GET['rex1'] : ""; //berhasil

$phone = $_SESSION['PHONE'];

if($_SESSION['ROLE'] == "CESA"){
	$sql = "select * from menu_group where nama_group != 'SMARTFREN' and status=1";
}else{
	$sql = "select * from menu_group where nama_group != 'SMARTFREN SF' and status=1";
}

if(strlen($phone) > 0){
	//cek id cnet
	$sql_cek = mysql_query("select name,saldo from customers_digital where user ='$user'");
	$ceks = mysql_num_rows($sql_cek);
	$btn_saldo = "";
	$menu_regis_cnet = "<a class='btn btn-dark mb-2 btn-sm' href=''>DAFTAR PRODUK DIGITAL</a>";
	$digitals = 0;
	if($ceks == 1){
		$data_cnet = mysql_fetch_object($sql_cek);
		$saldo = number_format($data_cnet->saldo);
		$menu_regis_cnet = "";
		$btn_saldo = "<a class='btn btn-dark mb-2 btn-sm' href='form_saldo.php'><small>Saldo $saldo</small></a>";
		$digitals = 1;
	}
}

?>
<style>
#loading {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background-color: white;
}
.textLoader{
  position: fixed;
  top: 40%;
}
</style>
<div id="loading">
          <span class="loader"></span>
          <div class="textLoader">
              <center>
                <img src="menu_icons/mobitels.gif" width="50%">
                <!--<br><br>
                <b>Please Wait ... </b>-->
              </center>
          </div>
    </div>    <!-- Main Start -->
    <main class="main-wrap index-page mb-xxl">
	    <section class="banner-section ratio2_1">
	        <div class="h-banner-slider">
			
				<?=$banner?>
	        </div>
      	</section>

		<?=$btn_saldo?>

		<div class="accordion" id="accordionExample">
			<?php
				
				$qry = mysql_query($sql);
				while($data=mysql_fetch_object($qry)){
					$id = $data->id;
					echo "<div class='accordion-item'>
							<h2 class='accordion-header' id='heading$id'>
							<button class='accordion-button font-md title-color collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse$id' aria-expanded='false' aria-controls='collapseOneXX'>
								$data->nama_group
							</button>
							</h2>
							<div id='collapse$id' class='accordion-collapse collapse' aria-labelledby='heading$id' data-bs-parent='#accordionExample' style=''>
							<div class='accordion-body'>
								<section class='category pt-0'>
									<div class='mb-2'>
										<div class='row gy-sm-4 gy-2 mb-1'>";
										
										if($digitals == 0){
											$qry2 = mysql_query("select * from apps_menu where id in ($data->id_menu) and id not in (8,9,10,11,12) and status=1");
										}else{
											$qry2 = mysql_query("select * from apps_menu where id in ($data->id_menu) and id not in (45) and status=1");
										}
										while($data2=mysql_fetch_object($qry2)){	
											$link = $data2->link;
											$img = $data2->img;
											$menu = $data2->menu;
											if($menu == "CHECK"){
												if($_SESSION['ROLE'] == 'CUSTOMER'){
													$link = "#";
												}
											}
											echo "<div class='col-3'>
													<div class='category-wrap'>
														<div class='bg-shape'></div>
															<a href='$link'> <img class='category img-fluid' src='iconm/$img' alt='category'> </a>
														<span class='title-color'><small>$menu</small></span>
													</div>
												</div>";	
										}

									echo "</div>
									</div>
								</section>
							</div>
							</div>
			          	</div>";

				}
			?>
        </div>
    </main>
    <?=$msg?>
<script>
var delay = 1500;
$(window).on('load', function() {
    setTimeout(function(){
        $("#loading").hide();
    },delay);
});
</script>
<?php
include("footer.inc.php");
?>
