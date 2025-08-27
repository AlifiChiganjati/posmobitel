<?php
session_start();
$title = "FORM REFUND";
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

if(isset($_POST['kirim'])){
	$tmstmp = date('ymdhis');
	$brand = isset($_POST['brand'])? cleanall(strtoupper($_POST['brand'])): "";
	$nama_toko = isset($_POST['nama_toko'])? cleanall($_POST['nama_toko']) : "";
	$tipe_barang = isset($_POST['type_barang'])? cleanall($_POST['type_barang']) : "";
	$imei = isset($_POST['imei'])? cleanall($_POST['imei']) : "";
	$area = isset($_POST['area'])? cleanall($_POST['area']) : "";
	
	//foto
	$namaFile = $_FILES['foto']['name'];
	$namaSementara = $_FILES['foto']['tmp_name'];
	$ext = end((explode(".", $namaFile)));
	$size=$_FILES['foto']['size'];
	$file_save = ($namaFile == "") ? "" : $tmstmp."_".$namaFile;

	//excel
	$namaFile1 = $_FILES['foto1']['name'];
	$namaSementara1 = $_FILES['foto1']['tmp_name'];
	$ext1 = end((explode(".", $namaFile)));
	$size1=$_FILES['foto1']['size'];
	$file_save1 = ($namaFile1 == "") ? "" : $tmstmp."_".$namaFile1;

	//zip
	$namaFile2 = $_FILES['foto2']['name'];
	$namaSementara2 = $_FILES['foto2']['tmp_name'];
	$ext2 = end((explode(".", $namaFile2)));
	$size2 =$_FILES['foto2']['size'];
	$file_save2 = ($namaFile2 == "") ? "" : $tmstmp."_".$namaFile2;

	$dirUpload = "bukti_refund/";
	if($brand && $nama_toko && $tipe_barang){
		//$qcekimei = mysql_query("select id from imei_master where imei ='$imei' and status =1");
		//$ada = mysql_num_rows($qcekimei);
		//if($ada == 1){
			//insert ke table refund
			//if($ext == "jpg" or $ext == "jpeg" or $ext == "png"){
				if($size < 2048576){
					$tt = "insert into refund set tanggal=now(), userid='$id_customer_rti', imei='$imei', file_name='$file_save', iduserlogin='$username',
					nama_toko='$nama_toko', type_barang='$tipe_barang',brand='$brand',excel='$file_save1', zip= '$file_save2', area = '$area',";
					if(mysql_query($tt)){
						$terupload = move_uploaded_file($namaSementara, $dirUpload.$file_save);
						$terupload1 = move_uploaded_file($namaSementara1, $dirUpload.$file_save1);
						$terupload2 = move_uploaded_file($namaSementara2, $dirUpload.$file_save2);
						$res = "Request refund berhasil!";
						$brand="";
						$nama_toko="";
						$tipe_barang="";
						$imei="";
					}else{
						$res = $tt;
					}
				}else{
					$res = "Ukuran foto Imei terlalu besar maksimal 2MB";
				}
			//}else{
				//$res = "Format foto bukti tidak sesuai!";
			//}
		//}else{
		//	$res = "imei $imei tidak ditemukan!";
		//}
	}else{
		$res ="Data tidak lengkap, harap isi semua form!";
	}
	$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
              <div class='offcanvas-body small'>
                <div class='app-info'>
                  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                  <div class='content'>
                    <h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                    <a href='#'>$res</a>
                  </div>
                </div>
              </div>
            </div>";
}
?>

<script>
function validateForm() {
  var  x01 = document.forms["myForm"]["no_faktur"].value;
  var  x02 = document.forms["myForm"]["imei"].value;
  var  x03 = document.forms["myForm"]["program"].value;
  var  x04 = document.forms["myForm"]["npwp"].value;


  if (x01 == "") {
    alert("No Faktur harus diisi!");
    return false;
  }

  if (x02 == "") {
    alert("Imei harus diisi");
    return false;
  }
}
</script>

﻿<div class="page-content-wrapper">
      <div class="container">
        
        <!-- Cart Wrapper-->
        <form method="post" enctype="multipart/form-data" accept=".jpg,.jpeg,.png">
        <div class="checkout-wrapper-area">
          <!-- Billing Address-->
          <div class="billing-information-card mb-3">
            <div class="card billing-information-title-card bg-success">
              <div class="card-body">
			  
                <h6 class="text-center mb-0 text-white">CLAIM REFUND</h6>
              </div>
            </div>
            <div class="card user-data-card">
              <div class="card-body row g-1">
					<div class="input-box">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Brand</span></div>
						<div class="data-content">
							<?php
								$xia = ($brand=='xiaomi') ? 'selected':'';
								$pc = ($brand=='poco') ? 'selected':'';
							?>
							<select id="brand" name="brand" class="form-control">
								 <option value="">Pilih Brand</option>
								 <option value='xiaomi' <?=$xia?>> Xiaomi </option>
								 <option value='poco' <?=$pc?>> Poco </option>
							 </select>
						</div>
					</div>
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Nama Toko</span></div>
						<div class="data-content">
								<input class="form-control" type="text" name="nama_toko" id='nama_toko' value="<?=$nama_toko?>" autocomplete="Off" onkeyup = "cekTokoRefund();">
						</div>
					</div>
					<div id="hasiltoko"> </div>
					<div class="input-box">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Type Barang</span></div>
						<div class="data-content">
								<input class="form-control" type="text" name="type_barang" value="<?=$tipe_barang?>" autocomplete="Off">
						</div>
					</div>
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Area</span></div>
						<div class="data-content">
								<input class="form-control" type="text" name="area" value="<?=$area?>" autocomplete="Off">
						</div>
					</div>
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>IMEI (Kosongkan jika lebih dari 1)</span></div>
						<div class="data-content">
								<input class="form-control" type="text" id="imei" name="imei" value="<?=$imei?>" autocomplete="Off">
						</div>
					</div>
					
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Foto Imei</span></div>
						<div class="data-content">
							<input id="foto" name="foto" type="file" multiple="" class="form-control">
						</div>
					</div>
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Excel</span></div>
						<div class="data-content">
							<input id="foto1" name="foto1" type="file" multiple="" class="form-control">
						</div>
					</div>
					<div class="input-box mt-1">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Rar / Zip</span></div>
						<div class="data-content">
							<input id="foto2" name="foto2" type="file" multiple="" class="form-control">
						</div>
					</div>
					<div class="input-box">
						<label>&nbsp;</label>
					</div>
					<input type="submit" name="kirim" value="KIRIM DATA" class="btn btn-sm btn-success w-100 mt-2">

              </div>
            </div>
          </div>

        </div>
      </form>

      </div>
    </div>


<?=$msg?>
    <?php
    include("footer_alt.inc.php");
     ?>
