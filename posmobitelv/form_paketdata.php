<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
session_start();
include("session_check.php");
$title = "Paket Data";

include("connection_main.php");
include("function.inc.php");
include("header_alt2.inc.php");
$user = $_SESSION['USER'];
include("setting.inc.php");

if(isset($_POST['next1'])){
  $nomor=$_POST['nomorhpc']? cleanall($_POST['nomorhpc']) : "";
  $jum_no =strlen($nomor);
  if($jum_no < 10){
    $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
			      <div class='offcanvas-body small'>
			        <div class='app-info'>
			          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
			          <div class='content'>
			            <h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
			            <a href='#'>Format No HP $nomor salah! </a>
			          </div>
			        </div>
			      </div>
			    </div>";
  }else{
    if($nomor){
		$ran = generate_string(2);
		$rax = rand(10,90);
		$nocode = "INV". date("dmy").date("Hi").$ran.$rax;

		$produk = $_POST['produks']? cleanall($_POST['produks']) : "";
		$qry = mysql_query("select * from product_elektrik where id_product='$produk'");
		$data = mysql_fetch_object($qry);
		$hrg=$data->unit_price;
		$hrg_modal=$data->vendor_price;
		$ket=$data->description;
		$rand = rand(100, 900);
		$reffx =  generate_string(10);
		$reff = "ELC-".$reffx . $rand;
		$sql ="insert into elektrik_temp set tipe='PAKET DATA', nomor='$nomor', kode='$produk', keterangan='$ket', harga='$hrg', harga_vendor='$hrg_modal', 
				date_transaksi=now(), reff='$reff', user='$user',customer_id='$no_cust', customer_name='$nama_cust',no_faktur='$nocode', status=1";
		$qry = mysql_query($sql);
		if(!$qry){
			$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
			      <div class='offcanvas-body small'>
			        <div class='app-info'>
			          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
			          <div class='content'>
			            <h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
			            <a href='#'>$sql</a>
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
			            <h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
			            <a href='#'>Data Tidak Lengkap! </a>
			          </div>
			        </div>
			      </div>
			    </div>";
	}
 }
}else if(isset($_POST['next2'])){
  $nohp3=$_POST['nomor2']? cleanall($_POST['nomor2']) : "";
  $kodep3=$_POST['produk2']? cleanall($_POST['produk2']) : "";
  $ket3=$_POST['ket2']? cleanall($_POST['ket2']) : "";
  $hrg3=$_POST['harga2']? cleanall($_POST['harga2']) : "";
  $harga_vendor2=$_POST['harga_vendor2']? cleanall($_POST['harga_vendor2']) : "";
  $reff=$_POST['reffx']? cleanall($_POST['reffx']) : "";
  $dates = date("d/m/Y");
  $idcustomerx = $_POST['idcustomerx']? cleanall($_POST['idcustomerx']) : "";
  $no_fakturx= $_POST['no_faktur']? cleanall($_POST['no_faktur']) : "";
  $pin= $_POST['pin']? cleanall($_POST['pin']) : "";
  $via= "SALDO";
  	if($via){
		if($pin == $pin_cnet){
			if($saldo_cnet >= $harga_vendor2){
				//$cnetapi_pulsa= cnetapi_topup($kodep3,$nohp3,$no_fakturx,$id_cnet,$pin,$pass_cnet);
				//$arr_api = explode(";", $cnetapi_pulsa);
				//$respon = $arr_api[1];
				//if($arr_api[0] == 1){
									
					if(mysql_query("update elektrik_temp set status = 3, via= '$via', respon='$respon' where reff='$reff' and status=1")){
						$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
							  <div class='offcanvas-body small'>
								<div class='app-info'>
								  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
								  <div class='content'>
									<h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
									<a href='#'>Transaksi Sedang Diproses!</a>
								  </div>
								</div>
							  </div>
							</div></div></div>";
					}else{
						$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
							  <div class='offcanvas-body small'>
								<div class='app-info'>
								  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
								  <div class='content'>
									<h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
									<a href='#'>Maaf sedang gangguan! </a>
								  </div>
								</div>
							  </div>
							</div>";
					}
				/**}else{
						$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
							  <div class='offcanvas-body small'>
								<div class='app-info'>
								  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
								  <div class='content'>
									<h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
									<a href='#'>Maaf sedang gangguan $respon! </a>
								  </div>
								</div>
							  </div>
							</div></div></div>";
				}**/
			}else{
				$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
						  <div class='offcanvas-body small'>
							<div class='app-info'>
							  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
							  <div class='content'>
								<h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
								<a href='#'>Saldo tidak cukup!</a>
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
								<h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
								<a href='#'>PIN SALAH!</a>
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
				            <h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
				            <a href='#'>Metode pembayaran belum dipilih! </a>
				          </div>
				        </div>
				      </div>
				    </div>";
	}

}
else if(isset($_POST['batal2'])){
  $no_fakturx= $_POST['no_faktur']? cleanall($_POST['no_faktur']) : "";
  mysql_query("update elektrik_temp set status=9 where no_faktur='$no_fakturx'");

}

$qry=mysql_query("select * from elektrik_temp where user ='$user' and status !=9 order by id desc limit 1");
$jum = mysql_num_rows($qry);
$data=mysql_fetch_object($qry);
$status=$data->status;
if($jum == 0){
  $view_s = "<form method='post'>
            <div class='row'>
				<div class='col-12'>
					<div class='content-color' style='font-size:11pt;'>Nomor HP:</div>
					<input id='nomorhpc' name='nomorhpc' type='number' style='font-size: 14pt;' class='form-control' autocomplete='Off' onkeyup='autoCompletepaketdata();' placeholder='Masukkan Nomor HP' required autofocus>
				</div>
			</div>
            <div id='hasildenom'> </div> 
        </form>";
}else{
	$no_pel = $data->nomor;
	$kode = $data->kode;
	$ketx = $data->keterangan;
	$hargax = $data->harga;
	$harga_vendor = $data->harga_vendor;
	$reffx = $data->reff;
	$no_faktur = $data->no_faktur;
	$idcustomerx = $data->customer_id;
	$namecusomerx = $data->customer_name;
		$qry_via = mysql_query("select via from pembayaran where status =1 order by id");
		while($data_via = mysql_fetch_object($qry_via)){
			$arr_via .= "<option value='".$data_via->via."'>".$data_via->via."</option>";
		}
	$btn2="alert alert-danger"; 
	$btn1="alert alert-danger";
	if($status == 1){				  
		  $view_s = "<form method='post'>
				<div class='panel-body'>
					<div class='row'>
						<div class='col-12'>
							<div class='form-group row'>
							  <div class='col-4'>
								<label for='name-2' class='block'>Nomor HP</label>
							  </div>
							  <div class='col-8'>
								: $no_pel
								<input id='name-2b' name='nomor2' type='hidden' class='form-control' value='$no_pel'>
							  </div>
							</div>

							<div class='form-group row'>
							  <div class='col-4'>
								<label for='name-2' class='block'>Kode Paket</label>
							  </div>
							  <div class='col-8'>
							  : $kode
								<input id='name-2b' name='produk2' type='hidden' class='form-control' value='$kode'>
							  </div>
							</div>
							<div class='form-group row'>
							  <div class='col-4'>
								<label for='name-2' class='block'>Keterangan </label>
							  </div>
							  <div class='col-8'>
							  : $ketx
								<input id='name-2b' name='ket2' type='hidden' class='form-control' value='$ket' >
							  </div>
							</div>
							<input id='name-2b' name='reffx' type='hidden' class='form-control' value='$reffx' >
							<input id='name-2b' name='no_faktur' type='hidden' class='form-control' value='$no_faktur' >
							<div class='form-group row'>
							  <div class='col-4'>
								<label for='name-2' class='block'>Harga</label>
							  </div>
							  <div class='col-8'>
							  : Rp.".number_format($hargax)."
								<input id='name-2b' name='harga2' type='hidden' class='form-control' value='$hargax' >
								<input id='name-2b' name='harga_vendor2' type='hidden' class='form-control' value='$harga_vendor' >
							  </div>
							</div>
							<div class='form-group mt-1'>
							  <div class='col-12'>
								<label for='name-2' class='block'>PIN Transaksi</label>
								<input type='password' placeholder='Masukkan PIN' id='pin' name='pin' class='form-control' autocomplete='off'>
							  </div>
							</div>
						</div>
					</div>
				</div>
				<div class='fixed-bottom bg-white'>
				  <div class='row input-box mx-2 mb-3'>
						<div class='col-6'>
							<button type='submit' id='myBtn' class='btn-solid bg-dark' name='batal2'> BATAL</button>
						</div>
						<div class='col-6'>
							<button type='submit' id='myBtn' class='btn-solid bg-dark' name='next2'> BELI</button>
						</div>
						
				  </div>
				</div>
				</form>";
	}
}

?>

<script type="text/javascript">
$(function () {
  $('#default-Modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var recid = button.data('myid');

    var modal = $(this);
    modal.find('#myid').val(recid);
  });
})
</script>

<script type="text/javascript">
$(function () {
  $('#smallPayment').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var recreff = button.data('myso');
    var reccust = button.data('mycust');
    var recdata1 = button.data('mydata1');
    var recdata2 = button.data('mydata2');

    var modal = $(this);
      modal.find('#reffz').val(recreff);
      modal.find('#id_customer').val(reccust);
      modal.find('#data1').val(recdata1);
      modal.find('#data2').val(recdata2);
  });
})
</script>

<style>
[type=radio] { 
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
  }

  /* IMAGE STYLES */
  [type=radio] ~ .colored-div{
    cursor: pointer;
  }

  /* CHECKED STYLES */
  [type=radio]:checked ~ .colored-div {
    background-color: #dc3545;
    color: white;
}
#alertx, #alerty{
  display: none;
}
</style>
<main class="main-wrap setting-page mb-xxl">
				<section class="low-price-section pt-0">
					<div class="">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<h4 class="title-color" style="font-size:14pt"><strong>BELI PAKET DATA</strong></h4>
							</div>
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<?php 
	                            	echo $view_s;
	                          	?>
	                         </div>
					  	</div>
					  <div>
						
					  </div>
					  
					</div>
				</section>


</main>
 <?=$msg?>
<?php
include("footer_alt_elektrik.php");
?>
