<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
	      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">
        <title>Scanner</title>
    </head>
     <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/style.css">
      <body>
        <div class="page-content-wrapper">
         <div class="container">
            <canvas></canvas>
            <select class='form-control'></select>
            <hr>
            <ul></ul>
            <?php
            $idcustomer = isset($_GET['idcustomer'])? $_GET['idcustomer'] : "";
            $idoutlet = isset($_GET['idoutlet'])? $_GET['idoutlet'] : "";
            $namacustomer = isset($_GET['namacustomer'])? $_GET['namacustomer'] : "";
            ?>

            <form id="myForm" method="post" action="../vfisik_imei.php" style="display: none;">
                 <input type='hidden' id="idcustomer" name='idcustomer' value="<?=$idcustomer?>">
                 <input type='hidden' id="idoutlet" name='idoutlet' value="<?=$idoutlet?>">
                 <input type='hidden' id="namacustomer" name='namacustomer' value="<?=$namacustomer?>">

    			       <input type='text' id="qrresux" name='qrresux' class='form-control' autocomplete="Off">
                 <input type="submit" id="submit2" name="submit" value="SUBMIT" class="btn btn-inverse w-100 text-white">
            </form>
            <!-- <iframe style="display:none" name="hidden-form"></iframe> -->
            <a href="../vfisik_imei.php"><img src="back.png"></a>
          </div>
        </div>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/qrcodelib.js"></script>
        <script type="text/javascript" src="js/webcodecamjquery.js"></script>
        <script type="text/javascript">
            var arg = {
                resultFunction: function(result) {
                    $('body').append($('<li>' + result.format + ': ' + result.code + '</li>'));
                    document.getElementById("qrresux").value= result.code;
					          document.getElementById("submit2").click();
                }
            };
            var decoder = $("canvas").WebCodeCamJQuery(arg).data().plugin_WebCodeCamJQuery;
            decoder.buildSelectMenu("select");
            /* decoder.play(); */
            /*  Without visible select menu
                decoder.buildSelectMenu(document.createElement('select'), 'environment|back').init(arg).play();
            */
            setTimeout(function(){

    					var $sel = $('select');
    					var $opts = $sel.children('option');
    					if ($opts.length > 1) {

    						var i = $opts.length - 1;
    						$sel.prop('selectedIndex', i);
    						decoder.play();
    					}else{

    					decoder.play();
    					}
    				}, 1000);

            $('select').on('change', function(){
                decoder.stop().play();
            });
        </script>
    </body>
</html>
