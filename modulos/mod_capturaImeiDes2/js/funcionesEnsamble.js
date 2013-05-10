// JavaScript Document
var contadorGrid=0;
function ajaxApp(divDestino,url,parametros,metodo){	
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		$("#cargando").show(); 
	},
	success:function(datos){ 
		$("#cargando").hide();
		$("#"+divDestino).show().html(datos);		
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}
function contarEquiposDes(){
	ajaxApp("infoEquiposDes","controladorEnsamble.php","action=contarDes","POST");
}
function iniciarCaptura(){
	$("#ventanaEnsambleContenido").html("");
	resetDatosScriptGrid();
	proceso=document.getElementById("txtProcesoAsignaDesensamble").value;
	usrAsignaDesensamble=document.getElementById("txtIdUsuarioAsignaDesensamble").value;
		
	if(proceso=="" || usrAsignaDesensamble==""){
		alert("Error:\n\n Puede deberse a las siguientes causas:\n\n Sesion no Valida, ingrese de nueva cuenta al Sistema.");
	}else{
		/*se configura el grid*/
		nombresColumnas=new Array("Imei","Mensaje");
		cargaInicial(2,"ventanaEnsambleContenido","controladorEnsamble.php","action=actualizaDatosAsignaDesensamble&usrAsignaDesensamble="+usrAsignaDesensamble+"&proceso="+proceso,"erroresCaptura",nombresColumnas);
		inicio();
		$("#txt_0").focus();
		$("#txt_0").removeClass("datoListado");
		$("#txt_0").addClass("elementoFocus");
	}
}
function contarEquiposAsigDes(){
	ajaxApp("infoEquiposAsigDes","controladorEnsamble.php","action=contarAsigDes","POST");
}