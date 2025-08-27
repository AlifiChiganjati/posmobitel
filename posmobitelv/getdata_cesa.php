<?php
session_start();
include("connection_cesa.inc.php");
include("function.inc.php");


if (isset($_GET['tipe'])){

	 if ($_GET['tipe'] == "customer"){
		$input = cleanall($_GET['term']);
		$qry = "SELECT customer_no, name, no_rs FROM customers WHERE (name LIKE '%$input%' or customer_no LIKE '%$input%' or no_rs LIKE '%$input%') and status != 2 limit 3";
		$query = mysql_query($qry);

		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
?>
<ul class="page-nav ps-0">
<?php
			while ($data = mysql_fetch_row($query))
			{
?>

			<li><a href="javascript:autoInsertcustomer_cesa_fisik('<?=$data[0] ."#". $data[1]."#". $data[2]?>');"> <?php echo $data[0] ." / ". $data[1]?> <i class='lni lni-chevron-right'></i></a></li>
<?php
			}
?>
</ul>
<?php
		}else{
			echo "<span class='badge badge-danger'>Data Customer tidak ditemukan!</span>";
		}
	}else if($_GET['tipe'] == "customereload"){
	$input = cleanall($_GET['term']);
		$qry = "SELECT customer_no, name, no_rs FROM customers WHERE (name LIKE '%$input%' or customer_no LIKE '%$input%' or no_rs LIKE '%$input%') limit 3";
		$query = mysql_query($qry);

		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
?>
<ul class="page-nav ps-0">
<?php
			while ($data = mysql_fetch_row($query))
			{
?>

			<li><a href="javascript:autoInsertcustomer_cesa('<?=$data[0] ."#". $data[1]."#". $data[2]?>');"> <?php echo $data[0] ." / ". $data[1]?> <i class='lni lni-chevron-right'></i></a></li>
<?php
			}
?>
</ul>
<?php
		}else{
			echo "<span class='badge badge-danger'>Data Customer tidak ditemukan!</span>";
		}
	}	
		
}

?>
