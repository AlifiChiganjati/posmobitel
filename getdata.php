<?php
session_start();
include("connection_main.php");
include("function.inc.php");
$idsales = $_SESSION['IDSALES'];

if (isset($_GET['tipe'])){

	if ($_GET['tipe'] == "customer"){
		$input = cleanall($_GET['term']);
		$input2 = cleanall($_GET['term2']);
		if($input2 == "poco"){
			$query = mysql_query("SELECT id_outlet_rai, nama, id_outlet_rti, unit_bisnis FROM customer_mobitel WHERE (nama LIKE '%$input%' or nohp LIKE '%$input%') limit 10");
			
		}else{
		//$query = mysql_query("SELECT id_outlet_rti, nama, id_outlet_rai, unit_bisnis FROM customer_mobitel WHERE (nama LIKE '%$input%' or nohp LIKE '%$input%') and (idsales_rti ='$idsales' or idsales_cesa='$idsales') limit 5");
			$query = mysql_query("SELECT id_outlet_rti, nama, id_outlet_rai, unit_bisnis FROM customer_mobitel WHERE (nama LIKE '%$input%' or nohp LIKE '%$input%') limit 10");
		}
		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
			while ($data = mysql_fetch_row($query))
			{
				$name = (strlen($data[1]) > 15)? substr($data[1], 0, 15) . " ..": "";
				?>
				<div class="type-password">
				<a id="customer" href="javascript:autoInsertcustomer('<?=$data[0] ."#". $data[1]?>');">
					<div class="input-box mb-0">
						<i class="iconly-Plus icli"></i>
						<label class="font-sm" for="customer">  </label>
						<small><?php echo $data[0] ." / ". $data[1]?> </small>
					</div>
				</a>
				</div>

				<?php
			}
		}else{
			echo "<span class='badge badge-danger'>Data Customer tidak ditemukan!</span>";
		}
	}
}
?>
