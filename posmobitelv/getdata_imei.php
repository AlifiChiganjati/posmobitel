<?php
include("connection_cesa.inc.php");
include("function.inc.php");

if (isset($_GET['tipe'])){

	 if ($_GET['tipe'] == "customer"){
		$input = cleanall($_GET['term']);
		$query = mysql_query("SELECT customer_no, name, no_rs, outlet_id, address FROM customers WHERE (name LIKE '%$input%' or customer_no LIKE '%$input%' or no_rs LIKE '%$input%' or outlet_id LIKE '%$input%') and status <> 2 limit 3");

		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
?>

<?php
			while ($data = mysql_fetch_row($query))
			{
?>
			<div class="type-password">
			<a id="customer" href="javascript:autoInsertcustomer_imei('<?=$data[0] ."#". $data[1]."#". $data[3]?>');">
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
			echo "<span class='badge badge-danger'>Data Customer tidak ditemukan!</span>";
		}
	}


}
?>
