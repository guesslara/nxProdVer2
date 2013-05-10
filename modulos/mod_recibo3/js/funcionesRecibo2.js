// JavaScript Document
var contadorTxt=0;
var nvoContador=1;
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
function ajaxAppVentana(divDestino,url,parametros,metodo){
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		$("#"+divDestino).show().html("Espere..."); 
	},
	success:function(datos){ 
		$("#"+divDestino).show().html(datos);		
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('Error: Al efectuar las operaciones en el servidor.'); }
	});
}
function recibirEquipos(user,modelo,mov,cantidad){	

	div="detalleRecibo";
	url="controladorRecibo.php";
	parametros="action=recibirEquipos&usuarioCaptura="+user+"&modelo="+modelo+"&mov="+mov+"&cantidad="+cantidad;
	metodo="GET";
	//alert(parametros);
	ajaxApp(div,url,parametros,metodo);
}
function recuperaDatos(){
	//se recuperan los datos de la gui
	
	var cantidad=document.getElementById("hdncantidad").value;
	var mov=document.getElementById("hdnmovimiento").value;
	var modelo=document.getElementById("cboModeloRadio").value;
	var recibe=document.getElementById("hdnUsuario").value;
	var proceso=document.getElementById("cboProceso").value;
	var lote=document.getElementById("txtLote").value;
	var clave=document.getElementById("txtClave").value;
	var clasificacion=document.getElementById("cboClasificacion").value;
	
	if((proceso=="")||(lote=="") || (clasificacion=="Selecciona")){
	
		alert("Debe llenar todos los datos");
	}else{
		
	div="detalleRecibo";
	url="controladorRecibo.php";
	//parametros="action=mostrarCaptura&modelo="+modelo+"&recibe="+recibe+"&proceso="+proceso+"&lote="+lote+"&clave="+clave+"&mov="+mov+"&cantidad="+cantidad;
	parametros="action=mostrarCaptura&modelo="+modelo+"&recibe="+recibe+"&proceso="+proceso+"&lote="+lote+"&clave="+clave+"&mov="+mov+"&cantidad="+cantidad+"&clasificacion="+clasificacion;
	metodo="POST";
	//alert(parametros);
	ajaxApp(div,url,parametros,metodo);
	

	$("#infoCaptura").append("<div style='float:left;width:10px;background:#CCC;border:1px solid #CCC;height:15px;padding:4px;'>&nbsp;</div><div style='float:left;width:340px; height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+modelo+"</div><div style='float:left;width:120px;height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+proceso+"</div><div style='float:left;width:180px;height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+lote+"</div><div style='clear:both;'></div>");	
	}
}
function verificaTeclaBDCode(evento){
	tc=evento.which+String.fromCharCode(evento.which);
	//alert(tc);
	if(evento.which==13){
		//alert('funcion ajax');
		var bdCode= new String(document.getElementById("bdCode").value);
	
		 if(bdCode!=""){
			var lght=parseFloat(bdCode.length);	
			var serial=bdCode.substr((lght-10),10);
			var imei=bdCode.substr((lght-25),15);
			
			document.getElementById("txtSerial").value=serial;
			document.getElementById("txtImei").value=imei;
			registrarDatos();
			//document.getElementById("txtImei").focus();
		}else{
		   
		//se recuperan los nuevos valores
		document.getElementById("txtImei").focus();
		var serial= new String(document.getElementById("txtSerial").value);
		var imei= new String(document.getElementById("txtImei").value);
		}
	
	}
}
function verificaTeclaSerial(evento){
	try{
		if(evento.which==13){		
			valor=document.getElementById("txtSerial").value;
			//var serialEnLista=buscarValor(valor);
			//if(serialEnLista==false){
				if(valor.length==10){
					//document.getElementById("txtImei").focus();
					registrarDatos();
				}else{
					alert("Verifique la longitud del serial");
				}
			//}
		}
	}catch(e){ alert("Error en la aplicacion \n\n"+e.description);}
}
	
	 
function verificaTeclaImei(evento){
	try{
		if(evento.which==13){
			valor=document.getElementById("txtImei").value;
			//se manda a llamar a la funcion para la busqueda
			//var estaEnGrid=buscarValor(valor);
			//if(estaEnGrid==false){
				if( isNaN(valor) ) {
					alert("El campo Imei solo acepta numeros:");		
				}else if(valor.length==15){
					document.getElementById("txtSerial").focus();		
				}else{
					alert("Verifique la longitud del Imei");
				}
			//}
		}
	}catch(e){ alert("Error en la Aplicacion \n\n"+e.descriptcion)}
}

