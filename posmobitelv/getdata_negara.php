<?php
session_start();
include("connection_rti.inc.php");
include("function.inc.php");
$idsales = $_SESSION['IDSALES'];

if (isset($_GET['tipe'])){

	if($_GET['tipe'] == "cek_negara"){
		$input = cleanall($_GET['term']);
		$id = cleanall($_GET['id']);
		$query = mysql_query("SELECT * FROM data_negara WHERE negara LIKE '%$input%' limit 4");
		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
			while ($data = mysql_fetch_row($query))
			{
				
				?>
				<div class="type-password">
				<a id="customer" href="javascript:autoInsertnegara('<?=$data[1]?>','<?=$id?>');">
					<div class="input-box mb-0">
						<i class="iconly-Plus icli"></i>
						<label class="font-sm" for="customer">  </label>
						&emsp;<small>  <?php echo $data[1]?> </small>
					</div>
				</a>
				</div>

				<?php
			}
		}else{
			echo "<span class='badge badge-danger'>Negara tidak ditemukan!</span>";
		}
	}
}
?>
