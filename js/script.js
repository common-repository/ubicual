// var $j = jQuery;
jQuery.noConflict();
function sb(){

	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function validatePhone(phone) {
		var re = /^\d+$/;
		return re.test(phone);
	}

	var formInputs = document.getElementById("ubicualform").querySelectorAll("input");
	var httpRequest = new XMLHttpRequest();
	var formData = new FormData();
	if(document.getElementById("terms").checked==false){
		alert("Debe Aceptar los Términos y Condiciones para enviar sus datos");
		return false;
	}
	for( var i=0; i < formInputs.length; i++ ){

		if((formInputs[i].required==true) && (formInputs[i].value=="") && (formInputs[i].type!="hidden")){
			alert("Por favor, rellene todos los campos marcados como obligatorios");
			return false;
		}
		if(formInputs[i].name=='phone'){
			if(validatePhone(document.getElementById("phone").value.replace(/ /g,'')) || document.getElementById("phone").value.replace(/ /g,'') == ""){
					formData.append(formInputs[i].name, formInputs[i].value);
				}
				else{
					alert("Introduzca un teléfono válido. Gracias.");
				return false;
			}
		}
		else if(formInputs[i].name=='email'){
			if(validateEmail(document.getElementById("email").value)){
					formData.append(formInputs[i].name, formInputs[i].value);
				}
			else {
				alert("Introduzca un email válido. Gracias.");
			return false;
			}

		}
		else{
			formData.append(formInputs[i].name, formInputs[i].value);
		}



	}

	httpRequest.onreadystatechange = function(){
		if ( this.readyState == 4 && this.status == 200 ) {
			var response = JSON.parse(httpRequest.responseText);
			if(response.status==true){
				alert("Datos enviados correctamente");
				// localStorage.setItem("submited", true);
				// location.reload();
			}
			else{
				if(response.message!==undefined){
					alert(response.message);
				}else{
					alert("Se ha producido un error. Por favor, inténtelo de nuevo");
				}
			}
		}
	};
	httpRequest.open("post", "https://subscribers.ubicual.com/api/addcontact");

	httpRequest.send(formData);

	return false;
}
