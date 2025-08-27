<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
session_start();
include("cek_login.inc.php");
include("connection.inc.php");
include("function.inc.php");
include("header.inc.php");
$user = $_SESSION['USERNAME'];

$filesname = "";
if ($_POST['submit'] == "UPLOAD")
{
	$filename = $_FILES["file"]["name"];
	$file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
	$file_ext = substr($filename, strripos($filename, '.')); // get file name
	$filesize = $_FILES["file"]["size"];
	$allowed_file_types = array('.xls','.csv');

	if (in_array($file_ext,$allowed_file_types) && ($filesize < 20000000))
	{

		$newfilename = $filename;
		$filesname = $filename;

    	move_uploaded_file($_FILES["file"]["tmp_name"], "excelinject/uploads/" . $newfilename);
		//echo "File uploaded successfully.";
		$msg = "<div id='hideDiv'><div class='alert alert-success' role='alert'>Upload File berhasil!</div></div>";
	}
	elseif (empty($file_basename))
	{
		//echo "Please select a file to upload.";
		$msg = "<div class='alert alert-warning' role='alert'>Pilih file untuk diupload</div>";
	}
	elseif ($filesize > 200000)
	{
		//echo "The file you are trying to upload is too large.";
		$msg = "<div id='hideDiv'><div class='alert alert-warning' role='alert'>Ukuran File terlalu besar</div></div>";
	}
	else
	{
		// file type error
		$res = "Hanya tipe file ini yang bisa diupload " . implode(', ',$allowed_file_types);
		$msg = "<div id='hideDiv'><div class='alert alert-warning' role='alert'>$res</div></div>";
		unlink($_FILES["file"]["tmp_name"]);
	}
}

if($_POST['submit'] == "READ DATA"){
error_reporting(0);
$files = isset($_POST['filesname'])? $_POST['filesname'] : "";

if(!$files){
	$msg = "<div id='hideDiv'><div class='alert alert-warning border-warning' role='alert'>
	Kolom File name tidak boleh kosong!</div></div>";
}else{

		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');

		require_once ("excelx/Classes/PHPExcel.php");
		$objPHPExcel = PHPExcel_IOFactory::load("excelinject/uploads/" . $files);

		$dataArr = array();

		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
		    $worksheetTitle     = $worksheet->getTitle();
		    $highestRow         = $worksheet->getHighestRow(); // e.g. 10
		    $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
		    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

		    for ($row = 1; $row <= $highestRow; ++ $row) {
		        for ($col = 0; $col < $highestColumnIndex; ++ $col) {
		            $cell = $worksheet->getCellByColumnAndRow($col, $row);
		            $val = $cell->getValue();
		        	$dataArr[$row][$col] = $val;
		        }
		    }
		}

		unset($dataArr[1]);

		foreach($dataArr as $val){
			
			$depo = str_replace("'", "", trim($val['0']));
			$imei = str_replace("'", "", trim($val['3']));
			$harga = str_replace("'", "", trim($val['4']));
			$hpp = str_replace("'", "", trim($val['5']));
			$product = str_replace("'", "", trim($val['6']));
			$expired = str_replace("'", "", trim($val['7']));
			$pbi = str_replace("'", "", trim($val['8']));
			$transdate = str_replace("'", "", trim($val['9']));
			
			$status_imei_master=cek_status_imei_master($imei);
			if($status_imei_master == 0){
				$qry = "INSERT INTO temp_adjustment_inject SET
					depo = '". $depo ."',
					id_product_lama ='". trim($val['1']) ."',
					id_product_baru ='". trim($val['2']) ."',
					imei ='". $imei ."',
					harga ='". $harga ."',
					hpp ='". $hpp ."',
					expired ='". $expired ."',
					id_product ='". $product ."',
					no_pbi = '".$pbi."',
					date_trans = '".$transdate."',
					user = '". $user ."'
					";

				if(!mysql_query($qry)){
					$msgx .="error $imei; ";			
				}
			}else if($status_imei_master == 8){
				$msgx .="$imei tidak ditemukan; ";
			}else if($status_imei_master == 1){
				$msgx .="$imei sold/di gudang SF; ";
			}
		}
		$msgxx ="Validasi data nya di Menu Temporary Inject";
		
		//echo "<script language=javascript> location.href = 'temp_gudang_stok.php'; </script>";
		//$msg = "<div id='hideDiv'><div class='alert alert-success' role='alert'>OK! Validasi data nya di Menu Temporary Inject</div></div>";
		$msg = "<div class='alert alert-success' role='alert'>$msgx <br>$msgxx</div>";
	}
}

?>
<div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        <?=$msg?>
                                        <div class="card">
                                            <div class="card-header">
                                            <h5>UPLOAD HASIL INJECT</h5>
                                            <small>temp_adjustment_inject</small>

                                        </div>
                                        <div class="card-block">
                                            <form id="main" method="post" enctype="multipart/form-data">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Ambil Data</label>
                                                    <div class="col-sm-10">
                                                         <input type="file" name="file" class="form-control"></input>
                                                        <span class="messages"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2"></label>
                                                    <div class="col-sm-10">
                                                    	<input type="submit" name="submit" class="btn btn-sm btn-primary m-b-0" 
                                                    	value="UPLOAD"></input>                                                       
                                                    </div>
                                                </div>
                                            </form>
                                            
                                            <form method="POST">
                                              <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Read Data XLS</label>
                                                    <div class="col-sm-10">
                                                         <input type="text" name="filesname" class="form-control" 
                                                         value='<?=$filesname?>'>
                                                        <span class="messages"></span>
                                                    </div>
                                               </div>
                                               
                                               <div class="form-group row">
                                                    <label class="col-sm-2"></label>
                                                    <div class="col-sm-10">
                                                    	<input type="submit" name= "submit" class="btn btn-sm btn-warning" 
                                                    	value="READ DATA"></input>                                                      
                                                    </div>
                                                </div>
											  
										      
										  </form>
  
                                        </div>
                                        </div>
                                        
                                        <div class="card">
                                            <div class="card-header">
                                            <h5>Sample Data EXCEL Extensi .XLS (2003)</h5>
                                            <p>Pastikan Format Tanggal Expired seperti gambar</p>
                                            <div class="card-header-right">
                                                <a href="excelinject/sample_inject.xls" class="btn btn-sm btn-primary"> DOWNLOAD SAMPLE EXCEL</a>
                                                </div>
                                        </div>
                                        <div class="card-block">
                                           <img src="assets/images/sample_upload_inject.png" />
                                        </div>
                                        </div>
										
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

<?php
include("footer.inc.php");
?>
