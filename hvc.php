<?php
$title = "HVC";
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

if($_POST['proses'] == "KIRIM"){
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']): "";
	$saldo = isset($_POST['saldo'])? cleanall($_POST['saldo']) : "0";


	if($idsales && $idcustomer){
		$nors = "0". substr($idcustomer, 2); // LA81338370040 = 0813383700040
			$arrsales = data_salesman($idsales);
				if($arrsales){
					if($cluster){ // if ($cluster == KUTAI)

					$transdate = date("d/m/Y");
					/*
					if($cluster == "KUTAI"){
						$tipe = "LAN-KT";
					}
					*/
					$tipe = "LAN-KT";

					$nofaktur = $arrsales->id_department ."-K12/". $idsales ."-". date("mY-dHis");
					$data1 = $depo; //depo
					$data2 = $cluster; //cluster
					$via = "5001031"; //beban budget mandiri tsel
					$totalsaldo = $saldo * 1000;

					//inject_stok_transfer
					if($idcustomer && $saldo) {
						$qry = "select id from hvc where idsalesman = '$idsales' and id_customer = '$idcustomer' and id_product='$tipe'
						and qty = '$saldo' and (DATE_ADD(tanggal, INTERVAL 24 HOUR) > NOW())";
						$ery = mysql_query($qry);
						if($si = mysql_fetch_object($ery)){

							$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
									<div class='offcanvas-body small'>
										<div class='app-info'>
											<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
											<div class='content'>
												<h3>Duplicate Order <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
												<a href='#'>Transaksi Gagal, status Double untuk Nomor, Produk dan Qty yang sama!</a>
											</div>
										</div>
									</div>
								</div>";
						}else{
							$reff = generate_string(10);
							$transdate = date("d/m/Y");
							//$gudang = "GUDANG $depo INJECT";
							$sql = "insert ignore into hvc (tanggal, idsalesman, id_product, qty, unit_price, total, warehouse, nama_hvc, id_customer,
							detail_notes, description, no_faktur, transdate, data1, data2, via, tipe, status)
							 values (now(), '$idsales', '$tipe', $saldo, 1000, '$totalsaldo', '$gudang', 'HVC-DIDIK', '$idcustomer', 'HVC COMBO $nors',
							 '$nors', '$nofaktur', '$transdate', '$data1', '$data2', '$via', 'LINKAJA', 1)";

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

								 echo "<script language=javascript> location.href = 'index.php?nox=Berhasil&rex1=Request anda sudah diterima. Terima kasih'; </script>";
							 }
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
	 arr1 = isValue(arr1);
	 var tot=0;
	 tot = parseInt(arr1);
	 tots = tot * 1000;
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
