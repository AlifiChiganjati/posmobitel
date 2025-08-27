<?php
$merk = $_GET['merk'];
$cat = $_GET['cat'];
$title = "Cart Order";
$back_button = "thumbnail_v2.php?merk=$merk&cat=$cat";
session_start();
//include("session_check.php");
if($merk == "xiaomi"){
	include("connection_rti.inc.php");
	$basez="https://nexa.my.id/posrti/";
	$ub = "RTI";
}else if($merk == "smartfren"){
	include("connection_cesa.inc.php");
	$basez="https://nexacloud.id/nexacesa/";
	$ub = "CESA";
}else if($merk == "accessories"){
	include("connection_cw.inc.php");
	$basez="https://nexacloud.id/posretail/";
	$ub = "CW";
}else{
	include("connection_rai.inc.php");
	$basez="https://nexa.my.id/posrai/";
	$ub="RAI";
}
include("function.inc.php");

$user = $_SESSION['USER'];
$idsales = $user;
$cat2 = cleanall($cat);
$xx = "select count(id) as jum from order_temp where user='$user' and kategori like '$cat2%'";
$qryx = mysql_query($xx);
$arr_jum = mysql_fetch_object($qryx);
$jum_cart = $arr_jum->jum;

include("header_alt.inc.php");

if(isset($_POST['proses'])){
	$bank = $_POST['via'];
	$idcustomer = $_POST['idcustomer'];
	$total_belanja = isset($_POST['total_belanja'])? intval($_POST['total_belanja']): 0;
	
	if((strlen($bank) > 0) && (strlen($idcustomer) > 0)){
		
		if($total_belanja > 0){
			if($bank == "qris_cw"){			
			$date=date("Ymd-His");
			$nofaktur = $ub."-".$date."-".rand(10, 99);
			
				header("Cache-Control: no-store, no-cache, must-revalidate");
				header("Pragma: no-cache");
				echo "<script language=javascript> location.href = 'order_qris.php?no_faktur=$nofaktur&totalbyr=$total_belanja&idcustomer=$idcustomer'; </script>";
				
			
			}else{
				$bank_arr = explode(";", $bank);
				$via = $bank_arr[0];
				$coa = $bank_arr[1];
				$date=date("Ymd");
				$date2=date("dHis");
				$xx = "select * from order_temp where user='$idsales' and kategori like '$cat%'";
				$qryx = mysql_query($xx);
				$no_faktur = $ub."-".$date."-".str_shuffle($date2);
				while($datax=mysql_fetch_object($qryx)){
					$qryz = "insert into order_det set no_faktur='$no_faktur', tanggal_order = now(), kode_produk='$datax->id_produk', nama_produk='$datax->nama_produk', 
								qty = $datax->qty, harga=$datax->harga, sales='$idsales', via = '$via', coa='$coa', id_customer='$idcustomer', diskon='$datax->diskon'";
					
					if(mysql_query($qryz)){
						mysql_query("delete from order_temp where id_produk='$datax->id_produk' and user='$idsales'");
						$sts = true;
					}else{
						$sts = false;
						$msg .= "error $datax->id;";
					}
								
				}
				
				if($sts == true){
					echo "<script language=javascript> location.href = 'order_sukses.php?merk=$merk&nf=$no_faktur'; </script>";
				}			
			}
		}else{
			$msg ="<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
              <div class='offcanvas-body small'>
                <div class='app-info'>
                  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                  <div class='content'>
                    <h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                    <a href='#'>Nilai Order anda tidak ditemukan.</a>
                  </div>
                </div>
              </div>
            </div>";			
		}			
		
	}else{
		$msg ="<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
              <div class='offcanvas-body small'>
                <div class='app-info'>
                  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                  <div class='content'>
                    <h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                    <a href='#'>Pembayaran & ID Pelanggan tidak boleh kosong!</a>
                  </div>
                </div>
              </div>
            </div>";
	}
}

	

?>
<!-- Main Start -->
<main class="main-wrap cart-page mb-xxl">
<div class='row g-1'>
<?=$msg?>

