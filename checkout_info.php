<?php
$title = "CHECKOUT";

session_start();
include("session_check.php");
include("connection_cesa.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$idsales = $_SESSION['IDSALES'];

$nors = isset($_GET['nors'])? cleanall($_GET['nors']) : "";
$namars = isset($_GET['namars'])? cleanall($_GET['namars']) : "";
$qty = isset($_GET['qty'])? cleanall($_GET['qty']) : "";
$total = isset($_GET['total'])? cleanall($_GET['total']) : "";
$fee = isset($_GET['fee'])? cleanall($_GET['fee']) : "";
$bank = isset($_GET['bank'])? cleanall($_GET['bank']) : "";
$faktur = isset($_GET['faktur'])? cleanall($_GET['faktur']) : "";
$diskon = isset($_GET['diskon'])? cleanall($_GET['diskon']) : "0";

$rpdiscount = $total * ($diskon / 100);
$totalbyr = ($total - $rpdiscount) + $fee;
$totalori = $total - $rpdiscount;

$pajak1 = 0;
$pajak2 = 0;
//pajak

$ofaktur = base64_decode($faktur);
$xfaktur = base64_encode($faktur);

$arrCustomer = data_customer($nors);
if($arrCustomer){
  //kondisi jika jml req saldo > 2.220.000
  $npwp = $arrCustomer->npwp; // xxxx | 00.000.000.0-000.000 (ada npwp = 0.5% dari 1.11% saldo -- ga ada  = 1% dari 1.11%saldo )
  $umkm = $arrCustomer->umkm;

  if($totalori > 2220000){
    $pajak1 = $totalori - ($totalori / 1.11);
    if($umkm == "YA"){
      $pajak2 = 0;
    }else{
      if($npwp == "00.000.000.0-000.000"){
        $pajak2 = $pajak1 * 0.01;
      }else{
        $pajak2 = $pajak1 * 0.005;
      }
    }
  }
}

?>
<!-- Main Start -->

<form method="post" class="custom-form">
    <main class="main-wrap order-detail cart-page mb-xxl">

			<div class="section-p-b">
          <div class="banner-box">
            <div class="media">
              <div class="img"><img src="assets/icons/svg/box.svg" alt="box"></div>
              <div class="media-body">
                <span class="font-sm"># <?=$ofaktur?></span>
                <span class="font-md">Order diterima</span>
              </div>
            </div>
          </div>
        </div>

      <!-- Tab Content Start -->
      <section class="order-detail pt-0">
        <h3 class="title-2">Order Details</h3>

        <!-- Detail list Start -->
        <ul>
          <li>
            <span>No Reseller</span>
            <span><?=$nors?></span>
          </li>
          <li>
            <span>Nama</span>
            <span><?=$namars?></span>
          </li>
					<li>
            <span>Via</span>
            <span><?=$bank?></span>
          </li>
          <li>
            <span>Nilai Saldo</span>
            <span><?=$qty?></span>
          </li>
		  
		  <li>
            <span>Diskon <?=$diskon?> %</span>
            <span><?=number_format($rpdiscount)?></span>
          </li>
		  
		  <li>
            <span>PPN 11%</span>
            <span><?=number_format($pajak1)?></span>
          </li>
		  
		  <li>
            <span>PPH 22</span>
            <span><?=number_format($fee)?></span>
          </li>

          
          <li>
            <span>Total Bayar</span>
            <span><?=number_format($totalbyr)?></span>
          </li>
        </ul>
        <!-- Detail list End -->
      </section>



		<!-- Tab Content End -->
    </main>

		<footer class="footer-wrap footer-button">
      <a href="print_struk_temp.php?det=<?=$faktur?>" class="btn-solid">PRINT</a>
    </footer>
		</form>

<?=$msg?>

    <!-- Main End -->
<?php
include("footer_alt.inc.php");
?>
