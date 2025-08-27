<?php
include("connection_main.php");
include("function.inc.php");
$id_h2h = "CN0006";
$pin_h2h = "95976";

$ran = generate_string(2);
$rax = rand(10,90);
$nocode = "INV". date("dmy").date("Hi").$ran.$rax;

if (isset($_GET['tipe'])){

	 if ($_GET['tipe'] == "customer"){
		$input = cleanall($_GET['term']);
		$query = mysql_query("SELECT customer_no, name, no_rs FROM customers WHERE (name LIKE '%$input%' or customer_no LIKE '%$input%') and category= 'ELEKTRIK' limit 3");

		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
			?>
			<ul class="page-nav ps-0">
			<?php
			while ($data = mysql_fetch_row($query))
			{
			?>
			<a href="javascript:autoInsertcustomer('<?=$data[0] ."#". $data[1]?>');"> <li style="font-size:25px"><?php echo $data[0] ." / ". $data[1]?> <i class='lni lni-chevron-right'></i></li></a><hr>
			<?php
			}
			?>
			</ul>
			<?php
		}else{
			echo "<span class='badge badge-danger'>Data Customer tidak ditemukan!</span>";
		}
	}else if ($_GET['tipe'] == "hlr"){
		$input = cleanall($_GET['term']);
		$query = mysql_query("SELECT * FROM hlr WHERE prefix LIKE '%$input%'");
		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
			$data = mysql_fetch_row($query);
			$opr=$data[0];
			$oprx = "PULSA ".$data[5];
			$qdenom =mysql_query("select * from product_elektrik where operator in (select nama_opr from operators where groups='$oprx' and status = 1) 
									and status='-' and aktif ='YA' order by unit_price");
			echo "<br><div class='row'>
						<div class='col-6'>Pilih Nominal Pulsa: </div>
						<div class='col-6 text-end'>$opr</div>
						</div><hr>";
			echo "<div class ='row'>";
			while($datad=mysql_fetch_row($qdenom))
			{
				$idp=$datad[0];
				//$desc = explode(" ",$datad[1]);
				$judul = str_replace($opr,"",$datad[1]);
				//$nominal = $desc[1];
			?>	
                    <label class='form-check-label col-xxl-3 col-md-3 col-4 g-1' for='produk<?=$datad[0]?>'> 
                        <input class='form-check-input' type='radio' name='produks' id='produk<?=$datad[0]?>' value='<?=$datad[0]?>' required>                 
                        <div class='alert border-secondary colored-div text-start pt-1 pb-1 mb-0' role='alert' style="font-size:16pt">
							<div class='row g-0'>
								<div class='col-12 text-center' style="font-size:11pt;"><strong><?=$datad[8]?></strong></div>
								<div class='col-12 text-center' style="font-size:9pt;"><small>Rp.<?=number_format($datad[3])?></small></div>		
							</div>
                        </div>
                    </label>
			<?php

			}	
			echo "</div>";
			echo "<div class='fixed-bottom bg-white'>
			      <div class='input-box mx-2 mb-3'>
			        <button type='submit' id='myBtn' class='btn-solid bg-dark' name='next1'> LANJUT</button>
			      </div>
			    </div>";
		}else{
			echo "<span class='badge badge-danger'>error</span>";
		}
	}else if ($_GET['tipe'] == "plntoken"){
		$input = cleanall($_GET['term']);
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://210.210.163.78:8087/inquiry_pln',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => "id=$id_h2h&pin=$pin_h2h&user=$id_h2h&pass=$pin_h2h&tujuan=$input&trxid=$nocode",
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/x-www-form-urlencoded',
		    'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		$rs= json_decode($response, true);
		$status = $rs['status'];
		if($status=="true"){
			$nama = $rs['nama_pelanggan'];
			echo "<div class='content-color mt-1' style='font-size:12pt;'>Nama Pelanggan:</div>
					<input id='nama_pelanggan' name='nama_pelanggan' type='text' class='form-control' autocomplete='Off' value='$nama' required style='font-size: 14pt;' readonly>";
			$qdenom =mysql_query("select * from product_elektrik where operator in (select nama_opr from operators where groups='PLN TOKEN' and status = 1) and status='-' and aktif='YA' order by unit_price");
			echo "<div class='row mt-2'>
						<div class='col-6'>Pilih Nominal: </div>
						<div class='col-6 text-end'></div>
						</div><hr>";
			echo "<div class ='row'>";
			while($datad=mysql_fetch_row($qdenom))
			{
				$idp=$datad[0];
			?>	
                    <label class='form-check-label col-xxl-6 col-md-6 col-6 ml-0 mr-0 g-1' for='produk<?=$datad[0]?>'> 
                        <input class='form-check-input' type='radio' name='produks' id='produk<?=$datad[0]?>' value='<?=$datad[0]?>' required>                 
                        <div class='alert border-secondary colored-div text-start py-1 mb-0' role='alert'>
							<div class='row'>
								<div class='col-12' style="font-size:10pt;height: 2rem;"><small><strong><?=$datad[8]?></strong></small></div>
								<div class='col-12 text-end' style="font-size:9pt;"><small>Rp.<?=number_format($datad[3])?></small></div>		
							</div>
                        </div>
                    </label>
			<?php

			}	
			echo "</div>";
		}else{
			echo " <br><div class='alert alert-danger' role='alert'> ID Pelanggan atau No Meter tidak ditemukan! </div>";
		}
	}else if ($_GET['tipe'] == "plnpasca"){
		$input = cleanall($_GET['term']);
		$input2 = cleanall($_GET['term2']);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://210.210.163.78:8087/inquiry',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => "id=$id_h2h&pin=$pin_h2h&user=$id_h2h&pass=$pin_h2h&vtype=$input2&tujuan=$input&trxid=$nocode",
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
			$message = $rs['message'];
			$periode= $rs['periode'];
	 		$total_tagihan=$rs['total_tagihan'];
			$reff1 = $rs['ref1'];
	 			echo "<div class='col-12 pb-3'>
						<p class='content-color' style='font-size:15pt;'>Nama Pelanggan :</p>
						<input id='detail' name='detail' type='text' class='form-control' autocomplete='Off' value='$nama' required readonly>
					</div>";
		        echo "<div class='col-12 pb-3'>
						<p class='content-color' style='font-size:15pt;'>Total Tagihan :</p>
						<input id='tagihan' name='tagihan' type='text' class='form-control' autocomplete='Off' value='$total_tagihan' required readonly>
					</div>";
				echo "<div class='col-12'>
						<p class='content-color' style='font-size:15pt;'>Periode:</p>
						<input id='periode' name='periode' type='text' class='form-control' autocomplete='Off' value='$periode' required readonly>
						<input id='reff1' name='reff1' type='hidden' class='form-control' autocomplete='Off' value='$reff1' required readonly>
					</div>";
				echo "<div class='fixed-bottom bg-white'>
			      <div class='input-box mx-2 mb-3'>
			        <button type='submit' id='myBtn' class='btn-solid bg-dark' name='next1'> LANJUT</button>
			      </div>
			    </div>";
				
	 		}else{
	 			echo $rs['message']." - ".$input2;
	 		}
	}else if ($_GET['tipe'] == "paketdata"){
		$input = cleanall($_GET['term']);
		
		$query = mysql_query("SELECT * FROM hlr WHERE prefix LIKE '%$input%'");
		$hasil = mysql_num_rows($query);
		if ($hasil > 0){
			$data = mysql_fetch_row($query);
			$opr=$data[0];
			$oprx = $data[4];
			$expl = explode("','",$oprx);
			$qdenom =mysql_query("select * from product_elektrik where operator in (select nama_opr from operators where groups='$oprx' and status = 1) 
									and status='-' and aktif ='YA' order by unit_price");
			echo "<br><div class='row'>
						<div class='col-6'>Pilih Paket: </div>
						<div class='col-6 text-end'>$expl[0]</div>
						</div>
						<div class='search-box'>
							<i class='iconly-Search icli search'></i>
							<input class='form-control' type='search' placeholder='Cari Paket Data..' style='font-size:10pt' id='myInput' onkeyup='myFunction()'>
						</div>";
			echo "<div class ='row mt-3' id='myUL'>";
			while($datad=mysql_fetch_row($qdenom))
			{
				$idp=$datad[0];
			?>	
                    <label class='form-check-label col-xxl-6 col-md-6 col-12 ml-0 mr-0 mb-0' for='produk<?=$datad[0]?>'> 
                        <input class='form-check-input' type='radio' name='produks' id='produk<?=$datad[0]?>' value='<?=$datad[0]?>' required>                 
                        <div class='alert border-secondary colored-div text-start py-1 mb-1' role='alert'>
							<div class='row'>
								<div class='col-12' style="font-size:9pt;height: 2rem;"><small><strong><?=$datad[1]?></strong></small></div>
								<div class='col-12 text-end' style="font-size:9pt;"><small><?=$datad[8]?></small></div>
								<div class='col-12 text-end' style="font-size:9pt;"><small>Rp.<?=number_format($datad[3])?></small></div>		
							</div>
                        </div>
                    </label>
			<?php

			}	
			echo "</div>";
			echo "<div class='fixed-bottom bg-white'>
			      <div class='input-box mx-2 mb-3'>
			        <button type='submit' id='myBtn' class='btn-solid bg-dark' name='next1'> LANJUT</button>
			      </div>
			    </div>";
		}else{
			echo "<span class='badge badge-danger'>error</span>";
		}
	}else if ($_GET['tipe'] == "emoney"){
		$input = cleanall($_GET['term']);		
		
			$qdenom =mysql_query("select * from product_elektrik where operator = '$input' and status='-' and aktif='YA' order by unit_price");
			echo "<br><div class='row mb-3'>
						<div class='col-6'>Pilih Denom: </div>
						<div class='col-6 text-end'>$input</div>
						</div><hr>
						<!--<div class='search-box mb-3'>
							<i class='iconly-Search icli search'></i>
							<input class='form-control' type='search' placeholder='Cari Denom..' style='font-size:15pt' id='myInput' onkeyup='myFunction()'>
						</div>-->";
			echo "<div class ='row'>";
			while($datad=mysql_fetch_row($qdenom))
			{
				$idp=$datad[0];
			?>	
                    <label class='form-check-label col-xxl-4 col-md-4 col-6 ml-0 mr-0 mb-0 g-1' for='produk<?=$datad[0]?>'> 
                        <input class='form-check-input' type='radio' name='produks' id='produk<?=$datad[0]?>' value='<?=$datad[0]?>' required>                 
                        <div class='alert border-secondary colored-div text-start py-1 mb-1' role='alert'>
							<div class='row'>
								<div class='col-12' style="font-size:9pt;height: 2rem;"><small><strong><?=$datad[1]?></strong></small></div>
								<div class='col-12 text-end' style="font-size:9pt;"><small><?=$datad[8]?></small></div>
								<div class='col-12 text-end' style="font-size:9pt;"><small>Rp.<?=number_format($datad[3])?></small></div>		
							</div>
                        </div>
                    </label>
			<?php

			}	
			echo "</div>";
			echo "<div class='fixed-bottom bg-white'>
			      <div class='input-box mx-2 mb-3'>
			        <button type='submit' id='myBtn' class='btn-solid bg-dark' name='next1'> LANJUT</button>
			      </div>
			    </div>";
	}else if ($_GET['tipe'] == "cek_callback"){
		$input = cleanall($_GET['nf']);
		$qry = mysql_query("select status from elektrik_temp where no_faktur='$input'");
		$data=mysql_fetch_object($qry);
		
		$status = $data->status;
		echo $status;
	}


}
?>
