<html>
  <head>
    <meta charset="UTF-8">
	      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">
        <title>Scanner</title>
<style>
#reader {
    width: 100%;
    top: -200px;
}
/*
@media(max-width: 600px) {
	#reader {
		width: 300px;
	}
}
*/
.empty {
    display: block;
    width: 100%;
    height: 20px;
}
</style>
<script>
window.onload=function(){
	var form = document.getElementById("start");
		form.click();
}

</script>
	</script>

  <body style="text-align: center;margin-bottom: 20px;">
    <div id="reader" style="display: inline-block;"></div>
    <div id="scanned-result"></div>
    <div>
        <select id="facingMode">
            <option value="user">user</option>
            <option value="environment" selected>environment</option>
        </select>
        <button id="start">Start Scanning</button>

        <?php
        $idcustomer = isset($_GET['idcustomer'])? $_GET['idcustomer'] : "";
        $idoutlet = isset($_GET['idoutlet'])? $_GET['idoutlet'] : "";
        $namacustomer = isset($_GET['namacustomer'])? $_GET['namacustomer'] : "";
        ?>
        <form id="myForm" method="post" action="../vfisik_imei.php">
             <input type='hidden' id="idcustomer" name='idcustomer' value="<?=$idcustomer?>">
             <input type='hidden' id="idoutlet" name='idoutlet' value="<?=$idoutlet?>">
             <input type='hidden' id="namacustomer" name='namacustomer' value="<?=$namacustomer?>">
             <input type='hidden' id="qrresuxbar" name='qrresuxbar' class='form-control' autocomplete="Off">
             <input type="submit" id="submit2" name="submit" value="SUBMIT" style="display: none;">
        </form>
   </div>

  </body>
  <script src="html5-qrcode/html5-qrcode.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.3/highlight.min.js"></script>
  <script src="html5-qrcode-demo.js"></script>

  </head>
</html>
