<?php
session_start();
$title = "CHECKOUT KONFIRMASI VA";
include("connection.inc.php");
include("function.inc.php");
include("session_check.php");
include("header_alt.inc.php");
$idsales = $_SESSION['IDSALES'];

$nors = isset($_GET['nors'])? cleanall($_GET['nors']) : "";
$namars = isset($_GET['namars'])? cleanall($_GET['namars']) : "";
$qty = isset($_GET['qty'])? cleanall($_GET['qty']) : "";
$total = isset($_GET['total'])? cleanall($_GET['total']) : "";
$fee = isset($_GET['fee'])? cleanall($_GET['fee']) : "";
$bank = isset($_GET['bank'])? cleanall($_GET['bank']) : "";
$faktur = isset($_GET['faktur'])? cleanall($_GET['faktur']) : "";
$tiket = isset($_GET['tiket'])? cleanall($_GET['tiket']) : "";

$totalbyr = $total + $fee + $tiket;

$bankname = get_bank_name($bank);
$nova = "88919" . substr($nors, 1);

?>
﻿
    <div class="page-content-wrapper">
      <div class="container">
        <?=$msg?>
        <!-- Cart Wrapper-->
        <form method="post">
        <div class="checkout-wrapper-area py-3">
          <!-- Billing Address-->
          <div class="billing-information-card mb-3">
            <div class="card billing-information-title-card bg-danger">
              <div class="card-body">
                <h6 class="text-center mb-0 text-white">Order diterima</h6>
              </div>
            </div>
            <div class="card user-data-card">
              <div class="card-body">
                <div class="single-profile-data d-flex align-items-center justify-content-between">
                  <div class="title d-flex align-items-center"><i class="lni lni-phone"></i><span>No Reseller</span></div>
                  <div class="data-content"><?=$nors?>
									</div>
                </div>

                <div class="single-profile-data d-flex align-items-center justify-content-between">
                  <div class="title d-flex align-items-center"><i class="lni lni-user"></i><span>Nama</span></div>
                  <div class="data-content"><?=$namars?>
                  </div>
                </div>

                  <div class="single-profile-data d-flex align-items-center justify-content-between">
                  <div class="title d-flex align-items-center"><i class="lni lni-wallet"></i><span>Virtual Account</span></div>
                  <div class="data-content"><?=$nova?>
                  </div>
                </div>

                <div class="single-profile-data d-flex align-items-center justify-content-between">
                  <div class="title d-flex align-items-center"><i class="lni lni-money-protection"></i><span>Nilai Saldo</span></div>
                  <div class="data-content"><?=number_format($qty)?>
                  </div>
                </div>

                <div class="single-profile-data d-flex align-items-center justify-content-between">
                  <div class="title d-flex align-items-center"><i class="lni lni-money-protection"></i><span>Administrasi</span></div>
                  <div class="data-content"><?=number_format($fee)?>
                  </div>
                </div>

                <div class="single-profile-data d-flex align-items-center justify-content-between">
                  <div class="title d-flex align-items-center"><i class="lni lni-money-protection"></i><span>Tiket</span></div>
                  <div class="data-content"><?=number_format($tiket)?>
                  </div>
                </div>

                <div class="single-profile-data d-flex align-items-center justify-content-between">
                  <div class="title d-flex align-items-center"><i class="lni lni-money-protection"></i><span>Total</span></div>
                  <div class="data-content"><?=number_format($totalbyr)?>
                  </div>
                </div>

              </div>
            </div>
          </div>


          <div class="card cart-amount-area">
            <div class="card-body d-flex align-items-center justify-content-between">
              <a href="index.php" class="btn btn-sm btn-success w-50">TUTUP</a> &nbsp;
              <a href="print_struk_temp_va.php?det=<?=$faktur?>" class="btn btn-sm btn-warning w-50">PRINT</a>
            </div>
          </div>
        </div>
      </form>
      </div>
    </div>

    <?php
    include("footer.inc.php");
     ?>
