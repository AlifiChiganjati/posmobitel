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
	$no_faktur = isset($_POST['no_faktur'])? cleanall($_POST['no_faktur']) : "";
	$imei = isset($_POST['imei'])? cleanall($_POST['imei']) : "";
	$program = isset($_POST['program'])? cleanall($_POST['program']) : "";
	//$foto = isset($_POST['foto'])? cleanall($_POST['foto']) : "";
	$nominal1 = isset($_POST['nominal1'])? cleanall($_POST['nominal1']) : "";
	$nominal2 = isset($_POST['nominal2'])? cleanall($_POST['nominal2']) : "";
	
	$namaFile = $_FILES['foto']['name'];
	$namaSementara = $_FILES['foto']['tmp_name'];
	$ext = end((explode(".", $namaFile)));
	$size=$_FILES['foto']['size'];
	// tentukan lokasi file akan dipindahkan
	$dirUpload = "bukti_refund/";

	$qcekimei = mysql_query("select id from imei_master where imei ='$imei'");
	$ada = mysql_num_rows($qcekimei);
	if($ada == 1){
		//insert ke table refund
		if($ext == "jpg" or $ext == "jpeg" or $ext == "png"){
			if($size < 2048576){
				mysql_query("insert into refund set tanggal=now(), userid='$id_customer_rti', no_faktur='$no_faktur', imei='$imei', program='$program', file_name='$namaFile', nominal_1='$nominal1', nominal_2='$nominal2', handle_by='$username'");
				$terupload = move_uploaded_file($namaSementara, $dirUpload.$namaFile);
				
				$no_faktur = "";
				$imei = "";
				$program = "";
				$nominal1 = "";
				$nominal2 = "";
				$res = "Request refund berhasil!";
			}else{
				$res = "Ukuran foto bukti terlalu besar";
			}
		}else{
			$res = "Format foto bukti tidak sesuai!";
		}
	}else{
		$res = "imei $imei tidak ditemukan!";
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
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Nomor Faktur</span></div>
						<div class="data-content">
							<input class="form-control" type="text" id="no_faktur" name="no_faktur" value="<?=$no_faktur?>" autocomplete="Off" onkeyup = "cekFakturRefund();">
						</div>
					</div>
					<div id="hasilproduk"> </div>

					<div class="input-box">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>IMEI</span></div>
						<div class="data-content">
								<input class="form-control" type="text" id="imei" name="imei" value="<?=$imei?>" autocomplete="Off">
						</div>
					</div>

					<div class="input-box">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Program</span></div>
						<div class="data-content">
							<select id="program" name="program" class="form-control">
								 <option value="">Pilih Program</option>
								 <option value='Program 1'> Program 1 </option>
								 <option value='Program 2'> Program 2 </option>

							 </select>
						</div>
					</div>

					<div class="input-box">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Foto bukti</span></div>
						<div class="data-content">
							<input id="foto" name="foto" type="file" multiple="" class="form-control" accept=".jpg,.jpeg,.png">
						</div>
					</div>

					<div class="input-box">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Nominal 1</span></div>
						<div class="data-content">
								<input class="form-control" type="text" id="nominal1" name="nominal1" value="<?=$nominal1?>" autocomplete="Off">
						</div>
					</div>

					<div class="input-box">
						<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Nominal 2</span></div>
						<div class="data-content">
								<input class="form-control" type="text" id="nominal2" name="nominal2" value="<?=$nominal2?>" autocomplete="Off">
						</div>
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