//var noCapturados=0;

function registrarDatos(){
	//recuperamos los valores
	var cantidad=document.getElementById("hdncantidad").value;
	//alert("Contador: "+noCapturados+"\n\n"+"Cantidad: "+cantidad);
	/*if((noCapturados == (cantidad-1)) || noCapturados<cantidad){
		//alert("recupero valores");
		noCapturados+=1;*/
		
/************************************************************************/
		var modelo=document.getElementById("modeloCaptura").value;
		var usuario=document.getElementById("usuarioRecibe").value;
		var proceso=document.getElementById("procesoRadio").value;
		var lote=document.getElementById("loteRadio").value;
		var clave=document.getElementById("claveRadio").value;
		var bdCode=document.getElementById("bdCode").value;
		var serial=document.getElementById("txtSerial").value;
		var imei=document.getElementById("txtImei").value;
		var mov=document.getElementById("hdnmovimiento").value;
		
		//declaro banderas
		bandera1=true;
		bandera2=true;
		
		if(bdCode==""){
			bdCode=""
		}
		
		if(serial==""){
			serial="--";
		}else{
				if(serial.length==10){
					bandera1=true;
				}else{
					alert("Verifique la longitud del serial");
					bandera1=false;
					document.getElementById("txtSerial").focus();
				}
		}
	
			
		if(imei==""){
			imei="--";
		}else{
			//valor=document.getElementById("txtImei").value;		
			 if(!isNaN(imei) && (imei.length==15) ) {
				 bandera2=true;
				//alert("El campo Imei solo acepta numeros:");
      			//return true;
			 }else{
			    bandera2=false;
				alert("Verifique los datos del Imei");
			 }
		}
		
		
		if(bandera1==true && bandera2==true){
		  div="msgReciboGrid";
		  url="controladorRecibo.php";
		  parametros="action=registrarDatos&modelo="+modelo+"&usuarioRecibe="+usuario+"&proceso="+proceso+"&lote="+lote+"&clave="+clave+"&bdCode="+bdCode+"&serial="+serial+"&imei="+imei+"&mov="+mov+"&cantidad="+cantidad;	
		  metodo="POST";
		  //alert(parametros); 
		  
		  ajaxAppVentana(div,url,parametros,metodo);
		  // $("#infoCaptura").html("<div>Equipos Capturados</div><div>"+(noCapturados)+"</div>");
		  document.getElementById("bdCode").value="";
		  document.getElementById("txtSerial").value="";
		  document.getElementById("txtImei").value="";
		  //
		 document.getElementById("txtImei").focus();
		 
		}
			
/************************************************************************/		
		/*if(noCapturados > (cantidad-1)){
			alert("se para la captura");
		}*/
		//alert("Contador: "+noCapturados+"\n\n"+"Cantidad: "+cantidad);
		
		//noCapturados=0;	

	//}
}
function validacion(){

	   document.getElementById("btncerrar").style.display="";
		document.getElementById("bdCode").readOnly=true;
		document.getElementById("txtSerial").readOnly=true;
		document.getElementById("txtImei").readOnly=true;
		document.getElementById("btnRegistrarDatos").disabled=true;
		document.getElementById("transparenciaGeneral").style.display="";
		document.getElementById("btncerrar").focus();
}

