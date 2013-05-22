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
function verificaTeclaImeiEmpaque(evento){
	if(evento.which==13){		
		//registrarDatos();
		//se valida la longitud de la cadena capturada
		var imei=document.getElementById("txtImeiEmpaque").value;
		if(imei.length < 15){
			$("#erroresCaptura").html("");
			$("#erroresCaptura").append("Error: verifique que haya introducido en el Imei la informacion correcta.");
			
		}else{
			document.getElementById("txtSimEmpaque").focus();
		}
		
	}
}
function verificaTeclaSimEmpaque(evento){
	if(evento.which==13){
		//try{
			/*
			var imei=document.getElementById("txtImeiEmpaque").value;
			var sim=document.getElementById("txtSimEmpaque").value;
			var id_empaque=document.getElementById("idEmpaqueCaptura").value;
			var id_caja=document.getElementById("txtIdCaja").value;
			parametros="action=guardaItemsEmpaque&imei="+imei+"&sim="+sim+"&id_caja="+id_caja+"&id_empaque="+id_empaque;
			alert(parametros);
			//se envian a la base de datos
			ajaxApp("erroresCaptura","controladorEnsamble.php","action=guardaItemsEmpaque&imei="+imei+"&sim="+sim+"&id_caja="+id_caja+"&id_empaque="+id_empaque,"POST");
			*/
			registrarDatos();
		//}catch(e){ alert("Error Aplicacion: Datos nulos");}
	}
}
function armarGridCaptura(imei,sim){
	contadorTxt+=1;
	//$("#div_grid").append("<br><input type='text' size='2' value='"+contadorTxt+"' /><input type='text' id='"+idCode+"' value='"+bdCode+"' size='60' /><input type='text' id='"+idImei+"' value='"+imei+"' /><input type='text' id='"+idSerial+"' value='"+serial+"' />");
	/*$("#capturados").html("");
	$("#capturados").html("<p style='font-size:14px;'>Equipos: "+contadorTxt+"</p>");
	$("#div_grid_ensamble").append("<div style='float:left;width:10px;background:#CCC;border:1px solid #CCC;height:15px;padding:4px;'>&nbsp;</div><div style='float:left;width:200px; height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+imei+"</div><div style='float:left;width:200px;height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+sim+"</div><div style='clear:both;'></div>");
	*/
	limpiaCajas();
}
function limpiaCajas(){
	document.getElementById("txtImeiEmpaque").value="";
	document.getElementById("txtSimEmpaque").value="";
	document.getElementById("txtImeiEmpaque").focus();
}
function registrarDatos(){
	var imeiEmpaque=document.getElementById("txtImeiEmpaque").value;
	var simEmpaque=document.getElementById("txtSimEmpaque").value;
	if(imeiEmpaque=="" || imeiEmpaque==null || simEmpaque=="" || simEmpaque==null){
		alert("Introduzca un numero de Imei o Sim valido.");
	}else{
		//ajaxApp("div_grid_ensamble","controladorEnsamble.php","action=actualizaStatusEquipo&imeiEnsamble="+imeiEnsamble,"GET"); 
		contadorGrid+=1;
		var valor=imeiEmpaque+"|"+simEmpaque;
		var valorId="txt_"+contadorGrid+"_"+valor;
		var valoresGrid="";
		if(contadorGrid==1){
			$("#div_grid_ensamble").append("<div class='cuadroGrid'>&nbsp;</div><div class='cuadroListadoCol'>&nbsp;</div><div class='imeiListadoCol'>Imei</div><div class='simListadoCol'>Sim</div><div class='resultadoGuardadoCol'>Mensaje</div><div class='elementoCab3'></div><div class='retornoCarro'></div><div class='cuadroGrid'>"+contadorGrid+"</div><div class='elemento'><input type='checkbox' checked='checked' name='cboImeiCapturado' id='cboImeiCapturado' value='"+valor+"' /></div><div class='elemento'><input type='text' class='imeiListado' value='"+imeiEmpaque+"' readonly='readonly' /></div><div class='elemento'><input type='text' class='simListado' value='"+simEmpaque+"' readonly='readonly' /></div><div class='elemento'><input type='text' name='"+valorId+"' id='"+valorId+"' class='resultadoGuardado' value='No Guardado' readonly='readonly' /></div><div class='retornoCarro'></div>");		
		}else{
			$("#div_grid_ensamble").append("<div class='cuadroGrid'>"+contadorGrid+"</div><div class='elemento'><input type='checkbox' checked='checked' name='cboImeiCapturado' id='cboImeiCapturado' value='"+valor+"' /></div><div class='elemento'><input type='text' class='imeiListado' value='"+imeiEmpaque+"' readonly='readonly' /></div><div class='elemento'><input type='text' class='simListado' value='"+simEmpaque+"' readonly='readonly' /></div><div class='elemento'><input type='text' name='"+valorId+"' id='"+valorId+"' class='resultadoGuardado' value='No Guardado' readonly='readonly' /></div><div class='retornoCarro'></div>");
		}		
		//$("#div_grid_ensamble").append("<div><input type='checkbox' checked='checked' name='cboImeiCapturado' id='cboImeiCapturado' value='"+valor+"' /><input type='text' name='' id='' class='imeiListado' value='"+imeiEmpaque+"' readonly='readonly' /><input type='text' name='' id='' class='simListado' value='"+simEmpaque+"' readonly='readonly' /><input type='text' name='"+valorId+"' id='"+valorId+"' value='No Guardado' readonly='readonly' style='background:#FFC; border:none;width:200px;' /></div>");
		//$("#div_grid_ensamble").append("<div class='cuadroGrid'>"+contadorGrid+"</div><div class='elemento'><input type='checkbox' checked='checked' name='cboImeiCapturado' id='cboImeiCapturado' value='"+valor+"' /></div><div class='elemento'><input type='text' class='imeiListado' value='"+imeiEmpaque+"' readonly='readonly' /></div><div class='elemento'><input type='text' class='simListado' value='"+simEmpaque+"' readonly='readonly' /></div><div class='elemento'><input type='text' name='"+valorId+"' id='"+valorId+"' class='resultadoGuardado' value='No Guardado' readonly='readonly' /></div><div class='retornoCarro'></div>");	
		$("#agregado").html(contadorGrid);
		limpiaCajas();
		procesarDatosGrid(valorId,imeiEmpaque,simEmpaque);
	}
}
function procesarDatosGrid(idElemento,imeiEmpaque,simEmpaque){	
	//se recibe el elemento
	var valorElemento=document.getElementById(idElemento).value;
	if(valorElemento=="No Guardado"){
		//se prepara la peticion y recuperado de valores para insertar en la base de datos
		document.getElementById(idElemento).style.background="orange";
		document.getElementById(idElemento).value="Verificando"
		//se recuperan los valores para cada registro
		var id_empaque=document.getElementById("idEmpaqueCaptura").value;
		var id_caja=document.getElementById("txtIdCaja").value;
		valores=imeiEmpaque+"|"+simEmpaque;
		//idElemento
		ajaxApp("erroresCaptura","controladorEnsamble.php","action=guardaEquipoEmpaque&idEmpaque="+id_empaque+"&idCaja="+id_caja+"&valores="+valores+"&idElemento="+idElemento,"POST");		
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
	var txtTecnico=document.getElementById("txtTecnico").value;
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
function asignarDatosForm(fecha,txtTecnico,txtEntrega,txtCaja,modelo,idEmpaque,modeloTexto){
	//alert("funcion");
	/*
	$("#cajaInfoCaptura").html("");
	$("#cajaInfoCaptura").html();	
	$("#fechaEmpaque").html("");
	$("#fechaEmpaque").html("<p>Fecha: "+fecha+"</p>");
	$("#modeloEmpaque").html("");
	$("#modeloEmpaque").html("<p>Modelo: "+modelo+"</p>");
	*/
	$("#datosAsignaForm").html("<p>Datos:</p><p>Fecha: "+fecha+"</p><p style='font-weight:bold; font-size:18px;'>Modelo: "+modeloTexto+"</p><p>&nbsp;</p>")
	$("#cajaInfoCaptura").html(txtCaja);
	document.getElementById("txtIdCaja").value=txtCaja;
	document.getElementById("idEmpaqueCaptura").value=idEmpaque;
	//document.getElementById("txtImeiEmpaque").focus();
}
function listarCapturas(filtro){
	$("#datosIniciales").hide();
	$("#listadoPanel").show();
	//se asigna la clase css
	if(filtro=="capturas"){
		$("#mostrarValidacionEmpaqueDiv").removeClass("tabEmpaqueListadoCapturasFocus");
		$("#mostrarValidacionEmpaqueDiv").addClass("tabEmpaqueListadoCapturas");
		$("#mostrarCapturasEmpaqueDiv").removeClass("tabEmpaqueListadoCapturas");
		$("#mostrarCapturasEmpaqueDiv").addClass("tabEmpaqueListadoCapturasFocus");	
	}else if(filtro=="validaciones"){
		$("#mostrarCapturasEmpaqueDiv").removeClass("tabEmpaqueListadoCapturasFocus");
		$("#mostrarCapturasEmpaqueDiv").addClass("tabEmpaqueListadoCapturas");
		$("#mostrarValidacionEmpaqueDiv").removeClass("tabEmpaqueListadoCapturas");
		$("#mostrarValidacionEmpaqueDiv").addClass("tabEmpaqueListadoCapturasFocus");
	}	
	ajaxApp("listadoEmpaque","controladorEnsamble.php","action=listarCapturas&filtro="+filtro,"POST");
}
function verMas(idEmpaque){
	//alert('Ver mas');
	$("#barraOpcionesEmpaque").html("");
	ajaxApp("detalleEmpaque","controladorEnsamble.php","action=verDetalleEmpaque&idEmpaque="+idEmpaque,"POST");
}
function nuevaCaja(){
	var caja=prompt("Introduzca el numero de caja");
	if(caja=="" || caja==null){
		alert("Introduzca el numero de caja a capturar");
	}else{
		var idEmpaque=document.getElementById("txtEmpaque").value;
		//alert(idEmpaque);
		ajaxApp("erroresCaptura","controladorEnsamble.php","action=guardarCaja&caja="+caja+"&idEmpaque="+idEmpaque,"POST");
	}
}
function capturarDetalleCaja(fecha,tecnico,entrega,caja,modelo,idEmpaque,modeloTexto){
	//se oculta las capas
	//$("#listadoPanel").hide();
	$("#transparenciaGeneral").show();
	//se tienen que activar las funciones para que el grid funcione de manera correcta
	//action=guardaEquipoEmpaque&idEmpaque="+id_empaque+"&idCaja="+id_caja+"&valores="+valores+"&idElemento="+idElemento
	//se agrega el proceso y el usuario para el detalle
	proceso=document.getElementById("txtProcesoEmpaque").value;
	usrEmpaque=document.getElementById("txtIdUsuarioEmpaque").value;
	/*se configura el grid*/
	nombresColumnas=new Array("Imei","Serial","Sim","MFGDATE","Mensaje");
	cargaInicial(5,"div_grid_ensamble","controladorEnsamble.php","action=guardaEquipoEmpaque&idEmpaque="+idEmpaque+"&idCaja="+caja+"&modelo="+modelo+"&proceso="+proceso+"&usrEmpaque="+usrEmpaque,"erroresCaptura",nombresColumnas);
	inicio();
	$("#txt_0").focus();
        $("#txt_0").removeClass("datoListado");
        $("#txt_0").addClass("elementoFocus");
	/*fin de la configuracion del grid*/
	asignarDatosForm(fecha,tecnico,entrega,caja,modelo,idEmpaque,modeloTexto);
}
function infoCaja(idEmpaque,caja,idInfoCaja){
	ajaxApp(idInfoCaja,"controladorEnsamble.php","action=muestraInfoCaja&idEmpaque="+idEmpaque+"&idCaja="+caja,"POST");
}
function nuevaEntrega(){
	ajaxApp("detalleEmpaque","controladorEnsamble.php","action=nuevaEntrega","POST");
	//$("#listadoPanel").hide();
	//$("#capturaCaja").hide();
	//$("#datosIniciales").show();
}
function exportarArchivoValidacion(id_empaque){
	ajaxApp("capturaFact","controladorEnsamble.php","action=exportarValidacion&id_empaque="+id_empaque,"POST");
}
function cancelarCaptura(){
	if(confirm("Realmente desea Cerrar la Ventana?")){
		$("#transparenciaGeneral").hide();
		contadorGrid=0;
		$("#div_grid_ensamble").html("");
		$("#datosAsignaForm").html("");
		$("#cajaInfoCaptura").html("");
		$("#agregado").html(contadorGrid);
		//se llama a la funcion dentro del grid para que hacer un reset sobre los parametros y se pueda capturar en otra ocasion
		resetDatosScriptGrid();
	}
}
function verFormatoLista(idEmpaque){
	//$("#listadoEmpaqueCajas").show();
	$("#transparenciaGeneral1").show();
	$("#divListadoCapturaValidacion").show();
	ajaxApp("listadoEmpaqueValidacion","controladorEnsamble.php","action=verFormatoListaEmpaque&idEmpaque="+idEmpaque,"POST");
}
function validarSims(idEmpaque){
	$("#transparenciaGeneral1").show();
	$("#divListadoCapturaValidacion").show();
	ajaxApp("listadoEmpaqueValidacion","controladorEnsamble.php","action=validarSims&idEmpaque="+idEmpaque,"POST");
}
function cerrarVentanaValidacion(){
	$("#transparenciaGeneral1").hide();
	$("#divListadoCapturaValidacion").hide();
}
function retirarEquipoEntrega(idEmpaque,imei){
	if(confirm("¿Esta seguro de retirar el imei ("+imei+") del empaque interno "+idEmpaque+" ?")){
		ajaxApp("notificaciones","controladorEnsamble.php","action=retirarimeiEmpaque&idEmpaque="+idEmpaque+"&imei="+imei,"POST");
	}
}
function cerrarMensajeNotificacion(){
	$("#notificaciones").hide();
}
function enviarAValidar(){
	var entregas="";
	for (var i=0;i<document.frmListadoCapturasEmpaque.elements.length;i++){
		if (document.frmListadoCapturasEmpaque.elements[i].type=="checkbox"){
			if (document.frmListadoCapturasEmpaque.elements[i].checked){
				//alert("Variable claves=["+claves+"]");
				if (entregas=="")
					entregas=entregas+document.frmListadoCapturasEmpaque.elements[i].value;
				else
					entregas=entregas+","+document.frmListadoCapturasEmpaque.elements[i].value;
			}	
		}
	}	
	//alert(entregas);
	if(entregas==""){
		alert('Seleccione por lo menos 1 Entrega para efectuar la Operacion');
	}else{		
		if(confirm('Esta seguro de Mover las Capturas para su validacion. \n\n Esto afectara a los Imeis contenidos en las entregas seleccionadas que son:\n\n'+entregas+'\n\nEsta accion no se puede deshacer.')){
			div="listadoEmpaque";
			url="controladorEnsamble.php";
			parametros="action=moverEntregasAValidar&entregas="+entregas;
			//alert(parametros);
			metodo="POST";
			ajaxApp(div,url,parametros,metodo);	
		}
		
	}
}
function verDetalleValidacion(id){
	//se manda el link para exportar el listado
	//enlaces="<a href=\"exportarValidacionAgrupada.php?id_validacion="+id+"\" target='_blank' title='Exportar Validacion' style=\"color:blue; font-size:10px;\"> Exportar Archivo </a> |";
	//enlaces=enlaces + "<a href=\"../mod_scripts/captura_archivo_validacion.php?id_validacion="+id+"\" target='_blank' title='Agregar Archivo de Validacion' style=\"color:blue; font-size:10px;\"> Agregar Archivo de Validaci&oacute;n </a> |";
	//$("#barraOpcionesEmpaque").html(enlaces);
	ajaxApp("detalleEmpaque","controladorEnsamble.php","action=verDetalleValidaciones&id="+id,"POST")
}
function exportarArchivoValidacionGrupal(id){
	//alert(id);capturaFact
	ajaxApp("capturaFact","controladorEnsamble.php","action=exportarValidacionAgrupada&id_validacion="+id,"POST");
}
function PaginaListadoCapturasEmpaque(NroPagina,filtro){
	$("#datosIniciales").hide();
	$("#listadoPanel").show();
	//se asigna la clase css
	if(filtro=="capturas"){
		$("#mostrarValidacionEmpaqueDiv").removeClass("tabEmpaqueListadoCapturasFocus");
		$("#mostrarValidacionEmpaqueDiv").addClass("tabEmpaqueListadoCapturas");
		$("#mostrarCapturasEmpaqueDiv").removeClass("tabEmpaqueListadoCapturas");
		$("#mostrarCapturasEmpaqueDiv").addClass("tabEmpaqueListadoCapturasFocus");	
	}else if(filtro=="validaciones"){
		$("#mostrarCapturasEmpaqueDiv").removeClass("tabEmpaqueListadoCapturasFocus");
		$("#mostrarCapturasEmpaqueDiv").addClass("tabEmpaqueListadoCapturas");
		$("#mostrarValidacionEmpaqueDiv").removeClass("tabEmpaqueListadoCapturas");
		$("#mostrarValidacionEmpaqueDiv").addClass("tabEmpaqueListadoCapturasFocus");
	}	
	ajaxApp("listadoEmpaque","controladorEnsamble.php","action=listarCapturas&filtro="+filtro+"&pag="+NroPagina,"POST");
}
function validarEnviados(idEmpaque){
	$("#transparenciaGeneral1").show();
	$("#divListadoCapturaValidacion").show();
	ajaxApp("listadoEmpaqueValidacion","controladorEnsamble.php","action=validarEnviados&idEmpaque="+idEmpaque,"POST");
}
function ventanaDialogoVerificacionEquipoEnviado(){
	$("#divVerificacionEquipoEnviado").hide();
	$("#listadoEmpaqueVerificacionEnviado").hide();
}
function verificarInformacionEnviada(imei){
	$("#divVerificacionEquipoEnviado").show();
	$("#listadoEmpaqueVerificacionEnviado").show();
	ajaxApp("listadoEmpaqueVerificacionEnviado","controladorEnsamble.php","action=verificarInfoEnviado&imei="+imei,"POST");
}
function actualizarValidacionDetalleValidaciones(idItems){
	//alert(idItems);
	parametros="id_validacion="+idItems;
	$("#divVerificacionEquipoValidacion").show();
	$("#listadoEmpaqueVerificacionValidacion").show();
	$("#txtVentanaValidacionID").attr("value",idItems);
	ajaxApp("listadoEmpaqueVerificacionValidacion","../mod_scripts/captura_archivo_validacion.php",parametros,"GET");
}
function ventanaDialogoVerificacionEquipoValidado(){	
	if(confirm("Desea actualizar el listado?\n\nEsta operacion puede tomar varios minutos.")){
		$("#divVerificacionEquipoValidacion").hide();
		$("#listadoEmpaqueVerificacionValidacion").hide();
		txtVentanaValidacionID=$("#txtVentanaValidacionID").val();
		verDetalleValidacion(txtVentanaValidacionID);	
	}else{
		$("#divVerificacionEquipoValidacion").hide();
		$("#listadoEmpaqueVerificacionValidacion").hide();
	}	
}
function capturarEntregas(modelo,idEntregaInterna,cantidadEquiposEmpacados,idValidacion){
	$("#divVerificacionEquipoEntregas").show();
	ajaxApp("listadoEmpaqueVerificacionValidacionEntregas","controladorEnsamble.php","action=verEntrega&id_modelo="+modelo+"&idEntregaInterna="+idEntregaInterna+"&cantidadEquiposEmpacados="+cantidadEquiposEmpacados+"&idValidacion="+idValidacion,"POST")
	//alert(modelo);
}
function ventanaDialogoVerificacionEquipoEntrega(){
	$("#divVerificacionEquipoEntregas").hide();
}
function guardarEntrega(){
	idModeloEntrega=$("#txtIdModeloEntrega").val();
	poEntrega=$("#poEntrega").val();
	releaseEntrega=$("#txtReleaseEntrega").val();
	fechaEntrega=$("#Fecha1").val();
	conceptoEntrega=$("#conceptoEntrega").val();
	cantidadEntrega=parseInt($("#cantidadEntrega").val());
	destinoEntrega=$("#txtDestinoEntrega").val();
	idEntregaInterna=$("#txtIdEntregaInterna").val();
	cantidadPorEntregar=parseInt($("#txtCantidadEquiposEmpacados").val());
	txtIdValidacion=$("#txtIdValidacion").val();
	//alert("Cantidad entrega: "+cantidadEntrega+"\n\nCantidad por Entregar: "+cantidadPorEntregar);
	if(cantidadEntrega >  cantidadPorEntregar || cantidadEntrega==0 || isNaN(cantidadEntrega) || cantidadEntrega==""){ // 	
		alert("Error: La cantidad a Entregar no puede exceder la Cantidad disponible para Capturar\n\nNo capture letras\n\nSolo capture Numeros\n\nNo deje espacios en blanco");
	}else{		
		if(idModeloEntrega=="" || releaseEntrega=="" || fechaEntrega=="" || conceptoEntrega=="" || cantidadEntrega=="" || cantidadEntrega==0 || isNaN(cantidadEntrega) || destinoEntrega=="" || idEntregaInterna=="" || cantidadPorEntregar==""){
			alert("Error:\n\nNo deje espacios en blanco.\n\nLa cantidad no puede ser 0 ni letras\n\nDebe capturar el Concepto de Entrega y Destino");
		}else{
			parametros="action=guardarEntregaEmpaque&idModeloEntrega="+idModeloEntrega+"&poEntrega="+poEntrega+"&releaseEntrega="+releaseEntrega+"&fechaEntrega="+fechaEntrega+"&conceptoEntrega="+conceptoEntrega+"&cantidadEntrega="+cantidadEntrega+"&destinoEntrega="+destinoEntrega+"&idEntregaInterna="+idEntregaInterna+"&cantidadPorEntregar="+cantidadPorEntregar+"&txtIdValidacion="+txtIdValidacion;
			ajaxApp("listadoEmpaqueVerificacionValidacionEntregas","controladorEnsamble.php",parametros,"POST");
		}
	}
}
function modificarEntrega(entregaModificar,modelo,idEntregaInterna,cantidadEquiposEmpacados,idValidacion){
	$("#divVerificacionEquipoEntregas").show();
	ajaxApp("listadoEmpaqueVerificacionValidacionEntregas","controladorEnsamble.php","action=modificarEntrega&id_modelo="+modelo+"&idEntregaInterna="+idEntregaInterna+"&cantidadEquiposEmpacados="+cantidadEquiposEmpacados+"&idValidacion="+idValidacion+"&entregaModificar="+entregaModificar,"POST")	
}
function modificarEntregaFormulario(){
	var diferencia=0;
	idModeloEntrega=$("#txtIdModeloEntregaMod").val();
	poEntrega=$("#poEntregaMod").val();
	releaseEntrega=$("#txtReleaseEntregaMod").val();
	fechaEntrega=$("#Fecha1Mod").val();
	conceptoEntrega=$("#conceptoEntregaMod").val();
	cantidadEntrega=parseInt($("#cantidadEntregaMod").val());
	destinoEntrega=$("#txtDestinoEntregaMod").val();
	idEntregaInterna=$("#txtIdEntregaInternaMod").val();
	cantidadPorEntregar=parseInt($("#txtCantidadEquiposEmpacadosMod").val());
	txtIdValidacion=$("#txtIdValidacionMod").val();
	txtEquiposBd=parseInt($("#txtCantidadEntregaModBd").val());
	
	alert(txtEquiposBd);
	alert("Cantidad por Entregar: "+cantidadEntrega+"\n\nCantidad Bd: "+txtEquiposBd);
	
	if(cantidadEntrega < txtEquiposBd){
		diferencia=txtEquiposBd-cantidadEntrega;
	}else if(cantidadEntrega > txtEquiposBd){
		alert("Error, la cantidad a modificar ");
	}
	
	alert(diferencia);
	
	if(cantidadPorEntregar==0){
		cantidadPorEntregar=cantidadEntrega;
	}
	//alert("Cantidad entrega: "+cantidadEntrega+"\n\nCantidad por Entregar: "+cantidadPorEntregar);
	alert("ID MODELO: "+idModeloEntrega+"\n\nPO ENTREGA: "+poEntrega+"\n\nRELEASE ENTREGA: "+releaseEntrega+"\n\nFecha: "+fechaEntrega+"\n\nCONCEPTO: "+conceptoEntrega+"\n\nCANTIDAD ENTREGA: "+cantidadEntrega+"\n\nDESTINO: "+destinoEntrega+"\n\nENTREGA INTERNA : "+idEntregaInterna+"\n\nCANTIDAD POR ENTREGAR: "+cantidadPorEntregar+"\n\nID VALIDACION: "+txtIdValidacion+"\n\nDIFERENCIA: "+diferencia);
	
	if(cantidadEntrega >  cantidadPorEntregar || cantidadEntrega==0 || isNaN(cantidadEntrega) || cantidadEntrega==""){ // 	
		alert("Error: La cantidad a Entregar no puede exceder la Cantidad disponible para Capturar\n\nNo capture letras\n\nSolo capture Numeros\n\nNo deje espacios en blanco");
	}else{		
		if(idModeloEntrega=="" || releaseEntrega=="" || fechaEntrega=="" || conceptoEntrega=="" || cantidadEntrega=="" || cantidadEntrega==0 || isNaN(cantidadEntrega) || destinoEntrega=="" || idEntregaInterna=="" || cantidadPorEntregar==""){
			alert("Error:\n\nNo deje espacios en blanco.\n\nLa cantidad no puede ser 0 ni letras\n\nDebe capturar el Concepto de Entrega y Destino");
		}else{
			parametros="action=guardarEntregaEmpaque&idModeloEntrega="+idModeloEntrega+"&poEntrega="+poEntrega+"&releaseEntrega="+releaseEntrega+"&fechaEntrega="+fechaEntrega+"&conceptoEntrega="+conceptoEntrega+"&cantidadEntrega="+cantidadEntrega+"&destinoEntrega="+destinoEntrega+"&idEntregaInterna="+idEntregaInterna+"&cantidadPorEntregar="+cantidadPorEntregar+"&txtIdValidacion="+txtIdValidacion;
			alert(parametros);
			//ajaxApp("listadoEmpaqueVerificacionValidacionEntregas","controladorEnsamble.php",parametros,"POST");
		}
	}
}
function eliminarEntrega(idEntregaInterna,cantidadEntrega,idValidacion){
	txtIdUsuarioEmpaque=$("#txtIdUsuarioEmpaque").val();
	if(confirm("Realmente desea eliminar la captura ACTUAL y todo lo ASOCIADO a la misma?")){		
		parametros="action=eliminarEntrega&idEntregaInterna="+idEntregaInterna+"&cantidadEntrega="+cantidadEntrega+"&txtIdUsuarioEmpaque="+txtIdUsuarioEmpaque+"&idValidacion="+idValidacion;
		//alert(parametros);
		ajaxApp("notificaciones","controladorEnsamble.php",parametros,"POST");
	}
}
function capturarEntrega(idEntregaInterna,entrega,modelo,idValidacion,numeroCaja,cantidadCapturada,idModeloCapturarEntrega,poAValidar,idValidacionAgrupada){
	//alert("Empaque interno: "+idEntregaInterna+"\n\nEntrega: "+entrega+"\n\nModelo: "+modelo+"\n\nId Validacion: "+idValidacion+"\n\num caja: "+numeroCaja+"\n\nCantidad Capturada: "+cantidadCapturada+"\n\nId Modelo: "+idModeloCapturarEntrega);
	//alert(idValidacionAgrupada);	
	//se muestran los divs
	$("#capturaFinal").show();
	//se procede a configurar el grid para la captura
	usrEmpaque=$("#txtIdUsuarioEmpaque").val();
	nombresColumnas=new Array("Imei","Mensaje");
	cargaInicial(2,"div_grid_capturaFinal","controladorEnsamble.php","action=guardaEquipoEmpaqueFinal&idEntregaInterna="+idEntregaInterna+"&conceptoEntrega="+entrega+"&modelo="+modelo+"&usrEmpaque="+usrEmpaque+"&numeroCaja="+numeroCaja+"&cantidadCapturada="+cantidadCapturada+"&idModeloCaptura="+idModeloCapturarEntrega+"&poAValidar="+poAValidar+"&idValidacionAgrupada="+idValidacionAgrupada,"erroresCapturaEmpaqueFinal",nombresColumnas);
	inicio();
	$("#txt_0").focus();
        $("#txt_0").removeClass("datoListado");
        $("#txt_0").addClass("elementoFocus");
	/*fin de la configuracion del grid*/
	asignarDatosCapturaFinal(idEntregaInterna,entrega,modelo,idValidacion,numeroCaja,poAValidar,idValidacionAgrupada);	
}
function asignarDatosCapturaFinal(idEntregaInterna,entrega,modelo,idValidacion,numeroCaja,poAValidar,idValidacionAgrupada){
	$("#barraTitulo1VentanaDialogoCapturaFinal").html("Capturando Entrega: "+entrega+" - PO: "+poAValidar);
	$("#idEmpaqueValidacion").attr("value",idValidacion);
	$("#datosAsignaFormCapturaFinal").html(modelo)
	$("#agregadoCajaCapturaFinal").html(numeroCaja);
}
function cancelarCapturaFinal(){
	if(confirm("Realmente desea Cerrar la Ventana de Captura")){
		idValidacion=$("#idEmpaqueValidacion").val();
		$("#capturaFinal").hide();
		$("#div_grid_capturaFinal").html("");
		verDetalleValidacion(idValidacion);
	}
}
function agregarCajaCaptura(idEntregaInterna,idValidacion,cantidadEntrega){
	ajaxApp("cargadorEmpaque","controladorEnsamble.php","action=agregarCajaCaptura&idEntregaInterna="+idEntregaInterna+"&idValidacion="+idValidacion+"&cantidadEntrega="+cantidadEntrega,"POST");
}
function finalizarEnviosEntregasFinal(idEntregaInterna){
	if(confirm("Realmente desea marcar los equipos como ENVIADOS?")){
		alert("Se marcaran como ENVIADOS los equipos de la entrega interna: "+idEntregaInterna);
		ajaxApp("detalleEmpaque","controladorEnsamble.php","action=finalizarEquiposFinal&idEntregaInterna="+idEntregaInterna,"POST");
	}
}
function cerrarVentanaFinalizacion1(){
	$("#transparenciaGeneral10").hide();
}
function marcarEquiposEnviados(idEntregaInterna,conceptoEntrega){
	idUsuarioActual=$("#txtIdUsuarioEmpaque").val();
	procesoEnvio=$("#txtProcesoEmpaqueEnvio").val();
	if(idUsuarioActual==""){
		alert("Ingrese de nuevo al Sistema para poder realizar esta tarea");
	}else{
		if(confirm("Realmente desea MARCAR como ENVIADOS los equipos asociados a la entrega: "+conceptoEntrega)){		
			$("#transparenciaGeneral10").show();//divListadoCapturaFinalizacion
			ajaxApp("listadoEmpaqueFinalizacion","controladorEnsamble.php","action=marcarEquiposEnviados&idEntregaInterna="+idEntregaInterna+"&conceptoEntrega="+conceptoEntrega+"&idUsuarioActual="+idUsuarioActual+"&procesoEnvio="+procesoEnvio,"POST");
		}
	}
}
function detalleEntregaListado(idEntrega,conceptoEntrega){
	//funcion para mostrar la ventana
	//alert(idEntrega+"\n"+conceptoEntrega);
	parametrosVentana="action=mostrarInfoEntrega&idEntrega="+idEntrega+"&conceptoEntrega="+conceptoEntrega;
	//se manda llamar a la funcion que contiene la funcionalidad de las ventanas
	generarVentana('Informaci&oacute;n de la Entrega '+conceptoEntrega,'450','700','controladorEnsamble.php',parametrosVentana);
}
function verificaEquiposMarcados(idValidaciones){
	//alert(idValidaciones);
	ajaxApp("detalleEmpaque","controladorEnsamble.php","action=verificarEquiposMarcados&idValidaciones="+idValidaciones,"POST");
}