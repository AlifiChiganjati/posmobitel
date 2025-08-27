<?php
include("connection_jbl.inc.php");
include("function.inc.php");
session_start();
if (isset($_GET['tipe'])){

	 if ($_GET['tipe'] == "customer"){
		$input = cleanall($_GET['term']);
		$query = mysql_query("SELECT customer_no, name, outlet_id, address FROM customers WHERE (name LIKE '%$input%' or customer_no LIKE '%$input%' or outlet_id LIKE '%$input%') and status <> 2 limit 3");

		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
?>

<?php
			while ($data = mysql_fetch_row($query))
			{
?>
			<div class="type-password">
			<a id="customer" href="javascript:autoInsertcustomer_imei_jbl('<?=$data[0] ."#". $data[1]."#". $data[2]?>');">
				<div class="input-box mb-0">
						<label class="font-xs" for="customer">  
						<?php echo $data[1] ." / ". $data[2] ."<br />".$data[4]?> </label>
				</div>
			</a>
			</div>
<?php
			}
?>

<?php
		}else{
			echo "Data Customer tidak ditemukan!";
		}
	}else if ($_GET['tipe'] == "product"){
		$gudangs = $_SESSION['IDSTORE'];
		$input = cleanall($_GET['term']);
		$query = mysql_query("select a.*, b.name_product 
								from stock_master a join products b 
								on a.id_product=b.id_product 
								where a.warehouse='$gudangs' and a.qty !=0 limit 3");

		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
			while ($data = mysql_fetch_object($query))
			{
				?>
				<div class='type-password'>
						<a id='customer' href="javascript:autoInsert_product_jbl('<?=$data->id_product .'#'. $data->name_product?>');">
							<div class='input-box mb-0'>
									<label class='font-xs' for='customer'>  
									<?php echo $data->id_product .' - Stok: '. $data->qty .'<br />'.$data->name_product ?>
									</label>
							</div>
						</a>
						</div>
				<?php
			}
		}else{
			echo $gudangs;
		}
	}


}
?>
