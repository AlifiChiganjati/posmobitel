<?php
$title = "Order Detail";
$back_button = $_GET['b'].".php";
session_start();
include("session_check.php");
include("connection_rti.inc.php");
include("function.inc.php");
include("header_alt3.inc.php");
$nf=$_GET['nf'];
?>
<!-- Main Start -->
<main class="main-wrap cart-page mb-xxl">
<div class='row g-1'>
<?=$msg?>

<?php
function get_img($kd){
	$sql = mysql_query("select image from products where id_product ='$kd'");
	$data = mysql_fetch_object($sql);
	return $data->image;
}

	$tt= "select * from order_det where no_faktur='$nf'";
	$qry = mysql_query($tt);
	$jums = mysql_num_rows($qry);
	if($jums == 0){
		echo "<div class='text-center'>Data tidak ditemukan...</div>";
	}else{
		$total=0;
		echo "<strong>$nf</strong>";
		echo '<div class="cart-item-wrap pt-0 mb-2">';
		while($data=mysql_fetch_object($qry)){
			$harga = number_format($data->harga);
			$sub_total = $data->harga * $data->qty;
			
			$base = base64_encode(base64_encode($data->id_produk));
			$link = "detail.php?prd=$base&merk=$merk&cat=$cat";
			$image=get_img($data->kode_produk);
			$via = $data->via;
			$cust = $data->id_customer;
			$tanggal = $data->tanggal_order;
			if($image == ""){
				$src = "menu_icons/1.png";
			}else{
				$src = "http://34.101.176.180/posrti/$image";
			}
			echo  "<div class='row border rounded g-0 mb-1 py-1'>
					<div class='col-2 align-text-bottom'>
						<img src='$src' alt='offer' width='100%'>
					</div>
					<div class='col-10'>
						<div class='row'>
							<div class='col-12 lh-1'>
								<div style='height:2rem'><small>$data->nama_produk</small></div>
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
		
		echo "<div class='cart-item-wrap pt-0 mb-2'>
				<div class ='row g-0'>
					<div class='col-4'>Total</div>
					<div class='col-8 text-end'><strong>Rp".number_format($total)."</strong></div>
					<div class='col-12'><hr></div>
				</div>
				<div class ='row g-1'>
					<div class='col-4'>Tanggal Order</div>
					<div class='col-8 text-end'>$tanggal</div>
					<div class='col-4'>Customer</div>
					<div class='col-8 text-end'>$cust</div>
					<div class='col-4'>Pembayaran</div>
					<div class='col-8 text-end'>$via</div>
					
				</div>
			</div>";
		
	}
	
?>
</div>
</main>
    <!-- Main End -->
<?php include("footer_alt.inc.php");?>
