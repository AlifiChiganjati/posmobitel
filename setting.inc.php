<?php
$outlet = "MOBITEL";
$folder = "tsel_kiosk_1";

$qry_cnet = mysql_query("select * from customers_digital where user='$user'");
$data_cnet = mysql_fetch_object($qry_cnet);
$id_cnet = $data_cnet->cnet_id;
$pin_cnet = $data_cnet->pin;
$pass_cnet = $data_cnet->h2h_pass;
$saldo_cnet = $data_cnet->saldo;

function topupsignature($trxtime, $id, $vtype, $tujuan, $trxid){
	 $rawbody = $trxtime . $id. $vtype. $tujuan . $trxid;
	 $body = strrev($rawbody);
	 $sha = 	hash('sha256', $body);
	 $signature = strtolower(bin2hex($sha));
	 return $signature;
}

function cnetapi_topup($kodep,$nohp,$nf,$id,$pin,$pass){
     $url = 'http://210.210.163.78:8092/topup2';
	 $trxtime = date("Y-m-d H:i:s");
	 $signature = topupsignature($trxtime, $id, $kodep, $tujuan, $nf);
     $postfield ="id=$id&pin=$pin&user=$id&pass=$pass&vtype=$kodep&tujuan=$nohp&trxid=$nf";

     $curl = curl_init();
     $res="";               
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     $rs= json_decode($response, true);
      $status = $rs['status'];
     if($status){
          $res = "1;$response";
     }else{
          $res = "0;$response - $postfield";
     }
     return $res;
     
}

function cnetapi_regis($nohp,$nama,$kota){
     $url = 'http://210.210.163.78:8092/registration';
     $postfield ="id=CN0025&pin=79400&user=CN0025&pass=55566&hp=$nohp&nama=$nama&kota=$kota";

     $curl = curl_init();
                    
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     $rs= json_decode($response, true);

     $status = $rs['status'];
     $pesan= $rs['message'];
     if($status){
          $agenid = $rs['agenid'];
          $pasword = $rs['password'];
          $pin = $rs['pin_transaksi'];
          $h2h_user = $rs['h2h_user'];
          $h2h_pass = $rs['h2h_password'];
          $res = "1;$agenid;$pasword;$pin;$h2h_user;$h2h_pass;$response";
     }else{
          $res = "0;0;0;registrasi ke cnet gagal $response;0;0;0";
     }
     return $res;
}

function cnetapi_deposit($id,$pin,$pass,$bank,$jumlah,$reff){
     $url = 'http://210.210.163.78:8092/deposit_flip';
     $postfield ="id=$id&pin=$pin&user=$id&pass=$pass&bank=$bank&jumlah=$jumlah&trxid=$reff";

     $curl = curl_init();
                    
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     $rs= json_decode($response, true);

     $status = $rs['status'];
     $pesan= $rs['message'];
     if($status){
          $pesan = $rs['message'];
          $res = "1;$pesan;0";
     }else{
          $res = "0;0;$response - $postfield";
     }
     return $res;
}

function cnetapi_gantipin($id,$pin,$pass,$passreg,$pinbaru){
     $url = 'http://210.210.163.78:8092/pin';
     $postfield ="id=$id&pin=$pin&user=$id&pass=$pass&passreg=$passreg&pinbaru=$pinbaru";

     $curl = curl_init();
                    
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     $rs= json_decode($response, true);

     $status = $rs['status'];
     $pesan= $rs['message'];
     if($status){
          $res = "1;0";
     }else{
          $res = "0;$response - $postfield";
     }
     return $res;
}

function cnetapi_ceksaldo($id,$pin,$pass){
    $url = 'http://210.210.163.78:8092/balance';
     $postfield ="id=$id&pin=$pin&user=$id&pass=$pass";

     $curl = curl_init();
                    
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     $rs= json_decode($response, true);

     $status = $rs['status'];
     
     if($status){
          $saldo= $rs['saldo'];
          $saldo_antri = $rs['saldo antri'];
          $res = "1;$saldo;$saldo_antri";
     }else{
          $res = "0;0;$response - $postfield";
     }
     return $res;
}

function cnetapi_inquiry($kodep,$nohp,$nf,$id,$pin,$pass){
     $url = 'http://210.210.163.78:8092/inquiry_pln';
     $postfield ="id=$id&pin=$pin&user=$id&pass=$pass&tujuan=$nohp&trxid=$nf";

     $curl = curl_init();
     $res="";               
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     $rs= json_decode($response, true);
      $status = $rs['status'];
     if($status){
        $nama = $rs['nama_pelanggan'];
          $res = "1;$nama;$nf";
     }else{
          $res = "0;$response - $postfield;0";
     }
     return $res;
     
}

function cnetapi_inquiry_ppob($kodep,$nohp,$nf,$id,$pin,$pass){
     $url = 'http://210.210.163.78:8092/inquiry';
     $postfield ="id=$id&pin=$pin&user=$id&pass=$pass&vtype=$nohp&tujuan=$kodep&trxid=$nf";

     $curl = curl_init();
     $res="";               
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     $rs= json_decode($response, true);
      $status = $rs['status'];
     if($status){
        $nama = $rs['nama_pelanggan'];
        $res = "1;$response;1;1";
     }else{
        $res = "0;$response;0;0";
     }
     return $res;
     
}

function cnetapi_pay($kodep,$nohp,$nf,$id,$pin,$pass,$tagihan){
     $url = 'http://210.210.163.78:8092/pay';
     $postfield ="id=$id&pin=$pin&user=$id&pass=$pass&vtype=$kodep&tujuan=$nohp&trxid=$nf&tagihan=$tagihan";

     $curl = curl_init();
     $res="";               
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     $rs= json_decode($response, true);
      $status = $rs['status'];
     if($status){
        $res = "1;$response;1;1";
     }else{
        $res = "0;$response;$postfield;0";
     }
     return $res;
     
}

function cnetapi_updates(){
     $url = 'http://210.210.163.78:8092/product';
     $postfield ="id=CN0025&pin=79400&user=CN0025&pass=55566&opr";

     $curl = curl_init();
                    
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     return $response;
}

function cnetapi_la($kodep,$nohp,$nf,$id,$pin,$pass,$saldo){
     $url = 'http://210.210.163.78:8092/linkaja';
     $postfield ="id=$id&pin=$pin&user=$id&pass=$pass&vtype=$kodep&tujuan=$nohp&trxid=$nf&saldo=$saldo";

     $curl = curl_init();
     $res="";               
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     $rs= json_decode($response, true);
      $status = $rs['status'];
     if($status){
          $res = "1;$response";
     }else{
          $res = "0;$response";
     }
     return $res;   
}
function cnetapi_potong_saldo_kirimuang($kodep,$nohp,$nf,$harga,$id,$pin,$pass){
     $url = 'http://210.210.163.78:8092/transfer';
     $postfield ="id=$id&pin=$pin&user=$id&pass=$pass&vtype=$kodep&tujuan=$nohp&trxid=$nf&saldo=$harga";

     $curl = curl_init();
     $res="";               
     curl_setopt_array($curl, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS => $postfield,
       CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
       ),
     ));

     $response = curl_exec($curl);

     curl_close($curl);
     $rs= json_decode($response, true);
      $status = $rs['status'];
     if($status){
          $res = "1;$response";
     }else{
          $res = "0;$response - $postfield";
     }
     return $res;
     
}
?>