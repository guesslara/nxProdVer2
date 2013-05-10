// JavaScript Document
var contadorGrid=0;
var contadorTxt=0;
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
		//registrarDatos();
		//se valida la longitud de la cadena capturada
		var imei=document.getElementById("txtImeiEnsamble").value;
		if(imei.length < 15){
			$("#erroresCaptura").html("");
			$("#erroresCaptura").append("Error: verifique que haya introducido en el Imei la informacion correcta.");
			
		}else{
			document.getElementById("txtSerialEmpaque").focus();
		}
		
	}
}
function verificaTeclaSerialEnsamble(evento){
	if(evento.which==13){		
		//alert('guarda Datos');
		var imei=document.getElementById("txtImeiEnsamble").value;
		var sim=document.getElementById("txtSerialEmpaque").value;
		var id_empaque=document.getElementById("idEmpaqueCaptura").value;
		var id_caja=document.getElementById("txtIdCaja").value;
		//se envian a la base de datos
		ajaxApp("erroresCaptura","controladorEnsamble.php","action=guardaItemsEmpaque&imei="+imei+"&sim="+sim+"&id_caja="+id_caja+"&id_empaque="+id_empaque,"POST");
	}
}
function armarGridCaptura(imei,sim){
	contadorTxt+=1;
	//$("#div_grid").append("<br><input type='text' size='2' value='"+contadorTxt+"' /><input type='text' id='"+idCode+"' value='"+bdCode+"' size='60' /><input type='text' id='"+idImei+"' value='"+imei+"' /><input type='text' id='"+idSerial+"' value='"+serial+"' />");
	$("#capturados").html("");
	$("#capturados").html("<p style='font-size:14px;'>Equipos: "+contadorTxt+"</p>");
	$("#div_grid_ensamble").append("<div style='float:left;width:10px;background:#CCC;border:1px solid #CCC;height:15px;padding:4px;'>&nbsp;</div><div style='float:left;width:200px; height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+imei+"</div><div style='float:left;width:200px;height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+sim+"</div><div style='clear:both;'></div>");	
	limpiaCajas();
}
function limpiaCajas(){
	document.getElementById("txtImeiEnsamble").value="";
	document.getElementById("txtSerialEmpaque").value=""
	document.getElementById("txtImeiEnsamble").focus();
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
	}
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
		div="div_grid_ensamble";
		url="controladorEnsamble.php";
		parametros="action=actualizaDatos&equipos="+equipos+"&proceso="+proceso+"&id_usuarioEnsamble="+id_usuarioEnsamble;
		alert(parametros);
		metodo="POST";
		ajaxApp(div,url,parametros,metodo);
	}
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
function guardaMovimiento(){
	//se recuperan lso datos
	var txtFecha=document.getElementById("txtFecha").value;
	var txtTecnico=document.getElementById("txtIdUsuarioEnsamble").value;
	var txtEntrega=document.getElementById("txtEntrega").value;
	//var txtCaja=document.getElementById("txtCaja").value;
	var cboModelo=document.getElementById("cboModelo").value;
	
	if(cboModelo=="" || txtFecha=="" || txtTecnico=="" || txtEntrega==""){
		alert("Error debe llenar todos los datos");
	}else{
		//se envia el formulario al controlador
		$("#datosIniciales").hide();
		//$("#capturaCaja").show();
		div="listadoEmpaque";
		//alert("action=guardarEmpaque&fecha="+txtFecha+"&txtTecnico="+txtTecnico+"&txtEntrega="+txtEntrega+"txtCaja="+txtCaja+"&modelo="+cboModelo);
		ajaxApp(div,"controladorEnsamble.php","action=guardarEmpaque&fecha="+txtFecha+"&txtTecnico="+txtTecnico+"&txtEntrega="+txtEntrega+"&modelo="+cboModelo,"POST");
		
	}
}
function asignarDatosForm(fecha,txtTecnico,txtEntrega,txtCaja,modelo,idEmpaque){
	//alert("funcion");
	$("#cajaInfoCaptura").html("");
	$("#cajaInfoCaptura").html("<p>"+txtCaja+"</p>");	
	$("#fechaEmpaque").html("");
	$("#fechaEmpaque").html("<p>Fecha: "+fecha+"</p>");
	$("#modeloEmpaque").html("");
	$("#modeloEmpaque").html("<p>Modelo: "+modelo+"</p>");
	document.getElementById("txtIdCaja").value=txtCaja;
	document.getElementById("idEmpaqueCaptura").value=idEmpaque;
	document.getElementById("txtImeiEnsamble").focus();
}
function listarCapturas(){
	$("#datosIniciales").hide();
	$("#listadoPanel").show();
	ajaxApp("listadoEmpaque","controladorEnsamble.php","action=listarCapturas","POST");
}
function verMas(idEmpaque){
	ajaxApp("detalleEmpaque","controladorEnsamble.php","action=verDetalleEmpaque&idEmpaque="+idEmpaque,"POST");
}
function nuevaCaja(){
	var caja=prompt("Introduzca el numero de caja");
	if(caja=="" || caja==null){
		alert("Introduzca el numero de caja a capturar");
	}else{
		var idEmpaque=document.getElementById("txtEmpaque").value;
		ajaxApp("mensajesEmpaque","controladorEnsamble.php","action=guardarCaja&caja="+caja+"&idEmpaque="+idEmpaque,"POST");
	}
}
function capturarDetalleCaja(fecha,tecnico,entrega,caja,modelo,idEmpaque){
	//se oculta las capas
	$("#listadoPanel").hide();
	$("#capturaCaja").show();
	asignarDatosForm(fecha,tecnico,entrega,caja,modelo,idEmpaque);
}
function infoCaja(idEmpaque,caja,idInfoCaja){
	ajaxApp(idInfoCaja,"controladorEnsamble.php","action=muestraInfoCaja&idEmpaque="+idEmpaque+"&idCaja="+caja,"POST");
}
function nuevaEntrega(){
	$("#listadoPanel").hide();
	$("#capturaCaja").hide();
	$("#datosIniciales").show();
}