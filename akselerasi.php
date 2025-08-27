<?php
$title = "AKSELERASI";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");

$idsales = $_SESSION['IDSALES'];
$depo = $_SESSION['DEPO'];
$gudang = $_SESSION['IDSTORE'];
$subcluster = $_SESSION['SUBCLUSTER'];

if($_POST['proses'] == "KIRIM"){
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']): "";
	$combosakti = isset($_POST['combosakti'])? cleanall($_POST['combosakti']) : "0";
	//$hvccombo = isset($_POST['hvccombo'])? cleanall($_POST['hvccombo']) : "";
	$digital = isset($_POST['digital'])? cleanall($_POST['digital']) : "0";
	$ketengan = isset($_POST['ketengan'])? cleanall($_POST['ketengan']) : "0";
	$disneyhotstar = isset($_POST['disneyhotstar'])? cleanall($_POST['disneyhotstar']) : "0";
	$voice = isset($_POST['voice'])? cleanall($_POST['voice']) : "0";

	if($idsales && $idcustomer){
		$nors = "0". substr($idcustomer, 2); // LA81338370040 = 0813383700040
			$arrsales = data_salesman($idsales);
				if($arrsales){
					if(is_exist_customer($idcustomer)){
					$transdate = date("d/m/Y");
					//if($cluster == "FLOSUM"){
					//	$tipe = "LAN-FL";
					//	$coa = "5001028";
					//	$iscoa = true;
					//}else if($cluster == "KUTAI"){
					//	$tipe = "LAN-KT";
					//	$coa = "5001032";
					//	$iscoa = true;
					//}else{
					//	$iscoa = false;
					//}
					if($subcluster == "KUTAI"){
						$tipe = "LAN-KT";
						$coa = "5001032";
						$iscoa = true;
					}else if($subcluster == "ENDE SIKKA"){
						$tipe = "LAN-ES";
						$coa = "5001028";
						$iscoa = true;
					}else if($subcluster == "FLORES TIMUR"){
						$tipe = "LAN-FT";
						$coa = "5001028";
						$iscoa = true;
					}else if($subcluster == "MANGGARAI"){
						$tipe = "LAN-FB";
						$coa = "5001028";
						$iscoa = true;
					}else if($subcluster == "SUMBA"){
						$tipe = "LAN-SB";
						$coa = "5001028";
						$iscoa = true;
					}else{
						$iscoa = false;
					}
					if($combosakti || $digital || $ketengan || $disneyhotstar || $voice) {
						//AKSELERASI - LA81236927415 - ABCABC CELL - RJW12313
						//$gudang = "UTAMA"; //karena mengurangi stok di gudang utama
						$gudang = "GUDANG ELEKTRIK - HQ";
						if($iscoa){
							if($combosakti){
								if(cekstatus_akselereasi($idsales, "COMBO SAKTI", $combosakti, $idcustomer)){
									$msgo .= "Order COMBO SAKTI = $combosakti sudah pernah terjadi";
								}else{
									$total = $combosakti * 1000;
									$ext = rand(100,999);
									$nofaktur = "IA.".$arrsales->id_department .'.'. $idsales .".". date("mY-dHis") . $ext;

									mysql_query("insert ignore into akselerasi (tanggal, idsalesman, id_product, qty, unit_price, total, warehouse, id_customer, detail_notes, description, no_faktur, transdate, via, data1, data2, data3)
									 value (now(), '$idsales', 'COMBO SAKTI', '$combosakti', 1000, $total, '$gudang', '$idcustomer', 'AKSELERASI COMBO SAKTI - $idcustomer - $idsales', '$nors', '$nofaktur', '$transdate', '$coa', '$cluster', '$tipe', '$depo')");
									 $msgo .= "Order COMBO SAKTI berhasil - ";

								}
							}

							if($digital){
								if(cekstatus_akselereasi($idsales, "DIGITAL", $digital, $idcustomer)){
									$msgo .= "Order DIGITAL = $digital sudah pernah terjadi";
								}else{
									$total = $digital * 1000;
									$ext = rand(100,999);
									$nofaktur = "IA.".$arrsales->id_department .'.'. $idsales .".". date("mY-dHis"). $ext;
									mysql_query("insert ignore into akselerasi (tanggal, idsalesman, id_product, qty, unit_price, total, warehouse, id_customer, detail_notes, description, no_faktur, transdate, via, data1, data2, data3)
									 value (now(), '$idsales', 'DIGITAL', '$digital', 1000, $total, '$gudang', '$idcustomer', 'AKSELERASI DIGITAL - $idcustomer - $idsales', '$nors', '$nofaktur', '$transdate', '$coa', '$cluster', '$tipe', '$depo')");
									 $msgo .= "Order DIGITAL berhasil - ";

							 }
							}

							if($ketengan){
								if(cekstatus_akselereasi($idsales, "KETENGAN", $ketengan, $idcustomer)){
									$msgo .= "Order KETENGAN = $ketengan sudah pernah terjadi";
								}else{
									$total = $ketengan * 1000;
									$ext = rand(100,999);
									$nofaktur = "IA.".$arrsales->id_department .'.'. $idsales .".". date("mY-dHis"). $ext;
									mysql_query("insert ignore into akselerasi (tanggal, idsalesman, id_product, qty, unit_price, total, warehouse, id_customer, detail_notes, description, no_faktur, transdate, via, data1, data2, data3)
									 value (now(), '$idsales', 'KETENGAN', '$ketengan', 1000, $total, '$gudang', '$idcustomer', 'AKSELERASI KETENGAN - $idcustomer - $idsales', '$nors', '$nofaktur', '$transdate', '$coa', '$cluster', '$tipe', '$depo')");
									 $msgo .= "Order KETENGAN berhasil - ";

							 }
							}

							if($disneyhotstar){
								if(cekstatus_akselereasi($idsales, "DISNEY HOTSTAR", $disneyhotstar, $idcustomer)){
									$msgo .= "Order DISNEY HOTSTAR = $disneyhotstar sudah pernah terjadi";
								}else{
									$total = $disneyhotstar * 1000;
									$ext = rand(100,999);
									$nofaktur = "IA.".$arrsales->id_department .'.'. $idsales .".". date("mY-dHis"). $ext;
									mysql_query("insert ignore into akselerasi (tanggal, idsalesman, id_product, qty, unit_price, total, warehouse, id_customer, detail_notes, description, no_faktur, transdate, via, data1, data2, data3)
									 value (now(), '$idsales', 'DISNEY HOTSTAR', '$disneyhotstar', 1000, $total, '$gudang', '$idcustomer', 'AKSELERASI DISNEY HOTSTAR - $idcustomer - $idsales', '$nors', '$nofaktur', '$transdate', '$coa', '$cluster', '$tipe', '$depo')");
									 $msgo .= "Order DISNEY HOTSTAR berhasil - ";

							 }
							}

							if($voice){
								if(cekstatus_akselereasi($idsales, "VOICE", $voice, $idcustomer)){
									$msgo .= "Order VOICE = $voice sudah pernah terjadi";
								}else{
									$total = $voice * 1000;
									$ext = rand(100,999);
									$nofaktur = "IA.".$arrsales->id_department .'.'. $idsales .".". date("mY-dHis") . $ext;
									mysql_query("insert ignore into akselerasi (tanggal, idsalesman, id_product, qty, unit_price, total, warehouse, id_customer, detail_notes, description, no_faktur, transdate, via, data1, data2, data3)
									 value (now(), '$idsales', 'VOICE', '$voice', 1000, $total, '$gudang', '$idcustomer', 'AKSELERASI VOICE - $idcustomer - $idsales', '$nors', '$nofaktur', '$transdate', '$coa', '$cluster', '$tipe', '$depo')");
									 $msgo .= "Order VOICE berhasil - ";
							 }
							}
							echo "<script language=javascript> location.href = 'index.php?nox=Notif&rex1=$msgo'; </script>";
						}else{
							$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
						      <div class='offcanvas-body small'>
						        <div class='app-info'>
						          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						          <div class='content'>
						            <h3>Not Found <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
						            <a href='#'>Akun Penyesuaian tidak ditemukan </a>
						          </div>
						        </div>
						      </div>
						    </div>";
						}
					}else{
						$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
								<div class='offcanvas-body small'>
									<div class='app-info'>
										<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
										<div class='content'>
											<h3>Data Kosong <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
											<a href='#'>Salah satu kolom harus diisi!</a>
										</div>
									</div>
								</div>
							</div>";
					}
				}else{
					$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
							<div class='offcanvas-body small'>
								<div class='app-info'>
									<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
									<div class='content'>
										<h3>Customer <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
										<a href='#'>ID Customer tidak dikenal, Pastikan Nomor nya di lengkapi</a>
									</div>
								</div>
							</div>
						</div>";
				}
			}else{
				$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
						<div class='offcanvas-body small'>
							<div class='app-info'>
								<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
								<div class='content'>
									<h3>Settlement <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
									<a href='#'>ID Anda tidak dapat melakukan transaksi ini / sudah settlement</a>
								</div>
							</div>
						</div>
					</div>";
				}

	}else{

		$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
				<div class='offcanvas-body small'>
					<div class='app-info'>
						<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						<div class='content'>
							<h3>Incomplete Data <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
							<a href='#'>Data tidak boleh kosong!</a>
						</div>
					</div>
				</div>
			</div>";
	}
}

?>

<script type="text/javascript">
	function suxtotal(){
		var combosakti  	= document.getElementById("combosakti").value;
		var digital  	= document.getElementById("digital").value;
		var ketengan 	= document.getElementById("ketengan").value;
		var disneyhotstar 	= document.getElementById("disneyhotstar").value;
		var voice 	= document.getElementById("voice").value;

		var total_raw = parseInt(combosakti) + parseInt(digital) + parseInt(ketengan) + parseInt(disneyhotstar) + parseInt(voice);
		var total = parseInt(total_raw) * 1000;
		document.getElementById("settotal").innerHTML = total.format(0, 3, '.', ',');
	}

	Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>


<script type="text/javascript">
function isValue(val){
	var vax = 0;
	if(!isNaN(val) && val.length!=0)
  {
      vax = parseInt(val);
  }
	return vax;
}
function findTotal(txdst){
	 var arr1 = document.getElementById("combosakti").value;
	 var arr2 = document.getElementById("digital").value;
	 var arr3 = document.getElementById("ketengan").value;
	 var arr4 = document.getElementById("disneyhotstar").value;
	 var arr5 = document.getElementById("voice").value;

	 arr1 = isValue(arr1);
	 arr2 = isValue(arr2);
	 arr3 = isValue(arr3);
	 arr4 = isValue(arr4);
	 arr5 = isValue(arr5);

	 var tot=0;
	 tot = parseInt(arr1) + parseInt(arr2) + parseInt(arr3) + parseInt(arr4) + parseInt(arr5);
	 tots = tot * 1000;
	 document.getElementById(txdst).innerHTML = tots.format(0, 3, '.', ',');}

	</script>

<!-- Main Start -->
<main class="main-wrap cart-page mb-xxl">
   <!-- Form Section Start -->
      <form class="custom-form" method="post">
        <div class="input-box">
          <i class="iconly-Call icli"></i>
          <input type="text" class="form-control" id="idcustomer" name="idcustomer" placeholder="Masukkan Nomor Reseller" onkeyup="autoCompletecustomer();" autocomplete="Off">

        </div>
        <div id="hasilcustomer" class="input-box"> </div>


        <div class="input-box">
          <i class="iconly-Profile icli"></i>
          <input type="text" placeholder="Nama Reseller" id="namacustomer" name="namacustomer" class="form-control" readonly>
        </div>

        <small class="font-xs title-color-text fw-500">Combo Sakti</small>
        <div class="input-box">
          <i class="iconly-Plus icli"></i>
          <input class="form-control" type="text" id="combosakti" name="combosakti" placeholder="Combo Sakti" onkeyup="findTotal('settotal')">
        </div>

        <small class="font-xs title-color-text fw-500">Digital</small>
        <div class="input-box">
          <i class="iconly-Plus icli"></i>
          <input class="form-control" type="text" id="digital" name="digital" placeholder="Digital" onkeyup="findTotal('settotal')">
        </div>

        <small class="font-xs title-color-text fw-500">Ketengan</small>
        <div class="input-box">
          <i class="iconly-Plus icli"></i>
          <input class="form-control" type="text" id="ketengan" name="ketengan" placeholder="Ketengan" onkeyup="findTotal('settotal')">
        </div>

        <small class="font-xs title-color-text fw-500">Disney Hotstar</small>
        <div class="input-box">
          <i class="iconly-Plus icli"></i>
          <input class="form-control" type="text" id="disneyhotstar" name="disneyhotstar" placeholder="Disney Hotstar" onkeyup="findTotal('settotal')">
        </div>

        <small class="font-xs title-color-text fw-500">Voice</small>
        <div class="input-box">
          <i class="iconly-Plus icli"></i>
          <input class="form-control" type="text" id="voice" name="voice" placeholder="Voice" onkeyup="findTotal('settotal')">
        </div>

        <small class="font-xs title-color-text fw-500">Total</small>
        <div class="input-box">
          <i class="iconly-Star icli"></i>
          <div id="settotal" class="form-controlx"> &nbsp;</div>
        </div>

        <input type="submit" class="btn-solid" name="proses" value="KIRIM">

      </form>
      <!-- Form Section End -->
    </main>
    <!-- Main End -->
		<br />
		<?=$msg?>
  <?php include("footer.inc.php");?>
