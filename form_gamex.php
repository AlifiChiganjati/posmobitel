<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
session_start();
$tipe = $_GET['tipe'];
$title=$tipe;
include("cek_login.inc.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");
include("setting.inc.php");

$user = $_SESSION['USERNAME'];
$mylink = base64_encode($user);

$idsalesman='XX88888';

$role = $_SESSION['ROLE'];
$depo = $_SESSION['DEPO'];

$btn1="alert alert-danger border border-danger"; 
$btn2="alert alert-light border border-danger";

//$prefix = code_department($depo);


if(isset($_POST['next1'])){
  $nomor=$_POST['nomorhpc']? cleanall($_POST['nomorhpc']) : "";
  //$no_cust=$_POST['idcustomer']? cleanall($_POST['idcustomer']) : "";
  //$nama_cust=$_POST['customername']? cleanall($_POST['customername']) : "";
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
		$qry = mysql_query("insert into elektrik_temp set tipe='PAKET DATA', nomor='$nomor', kode='$produk', keterangan='$ket', harga='$hrg', 
							harga_vendor='$hrg_modal', date_transaksi=now(), reff='$reff',user='$user',customer_id='$no_cust',customer_name='$nama_cust',
							no_faktur='$nocode', outlet='$outlet'");
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
$via= $_POST['via']? cleanall($_POST['via']) : "";
	if($via){
	    if(mysql_query("update elektrik_temp set status = 1, via= '$via' where reff='$reff' and status=0")){
	        $status = true;
	        $pesan= "";
	        if($status){
	          $qry_inv = "insert into sales_invoice set date_order =now(),id_product='$kodep3',qty=1,unit_price=$harga_vendor2,total=$hrg3,warehouse_name='ELEKTRIK',
				detail_notes='$nohp3', reff='$reff', no_faktur='$no_fakturx', tipe='ELEKTRIK',opr='$user',kasir='$user',taxdate='$dates',transdate='$dates', data1='10',idsalesman='$idsalesman', status=3, response='$response'";
	          if(mysql_query($qry_inv)){
	            $qry_so="insert into sales_order set date_order=now(), no_faktur='$no_fakturx', idsalesman='$idsalesman', tipe='SO', CLUSTER='OTHER'";
	            if(mysql_query($qry_so)){
	               if($via == "TUNAI"){
						echo "<script language=javascript> location.href = 'print_struk_bank.php?nf=$no_fakturx'; </script>";
					}else{
						echo "<script language=javascript> location.href = 'proses_api.php?nf=$no_fakturx'; </script>";
					}
	            }else{
	              $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
				      <div class='offcanvas-body small'>
				        <div class='app-info'>
				          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
				          <div class='content'>
				            <h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
				            <a href='#'>Maaf sedang gangguan x002! </a>
				          </div>
				        </div>
				      </div>
				    </div></div></div>";
	            }
	          }else{
	            $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
				      <div class='offcanvas-body small'>
				        <div class='app-info'>
				          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
				          <div class='content'>
				            <h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
				            <a href='#'>Maaf sedang gangguan x001! </a>
				          </div>
				        </div>
				      </div>
				    </div></div></div>";
	          }
	        }else{
	          $qry_invx = "update elektrik_temp set status=2 where no_faktur = '$no_fakturx'";
	          if(mysql_query($qry_invx)){
	            $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
				      <div class='offcanvas-body small'>
				        <div class='app-info'>
				          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
				          <div class='content'>
				            <h3>Notification! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
				            <a href='#'>Maaf sedang gangguan x003! </a>
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
				            <a href='#'>Maaf sedang gangguan x004! </a>
				          </div>
				        </div>
				      </div>
				    </div></div></div>";
	          }
	        }
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
  //batalkan transaksi sebelum masuk ke sales invoice
  $no_fakturx= $_POST['no_faktur']? cleanall($_POST['no_faktur']) : "";
  mysql_query("update elektrik_temp set status=9 where no_faktur='$no_fakturx'");
  mysql_query("update payment_detail set status=2 where no_faktur='$no_fakturx'");
}

$qry=mysql_query("select * from elektrik_temp where user ='$user' and status =0 and outlet='$outlet' order by id desc limit 1");
$jum = mysql_num_rows($qry);
$data=mysql_fetch_object($qry);
$status=$data->status;
if($jum == 0){
	$qryppob=mysql_query("select * from operators where groups ='GAME' and status=1 order by nama_opr");
  while($datappob=mysql_fetch_object($qryppob)){
    $res .="<option value='$datappob->nama_opr'>$datappob->nama_opr</option>";
  }
  $view_s = "<form method='post'>
            <div class='row'>
				<div class='col-12 pb-3'>
					<p class='content-color' style='font-size:11pt;'>Pilih Game : </p>
					
					<select name='layanan1' id='layanan1' class='js-example-basic-single form-control' style='font-size: 14pt;'>
					<option value='0'>--Pilih Produk--</option>
                      $res
                  </select>
				</div>
				<div class='col-12'>
					<p class='content-color' style='font-size:11pt;'>Nomor/ID :</p>
					<input id='nomorhpc' name='nomorhpc' type='number' style='font-size: 14pt;' class='form-control' autocomplete='Off' onkeyup='autoCompletemoney();' placeholder='Masukkan Nomor/ID Game...' required autofocus>
				</div>
			</div>
            <div id='hasildenom'> </div> 
			<footer class='footer-wrap pt-0 pb-2'>
				<div class='row'>
					<div class='col-4 text-center'>
						<a href='home.php'>
							<ul class='footer'>
						        <li class='footer-item'>
						          	<div class='footer-link'>
						            	<i class='iconly-Home icli'></i>
						            	<span>Home</span>
						          	</div>
						        </li>
						    </ul>
						</a>
					</div>
					<div class='col-4 text-center'>
						<ul class='footer'>
				        	<li class='footer-item'>
					        	<div class='footer-link'>
					            	&emsp;
					            	<span>|</span>
					         	</div>
				        	</li>
				      	</ul>					
					</div>
					<div class='col-4 text-center'>
						<button type='submit' name='next1'>
							<ul class='footer'>
					        	<li class='footer-item'>
						        	<div class='footer-link'>
						            	<i class='iconly-Arrow-Right-Square icli'></i>
						            	<span>Lanjut</span>
						         	</div>
					        	</li>
					      	</ul>
					    </button>
					</div>
				</div>
		    </footer>
        </form>";
}else{
	$disable="disabled";
	$no_pel = $data->nomor;
	$kode = $data->kode;
	$ketx = $data->keterangan;
	$hargax = $data->harga;
	$harga_vendor = $data->harga_vendor;
	$reffx = $data->reff;
	$no_faktur = $data->no_faktur;
	$idcustomerx = $data->customer_id;
	$namecusomerx = $data->customer_name;
		$qry_via = mysql_query("select via from pembayaran where status = 1 order by id");
		while($data_via = mysql_fetch_object($qry_via)){
			$arr_via .= "<option value='".$data_via->via."'>".$data_via->via."</option>";
		}
	$btn2="alert alert-danger"; 
	$btn1="alert alert-danger";
	if($status == 0){
				  
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
				  </div>
				  
				</div>

					<div class='form-group row'>
					  <div class='col-sm-5'>
						<label for='name-2' class='block'>Metode Pembayaran</label>
					  </div>
					  <div class='col-sm-7'>
							<select name='via' class='form-control' style='font-size:14pt'>
								<option value='QRIS' selected>QRIS</option>
							</select>
					  </div>
					</div>
					
				  </div>
				   <footer class='footer-wrap pt-0 pb-2'>
						<div class='row'>
							<div class='col-4 text-center'>
								<button type='submit' name='batal2'>
									<ul class='footer'>
								        <li class='footer-item'>
								          	<div class='footer-link'>
								            	<i class='iconly-Delete'></i>
				            					<span>Batal</span>
								          	</div>
								        </li>
								    </ul>
								</button>
							</div>
							<div class='col-4 text-center'>
								<ul class='footer'>
						        	<li class='footer-item'>
							        	<div class='footer-link'>
							            	&emsp;
							            	<span>|</span>
							         	</div>
						        	</li>
						      	</ul>					
							</div>
							<div class='col-4 text-center'>
								<button type='submit' name='next2'>
									<ul class='footer'>
							        	<li class='footer-item'>
								        	<div class='footer-link'>
								            	<i class='iconly-Buy icli'></i>
				            					<span>Beli</span>
								         	</div>
							        	</li>
							      	</ul>
							    </button>
							</div>
						</div>
				    </footer>
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
				<br>
				 <section class="low-price-section pt-0">
					<div class="top-content">
					  <div class="row">
					  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
							<h4 class="title-color" style="font-size:14pt"><strong>TOPUP GAME</strong></h4>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
	                        <div class="panel panel-default">
	                          <?php 
	                            echo $view_s;
	                          ?>
	                        </div>
                      </div>
					  </div>
					</div>
				</section>


</main>
 <?=$msg?>
<?php
include("footer.inc.php");
?>
