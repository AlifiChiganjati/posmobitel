<?php
function TabelFooter($filename, $rCount,$pg,$jml, $parameter) {
  $pgCount = (int)ceil( $rCount / $jml);
  $pgCount = (int) $pgCount;
  $pgNext = $pgCount;
  $pgFirst = 1;
  $pgLast = $pgCount;
  if ($pgCount > 10) {
  	$pgFirst = ($pg > 10) ? $pg - 5 : 1;
  	$pgLast = $pg + 5;
  }

  if ($rCount > $jml) {
  	echo "&nbsp;<a class='btn btn-sm' href='$filename?$parameter&pg=1'>First</a>&nbsp;";
  	if ($pg > 1) echo "&nbsp;<a class='btn btn-sm' href='$filename?$parameter&pg=".($pg-1)."'>Prev</a>&nbsp;";
  	for ($i=$pgFirst;$i<=$pgLast;$i++) {
  		if ($i == $pg) {
  			echo "&nbsp; - ".$i." - &nbsp;";
  		} else {
  			echo "&nbsp;<a class='btn btn-sm' href='$filename?$parameter&pg=$i'>$i</a>&nbsp;";
  		}
  	}
  	if ($pg < $pgCount) echo "&nbsp;<a class='btn btn-sm' href='$filename?$parameter&pg=".($pg+1)."'>Next</a>&nbsp;";
  	echo "&nbsp;<a class='btn btn-sm' href='$filename?$parameter&pg=$pgCount'>Last</a>&nbsp;";
  }
  echo "<br><small>Halaman $pg dari $pgCount halaman dari total $rCount record</small>";
  return true;
}

 function cleanall($data){
   $filter = mysql_real_escape_string(trim(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)))));
   return $filter;
 }

 function cleaninput($data){
   $filter = strtoupper(trim(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)))));
   return $filter;
 }

 function cluster($data){
   $res = "-";
   $sql = mysql_query("select branch_name from branch where branch_id = '$data'");
   if($rs = mysql_fetch_object($sql)){
     $res = $rs->branch_name;
   }
   mysql_free_result($sql);
   return $res;
 }

 function charreplace($value) {
   $search = array(",",".");
   $replace = array("","");
   return str_replace($search, $replace, $value);
 }

 function data_salesman($idsales){
   $res= false;
     $q=mysql_query("select idsales, name, depo, id_department, id_branch, id_store, gudang_utama, cluster from salesman where idsales='$idsales' and settlement = 0");
     $n = mysql_num_rows($q);
     if ($n > 0) {
       if($rs = mysql_fetch_object($q)){
         $res = $rs;
       }
     }
     mysql_free_result($q);
     return $res;
 }

 function name_customer($idcustomer){
   $res= "-";
     $q=mysql_query("select name from customers where customer_no = '$idcustomer'");
     $n = mysql_num_rows($q);
     if ($n > 0) {
       if($rs = mysql_fetch_object($q)){
         $res = $rs->name;
       }
     }
     mysql_free_result($q);
     return $res;
 }

 function data_customer($idcustomer){
   $res= false;
	if(strlen($idcustomer) > 0){
		$q=mysql_query("select name, no_rs, customer_no, outlet_id, level, disc_eload, npwp, umkm from customers where no_rs = '$idcustomer' or customer_no='$idcustomer'");
		 $n = mysql_num_rows($q);
		 if ($n > 0) {
		   if($rs = mysql_fetch_object($q)){
			 $res = $rs;
		   }
		 }
		 mysql_free_result($q);
	}     
     return $res;
 }

 function data_customer_fisik($idcustomer){
   $res= false;
     $q=mysql_query("select name, customer_no, outlet_id, level, npwp, umkm from customers where (no_rs = '$idcustomer' or customer_no='$idcustomer') and status <> 2");
     $n = mysql_num_rows($q);
     if ($n > 0) {
       if($rs = mysql_fetch_object($q)){
         $res = $rs;
       }
     }
     mysql_free_result($q);
     return $res;
 }

 function data_customer_fisik_jbl($idcustomer){
		$res= false;
		$q=mysql_query("select name, customer_no, outlet_id, level, npwp, umkm from customers where customer_no='$idcustomer'");
		$n = mysql_num_rows($q);
		if ($n > 0) {
			if($rs = mysql_fetch_object($q)){
				$res = $rs;
			}
		}
		mysql_free_result($q);
		return $res;
	}

 function is_exist_customer($idcustomer){
   $res= false;
     $q=mysql_query("select customer_no from customers where customer_no = '$idcustomer'");
       if($rs = mysql_fetch_object($q)){
         $res = true;
       }
     mysql_free_result($q);
     return $res;
 }

 function insertlog($log, $user){
   mysql_query("insert into user_activities (tanggal, user, activity) values (now(), '$user', '$log')");
 }

 function sum_inbox ($idsales){
   $qry = "select count(id) as jml from sales_invoice where idsalesman ='$idsales'";
   $sql = mysql_query($qry);
   if($rs = mysql_fetch_object($sql)){
     $total = $rs->jml;
   }else{
     $total = 0;
   }
   mysql_free_result($sql);
   return $total;
 }

 function cek_harga_product($id_product){
   $qry = "select unit_price from products where id_product='$id_product'";
   $sql = mysql_query($qry);
   if($rs = mysql_fetch_object($sql)){
     return $rs->unit_price;
   }else{
     return false;
   }
 }

 function cek_level_customer($no){
   $qry = "select level from customers where customer_no='$no' or no_rs='$no'";
   $sql = mysql_query($qry);
   if($rs = mysql_fetch_object($sql)){
     return $rs->level;
   }else{
     return "1";
   }
 }


 function is_one_product($idproduct, $sales){
   $qry = "select id_product from sales_invoice_multi where status = 0 and sent = 0 and idsalesman='$sales'";
   $sql = mysql_query($qry);
   $rows = mysql_num_rows($sql);
   $res = true;
   if($rows > 0){
     while($rs = mysql_fetch_object($sql)){
       $product = $rs->id_product;
       if($product != $idproduct){
         $res = false;
         break;
       }else{
         $res = true;
       }
     }
   }
   return $res;
 }

 function is_queueing($serial){
   $qry = "select id from sales_invoice_multi where detail_notes = '$serial'";
   $sql = mysql_query($qry);
   if($rs = mysql_fetch_object($sql)){
     return true;
   }else{
     return false;
   }
 }

 function cekstatus_imei($idsales, $nomor){
   $res = false;
   $qry = mysql_query("select id from sales_invoice where idsalesman = '$idsales' and description='$nomor' and tipe = 'SN1'
      and (DATE_ADD(date_order, INTERVAL 1 WEEK) > NOW())
      union select id from log_sales_invoice where idsalesman = '$idsales' and description='$nomor' and tipe = 'SN1'
      and (DATE_ADD(date_order, INTERVAL 1 WEEK) > NOW())");
   if ($r = mysql_fetch_object($qry)) {
     $res = true;
   }
   mysql_free_result($qry);
   return $res;
 }


 function get_product_imei($imei, $warehouse){
   $res = false;
   $qry = "select id_product from imei_gudang where imei = '$imei' and warehouse='$warehouse'";
   $sql = mysql_query($qry);
   if($rs = mysql_fetch_object($sql)){
     $res = $rs->id_product;
   }
   mysql_free_result($sql);
   return $res;
 }

 function status_transaksi($status){
   switch($status){
     case "0" : $res = "ANTRIAN"; break;
     case "1" : $res = "ON PROGRESS"; break;
     case "2" : $res = "GAGAL"; break;
     case "4" : $res = "BERHASIL"; break;

   }
   return $res;
 }

 function status_deposit($status){
   switch($status){
     case "0" : $res = "ANTRIAN"; break;
     case "1" : $res = "DEP SUKSES"; break;
     case "2" : $res = "DIBATALKAN"; break;

   }
   return $res;
 }

 function get_bank_name($account_no){
   $res = "-";
   $qry = "select account_name from accounts where account_no = '$account_no'";
   $sql = mysql_query($qry);
   if($rs = mysql_fetch_object($sql)){
     $res = $rs->account_name;
   }
   mysql_free_result($sql);
   return $res;
 }


 function is_stock_inwarehouse($imei, $warehouse){
   $res = false;
   $qry = "select imei from imei_gudang where imei = '$imei' and warehouse='$warehouse' and status ='0'";
   $sql = mysql_query($qry);
   if($rs = mysql_fetch_object($sql)){
     $res = true;
   }
   mysql_free_result($sql);
   return $res;
 }

 function getMyTimeDiff($t1,$t2)
		{
		$a1 = explode(":",$t1);
		$a2 = explode(":",$t2);
		$time1 = (($a1[0]*60*60)+($a1[1]*60)+($a1[2]));
		$time2 = (($a2[0]*60*60)+($a2[1]*60)+($a2[2]));
		$diff = abs($time1-$time2);

		$hours = floor($diff/(60*60));
		$mins = floor(($diff-($hours*60*60))/(60));
		$secs = floor(($diff-(($hours*60*60)+($mins*60))));
		$result = $hours.":".$mins.":".$secs;

    //$result = $mins;

		return $result;
		}


    function cekstatus_akselereasi($idsalesman, $id_product, $qty, $id_customer){ //idcust = LA
      $res = false;
      $qry = mysql_query("select id from akselerasi where idsalesman = '$idsalesman' and id_product='$id_product' and qty='$qty' and id_customer='$id_customer'
         and (DATE_ADD(tanggal, INTERVAL 24 HOUR) > NOW())");
      if ($r = mysql_fetch_object($qry)) {
        $res = true;
      }
      mysql_free_result($qry);
      return $res;
    }

    function generate_string($strength = 16) {
     $input = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
     $input_length = strlen($input);
     $random_string = '';
     for($i = 0; $i < $strength; $i++) {
         $random_character = $input[mt_rand(0, $input_length - 1)];
         $random_string .= $random_character;
     }
     return $random_string;
    }


    function cek_harga_product_level($id_product, $level){

      $qry = "select unit_price, harga$level as harga from products where id_product='$id_product'";
      //echo $qry;
      $sql = mysql_query($qry);
      if($rs = mysql_fetch_object($sql)){
        if($rs->harga > 0){
          $hrgproduct = $rs->harga;
        }else{
          $hrgproduct = $rs->unit_price;
        }
        return $hrgproduct;
      }else{
        return false;
      }
    }



    function ticket(){
      $ticket = false;
      $q = "select no from ticket_no where tanggal = date(now())";
      $sql = mysql_query($q);
      if($re = mysql_fetch_object($sql)){
        $ticket = $re->no + 1;
        mysql_query("update ticket_no set no = `no` + 1 where tanggal = date(now()) and no=$re->no");
      }else{
        $im = "insert into ticket_no (tanggal, no) values (now(), 101)";
        mysql_query($im);
        $ticket = 101;
      }
      return $ticket;
    }

  function cek_status_settlement($idsales){
    $res= false;
    $q=mysql_query("select id from settlement where idsalesman = '$idsales' and date(date_settle) = date(now()) and status < 2");
    $n = mysql_num_rows($q);
    if($rs = mysql_fetch_object($q)){
        $res = true;
    }
    mysql_free_result($q);
    return $res;
}

function aol_auth(){
 $res=false;
   $q=mysql_query("select baseurl, bearer, session from aol limit 1");
   $n = mysql_num_rows($q);
   if ($n > 0) {
     if($rs = mysql_fetch_object($q)){
       $res = $rs;
     }
   }
   mysql_free_result($q);
   return $res;
}

function cek_nofaktur($no_faktur){
  $res=false;

    $q=mysql_query("select idsalesman, id_customer, data1 as depo, data2 as cluster FROM log_sales_invoice WHERE no_faktur = '$no_faktur' GROUP BY no_faktur");
    $n = mysql_num_rows($q);
    if ($n > 0) {
      if($rs = mysql_fetch_object($q)){
        $res = $rs;
      }
    }else{
      $qe=mysql_query("select idsalesman, id_customer, data1, data2 as depo, data3 as cluster, duedate FROM log_sales_fisik_special WHERE no_faktur = '$no_faktur' GROUP BY no_faktur");
      $ne = mysql_num_rows($qe);
      if ($ne > 0) {
        if($rse = mysql_fetch_object($qe)){
          $res = $rse;
        }
      }
    //mysql_free_result($q);
    }
    return $res;
  }

  function sum_request_harga($cluster){
  	$sql = "select count(id) as jml from sales_invoice where status=0 and data2='$cluster' and is_request=1 and newprice > 0";
    //echo $sql;
  	$mql = mysql_query($sql);
  		if($rs = mysql_fetch_object($mql)){
  			$total = $rs->jml;
  		}else{
  			$total = 0;
  		}
  		return $total;
  }

  function get_stok_jbl($produk,$gudang){
    $res = 0;
    $qry = "select qty from stock_master where id_product= '$produk' and warehouse='$gudang'";
    $sql = mysql_query($qry);
    $ada = mysql_num_rows($sql);
    if($ada > 0){
      $data = mysql_fetch_object($sql);
      $res = $data->qty;
    }
    mysql_free_result($sql);
    return $res;
  }
  
function generateOutletId($nohp)
{
    $prefix = 'JBLTK';
    $date = date('dmy'); // Tanggal: 250624
    // $time = date('His'); // Waktu: 102530
    // $random= rand(1000, 9999);
    $last4 = substr($nohp, -4);
  return $prefix . $date  . $last4;
}

function uploadToRemote($fileTmp, $fileName, $fileType, $folder, $uploadUrl) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uploadUrl . "posjbl/upload_foto.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "file"   => new CURLFile($fileTmp, $fileType, $fileName),
        "folder" => $folder
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
//                if (curl_errno($ch)) {
//     echo "cURL Error: " . curl_error($ch);
// }
    curl_close($ch);
     
          // var_dump($response);
          // exit();
    $result = json_decode($response, true);
    if ($result && $result["status"] == "ok") {
        return $result["url"]; // URL lengkap
    }
    return "";
}

 ?>