function muuestraDatosRepetidos(param,valor){
	//alert(opcion+" : "+cantidad);	
	//div=param;
	 
	$("#"+param).append("<div><a href='detalleRecibo_2.php?opcion="+param+"&valor="+valor+"' target='_blank'>"+valor+"</a></div>");
	
	//url="detalleRecibo.php";
	//parametros="opcion="+param+"&valor="+valor;
	//metodo="GET";
	//ajaxAppVentana(div,url,parametros,metodo);
}
function gridReciboDimensiones(){
	var altoDocRecibo2=$(document).height();
	document.getElementById("gridCaptura").style.height=(altoDocRecibo2-270)+"px";
	//document.getElementById("bdCode").focus();
}
function armaGrid(bdCode,serial,imei){	
	contadorTxt+=1;
	//$("#div_grid").append("<br><input type='text' size='2' value='"+contadorTxt+"' /><input type='text' id='"+idCode+"' value='"+bdCode+"' size='60' /><input type='text' id='"+idImei+"' value='"+imei+"' /><input type='text' id='"+idSerial+"' value='"+serial+"' />");
	$("#gridCaptura").append("<div style='float:left;width:10px;background:#CCC;border:1px solid #CCC;height:15px;padding:4px;'>&nbsp;</div><div style='float:left;width:340px; height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+bdCode+"</div><div style='float:left;width:120px;height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+imei+"</div><div style='float:left;width:180px;height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+serial+"</div><div style='clear:both;'></div>");	
	
}
function armaGrid2(bdCode,imei,serial){
	//$("#gridCaptura").append("<div style='float:left;width:20px;background:#CCC;border:1px solid #CCC;height:15px;padding:4px;font-weight:bold;'>"+nvoContador+"</div><div style='float:left;width:340px; height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+bdCode+"</div><div style='float:left;width:120px;height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+imei+"</div><div style='float:left;width:180px;height:15px;border:1px solid #CCC;background:#FFF;padding:4px;'>"+serial+"</div><div style='clear:both;'></div>");
	var valoresFila=imei+",,,"+serial;	
	var idObsoleto="obsoleto"+serial;
	var idNoEnviar="noEnviar"+imei;
	var chkRecibo="chkRecibo"+imei;
	//identificadores para las cajas
	var idImeiLista="imeiLista"+imei;
	var idSerialLista="serialLista"+serial;
	var idBdCodeLista="bdcodeLista"+bdCode;
	$("#gridCaptura").append("<div class='estiloContador'>"+nvoContador+"</div><div class='estiloCheck'><input type='checkbox' name='chkRecibo' id='"+chkRecibo+"' value='"+valoresFila+"' checked='checked' /></div><input type='text' id='"+idImeiLista+"' class='estiloImei' value='"+imei+"' readonly='readonly'><input type='text' id='"+idSerialLista+"' class='estiloSerial' value='"+serial+"' readonly='readonly'><input type='text' id='"+idBdCodeLista+"' class='estiloBdCode' value='"+bdCode+"' readonly='readonly'><input type='text' id='"+idObsoleto+"' class='validacion1' value='S/V' readonly='readonly'><input type='text' id='"+idNoEnviar+"' class='validacion2' value='S/V' readonly='readonly'><div class='estiloContador'>"+nvoContador+"</div><div style='clear:both;'></div>");
	nvoContador+=1;
}
function cambiaVentana(div){
	switch(div){
		case "1":
			$("#detalleListadoRecibo").hide();
			$("#detalleRecibo").show();
			$("#recibo1").css("background","#666");
			$("#recibo2").css("background","#CCC");
		break;
		case "2":
			$("#detalleRecibo").hide();
			$("#detalleListadoRecibo").show();
			$("#recibo2").css("background","#CCC");
			$("#recibo1").css("background","#FFF");
		break;
	}
}
function consultaReporteReciboListado(){
	//se recuperan las 2 fechas
	cambiaVentana('2');
	$("#calendario3").hide();
	var fecha1=document.getElementById("dp-5").value;
	var fecha2=document.getElementById("dp-6").value;
	
	/*var txtModeloBusquedaRecibo=document.getElementById("txtModeloBusquedaRecibo").value;
	var txtImeiBusquedaRecibo=document.getElementById("txtImeiBusquedaRecibo").value;
	var txtSerialBusquedaRecibo=document.getElementById("txtSerialBusquedaRecibo").value;
	var txtBDCodeBusquedaRecibo=document.getElementById("txtBDCodeBusquedaRecibo").value;
	var txtLoteBusquedaRecibo=document.getElementById("txtLoteBusquedaRecibo").value;*/
	div="detalleListadoRecibo";
	url="controladorRecibo.php";
	parametros="action=buscarEquipos&fecha1="+fecha1+"&fecha2="+fecha2;
	metodo="POST";
	ajaxAppVentana(div,url,parametros,metodo);
}
function mostrarCalendarios(calendario){
	$("#"+calendario).show();
	if(calendario=="calendario2"){
		$("#tecnicosDesensambleReporte").html("");
		div="tecnicosDesensambleReporte";
		url="controlador.php";
		parametros="action=mostrarTecnicosDes";
		metodo="GET";
		ajaxAppVentanas(div,url,parametros,metodo);
	}else if(calendario=="calendario3"){
		$("#opcionesBusquedaRecibo").html("");
		div="opcionesBusquedaRecibo";
		url="controladorRecibo.php";
		parametros="action=opcionesBusquedaRecibo";
		metodo="GET";
		//alert(parametros);
		//ajaxAppVentana(div,url,parametros,metodo);
	}
}
function ocultaCalendarios(calendario){
	$("#"+calendario).hide();
}
function cerrar(){
	window.close()
}
function contador($modelo,$mov){
	var modelo=document.getElementById("modeloCaptura").value;
	var mov=document.getElementById("hdnmovimiento").value;
	
	div="infoCaptura";
	url="controladorRecibo.php";
	parametros="action=contador&modelo="+modelo+"&mov="+mov;
	metodo="GET";
	//alert(parametros);
	ajaxApp(div,url,parametros,metodo);
}
function mostrarEquipos(){
	div="detalleRecibo";
	ajaxApp(div,"../mod_almacen/modelo.php","ac=buscarEquipos","POST")
}
function listarDetalle(modelo,filtro,parametro){	
	ajaxApp('detalle',"../mod_almacen/modelo.php",'ac=listarDetalle&modelo='+modelo+'&filtro='+filtro+'&parametro='+parametro,"POST");
}
function exportarCajaInterna(evento){
	if(evento.which==13){
		//se recupera la caja de texto
		var txtCajaInternaExportar=document.getElementById("txtCajaInternaExportar").value;
		//alert(txtCajaInternaExportar);
		ajaxApp("archivoExportar","controladorRecibo.php","action=exportaCajaInterna&cajaInterna="+txtCajaInternaExportar,"POST");
	}
}
function validarNoEnviar(){
	var imeis="";
	var pos=0;
	var elementos="";

	for (var i=0;i<document.reciboEquipos.elements.length;i++){
		if (document.reciboEquipos.elements[i].type=="checkbox"){
			if (document.reciboEquipos.elements[i].checked){				
				if (elementos=="")
					elementos=elementos+document.reciboEquipos.elements[i].value;
				else
					elementos=elementos+",,,"+document.reciboEquipos.elements[i].value;
					
			}	
		}
	}

	var elementos=elementos.split(",,,");
		
	for(var i=0;i<elementos.length;i++){
		if(imeis==""){
			imeis=imeis+elementos[i];
		}else{
			imeis=imeis+","+elementos[pos+2];
			pos=pos+2;
		}
		if((elementos.length-2)==pos){
			break;
		}
	}
	//peticion ajax
	ajaxApp("msgReciboGrid","controladorRecibo.php","action=validarNoEnviar&imeis="+imeis,"POST");
	//se deshabilita el boton de Validacion de No Enviar
	$("#btnValidarNoEnviar").attr("disabled",true);
	//alert(imeis);
	/*imeis=imeis.split(",");
	for(var j=0;j<imeis.length;j++){
		document.writeln("<p>" + j + ": " + imeis[j] + "</p><br>");
	}*/
	
}
function validarObsoletos(){
	var series="";
	var pos=1;
	var elementos="";

	for (var i=0;i<document.reciboEquipos.elements.length;i++){
		if (document.reciboEquipos.elements[i].type=="checkbox"){
			if (document.reciboEquipos.elements[i].checked){				
				if (elementos=="")
					elementos=elementos+document.reciboEquipos.elements[i].value;
				else
					elementos=elementos+",,,"+document.reciboEquipos.elements[i].value;
					
			}	
		}
	}

	var elementos=elementos.split(",,,");
	for(var i=0;i<elementos.length;i++){
		if(series==""){
			series=series+elementos[pos];
		}else{
			series=series+","+elementos[pos+2];
			pos=pos+2;
		}
		if((elementos.length-1)==pos){
			break;
		}
	}
	//peticion ajax
	ajaxApp("msgReciboGrid","controladorRecibo.php","action=validarObsoletos&series="+series,"POST");
	//se deshabilita el boton de Validar Obsoletos
	$("#btnValidarObsoletos").attr("disabled",true);
	/*series=series.split(",");
	for(var j=0;j<series.length;j++){
		document.writeln("<p>" + j + ": " + series[j] + "</p><br>");
	}*/
}
function buscarValor(valor){
	try{
		//valor="001701086003710";
		//valor="364VJCB836";
		//alert(valor);
		var encontrado=false;
		for (var i=0;i<document.reciboEquipos.elements.length;i++){
			if (document.reciboEquipos.elements[i].type=="text"){
				if (document.reciboEquipos.elements[i].value){					
					var valorText=document.reciboEquipos.elements[i].value;//se recupera el valor del campo para poderlo comparar
					var idEncontrado="#"+document.reciboEquipos.elements[i].id;//se recupera el ID del elemento HTML									
					//alert("Valor: "+ valor + "\n\n" + "valorText: " + valorText + "\n\nID elememto: " + idEncontrado);
					if (valor==valorText){
						if(idEncontrado=="#txtImei" || idEncontrado=="#txtSerial" || idEncontrado=="#bdCode"){
					//		alert("entro 1");
							encontrado=false;	
						}else{
					//		alert("entro 2");
						$(idEncontrado).css("background","yellow");//se coloca un color indicativo de k se ha encontrado el elemento						
						alert("El valor "+ valor + " se encuentra capturado en la lista");//mensaje de error
						encontrado=true;
						}
					}									
				}	
			}
		}
		return encontrado;
	}catch(e){ alert("Error al ejecutar la busqueda");}
}
function cancelarCaptura(){
	//de aki sirve para llamar a la funcion para hacer un reset a los forms
	if(confirm("Realmente desea CANCELAR la captura actual?")){
		resetForm();
	}
}
function resetForm(){
	//se regresa el contador a 0
	nvoContador=1;
	//se vuelven a activar los botones
	$("#btnValidarNoEnviar").attr("disabled",false);
	$("#btnValidarObsoletos").attr("disabled",false);
	//se vacia el grid
	$("#gridCaptura").html("");
	//se borran los mensajes si existen
	$("#msgReciboGrid").html("");
	//se coloca el focus en el campo imei
	document.getElementById("txtImei").focus();	
}
function guardarGrid(){
	//se verifica si los botones ya se encuentran inactivos
	btnValidarObsoletos=$("#btnValidarObsoletos").attr("disabled");
	btnValidarNoEnviar=$("#btnValidarNoEnviar").attr("disabled");
	//se pregunta al usuario si se desea insertar la informacion en la base de datos
	
	if(confirm("Desea GUARDAR LA INFORMACION EN LA BASE DE DATOS")){
		if(btnValidarObsoletos==false){
			alert("Error debe de validar los Equipos Obsoletos");
		}else if(btnValidarNoEnviar==false){
			alert("Error debe de validar los Equipos Marcados como No enviar");
		}else{
			//recuperamos los valores
			var cantidad=document.getElementById("hdncantidad").value;	
			/************************************************************************/
			var modelo=document.getElementById("modeloCaptura").value;
			var usuario=document.getElementById("usuarioRecibe").value;
			var proceso=document.getElementById("procesoRadio").value;
			var lote=document.getElementById("loteRadio").value;
			var clave=document.getElementById("claveRadio").value;
			var bdCode=document.getElementById("bdCode").value;
			var serial=document.getElementById("txtSerial").value;
			var imei=document.getElementById("txtImei").value;
			var mov=document.getElementById("hdnmovimiento").value;
			var clasificacion=document.getElementById("clasificacion").value;
				
			//declaro banderas
			bandera1=true;
			bandera2=true;
				
			if(bdCode==""){
				bdCode=""
			}
				
			if(serial==""){
				serial="--";
			}else{
				if(serial.length==10){
					bandera1=true;
				}else{
					alert("Verifique la longitud del serial");
					bandera1=false;
					document.getElementById("txtSerial").focus();
				}
			}
				
			if(imei==""){
				imei="--";
			}else{
					//valor=document.getElementById("txtImei").value;		
				if(!isNaN(imei) && (imei.length==15) ) {
					bandera2=true;
					//alert("El campo Imei solo acepta numeros:");
					//return true;
				}else{
					bandera2=false;
					alert("Verifique los datos del Imei");
				}
			}
					
			if(bandera1==true && bandera2==true){
				//se recuperan los valores de los checks k son los equipos k ya kdan listos para subirse a la base de datos			
				var elementos="";
				for (var i=0;i<document.reciboEquipos.elements.length;i++){
					if (document.reciboEquipos.elements[i].type=="checkbox"){
						if (document.reciboEquipos.elements[i].checked){				
							if (elementos=="")
								elementos=elementos+document.reciboEquipos.elements[i].value;
							else
								elementos=elementos+",,,"+document.reciboEquipos.elements[i].value;
						
						}	
					}
				}			
				//elementos=elementos.split(",,,");
				
				
				div="msgReciboGrid";
				url="controladorRecibo.php";
				//parametros="action=registrarDatos&modelo="+modelo+"&usuarioRecibe="+usuario+"&proceso="+proceso+"&lote="+lote+"&clave="+clave+"&bdCode="+bdCode+"&serial="+serial+"&imei="+imei+"&mov="+mov+"&cantidad="+cantidad+"&elementos="+elementos;
				parametros="action=registrarDatos2&modelo="+modelo+"&usuarioRecibe="+usuario+"&proceso="+proceso+"&lote="+lote+"&clave="+clave+"&mov="+mov+"&cantidad="+cantidad+"&elementos="+elementos+"&clasificacion="+clasificacion;
				metodo="POST";
				//alert(parametros); 
				  
				ajaxAppVentana(div,url,parametros,metodo);
				// $("#infoCaptura").html("<div>Equipos Capturados</div><div>"+(noCapturados)+"</div>");
				document.getElementById("bdCode").value="";
				document.getElementById("txtSerial").value="";
				document.getElementById("txtImei").value="";
				//
				document.getElementById("txtImei").focus();
			}
		}
	}else{
		if(confirm("Desea CANCELAR la captura actual")){
			resetForm();
		}
	}
}
function buscarPorLote(){
	var txtBusquedaLote=$("#txtBusquedaLote").attr("value");
	//alert(txtBusquedaLote);
	ajaxApp("detalleReciboListadoBusqueda","controladorRecibo.php","action=buscarLote&lote="+txtBusquedaLote,"POST");
	/*vinculos*/
	$("#divOpcionesLinks").html("<p><a href='#' onclick='exportarListadoLote("+txtBusquedaLote+")' title='Exportar Listado'>Exportar Listado</a></p><p><a href='#'>Exportar Listado por Modelo</a></p>");
}
function verDetalleBusqueda(lote,modelo){
	ajaxApp("detalleReciboListadoBusqueda_izq","controladorRecibo.php","action=mostrarResumenBusqueda&lote="+lote+"&modelo="+modelo,"POST");
}
function PaginaBusquedaListado(nroPagina,lote,modelo){
	ajaxApp("detalleReciboListadoBusqueda_izq","controladorRecibo.php","action=mostrarResumenBusqueda&lote="+lote+"&modelo="+modelo+"&pag="+nroPagina,"POST");
}
function cerrarMensajeGuardado(){
	$("#desv").hide();
	$("#msgManttoProg").hide();
}