var $ = function (id) {
    return document.getElementById(id);
}

function addpwd(key){
	var pwd = document.getElementById("loginForm").pwd;
	if(pwd.value.length < 4){
		return pwd = pwd + key;
	}
	if(pwd.value.length == 4){
		document.getElementById("message").style.display = "block";
		setTimeout(submitForm,1000);	
	}
}

function submitForm(){
	document.getElementById("loginForm").submit();
}

function emptypwd(){
	document.getElementById("loginForm").pwd.value = "";
}