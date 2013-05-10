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
		//$("#datosFormularioEnsamble").html("");
		//registrarDatos();
		$("#txtSimEnsamble").focus();
	}
}
function verificaTeclaSimEnsamble(evento){
	if(evento.which==13){
		$("#datosFormularioEnsamble").html("");
		registrarDatos();		
	}
}
function registrarDatos(){
	var imeiEnsamble=document.getElementById("txtImeiEnsamble").value;
	var simEnsamble=document.getElementById("txtSimEnsamble").value;
	var cadenaCheck=imeiEnsamble+"|"+simEnsamble;
	if(imeiEnsamble=="" || imeiEnsamble==null || simEnsamble=="" || simEnsamble==null){
		alert("Verifique que los datos esten correctos.");
	}else{
		//ajaxApp("div_grid_ensamble","controladorEnsamble.php","action=actualizaStatusEquipo&imeiEnsamble="+imeiEnsamble,"GET"); 
		contadorGrid+=1;		
		$("#div_grid_ensamble").append("<div><input type='checkbox' checked='checked' name='cboImeiCapturado' id='cboImeiCapturado' value='"+cadenaCheck+"' /><input type='text' name='' id='' value='"+imeiEnsamble+"' readonly='readonly' /><input type='text' name='' id='' value='"+simEnsamble+"' readonly='readonly' /></div>");
		$("#agregado").html(contadorGrid);
		//ajaxApp("div_grid_ensamble","controladorEnsamble.php","action=actualizaEquipoEnsamble&imei="+imeiEnsamble,"POST");
	}
	$("#txtImeiEnsamble").attr("value","");
	$("#txtSimEnsamble").attr("value","");
	$("#txtImeiEnsamble").focus();
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
		try{
			proceso=document.getElementById("txtProcesoEnsamble").value;
			id_usuarioEnsamble=document.getElementById("txtIdUsuarioEnsamble").value;
			linea=document.getElementById("cboLinea").value;
			filtro=document.getElementById("cboClasificacion").value;
			if(linea !="Selecciona" && filtro !="Selecciona"){
				div="div_grid_ensamble";
				url="controladorEnsamble.php";
				parametros="action=actualizaDatosIngenieria&equipos="+equipos+"&proceso="+proceso+"&id_usuarioEnsamble="+id_usuarioEnsamble+"&linea="+linea+"&filtro="+filtro;
				//alert(parametros);
				metodo="POST";
				ajaxApp(div,url,parametros,metodo);
			}else if(linea=="Selecciona"){
				alert("Error, Verifique que se haya seleccionado una Linea.");
			}else if(filtro=="Selecciona"){
				alert("Error, Verifique que se haya seleccionado una Clasificacion para los Imei(s).");
			}
		}catch(e){ alert("Ha ocurrido un error en la Aplicacion.\n\n ERROR CODIGO[2]")}
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
	//document.getElementById("txtImeiEnsamble").focus();
	$("#agregado").html(contadorGrid);
}
function nuevacaptura(){
	//se vacia el grid
	$("#div_grid_ensamble").html("");	
	resetForm();
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
function mostrarTab(div){
	if(div=="ventanaEnsambleContenido"){
		$("#ventanaEnsambleContenido").show();	$("#ventanaEnsambleContenido2").hide(); $("#ventanaEnsambleContenido2").html("");
		$("#ventanaEnsambleContenido").html("");
		configurarGridOk();
	}else if(div=="ventanaEnsambleContenido2"){
		$("#ventanaEnsambleContenido2").show();	$("#ventanaEnsambleContenido").hide(); $("#ventanaEnsambleContenido").html("");
		$("#ventanaEnsambleContenido2").html("");
		configurarScrap();
	}
}
function mensajeFlex(opcion){
	if(opcion=="s"){
		alert("Las tarjetas que van a ser capturadas, se guardaran contabvilizando FLEX NUEVO!!");
	}else if(opcion=="n"){
		alert("Capturando Equipos sin Flex Nuevo");
	}
}
function mostrarAdvertenciaCaptura(){	
	var linea=$("#cboLinea").val();
	if(linea=="Selecciona"){
		alert("Error:\n\n Puede deberse a las siguientes causas:\n\n Seleccione un linea.");
	}else{
		$("#opcionFormFlex").show();
		$("#btnFormFlexNo").focus();
	}
}
function configurarGridOk(){	
	resetDatosScriptGrid();
	proceso=document.getElementById("txtProcesoEnsamble3").value;
	usrEnsamble=document.getElementById("txtIdUsuarioEnsamble3").value;
	//se verifican los elementos
	var linea=$("#cboLinea").val();
	var filtroFlex=$("#txtOpcionFlex").val();
	
	if(linea=="Selecciona"){
		alert("Error:\n\n Puede deberse a las siguientes causas:\n\n Seleccione un linea.");
	}else{
		/*se configura el grid*/
		nombresColumnas=new Array("Imei","Sim","Mensaje");
		cargaInicial(3,"ventanaEnsambleContenido","controladorEnsamble.php","action=actualizaDatosIngenieria&proceso="+proceso+"&usrEnsamble="+usrEnsamble+"&linea="+linea+"&filtroFlex="+filtroFlex,"erroresCaptura",nombresColumnas);
		inicio();
		$("#txt_0").focus();
		$("#txt_0").removeClass("datoListado");
		$("#txt_0").addClass("elementoFocus");
	}
}
function configurarScrap(){
	resetDatosScriptGrid();
	proceso=document.getElementById("txtProcesoEnsamble3").value;
	usrEnsamble=document.getElementById("txtIdUsuarioEnsamble3").value;
	var linea=$("#cboLinea").val();
	var filtroFlex="0";
	
	if(linea=="Selecciona"){
		alert("Error:\n\n Puede deberse a las siguientes causas:\n\n Seleccione un linea.");
	}else{
		/*se configura el grid*/
		nombresColumnas=new Array("Imei","Mensaje");
		cargaInicial(2,"ventanaEnsambleContenido2","controladorEnsamble.php","action=actualizaDatosIngenieriaScrap&proceso="+proceso+"&usrEnsamble="+usrEnsamble+"&linea="+linea,"erroresCaptura",nombresColumnas);
		inicio();
		$("#txt_0").focus();
		$("#txt_0").removeClass("datoListado");
		$("#txt_0").addClass("elementoFocus");
	}
}
function colocarLinea(){
	var linea=$("#cboLinea").val();
	$("#infoLineaCaptura").html("");
	$("#infoLineaCaptura").html("L&iacute;nea <br> "+linea);
}
function colocaValorFlex(opcion){
	if(opcion=="nuevo"){
		$("#infoCapturaFlex").html("");
		$("#infoCapturaFlex").html("<br><strong>Opcion elegida:</strong><br><br><span style='color:#FF0000;font-weight:bold;margin-left:10px;'>Flex Nuevo.</span>");
		$("#txtOpcionFlex").attr("value","1");
	}else if(opcion=="procesado"){
		$("#infoCapturaFlex").html("");
		$("#infoCapturaFlex").html("<br><strong>Opcion elegida:</strong><br>Default.");
		$("#txtOpcionFlex").attr("value","0");
	}
	mostrarTab('ventanaEnsambleContenido');	
	$("#opcionFormFlex").hide();
}
function contarEquiposIng(){
	ajaxApp("infoEquiposIng","controladorEnsamble.php","action=contarIng","POST");
}
function consultaAntEqui(){
	ajaxApp("consultaEqui","controladorEnsamble.php","action=contAntEqui","POST");
}
function muestrAnt(){
	$("#ventanaEnsambleContenido").append("<div id='ConQueryA' style='width:98%; height: 98%; margin: 3px; display: none; font-size: 15px;'><div id='QueryRes' style='width: 39%; height: 98%; border: 1px solid #f0f0f0; overflow: auto; margin: 3px;float: left;'></div><div id='GrafRes' style='width: 58%; height: 98%; border: 1px solid #f0f0f0; overflow: auto; margin-top: 3px; margin-bottom: 3px;margin-right: 3px;float: right;'></div></div>");
	
	
	var fechaIni=$("#fechaIni").val();
	var fechaFin=$("#fechaFin").val();
	if(fechaIni==""||fechaFin==""){
		alert("Ingresa un intervalo de fechas");
		return;
	}
	$("#ConQueryA").show();
	$("#GrafRes").append("<iframe src='grafProc.php?fechaIni="+fechaIni+"&fechaFin="+fechaFin+"' method='GET' style=\"width:95%;height:270px;margin:5px;\"></iframe><iframe src='grafSend.php?fechaIni="+fechaIni+"&fechaFin="+fechaFin+"' method='GET' style=\"width:95%;height:270px;margin:5px;\"></iframe>")
	ajaxApp("QueryRes","controladorEnsamble.php","action=showRes&fechaIni="+fechaIni+"&fechaFin="+fechaFin,"POST");
}
function muestraMod(Li){
	$("#modL"+Li).show();
	$("#close"+Li).append("<label><a href='#' onclick='closeMod("+Li+");' style='color:red;text-decoration: none;' title='Ocultar'><img src='../../img/icon_delete.gif' border='0' /></a></label>")
}
function closeMod(Li){
	$("#modL"+Li).hide();
	$("#close"+Li).html("");
}
function queryMod(Li,fechaIni,fechaFin){
	ajaxApp("modL"+Li,"controladorEnsamble.php","action=queryMod&")
}