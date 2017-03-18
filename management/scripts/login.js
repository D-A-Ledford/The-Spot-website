var $ = function (id) {
    return document.getElementById(id);
}

function addpwd(key){
	var pwd = document.forms[0].pwd;
	if(pwd.value.length < 4){
		pwd.value = pwd.value + key;
	}
	if(pwd.value.length == 4){
		document.getElementById("message").style.display = "block";
		setTimeout(submitForm,1000);	
	}
}

function submitForm(){
	document.forms[0].submit();
}

function emptypwd(){
	document.forms[0].pwd.value = "";
}