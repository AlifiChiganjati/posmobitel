<?php
session_start();
include("connection_rti.inc.php");
include("function.inc.php");
$idsales = $_SESSION['IDSALES'];
$idoutlet_rti = $_SESSION['IDOUTLET_RTI'];

if (isset($_GET['tipe'])){

	if ($_GET['tipe'] == "cek_faktur"){
		$input = cleanall($_GET['term']);
		$query = mysql_query("SELECT no_faktur from log_sales_invoice where id_customer ='$idoutlet_rti' and no_faktur like '%$input%' group by no_faktur limit 3");

		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
			while ($data = mysql_fetch_row($query))
			{
			?>
				<div class="type-password">
				<a id="customer" href="javascript:autoInsertnfrefund('<?=$data[0]?>');">
					<div class="input-box mb-0">
						<label class="font-sm" for="customer">  </label>
						<small><?php echo $data[0];?> </small>
					</div>
				</a>
				</div>
			<?php
			}
		}else{
			echo "<span class='badge badge-danger'>No Faktur tidak ditemukan!</span>"; 
		}
	}else if ($_GET['tipe'] == "cek_toko"){
		$input = cleanall($_GET['term']);
		$query = mysql_query("SELECT name from customers where name like '%$input%' limit 3");

		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
			while ($data = mysql_fetch_row($query))
			{
			?>
				<div class="type-password">
				<a id="customer" href="javascript:autoInsertrefundTOko('<?=$data[0]?>');">
					<div class="input-box mb-0 border p-2">
						<label class="font-sm" for="customer">  </label>
						<small><?php echo $data[0];?> </small>
					</div>
				</a>
				</div>
			<?php
			}
		}else{
			echo "<span class='badge badge-danger'>Nama Toko tidak ditemukan!</span>"; 
		}
	}
	
	if ($_GET['tipe'] == "produk_search"){
		$input = cleanall($_GET['term']);
		$ctx = cleanall($_GET['cat']);
		$query = mysql_query("select * from product_mobitel where product_name like '%$input%' and product_category like '$ctx%' limit 3");

		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
			while ($data = mysql_fetch_row($query))
			{
			?>
				<div class='col-12 m-1'>
					<a id="customer" href="javascript:toSearchbox('<?=$data[1]?>');">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
						<?php echo $data[1]?>
					</a>
				</div>
			<?php
			}
		}else{
			echo "<span class='badge badge-danger'>Data Product tidak ditemukan!</span>"; 
		}
	}
}
?>
