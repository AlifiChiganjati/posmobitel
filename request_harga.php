<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();
$title = "LIST REQUEST";
include("connection.inc.php");
include("function.inc.php");
include("session_check.php");
include("header_alt.inc.php");
$idsales = $_SESSION['IDSALES'];
$clustero = $_SESSION['CLUSTER'];
$cluster = cluster($clustero);

if($_POST['tolak'] == "TOLAK"){
  $idsalex = isset($_POST['idsalex'])? $_POST['idsalex'] : "";
  $idprox = isset($_POST['idprox'])? $_POST['idprox'] : "";
  $idcux = isset($_POST['idcux'])? $_POST['idcux'] : "";

  mysql_query("update sales_invoice set is_request= 0, sent_request=0, newprice=0 where status=0 and idsalesman='$idsalex' and id_product='$idprox' and id_customer='$idcux' and is_request=1 and newprice > 0");
  $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
        <div class='offcanvas-body small'>
          <div class='app-info'>
            <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
            <div class='content'>
              <h3>Notifikasi <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
              <a href='#'>Permintaan perubahan harga telah anda tolak</a>
            </div>
          </div>
        </div>
      </div>";
}

if($_POST['setuju'] == "SETUJU"){
  $idsalex = isset($_POST['idsalex'])? $_POST['idsalex'] : "";
  $idprox = isset($_POST['idprox'])? $_POST['idprox'] : "";
  $idcux = isset($_POST['idcux'])? $_POST['idcux'] : "";

  $qry = "select * from sales_invoice where status=0 and idsalesman='$idsalex' and id_product='$idprox' and id_customer='$idcux' and is_request=1";
  $sql = mysql_query($qry);
  while($rx = mysql_fetch_object($sql)){
    if($rx->newprice == 0){
      mysql_query("update sales_invoice set is_request= 0, sent_request=0, newprice=0, approval='$idsales' where status=0 and idsalesman='$idsalex' and id_product='$idprox' and id_customer='$idcux' and is_request=1");
    }else{
      mysql_query("update sales_invoice set unit_price=$rx->newprice, total=$rx->newprice, is_request= 0, sent_request=0, newprice=0, approval='$idsales' where status=0 and idsalesman='$idsalex' and id_product='$idprox' and id_customer='$idcux' and is_request=1");
    }

  }

  $msg = "
  <div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
        <div class='offcanvas-body small'>
          <div class='app-info'>
            <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
            <div class='content'>
              <h3>Notifikasi <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
              <a href='#'>Anda telah menyetujui permintaan perubahan</a>
            </div>
          </div>
        </div>
      </div>";
}

$qry = "select sum(qty) as jml, sum(total) as oritotal, sum(newprice) as newtotal, id_product, idsalesman, warehouse_name, id_customer from sales_invoice where status = 0 and sent_request=1 and newprice > 0 and data2= '$cluster' group by idsalesman, id_product, id_customer order by date_order asc limit 20";
$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  $i = 1;
  while ($rs = mysql_fetch_object($sql)){
    $ixpro = strtoupper($rs->id_product);
    $name = name_customer($rs->id_customer);
    $oritotal = $rs->oritotal;
    $newtotal = $rs->newtotal;
	$wh = $rs->warehouse_name;
    $res .= "<form method='POST' name='frm1'>
          <table class='table mb-0'>
            <tbody>
            <tr>
                <th scope='row'>
					<a class='remove-product' href='#'>$i</a>
				</th>         
				<td>
					<div class='row'>
						<div class='col-8'><b>$rs->idsalesman</b></div><div class='col-4'>".number_format($oritotal)."</div>
						<div class='col-8'>QTY = $rs->jml Pcs</div><div class='col-4'>".number_format($newtotal)."</div>
						<div class='col-12'><small>$rs->id_customer - $name</small></div>
						<div class='col-12'><small>$wh</small></div>
						<div class='col-12 text-center'><small>$status</small></div>
					</div>
				</td>
            </tr>
            <tr>
                <td colspan='3'> 
					<div class='card-body d-flex align-items-center justify-content-between'>
						<input type='hidden' id='idsalex' name='idsalex' value='$rs->idsalesman'>
						<input type='hidden' id='idprox' name='idprox' value='$ixpro'>
						<input type='hidden' id='idcux' name='idcux' value='$rs->id_customer'>

						<input type='submit' name='tolak' value='TOLAK' class='btn btn-sm btn-warning' onClick=\"return confirm('Apakah data ini dibatalkan?');\">
						<input type='submit' name='setuju' value='SETUJU' class='btn btn-sm btn-success' onClick=\"return confirm('Apakah yakin akan proses data ini ?');\">
					</div>
                </td>
            </tr>
          </tbody>
        </table></form>";

  $i++;
  }
}

?>
﻿ <?=$msg?>
    <div class="page-content-wrapper">
      <div class="container">
        <!-- Cart Wrapper-->
        <div class="cart-wrapper-area py-3">
          <div class="cart-table card mb-3">
            <div class="table-responsive card-body">

                  <?php echo $res; ?>

            </div>
          </div>


        </div>
      </div>
    </div>

    <?php
    include("footer.inc.php");
     ?>
