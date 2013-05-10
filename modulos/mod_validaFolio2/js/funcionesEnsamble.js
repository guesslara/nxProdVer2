// JavaScript Document
var i=0;
function ajaxApp(divDestino,url,parametros,metodo){	
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		$("#"+divDestino).show().html("<i>Cargando informacion</i>"); 
	},
	success:function(datos){ 
		//$("#cargando").hide();
		$("#"+divDestino).show().html(datos);		
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}
function verificaTeclaImeiEnsamble(evento){
	if(evento.which==13){
		$("#datosFormularioEnsamble").html("");
		registrarDatos();
	}
}
function mostrarFolioSeleccionado(){
	folioCliente=$("#cboCliente").val();
	folioIq=$("#cboIq").val();
	folioCliente=folioCliente.toString();
	folioIq=folioIq.toString();
	//se comparan los folios
	if(folioCliente == folioIq){
		alert("Se van a mostrar los datos de los Folios seleccionados esta operacion puede durar unos minutos");
		ajaxApp("detalleValidaciones","controladorEnsamble.php","action=mostrarDatosfolio&folioCliente="+folioCliente+"&folioIq="+folioIq,"POST");
	}else{
		alert("Debe elegir el mismo folio para poder validar la CAPTURA");
	}
}
function validarInfo(){
	alert("El proceso de validacion puede durar varios minutos.");
	limiteDatos=$("#txtTotalIndiceJ").val();
	for(i=0;i<limiteDatos;i++){
		//se arman los nombres de las cajas para el imei
		imeiC="txtCI_"+i;
		imeiI="txtII_"+i;
		resuI="txtRI_"+i;
		valorImeiC=$("#"+imeiC).val();
		valorImeiI=$("#"+imeiI).val();
		valorImeiC=valorImeiC.toString();
		valorImeiI=valorImeiI.toString();
		if(valorImeiC==valorImeiI){
			$("#"+resuI).attr("value","VERDADERO");//66FF66
			$("#"+resuI).css("background-color","#66FF66");
		}else{
			$("#"+resuI).attr("value","FALSO");
			$("#"+resuI).css("background-color","red");//$(this).css("background-color");
			$("#"+resuI).css("color","white");
		}
		//se arman los nombres para el serial
		serialC="txtCS_"+i;
		serialI="txtIS_"+i;
		resultS="txtRS_"+i;
		valorSerialC=$("#"+serialC).val();
		valorSerialI=$("#"+serialI).val();
		valorSerialC=valorSerialC.toString();
		valorSerialI=valorSerialI.toString();
		if(valorSerialC==valorSerialI){
			$("#"+resultS).attr("value","VERDADERO");
			$("#"+resultS).css("background-color","#66FF66");
		}else{
			$("#"+resultS).attr("value","FALSO");
			$("#"+resultS).css("background-color","red");
			$("#"+resultS).css("color","white");
		}
	}
}
function mostrarResumen(){
	$("#detalleValidaciones").hide();
	$("#ventanaEnsambleContenido2").show();
	datosFolio=$("#cboCliente").val();
	ajaxApp("ventanaEnsambleContenido2","controladorEnsamble.php","action=mostrarResumen&datosFolio="+datosFolio,"POST");
}
