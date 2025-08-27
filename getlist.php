<?php
include("connection.inc.php");
include("function.inc.php");

function processDrpdown($selectedVal) {
    //echo "Selected value in php ".$selectedVal;
	if($selectedVal <> ""){
		$ro = "select * from lokasi where kecamatan = '$selectedVal'";
		$rs = mysql_query($ro);
		$re = mysql_num_rows($rs);
			if($re > 0){

				$i=1;
				if($rso = mysql_fetch_object($rs)){
					echo "<div class='form-group row'>
								<label class='col-sm-12 col-form-label'>Kabupaten</label>
									<div class='col-sm-12'>
												 <input  class='form-control' id='kabupaten' type='text' name='kabupaten' value='$rso->kabupaten' size=15 readonly>
									</div>
								</div> <br />";

				$i++;
				}

			}

	}else{
		echo "";
	}
}

if ($_POST['dropdownValue']){
    processDrpdown(cleanall($_POST['dropdownValue']));
}

?>
