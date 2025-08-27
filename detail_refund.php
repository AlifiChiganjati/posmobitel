<?php
session_start();
$title = "DETAIL REFUND";
include("connection_rti.inc.php");
include("function.inc.php");
include("session_check.php");
include("header_alt2.inc.php");
$idsales  = $_SESSION['IDSALES'];
$gudang = $_SESSION['GUDANG'];
$username = $_SESSION['USER'];
//cek id customer
$id_customer_rai = $_SESSION['IDOUTLET_RAI'];
$id_customer_rti = $_SESSION['IDOUTLET_RTI'];
$idrf = isset($_GET['i']) ? cleanall($_GET['i']) : "";
$qry_rf = mysql_query("select * from refund where id = $idrf and iduserlogin = '$username'");
$data_rf = mysql_fetch_object($qry_rf);
$brand = $data_rf->brand;
$nama_toko = $data_rf->nama_toko;
$tipe_barang = $data_rf->type_barang;
$area=$data_rf->area;
$imei = $data_rf->imei;
$foto_imei = $data_rf->file_name;
if($foto_imei != ""){
	$img_imei = "<img src='bukti_refund/$foto_imei' width='80%'>";
}
$excel = $data_rf->excel;
$rar = $data_rf->zip;
$bukti_transfer = $data_rf->bukti_transfer;
$img_transfer = "";
if($bukti_transfer != ""){
	$img_transfer = "<img src='https://nexa.my.id/posrti/bukti_transfer/$bukti_transfer' width='80%'>";
}
$statusx = $data_rf->status;
$status = "";
switch ($statusx){
	case "0":
	  $status = "Diproses";
	  break;
	case "1":
	  $status = "Berhasil";
	  break;
	case "2":
		$status = "Ditolak";
		break;
	case "9":
		  $status = "Batal";
		  break;
  }
?>

﻿<div class="page-content-wrapper">
      <div class="container">
        
        <!-- Cart Wrapper-->
        <form method="post" enctype="multipart/form-data" accept=".jpg,.jpeg,.png">
        <div class="checkout-wrapper-area">
          <!-- Billing Address-->
          <div class="billing-information-card">
            <div class="card user-data-card">
              <div class="card-body row g-1">
					<div class="col-12 text-center"><strong>Klaim <?=$status?></strong></div>
					<div class="input-box">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Brand</span></div>
						<div class="data-content">
							<input class="form-control" type="text" name="nama_toko" id='nama_toko' value="<?=$brand?>" readonly>
						</div>
					</div>
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Nama Toko</span></div>
						<div class="data-content">
							<input class="form-control" type="text" name="nama_toko" id='nama_toko' value="<?=$nama_toko?>" readonly>
						</div>
					</div>
					<div id="hasiltoko"> </div>
					<div class="input-box">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Type Barang</span></div>
						<div class="data-content">
								<input class="form-control" type="text" name="type_barang" value="<?=$tipe_barang?>" readonly>
						</div>
					</div>
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Area</span></div>
						<div class="data-content">
								<input class="form-control" type="text" name="area" value="<?=$area?>" readonly>
						</div>
					</div>
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>IMEI</span></div>
						<div class="data-content">
								<input class="form-control" type="text" id="imei" name="imei" value="<?=$imei?>" readonly>
						</div>
					</div>
					
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Foto Imei</span></div>
						<div class="data-content">
							<?=$img_imei?>
						</div>
					</div>
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Excel</span></div>
						<div class="data-content">
						<input class="form-control" type="text" id="imei" name="imei" value="<?=$excel?>" readonly>
						</div>
					</div>
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Rar / Zip</span></div>
						<div class="data-content">
						<input class="form-control" type="text" id="imei" name="imei" value="<?=$rar?>" readonly>
						</div>
					</div>
					<?=$img_transfer?>

              </div>
            </div>
          </div>
		  	<div class='mt-5'>&emsp;</div>
			<div class='mt-5'>&emsp;</div>
        </div>
      </form>

      </div>
    </div>


<?=$msg?>
    <?php
    include("footer.inc.php");
     ?>
