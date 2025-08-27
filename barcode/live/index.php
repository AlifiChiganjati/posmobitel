<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>index</title>
    <meta name="description" content="" />
    <meta name="author" content="Christoph Oberhofer" />

    <meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
  </head>

  <body>
      <header>

      </header>
    <section id="container" class="container">
        <div class="controls">
            <fieldset class="input-group">
                <button class="stop">Stop</button>
            </fieldset>
            <div style="display: none;">
            <fieldset class="reader-config-group">
                <label>
                    <span>Barcode-Type</span>
                    <select name="decoder_readers">
                        <option value="code_128" selected="selected">Code 128</option>
                        <option value="code_39">Code 39</option>
                        <option value="code_39_vin">Code 39 VIN</option>
                        <option value="ean">EAN</option>
                        <option value="ean_extended">EAN-extended</option>
                        <option value="ean_8">EAN-8</option>
                        <option value="upc">UPC</option>
                        <option value="upc_e">UPC-E</option>
                        <option value="codabar">Codabar</option>
                        <option value="i2of5">Interleaved 2 of 5</option>
                        <option value="2of5">Standard 2 of 5</option>
                        <option value="code_93">Code 93</option>
                    </select>
                </label>
                <label>
                    <span>Resolution</span>
                    <select name="input-stream_constraints">
                        <option value="320x240">320px</option>
                        <option selected="selected" value="640x480">640px</option>
                        <option value="800x600">800px</option>
                        <option value="1280x720">1280px</option>
                        <option value="1600x960">1600px</option>
                        <option value="1920x1080">1920px</option>
                    </select>
                </label>
                <label>
                    <span>Patch</span>
                    <select name="locator_patch-size">
                        <option value="x-small">x-small</option>
                        <option value="small">small</option>
                        <option selected="selected" value="medium">medium</option>
                        <option value="large">large</option>
                        <option value="x-large">x-large</option>
                    </select>
                </label>
                <label>
                    <span>Half-Sample</span>
                    <input type="checkbox" checked="checked" name="locator_half-sample" />
                </label>
                <label>
                    <span>Workers</span>
                    <select name="numOfWorkers">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option selected="selected" value="4">4</option>
                        <option value="8">8</option>
                    </select>
                </label>
                <label>
                    <span>Camera</span>
                    <select name="input-stream_constraints" id="deviceSelection">
                    </select>
                </label>
                <label style="display: none">
                    <span>Zoom</span>
                    <select name="settings_zoom"></select>
                </label>
                <label style="display: none">
                    <span>Torch</span>
                    <input type="checkbox" name="settings_torch" />
                </label>
            </fieldset>
          </div>
        </div>
      <div id="result_strip">
        <ul class="thumbnails"></ul>
        <ul class="collector"></ul>

        <?php
        $idcustomer = isset($_GET['idcustomer'])? $_GET['idcustomer'] : "";
        $idoutlet = isset($_GET['idoutlet'])? $_GET['idoutlet'] : "";
        $namacustomer = isset($_GET['namacustomer'])? $_GET['namacustomer'] : "";
        ?>
        <form id="myForm" method="post" action="../../vfisik_imei.php">
             <input type='hidden' id="idcustomer" name='idcustomer' value="<?=$idcustomer?>">
             <input type='hidden' id="idoutlet" name='idoutlet' value="<?=$idoutlet?>">
             <input type='hidden' id="namacustomer" name='namacustomer' value="<?=$namacustomer?>">
             <input type='hidden' id="qrresuxbar" name='qrresuxbar' class='form-control' autocomplete="Off">
             <input type="submit" id="submit2" name="submit" value="SUBMIT" style="display: none;">
        </form>

      </div>

      <div id="interactive" class="viewport" style="width: 100%;"></div>
      <a href="../../vfisik_imei.php"><img src="back.png"></a>
    </section>


    <script src="vendor/jquery-1.9.0.min.js" type="text/javascript"></script>
    <script src="//webrtc.github.io/adapter/adapter-latest.js" type="text/javascript"></script>
    <script src="../dist/quagga.js" type="text/javascript"></script>
    <script src="live_w_locator.js" type="text/javascript"></script>
  </body>
</html>
