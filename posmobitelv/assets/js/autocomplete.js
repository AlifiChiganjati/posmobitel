var ajaxRequest;
function getAjax(){
	try{
		ajaxRequest = new XMLHttpRequest();
	}catch (e){
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		}catch (e){
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			}catch (e){
				alert("Your browser broke!");
				return false;}
		}
	}
}


function autoCompletesales(field){
	getAjax();
	input = document.getElementById('idsalesman').value;
	if (input == ""){
		document.getElementById("hasilsalesman").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata.php?tipe=salesman&term="+input);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilsalesman").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}

function autoInsertsales(nama){
	var arr = nama;
	data = arr.split("#");
	document.getElementById("idsalesman").value = data[0];
	document.getElementById("salesname").value = data[1];
	document.getElementById("gudang").value = data[2];
	document.getElementById("hasilsalesman").innerHTML = "";
}

function autoCompletecustomer(field){
	getAjax();
	input = document.getElementById('idcustomer').value;
	input2 = document.getElementById('merk').value;
	if (input == ""){
		document.getElementById("hasilcustomer").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata.php?tipe=customer&term="+input+"&term2="+input2);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilcustomer").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}

function autoCompletecustomer_cesa(field){
	getAjax();
	input = document.getElementById('idcustomer').value;
	if (input == ""){
		document.getElementById("hasilcustomer").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata_cesa.php?tipe=customer&term="+input);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilcustomer").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}

function autoCompletecustomer_cesa_eload(field){
	getAjax();
	input = document.getElementById('idcustomer').value;
	if (input == ""){
		document.getElementById("hasilcustomer").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata_cesa.php?tipe=customereload&term="+input);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilcustomer").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}

function autoInsertcustomer_cesa(nama){
	var arr = nama;
	data = arr.split("#");
	document.getElementById("idcustomer").value = data[0];
	document.getElementById("namacustomer").value = data[1];
	document.getElementById("noeload").value = data[2];
	document.getElementById("hasilcustomer").innerHTML = "";

}

function autoInsertcustomer_cesa_fisik(nama){
	var arr = nama;
	data = arr.split("#");
	document.getElementById("idcustomer").value = data[0];
	document.getElementById("namacustomer").value = data[1];
	document.getElementById("hasilcustomer").innerHTML = "";

}

function autoInsertcustomer(nama){
	var arr = nama;
	data = arr.split("#");
	document.getElementById("idcustomer").value = data[0];
	document.getElementById("namacustomer").value = data[1];
	document.getElementById("hasilcustomer").innerHTML = "";

}

function autoCompletecustomer_imei(field){
	getAjax();
	input = document.getElementById('idcustomer').value;
	
	if (input == ""){
		document.getElementById("hasilcustomer").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata_imei.php?tipe=customer&term="+input);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilcustomer").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}

function autoInsertcustomer_imei(nama){
	var arr = nama;
	data = arr.split("#");
	document.getElementById("idcustomer").value = data[0];
	document.getElementById("namacustomer").value = data[1];
	document.getElementById("hasilcustomer").innerHTML = "";
}

function autoCompleteproduct2(field){
	getAjax();
	input = document.getElementById('produk').value;
	console.log(input);
	if (input == ""){
		document.getElementById("hasilproduk").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata_produk.php?tipe=produk2&term="+input);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilproduk").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}

function autoInsertproduk(nama){
	var arr = nama;
	data = arr.split(";");
	document.getElementById("idproduk").value = data[0];
	document.getElementById("produk").value = data[1];
	document.getElementById("hasilproduk").innerHTML = "";
}

function toSearchbox(nama){
	document.getElementById("searchx").value = nama;
	document.getElementById("hasilproduk").innerHTML = "";
}

function autocekProduk(){
	getAjax();
	input = document.getElementById('searchx').value;
	input2 = document.getElementById('catx').value;
	console.log(input);
	if (input == ""){
		document.getElementById("hasilproduk").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata_produk.php?tipe=produk_search&term="+input+"&cat="+input2);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilproduk").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}

function cekFakturRefund(){
	getAjax();
	input = document.getElementById('no_faktur').value;
	console.log(input);
	if (input == ""){
		document.getElementById("hasilproduk").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata_refund.php?tipe=cek_faktur&term="+input);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilproduk").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}

function cekTokoRefund(){
	getAjax();
	input = document.getElementById('nama_toko').value;
	console.log(input);
	if (input == ""){
		document.getElementById("hasiltoko").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata_refund.php?tipe=cek_toko&term="+input);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasiltoko").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}
function ceknegara(id){
	getAjax();
	input = document.getElementById(id).value;
	if (input == ""){
		document.getElementById("hasilnegara").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata_negara.php?tipe=cek_negara&term="+input+"&id="+id);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilnegara").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}
function autoInsertnfrefund(nama){
	var arr = nama;
	document.getElementById("no_faktur").value = arr;
	document.getElementById("hasilproduk").innerHTML = "";
}

function autoInsertrefundTOko(nama){
	var arr = nama;
	document.getElementById("nama_toko").value = arr;
	document.getElementById("hasiltoko").innerHTML = "";
}

function autoInsertnegara(nama,id){
	var arr = nama;
	document.getElementById(id).value = arr;
	document.getElementById("hasilnegara").innerHTML = "";
}