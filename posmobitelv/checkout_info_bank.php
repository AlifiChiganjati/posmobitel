<?php
$title = "CHECKOUT KONFIRMASI";
session_start();
include("session_check.php");
include("connection_cesa.inc.php");
include("function.inc.php");
include("header_alt3.inc.php");

$idsales = $_SESSION['IDSALES'];

$nors = isset($_GET['nors'])? cleanall($_GET['nors']) : "";
$namars = isset($_GET['namars'])? cleanall($_GET['namars']) : "";
$qty = isset($_GET['qty'])? cleanall($_GET['qty']) : "";
$total = isset($_GET['total'])? cleanall($_GET['total']) : "";
$fee = isset($_GET['fee'])? cleanall($_GET['fee']) : "";
$bank = isset($_GET['bank'])? cleanall($_GET['bank']) : "";
$faktur = isset($_GET['faktur'])? cleanall($_GET['faktur']) : "";
$tiket = isset($_GET['tiket'])? cleanall($_GET['tiket']) : "";
$diskon = isset($_GET['diskon'])? cleanall($_GET['diskon']) : "0";

$pajak1 = isset($_GET['pajak1'])? cleanall($_GET['pajak1']) : "0";
$pajak2 = isset($_GET['pajak2'])? cleanall($_GET['pajak2']) : "0";

//$totalbyr = $total + $fee + $tiket;
//$bankname = get_bank_name($bank);

$orifaktur = base64_decode($faktur);
$pajak1 = isset($_GET['pajak1'])? cleanall($_GET['pajak1']) : "0";
$pajak2 = isset($_GET['pajak2'])? cleanall($_GET['pajak2']) : "0";
$rpdiscount = $total * ($diskon / 100);
$totalbyr = ($total - $rpdiscount) + $tiket + $pajak2;
$bankname = get_bank_name($bank);

?>

<!-- Main Start -->
<form method="post" class="custom-form">
    <main class="main-wrap order-detail cart-page mb-xxl">

			<div class="section-p-b">
          <div class="banner-box">
            <div class="media">
              <div class="img"><img src="assets/icons/svg/box.svg" alt="box"></div>
              <div class="media-body">
                <span class="font-xs"># <?=$orifaktur?></span>
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
            <span><?=$bankname?></span>
          </li>
          <li>
            <span>Nilai Saldo</span>
            <span><?=number_format($qty)?></span>
          </li>          
          <li>
            <span>Total Saldo</span>
            <span><?=number_format($total)?></span>
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
            <span>Tiket</span>
            <span><?=number_format($tiket)?></span>
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
      <a href="print_struk_temp_bank.php?det=<?=$faktur?>" class="btn-solid">PRINT</a>
    </footer>
		</form>

<?=$msg?>

    <!-- Main End -->
<?php
include("footer_alt.inc.php");
?>
