// JavaScript Document
function ajaxApp(divDestino,url,parametros,metodo){	
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		$("#cargadorGeneral").show(); 
	},
	success:function(datos){ 
		$("#cargadorGeneral").hide();
		$("#"+divDestino).show().html(datos);		
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}
function nuevaReemplazo(){
	ajaxApp("detalleEmpaque","controladorEnsamble.php","action=mostrarReemplazo","POST");
}
function mostrarResumenImei(evento){
	if(evento.which==13){
		var imei=$("#txtImeiBusquedaBounce").val();
		if(imei=="" || imei==null || imei.length<15){
			alert("Error: Verifique la informacion Introducida");
		}else{
			ajaxApp("detalleResumenImei","controladorEnsamble.php","action=mostrarResumen&imei="+imei,"POST");
		}
	}
}
function cambiarEvento(evento,idSerial){
	if(evento.which==13){
		if(idSerial=="txtSerialBounce"){
			$("#txtSerialBounce").focus();	
		}else if(idSerial=="btnGuardarReemplazo"){
			$("#btnGuardarReemplazo").focus();
		}
		
	}
}
function guardarReemplazo(){
	var tipoCambio=$("#cboTipoCambio").val();
	var imeiProceso=$("#txtImeiBusquedaBounce").val();
	var imeiBounce=$("#txtImeiBounce").val();
	var serialBounce=$("#txtSerialBounce").val();
	if(tipoCambio=="" || imeiProceso=="" || imeiBounce=="" || imeiProceso==null || imeiBounce==null || imeiProceso.length<15 || imeiBounce.length<15 || serialBounce=="" || serialBounce==null || serialBounce.length<10 || serialBounce>10){
		alert("Verifique la informacion del IMEI o el Numero de Serie");
	}else{
		ajaxApp("divDetalleGuardado","controladorEnsamble.php","action=guardarDetalle&imeiProceso="+imeiProceso+"&imeiBounce="+imeiBounce+"&serialBounce="+serialBounce+"&tipoCambio="+tipoCambio,"POST");
	}
}