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
function verificaTeclaImeiEnsamble(evento){
	if(evento.which==13){
		$("#datosFormularioEnsamble").html("");
		registrarDatos();
	}
}
function registrarDatos(){
	var imeiEnsamble=document.getElementById("txtImeiEnsamble").value;
	if(imeiEnsamble=="" || imeiEnsamble==null){
		alert("Introduzca un numero de Imei valido.");
	}else{
		//ajaxApp("div_grid_ensamble","controladorEnsamble.php","action=actualizaStatusEquipo&imeiEnsamble="+imeiEnsamble,"GET"); 
		contadorGrid+=1;
		$("#div_grid_ensamble").append("<div><input type='checkbox' checked='checked' name='cboImeiCapturado' id='cboImeiCapturado' value='"+imeiEnsamble+"' /><input type='text' name='' id='' value='"+imeiEnsamble+"' readonly='readonly' /></div>");
		$("#agregado").html("Equipos en el listado: "+contadorGrid);
		//ajaxApp("div_grid_ensamble","controladorEnsamble.php","action=actualizaEquipoEnsamble&imei="+imeiEnsamble,"POST");
	}
	$("#txtImeiEnsamble").attr("value","");
}
function limpiaCaja(){
	document.getElementById("txtImeiEnsamble").value="";
	document.getElementById("txtImeiEnsamble").focus();
}
function procesaFormulario(){	
	var equipos="";

	for (var i=0;i<document.frmEquiposEnsamble.elements.length;i++){
		if (document.frmEquiposEnsamble.elements[i].type=="checkbox"){
			if (document.frmEquiposEnsamble.elements[i].checked){
				//alert("Variable claves=["+claves+"]");
				if (equipos=="")
					equipos=equipos+document.frmEquiposEnsamble.elements[i].value;
				else
					equipos=equipos+","+document.frmEquiposEnsamble.elements[i].value;
			}	
		}
	}
	//alert(cboMoverReqs);
	//alert(equipos);
	if(equipos==""){
		alert('Seleccione por lo menos 1 equipo para poder continuar con la operacion.');
	}else{
		proceso=document.getElementById("txtProcesoEnsamble").value;
		id_usuarioEnsamble=document.getElementById("txtIdUsuarioEnsamble").value;
		proceso1=document.getElementById("txtProcesoAsig").value;
		div="div_grid_ensamble";
		url="controladorEnsamble.php";
		parametros="action=actualizaDatosAlmacenDiagnostico&equipos="+equipos+"&proceso="+proceso+"&id_usuarioEnsamble="+id_usuarioEnsamble+"&txtProcesoAsig="+proceso1;
		//alert(parametros);
		metodo="POST";
		ajaxApp(div,url,parametros,metodo);
	}
}
function habilitaElementos(){
	var opcion=$("#cboClasificacion").attr("value");
	alert(opcion)
	if(opcion != ""){
		$("#txtImeiEnsamble").removeAttr("disabled");
		$("#btnRegistrarImei").removeAttr("disabled");
		document.getElementById("txtImeiEnsamble").focus();
	}else{
		$("#txtImeiEnsamble").attr("disabled","disabled");
		$("#btnRegistrarImei").attr("disabled","disabled");
	}	
}
function cancelarCaptura(){
	//de aki sirve para llamar a la funcion para hacer un reset a los forms
	if(confirm("Realmente desea CANCELAR la captura actual?")){
		resetForm();
	}
}
function resetForm(){
	//se regresa el contador a 0
	contadorGrid=0;
	//se vacia el grid
	//$("#div_grid_ensamble").html("");	
	//se coloca el focus en el campo imei
	document.getElementById("txtImeiEnsamble").focus();
	$("#agregado").html("Equipos en el listado: "+contadorGrid);
}
/*
var cboMoverReqs=document.getElementById("cboMoverReqs").value;
	var reqsGrid="";

	for (var i=0;i<document.frmReqsGrid.elements.length;i++){
		if (document.frmReqsGrid.elements[i].type=="checkbox"){
			if (document.frmReqsGrid.elements[i].checked){
				//alert("Variable claves=["+claves+"]");
				if (reqsGrid=="")
					reqsGrid=reqsGrid+document.frmReqsGrid.elements[i].value;
				else
					reqsGrid=reqsGrid+","+document.frmReqsGrid.elements[i].value;
			}	
		}
	}
	//alert(cboMoverReqs);
	//alert(reqsGrid);
	if((reqsGrid=="") || (cboMoverReqs=="")){
		alert('Seleccione por lo menos 1 Requisicion para efectuar la Operacion');
	}else{		
		div="detalleReqs";
		url="controlador.php";
		parametros="action=moverReqs&reqsGrid="+reqsGrid+"&directorio="+cboMoverReqs;
		//alert(parametros);
		metodo="GET";
		ajaxApp(div,url,parametros,metodo);
	}
*/