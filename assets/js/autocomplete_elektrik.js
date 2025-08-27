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
		ajaxRequest.open("GET","getdata_elektrik.php?tipe=salesman&term="+input);
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
	if (input == ""){
		document.getElementById("hasilcustomer").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata_elektrik.php?tipe=customer&term="+input);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilcustomer").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}

function autoInsertcustomer(nama){
	var arr = nama;
	data = arr.split("#");
	document.getElementById("idcustomer").value = data[0];
	document.getElementById("namacustomer").value = data[1];
	//document.getElementById("saldo").focus();
	document.getElementById("hasilcustomer").innerHTML = "";

}

function autoCompletecustomer_imei(field){
	getAjax();
	input = document.getElementById('idcustomer').value;
	if (input == ""){
		document.getElementById("hasilcustomer").innerHTML = "";
	}else{
		ajaxRequest.open("GET","getdata_elektrik_imei.php?tipe=customer&term="+input);
		ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasilcustomer").innerHTML = ajaxRequest.responseText;}
		ajaxRequest.send(null);
	}
}

function autoInsertcustomer_imei(nama){
	var arr = nama;
	data = arr.split("#");
	document.getElementById("idcustomer").value = data[0];
	document.getElementById("namacustomer").value = data[1];
	document.getElementById("idoutlet").value = data[2];
	document.getElementById("hasilcustomer").innerHTML = "";
}

function autoCompletehlr(field){
	getAjax();
	input = document.getElementById('nomorhpc').value;
	let length = input.length;
	let prefix = input.substring(0, 4);
	if(length >= 4)
	{
		console.log(input);
		if (input == ""){
			document.getElementById("hasildenom").innerHTML = "";
		}else{
			ajaxRequest.open("GET","getdata_elektrik.php?tipe=hlr&term="+prefix);
			ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasildenom").innerHTML = ajaxRequest.responseText;}
			ajaxRequest.send(null);
		}
	}else if(length < 4){
		document.getElementById("hasildenom").innerHTML = "";
	}
}

function autoCompleteplntoken(field){
	getAjax();
	input = document.getElementById('nomorhpc').value;
	
	let length = input.length;
	if(length >= 8)
	{
		if (input == ""){
			document.getElementById("hasildenom").innerHTML = "";
		}else{
			ajaxRequest.open("GET","getdata_elektrik.php?tipe=plntoken&term="+input);
			ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasildenom").innerHTML = ajaxRequest.responseText;}
			ajaxRequest.send(null);
		}
	}else if(length < 4){
		document.getElementById("hasildenom").innerHTML = "";
	}
}

function autoCompleteplnpasca(field){
	getAjax();
	input = document.getElementById('nomorhpc').value;
	input2 = document.getElementById('layanan1').value;
	let length = input.length;
	if(length >= 4)
	{
		if (input == ""){
			document.getElementById("hasildenom").innerHTML = "";
		}else{
			ajaxRequest.open("GET","getdata_elektrik.php?tipe=plnpasca&term="+input+"&term2="+input2);
			ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasildenom").innerHTML = ajaxRequest.responseText;}
			ajaxRequest.send(null);
		}
	}else if(length < 4){
		document.getElementById("hasildenom").innerHTML = "";
	}
}

function autoCompletepaketdata(field){
	getAjax();
	input = document.getElementById('nomorhpc').value;
	let length = input.length;
	let prefix = input.substring(0, 4);
	if(length >= 4)
	{
		if (input == ""){
			document.getElementById("hasildenom").innerHTML = "";
		}else{
			ajaxRequest.open("GET","getdata_elektrik.php?tipe=paketdata&term="+prefix);
			ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasildenom").innerHTML = ajaxRequest.responseText;}
			ajaxRequest.send(null);
		}
	}else if(length < 4){
		document.getElementById("hasildenom").innerHTML = "";
	}
}

function myFunction() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName("label");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("small")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

function autoCompletemoney(field){
	getAjax();
	input = document.getElementById('nomorhpc').value;
	operator = document.getElementById('layanan1').value;
	let length = input.length;
	if(length >= 4)
	{
		console.log(operator);
		if (input == ""){
			document.getElementById("hasildenom").innerHTML = "";
		}else{
			ajaxRequest.open("GET","getdata_elektrik.php?tipe=emoney&term="+operator);
			ajaxRequest.onreadystatechange = function()	{ document.getElementById("hasildenom").innerHTML = ajaxRequest.responseText;}
			ajaxRequest.send(null);
		}
	}else if(length < 4){
		document.getElementById("hasildenom").innerHTML = "";
	}
}
