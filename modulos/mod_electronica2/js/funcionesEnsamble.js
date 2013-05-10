// JavaScript Document
var contadorGrid=0;
var contadorCajasWip2=0;
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
function mostrarAdvertenciaCaptura(){	
	var linea=$("#cboLinea").val();
	if(linea=="Selecciona"){
		alert("Error:\n\n Puede deberse a las siguientes causas:\n\n Seleccione un linea.");
	}else{
		$("#opcionFormFlex").show();
		$("#btnFormFlexNo").focus();
	}
}
function mostrarTab(div){
	if(div=="ventanaEnsambleContenido"){
		$("#ventanaEnsambleContenido").show();
		$("#ventanaEnsambleContenido2").hide();
		$("#ventanaEnsambleContenido2").html("");
		$("#ventanaEnsambleContenido").html("");
		iniciarCaptura();
	}else if(div=="ventanaEnsambleContenido2"){
		$("#ventanaEnsambleContenido2").show();	$("#ventanaEnsambleContenido").hide();  $("#ventanaEnsambleContenido").html("");
		$("#ventanaEnsambleContenido2").html("<div class='divContenedorImeiCatalogoFallas'><div class='divImeiCatalogoFallas'>Imei</div><div class='divImeiCatalogoFallas'>Resultado</div></div><div id='listadoImeisAReparacion'></div>");
		//$("#ventanaEnsambleContenido2").html("");
		configurarScrap();
		cargaCatalogoFallas();//agregarElementoWip2();
	}
}
function iniciarCaptura(){
	//$("#ventanaEnsambleContenido").html("");
	resetDatosScriptGrid();
	proceso=document.getElementById("txtProcesoDiagnostico").value;
	usrDiagnostico=document.getElementById("txtIdUsuarioDiagnostico").value;
	filtro="OK";
	$("#infoLineaCaptura").html("");
	$("#infoLineaCaptura").html("Capturando OK");
	if(proceso=="" || usrDiagnostico==""){
		alert("Error:\n\n Puede deberse a las siguientes causas:\n\n Sesion no Valida, ingrese de nueva cuenta al Sistema.");
	}else{
		/*se configura el grid*/
		nombresColumnas=new Array("Imei","Mensaje");
		cargaInicial(2,"ventanaEnsambleContenido","controladorEnsamble.php","action=actualizaDatosActualizaDatosDiagnostico&usrDiagnostico="+usrDiagnostico+"&proceso="+proceso+"&filtro="+filtro,"erroresCaptura",nombresColumnas);
		inicio();
		$("#txt_0").focus();
		$("#txt_0").removeClass("datoListado");
		$("#txt_0").addClass("elementoFocus");
	}
}
function configurarScrap(){
	//$("#ventanaEnsambleContenido").html("");
	resetDatosScriptGrid();
	proceso=document.getElementById("txtProcesoDiagnostico").value;
	usrDiagnostico=document.getElementById("txtIdUsuarioDiagnostico").value;
	filtro="SCRAP";
	$("#infoLineaCaptura").html("");
	$("#infoLineaCaptura").html("<div style='color:red;'>Capturando WIP2</div>");
	if(proceso=="" || usrDiagnostico==""){
		alert("Error:\n\n Puede deberse a las siguientes causas:\n\n Sesion no Valida, ingrese de nueva cuenta al Sistema.");
	}else{
		/*se configura el grid*/
		/*
		nombresColumnas=new Array("Imei SCRAP","Mensaje");
		cargaInicial(2,"ventanaEnsambleContenido2","controladorEnsamble.php","action=actualizaDatosActualizaDatosDiagnostico&usrDiagnostico="+usrDiagnostico+"&proceso="+proceso+"&filtro="+filtro,"erroresCaptura",nombresColumnas);
		inicio();
		$("#txt_0").focus();
		$("#txt_0").removeClass("datoListado");
		$("#txt_0").addClass("elementoFocus");
		*/
		agregarElementoWip2();
	}
}
function cargaCatalogoFallas(){
	//$("#opcionFormFlex").show();
	ajaxApp("divCatalogoFallas","controladorEnsamble.php","action=cargaCatalogoFallas","POST");	
}
function agregarElementoWip2(){
	idElementoImeiWip2="txtImeiWip2_"+contadorCajasWip2;
	idElementoImeiWip2Res="txtImeiWip2Res"+contadorCajasWip2;
	idListadoFallas="listadoCatalogoFallas_"+contadorCajasWip2;
	idElementoResWip2="txtResWip2_"+contadorCajasWip2;
	elemento="<input type='text' name='"+idElementoImeiWip2+"' id='"+idElementoImeiWip2+"' onkeypress=\"recuperaValorWIP2('"+contadorCajasWip2+"',event)\" /><input type='text' name='"+idElementoImeiWip2Res+"' id='"+idElementoImeiWip2Res+"' readonly='readonly' />";
		
	elemento+="<div class='divSeparador'></div>";
	$("#listadoImeisAReparacion").append(elemento);
	$("#"+idElementoImeiWip2).focus();
	contadorCajasWip2+=1;
	for (var i=0;i<document.frmCatalogoFallas.elements.length;i++){
		if (document.frmCatalogoFallas.elements[i].type=="checkbox"){
			if (document.frmCatalogoFallas.elements[i].checked){				
				document.frmCatalogoFallas.elements[i].checked=document.frmCatalogoFallas.elements[i].checked=0;				
			}	
		}
	}
}
function recuperaValorWIP2(indiceCajaActual,evento){
	if(evento.which==13){
		idElementoImeiWip2="txtImeiWip2_"+indiceCajaActual;
		idElementoImeiWip2Res="txtImeiWip2Res"+indiceCajaActual;
		//se recupera el valor de la caja
		valor=$("#"+idElementoImeiWip2).val();
		//se muestra el catalogo de fallas		
		$("#opcionFormFlex").show();
		$("#"+idElementoImeiWip2Res).attr("value",valor);
		$("#txtImeiWip2").attr("value",valor);
		$("#txtImeiWip2Res").attr("value",idElementoImeiWip2Res);		
	}
}
function guardaDatosWip2(){
	var fallas="";
	for (var i=0;i<document.frmCatalogoFallas.elements.length;i++){
		if (document.frmCatalogoFallas.elements[i].type=="checkbox"){
			if (document.frmCatalogoFallas.elements[i].checked){
				//alert("Variable claves=["+claves+"]");
				if (fallas=="")
					fallas=fallas+document.frmCatalogoFallas.elements[i].value;
				else
					fallas=fallas+","+document.frmCatalogoFallas.elements[i].value;
			}	
		}
	}	
	if(fallas==""){
		alert("Error: Debe seleccionar una Falla del Catalogo");
	}else{		
		$("#opcionFormFlex").hide();
		txtImeiWip2=$("#txtImeiWip2").val();
		txtImeiWip2Res=$("#txtImeiWip2Res").val();
		var procesoDiagnostico=$("#txtProcesoDiagnostico").val();
		var idUsuarioProceso=$("#txtIdUsuarioDiagnostico").val();
		var parametros="action=guardarDiagWip2&txtImeiWip2="+txtImeiWip2+"&fallas="+fallas+"&cajaRespuesta="+txtImeiWip2Res+"&procesoDiagnostico="+procesoDiagnostico+"&idUsuarioProceso="+idUsuarioProceso;		
		//alert(parametros);
		ajaxApp("erroresCaptura","controladorEnsamble.php",parametros,"POST");
		agregarElementoWip2();
	}
}
function cerrarCatalogoFallas(){
	$("#opcionFormFlex").hide();
	for (var i=0;i<document.frmCatalogoFallas.elements.length;i++){
		if (document.frmCatalogoFallas.elements[i].type=="checkbox"){
			if (document.frmCatalogoFallas.elements[i].checked){				
				document.frmCatalogoFallas.elements[i].checked=document.frmCatalogoFallas.elements[i].checked=0;				
			}	
		}
	}
}
function contarEquiposDiag(){	
	ajaxApp("infoEquiposDiag","controladorEnsamble.php","action=contarDiag","POST");
}
