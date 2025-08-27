<?php
session_start();
$title = "TRANSAKSI HARI INI";
include("connection.inc.php");
include("session_check.php");
include("function.inc.php");
include("header_mini.inc.php");
$qry = "select w.id_product, count(w.id) as jml, p.name_product from imei_gudang w left join products p on (w.id_product = p.id_product) where w.warehouse ='$gudang' and w.status = '0'";
$qry = "select id_product, count(id) as jml,
            sum(qty) as tqty, sum(total) as ttotal, sum(fee) as tfee,
            sum(total + fee) as tbayar
            from log_sales_invoice
            where (status=4) and idsalesman = '$idsales' and date(date_order) = date(now()) group by id_product";

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  while ($rs = mysql_fetch_object($sql)){
    $ixpro = strtoupper($rs->id_product);
    // $dxpro = strtoupper($rs->name_product);
    $stotal = number_format($rs->ttotal);
    $res .= "<tr>
                <td><a href='#'>$ixpro <span>$rs->jml</span> </a></td>
                <td>
                  <div class='quantity'>
                    $stotal
                  </div>
                </td>
              </tr>";

    $total += $rs->ttotal;
  }
}

?>
﻿
    <div class="page-content-wrapper">
      <div class="container">
        <!-- Cart Wrapper-->
        <div class="cart-wrapper-area py-3">
          <div class="cart-table card mb-3">
            <div class="table-responsive card-body">
              <table class="table mb-0">
                <tbody>
                  <?php echo $res; ?>
                </tbody>
              </table>
            </div>
          </div>


        </div>
      </div>
    </div>

    <?php
    include("footer.inc.php");
     ?>
