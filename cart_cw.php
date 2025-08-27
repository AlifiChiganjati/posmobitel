<?php
$merk = $_GET['merk'];
$cat = $_GET['cat'];
$title = "Cart Order ".strtoupper($cat)." ".strtoupper($merk);
$back_button = "thumbnail_cw.php?merk=$merk&cat=$cat";
session_start();
include("session_check.php");
include("connection_cw.inc.php");
$basez="https://nexacloud.id/posretail/";
include("function.inc.php");
include("header_alt_cw.inc.php");

if(isset($_POST['proses'])){
	$bank = $_POST['via'];
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
					qty = $datax->qty, harga=$datax->harga, sales='$idsales', via = '$via', coa='$coa'";
		if(mysql_query($qryz)){
			mysql_query("delete from order_temp where id_produk='$datax->id_produk' and user='$idsales'");
			$sts = true;
		}else{
			$sts = false;
			$msg .= "error $datax->id; ";
		}
					
	}
	
	if($sts == true){
		echo "<script language=javascript> location.href = 'order_sukses.php?merk=$merk&nf=$no_faktur'; </script>";
	}
}

	

?>
<!-- Main Start -->
<main class="main-wrap cart-page mb-xxl">
<div class='row g-1'>
<?=$msg?>

<?php
	$sql_bank = mysql_query("SELECT * FROM accounts WHERE department = 'ALL DEPT' AND description <> ''");
	while($data_bank = mysql_fetch_object($sql_bank)){
		$list_bank .= "<option value='$data_bank->account_name;$data_bank->account_no'>$data_bank->account_name</option>";
	}

	$tt= "select * from order_temp where user='$idsales' and kategori like '$cat%'";
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
			$sub_total = $data->harga * $data->qty;
			
			$base = base64_encode(base64_encode($data->id_produk));
			$link = "detail.php?prd=$base&merk=$merk&cat=$cat";
			$link_delete = "delete_cart.php?prd=".$base."&merk=".$merk."&cat=".$cat;
			$image=$data->images;
			if($image == ""){
				$src = "menu_icons/1.png";
			}else{
				$src = $basez."".$data->images;
			}
			echo  "<div class='row border rounded g-0 mb-1 py-1'>
					<div class='col-2 align-text-bottom'>
						<a href='detail.php?prd=$base&merk=$merk&cat=$cat'><img src='$src' alt='offer' width='100%'></a>
					</div>
					<div class='col-10'>
						<div class='row'>
							<div class='col-10 lh-1'>
								<small>$data->nama_produk</small>
							</div>
							<div class='col-2'>
								<a href='$link_delete' class='btn btn-sm btn-dark'><svg xmlns='http://www.w3.org/2000/svg' width='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path></svg></a>
							</div>
							<div class='col-5'>
								<small>$harga</small>
							</div>
							<div class='col-2 g-0 text-end'>
								<small>$data->qty unit</small>
							</div>
							<div class='col-5 text-end'>
								<small><strong>".number_format($sub_total)."&nbsp;&nbsp;</strong></small>
							</div>
						</div>
					</div>
				</div>";
			$total += $sub_total;
		}
		echo "</div>";
		echo "<form method='post'>
				Pilih metode pembayaran:
				<select name='via' class='form-control mt-1' required>
					<option value=''>Pilih pembayaran</option>
					$list_bank
				</select>
				<footer class='footer-wrap shop bg-dark'>
				  <ul class='footer'>
					<li class='footer-item text-white'>
					  Rp".number_format($total)."
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