<?php

	if($merk == "accessories"){
		$list_bank .= "<option value='qris_cw'>QRIS PAYMENT</option>";
	}else if($merk == "smartfren"){
		$list_bank .= "<option value='BCA - 0490649999 - CV CENTRUM SAKTI;11300002'>BCA - 0490649999 - CV CENTRUM SAKTI</option>";
		$list_bank .= "<option value='BRI - 036801003980306 - CV CENTRUM SAKTI;11300017'>BRI - 036801003980306 - CV CENTRUM SAKTI</option>";
		$list_bank .= "<option value='MANDIRI - 1450021899998 - CV CENTRUM SAKTI;11300001'>MANDIRI - 1450021899998 - CV CENTRUM SAKTI</option>";
		
	}else{
		$sql_bank = mysql_query("SELECT * FROM accounts WHERE department = 'ALL DEPT' AND description <> '' AND description LIKE '%#%'");
		while($data_bank = mysql_fetch_object($sql_bank)){
			$rawbank = $data_bank->description;
			
			$arrbank = explode("#", $rawbank);
			$bank_type = $arrbank[0];
			$bank_no = $arrbank[1];
			$bank_name = $arrbank[2];
			$value = "$bank_type - $bank_no - $bank_name";
			$list_bank .= "<option value='$value;$data_bank->account_no'><small>$value<hr></small></option>";
		}
	}
	
	//<small style='text-decoration: line-through;'><strong>".number_format($sub_total)."&nbsp;&nbsp;</strong></small>
	$tt= "select * from order_temp where user='$user' and kategori like '$cat%'";
	$qry = mysql_query($tt);
	$jums = mysql_num_rows($qry);
	if($jums == 0){
		echo "<div class='text-center'>Belum ada produk di keranjang...</div>";
	}else{
		$total=0;
		echo "Detail order:";
		echo '<div class="cart-item-wrap pt-0 mb-2">';
		while($data=mysql_fetch_object($qry)){
			$harga = number_format($data->harga);
			$sub_total = ($data->harga - $data->diskon) * $data->qty;
			
			$base = base64_encode(base64_encode($data->id_produk));
			$link = "detail_v2.php?prd=$base&merk=$merk&cat=$cat";
			$link_delete = "delete_cart_v2.php?prd=".$base."&merk=".$merk."&cat=".$cat;
			$image=$data->images;
			$diskon = $data->diskon;
			if($diskon != 0){
				$harga_diskon = $data->harga - $diskon;
				$total_diskon = $harga_diskon * $data->qty;
				$tampil = "<div class='col-5'>
								<small>".number_format($harga_diskon)."</small>
							</div>
							<div class='col-2 g-0 text-end'>
								<small>$data->qty unit</small>
							</div>
							<div class='col-5 text-end'>
								<small><strong>".number_format($total_diskon)."&nbsp;&nbsp;</strong></small>
							</div>";
				$tampil2 = "<div class='col-5'>
								<small style='text-decoration: line-through;'>$harga</small>
							</div>
							<div class='col-2 g-0 text-end'>
							</div>
							<div class='col-5 text-end'>
								
							</div>";
			}else{
				$tampil = "";
				$tampil2 = "<div class='col-5'>
								<small>$harga</small>
							</div>
							<div class='col-2 g-0 text-end'>
								<small>$data->qty unit</small>
							</div>
							<div class='col-5 text-end'>
								<small><strong>".number_format($sub_total)."&nbsp;&nbsp;</strong></small>
							</div>";
			}
			
			if($image == ""){
				$src = "menu_icons/1.png";
			}else{
				$src = $basez."".$data->images;
			}
			echo  "<div class='row border rounded g-0 mb-1 py-1'>
					<div class='col-2 text-center'>
						<a href='#'><img src='$src' alt='offer' width='90%'></a>
					</div>
					<div class='col-10'>
						<div class='row'>
							<div class='col-10 lh-1'>
								<small>$data->nama_produk</small>
							</div>
							<div class='col-2'>
								<a href='$link_delete' class='btn btn-sm btn-dark'><svg xmlns='http://www.w3.org/2000/svg' width='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path></svg></a>
							</div>
							$tampil2
							$tampil
						</div>
					</div>
				</div>";
			$total += $sub_total;
		}
		echo "</div>";
		echo "<form method='post' class='custom-form'>
				
				<h1 class='font-md title-color fw-600'>Pilih metode pembayaran</h1>
				<div class='input-box'>
					<select name='via' class='form-control mt-1' required>
					<option value=''>Pilih pembayaran</option>
					$list_bank
					</select>
				 </div>
				 
				
				 <div class='input-box'>
					<input type='hidden' class='form-control mt-1' id='merk' name='merk' value='$merk'></input>
					<input type='text' class='form-control mt-1' required='' placeholder='ID Pelanggan' id='idcustomer' name='idcustomer' onkeyup='autoCompletecustomer();' autocomplete='Off'></input>	
					<i class='iconly-Profile icli'></i>
				 </div>
				 
				 <div id='hasilcustomer' class='input-box'> </div>
				 
				 <div class='input-box'>
					<input type='text' class='form-control mt-1' placeholder='Nama Pelanggan' id='namacustomer' name='namacustomer' autocomplete='Off' readonly></input>	
					<input type='hidden' class='form-control mt-1' id='total_belanja' name='total_belanja' value='$total'></input>	
				 </div>
				 
				 
										
				
				<footer class='footer-wrap shop bg-dark'>
				  <ul class='footer'>
					<li class='footer-item text-white'>
					  Rp ".number_format($total)."
					</li>
					<li class='footer-item'>
					  <button type='submit' name='proses' class='font-md text-white'>Proses Order <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'><polyline points='9 18 15 12 9 6'></polyline></svg></button>
					</li>
				  </ul>
				</footer>
				</form>";
	}
	
?>
</div>
</main>
    <!-- Main End -->
<?php include("footer_alt.inc.php");?>
