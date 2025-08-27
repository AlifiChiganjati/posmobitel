<?php
$merk = $_GET['merk'];
$cat = $_GET['cat'];
$title = "ALOKASI DETAIL ".strtoupper($merk);
$back_button = "alokasi.php?merk=$merk";
session_start();
include("session_check.php");
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
$cat2 = cleanall($cat);
$xx = "select count(id) as jum from order_temp where user='$user' and kategori like '$cat2%'";
$qryx = mysql_query($xx);
$arr_jum = mysql_fetch_object($qryx);
$jum_cart = $arr_jum->jum;

include("header_alt3.inc.php");
$nf = base64_decode($_GET['nf']);
if(isset($_POST['proses'])){
	$bank = $_POST['via'];
	$idcustomer = $_POST['idcustomer'];
	$total_belanja = isset($_POST['total_belanja'])? intval($_POST['total_belanja']): 0;
	$data2 = $_POST['data2'];
	$data3 = $_POST['data3'];
	if((strlen($bank) > 0) && (strlen($idcustomer) > 0)){
		
		if($total_belanja > 0){
				$bank_arr = explode(";", $bank);
				$via = $bank_arr[0];
				$coa = $bank_arr[1];
				$date=date("Ymd");
				$date2=date("dHis");
				//$xx = "select * from order_temp where user='$idsales' and kategori like '$cat%'";
				//$qryx = mysql_query($xx);
				//$no_faktur = $ub."-".$date."-".str_shuffle($date2);
				//while($datax=mysql_fetch_object($qryx)){
					$qryz = "insert into payment_detail set status=4, no_faktur='$nf', tanggal_proses = now(), payment_type='DIRECT', coa='$coa', id_customer='$idcustomer', user='$user', pay='$total_belanja', data2='$data2', data3='$data3'";
					
					if(mysql_query($qryz)){
						$msg = $msg ="<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
						<div class='offcanvas-body small'>
						  <div class='app-info'>
							<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
							<div class='content'>
							  <h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
							  <a href='#'>Update payment alokasi berhasil.</a>
							</div>
						  </div>
						</div>
					  </div>";
					}else{
						$msg = $msg ="<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
						<div class='offcanvas-body small'>
						  <div class='app-info'>
							<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
							<div class='content'>
							  <h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
							  <a href='#'>Maaf sedang gangguan.</a>
							</div>
						  </div>
						</div>
					  </div>";
					}
								
				//}	
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

	
function get_nama_produk($idp){
	$qry = mysql_query("select name_product,image from products where id_product ='$idp'");
	$jum=mysql_num_rows($qry);
	if($jum == 0){
		$dtp = "produk tidak ditemukan";
	}else{
		$dtp = mysql_fetch_object($qry);
	}
	return $dtp;
}
?>
<!-- Main Start -->
<main class="main-wrap cart-page mb-xxl">
<div class='row g-1'>
<?=$msg?>

<?php

	if($merk == "smartfren"){
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
	//$userx = "MOBI00011";
	
	$tt= "select *, count(qty) as qtys, sum(total) as totals from sales_invoice where idsalesman = '$user' and status=1 and no_faktur = '$nf' group by id_product";
	$qry = mysql_query($tt);
	$jums = mysql_num_rows($qry);
	if($jums == 0){
		echo $tt;
		echo "<div class='text-center'>Belum ada produk di keranjang...</div>";
	}else{
		$total=0;
		echo "<strong>$nf</strong>";
		echo '<div class="cart-item-wrap pt-0 mb-2">';
		while($data=mysql_fetch_object($qry)){
			$cust = $data->id_customer;
			$data2 = $data->salesman2;
			$data3 = $data->data2;
			$harga = number_format($data->harga);
			$sub_total = $data->totals;
			
			$base = base64_encode(base64_encode($data->id_produk));
			
			$diskon = $data->diskon;
			$arr_det_product = get_nama_produk($data->id_product);
			$image=$arr_det_product->image;
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
								<small>".number_format($sub_total)."</small>
							</div>
							<div class='col-2 g-0 text-end'>
								<small>$data->qtys unit</small>
							</div>
							<div class='col-5 text-end'>
								<small><strong>".number_format($data->totals)."&nbsp;&nbsp;</strong></small>
							</div>";
			}
			
			if($image == ""){
				$src = "menu_icons/1.png";
			}else{
				$src = $basez."".$image;
			}
			echo  "<div class='row border rounded g-0 mb-1 py-1'>
					<div class='col-2 text-center'>
						<a href='#'><img src='$src' alt='offer' width='90%'></a>
					</div>
					<div class='col-10'>
						<div class='row'>
							<div class='col-12'>
								<small>".$arr_det_product->name_product."</small>
							</div>
							$tampil2
							$tampil
						</div>
					</div>
				</div>";
			$total += $sub_total;
		}
		echo "</div> ";
		echo "<div class='custom-form'><div class='row'><div class='col-6'>TOTAL</div><div class='col-6 text-end'><strong>Rp".number_format($total)."</strong></div></div></div>";
		echo "<form method='post' class='custom-form'>
				<br>
				<div class='input-box'>
					ID Customer
					<input type='text' name='idcustomer' value='$cust' class='form-control' readonly>
					<input type='hidden' name='total_belanja' value='$total' class='form-control' readonly>
					<input type='hidden' name='data2' value='$data2' class='form-control' readonly>
					<input type='hidden' name='data3' value='$data3' class='form-control' readonly>
				</div>
				<h1 class='font-md title-color fw-600'>Pilih metode pembayaran</h1>
				
				<div class='input-box'>
					<select name='via' class='form-control mt-1' required>
					<option value=''>Pilih pembayaran</option>
					$list_bank
					</select>
				</div>	
				
				<footer class='footer-wrap shop bg-dark'>
				  <ul class='footer'>
					<li class='footer-item text-white'>
					Rp".number_format($total)."
					</li>
					<li class='footer-item'>
					  <button type='submit' name='proses' class='font-md text-white'>Proses Alokasi <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'><polyline points='9 18 15 12 9 6'></polyline></svg></button>
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
