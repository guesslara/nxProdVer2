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
		$("#"+divDestino).show().html("Buscando..."); 
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
		//registrarDatos();
		buscarRegistros();
	}
}
function buscarRegistros(){
	var imeiEnsamble=document.getElementById("txtImeiEnsamble").value;
	var filtro=document.getElementById("cboFiltroBusqueda").value;
	if(imeiEnsamble=="" || imeiEnsamble==null || filtro=="--"){
		alert("Introduzca un parametro valido o verifique el filtro de Busqueda.");
	}else{		
		ajaxApp("eqProceso","controladorEnsamble.php","action=buscarEquipo&imei="+imeiEnsamble+"&filtro="+filtro,"POST");
	}
	document.getElementById("txtImeiEnsamble").value="";
}
function registrarDatos(){
	var imeiEnsamble=document.getElementById("txtImeiEnsamble").value;
	if(imeiEnsamble=="" || imeiEnsamble==null){
		alert("Introduzca un numero de Imei valido.");
	}else{
		//ajaxApp("div_grid_ensamble","controladorEnsamble.php","action=actualizaStatusEquipo&imeiEnsamble="+imeiEnsamble,"GET"); 
		/*contadorGrid+=1;
		$("#div_grid_ensamble").append("<div><input type='checkbox' checked='checked' name='cboImeiCapturado' id='cboImeiCapturado' value='"+imeiEnsamble+"' /><input type='text' name='' id='' value='"+imeiEnsamble+"' readonly='readonly' /></div>");
		$("#agregado").html("Equipos en el listado: "+contadorGrid);*/
		ajaxApp("div_grid_ensamble","controladorEnsamble.php","action=actualizaEquipoEnsamble&imei="+imeiEnsamble,"POST");
	}
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
function verificaTeclaSerial(evento,caja){
	if(evento.which==13 && caja==1){
		document.getElementById("txtLote").focus();
	}
}
function verificaTeclaLote(evento,caja){
	if(evento.which==13 && caja==2){
		document.getElementById("txtClave").focus();
	}
}
function verificaTeclaClave(evento,caja){
	if(evento.which==13 && caja==3){
		document.getElementById("txtStatus").focus();
	}
}
function verificaTeclaStatus(evento,caja){
	if(evento.which==13 && caja==4){
		document.getElementById("txtProceso").focus();
	}
}
function verificaTeclaProceso(evento,caja){
	if(evento.which==13 && caja==5){
		document.getElementById("txtDesensamble").focus();
	}
}
function verificaTeclaDesensamble(evento,caja){
	if(evento.which==13 && caja==6){
		document.getElementById("txtIngenieria").focus();
	}
}
function verificaTeclaIngenieria(evento,caja){
	if(evento.which==13 && caja==7){
		document.getElementById("btnActualizar").focus();
	}
}
function actualizaRegistro(){
	//se recuperan los valores
	var imei=document.getElementById("txtImei").value;
	var serial=document.getElementById("txtSerial").value;
	var lote=document.getElementById("txtLote").value;
	var clave=document.getElementById("txtClave").value;
	var status=document.getElementById("txtStatus").value;
	var statusProceso=document.getElementById("txtProceso").value;
	var statusDesensamble=document.getElementById("txtDesensamble").value;
	var statusIngenieria=document.getElementById("txtIngenieria").value;
	var id=document.getElementById("txtId").value;
	
	
	ajaxApp("div_grid_ensamble","controladorEnsamble.php","action=actualizaReg&imei="+imei+"&serial="+serial+"&lote="+lote+"&clave="+clave+"&status="+status+"&statusProceso="+statusProceso+"&statusDesensamble="+statusDesensamble+"&statusIngenieria="+statusIngenieria+"&id="+id,"POST");
}
function abrirCampos(){
	$("#transparenciaGeneral").show();
	$("#ventanaDialogo").show();	
}
function comienzaEdicion(){
//se oculta la informacion de la consulta
			$("#datos_imei").hide();
			$("#datos_serial").hide();
			$("#datos_sim").hide();
			$("#datos_lote").hide();
			$("#datos_clave").hide();
			$("#datos_movimiento").hide();
			$("#datos_status").hide();
			$("#datos_statusProceso").hide();
			$("#datos_statusDesensamble").hide();
			$("#datos_statusDiagnostico").hide();
			$("#datos_statusAlmacen").hide();
			$("#datos_statusIngenieria").hide();
			$("#datos_statusEmpaque").hide();
			$("#datos_statusIq").hide();
			
			$("#txt_mod_imei").show();
			$("#txt_mod_serial").show();
			$("#txt_mod_sim").show();
			$("#txt_mod_lote").show();
			$("#txt_mod_clave").show();
			$("#txt_mod_movimiento").show();
			$("#txt_mod_status").show();
			$("#txt_mod_statusProceso").show();
			$("#txt_mod_statusDesensamble").show();
			$("#txt_mod_statusDiagnostico").show();
			$("#txt_mod_statusAlmacen").show();
			$("#txt_mod_statusIngenieria").show();
			$("#txt_mod_statusEmpaque").show();
			$("#txt_mod_statusIq").show();
}
function verificaUsuario(){	
	var usuarioMod=document.getElementById('txtUsuarioMod').value;
	var passmod=document.getElementById('txtPassMod').value;
	if((usuarioMod=="") || (usuarioMod==null) || (passmod=="")){
		alert('Escriba su nombre de usuario y password para poder continuar');
	}else{
		//$("#ventanaDialogo").hide();
		//$("#transparenciaGeneral").hide();			
		//var parametros=actualizaRegistro();
		parametros="action=verificaUsuario&usuarioMod="+usuarioMod+"&passMod="+passmod;		
		ajaxApp("verificacionUsuario","controladorEnsamble.php",parametros,"POST");
	}
}