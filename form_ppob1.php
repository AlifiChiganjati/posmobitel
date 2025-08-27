<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
session_start();
$tipe = $_GET['tipe'];
$title="PPOB";
include("connection_main.php");
include("function.inc.php");
include("header_alt2.inc.php");

?>
<main class="main-wrap setting-page mb-xxl">
  <form class="custom-form" action="" method="post">
    <div class='row mt-2'>
        <?php
		$sql ="select * from product_elektrik where operator='PPOB' and aktif='YA'";
		$qry = mysql_query($sql);
        while ($data = mysql_fetch_object($qry)) {
			$opr = $data->description;
			$kode = $data->id_product;
			$link = "form_ppob.php?kode=$kode&produk=$opr";
			echo "<label class='form-check-label col-xxl-4 col-md-4 col-6 g-0' for='produk'>
                  <a href='$link'>                
                  <div class='alert border colored-div text-center py-2 mx-1 g-0 shadow-sm' role='alert'>
                    <strong style='font-size:8pt'>$opr </strong>
                  </div>
                  </a>
                </label>";
        }
        ?>
      </div>
  </form>
</main>
 <?=$msg?>
<?php
include("footer_alt.inc.php");
?>
