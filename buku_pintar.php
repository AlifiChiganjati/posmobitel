<?php
$title = "BUKU PINTAR";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");
$idsales = $_SESSION['IDSALES'];
$depo = $_SESSION['DEPO'];
$gudang = $_SESSION['IDSTORE'];
$clustero = $_SESSION['CLUSTER'];
$cluster = cluster($clustero);

$subcluster = $_SESSION['SUBCLUSTER'];

$product = '';
	$rs = mysql_query("select id_product from product_inject where tipe = 'SARA' and cluster='$cluster' and keterangan='PAKET' order by id_product");
  $i=0;
	while ($r = mysql_fetch_object($rs)) {
    $product .= "<option value='$r->id_product'> $r->id_product </option>";
    $i++;
	}
mysql_free_result($rs);

$sp = '';
	$rs = mysql_query("select id_product from product_inject where tipe = 'SARA' and cluster='$cluster' and keterangan='SP' order by id_product");
  $i=0;
	while ($r = mysql_fetch_object($rs)) {
    $sp .= "<option value='$r->id_product'> $r->id_product </option>";
    $i++;
	}
mysql_free_result($rs);


//$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']): "";
//$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']): "";

if($_POST['proses'] == "KIRIM"){
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']): "";
	$saldo = isset($_POST['saldo'])? cleanall($_POST['saldo']) : "0";
	$refund = isset($_POST['refund'])? cleanall($_POST['refund']) : "0";
	$keterangan = isset($_POST['keterangan'])? cleanall($_POST['keterangan']) : "";
	$sp = isset($_POST['sp'])? cleanall($_POST['sp']) : "";
	$pcs = isset($_POST['pcs'])? cleanall($_POST['pcs']) : "1";

	if($idsales && $idcustomer){
		$nors = "0". substr($idcustomer, 2); // LA81338370040 = 0813383700040
			$arrsales = data_salesman($idsales);
				if($arrsales){

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
					
					$bpintar = $pcs * $refund;
					$svesaldo = ($saldo * 1000) + $bpintar;

					//inject_stok_transfer
					if($idcustomer && $saldo && $keterangan && $pcs && $refund) {
						$reff = generate_string(10);
						$transdate = date("d/m/Y");
						$gudang = "GUDANG $depo - INJECT";

						$nofaktur = "IO.".$arrsales->id_department .'.'. $idsales .".". date("mY-dHis");

						$sql = "insert into inject_stok_transfer (date_order, idsalesman, id_product, qty, total, refund, warehouse, warehouse_reference, id_customer,
						detail_notes, description, data1, data2, cluster, reff, transdate, no_faktur, opr, tipe, jenis)
						 values (now(), '$idsales', '$tipe', $pcs, '$svesaldo', '$refund', 'GUDANG ELEKTRIK - HQ', '$gudang', '$idcustomer', '$keterangan $sp $pcs PCS $idsales $idcustomer',
						 '$nors', '$arrsales->name', 'TELCO HQ', '$cluster', '$reff', '$transdate', '$nofaktur', 'apps', 'LINKAJA', 'BUKU PINTAR')";

						 //echo $sql;

						 if(!mysql_query($sql)){

							$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
						      <div class='offcanvas-body small'>
						        <div class='app-info'>
						          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						          <div class='content'>
						            <h3>Error in Query <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
						            <a href='#'>Error Internal Logic</a>
						          </div>
						        </div>
						      </div>
						    </div>";
						 }else{

							 echo "<script language=javascript> location.href = 'index.php?nox=Berhasil&rex1=Request Inject sudah diterima. Menunggu Validasi dari HQ'; </script>";
						 }

					}else{

						$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
								<div class='offcanvas-body small'>
									<div class='app-info'>
										<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
										<div class='content'>
											<h3>Data Kosong <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
											<a href='#'>Semua Kolom harus diisi!</a>
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
		var saldo  	= document.getElementById("saldo").value;
		var total_raw = parseInt(saldo);
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
		 var arr1 = document.getElementById("saldo").value;
		 var arr2 = document.getElementById("pcs").value;
		 var arr3 = document.getElementById("refund").value;

		 arr1 = isValue(arr1);
		 arr2 = isValue(arr2);
		 arr3 = isValue(arr3);

		 var tot=0;
		 var bpx = parseInt(arr2);
		 var rfx = parseInt(arr3);
		 var hpx = bpx * rfx;
		 tot = parseInt(arr1);
		 tots = tot * 1000;
		 tots = tots + hpx;
		 document.getElementById(txdst).innerHTML = tots.format(0, 3, '.', ',');;}

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

        <div class="input-box">
          <i class="iconly-Plus icli"></i>
           <input class="form-control" type="text" id="saldo" name="saldo" placeholder="Nilai Saldo" onkeyup="findTotal('settotal')">
        </div>

        <div class="input-box">
			<select name="keterangan" class="form-control">
				 <option value="">Pilih Paket</option>
				 <?=$product?>
			 </select>
        </div>

        <div class="input-box">
					<input class="form-control" type="text" id="sp" name="sp" value="BUKU PINTAR" readonly>
        </div>
		<div class="input-box">
			<select id="refund" name="refund" class="form-control" onchange="findTotal('settotal')">
				 <option value="0">Saldo Refund</option>
				 <option value="1">Rp. 1</option>
				 <option value="250">Rp. 250</option>
					<option value="300">Rp. 300</option>

				 <option value="600">Rp. 600</option>
			 </select>
        </div>
        <div class="input-box">
          <i class="iconly-Plus icli"></i>
          <input class="form-control" type="text" id="pcs" name="pcs" placeholder="Jml Pcs" autocomplete="Off" onkeyup="findTotal('settotal')">
        </div>

				<small class="font-xs title-color fw-500">Total</small>
        <div class="input-box">
          <i class="iconly-Info-Circle icli"></i>
          <div id="settotal" class="form-controlx"> &nbsp;</div>
        </div>

				<input type="submit" class="btn-solid" name="proses" value="KIRIM">

      </form>
      <!-- Form Section End -->
    </main>
		<br />
			<?=$msg?>
    <!-- Main End -->
  <?php include("footer.inc.php");?>
