// JavaScript Document
//var elemMenu;
var herrMenu;
function vMantto(){
	$("#desv").show();
	$.ajax({
	async:true,
	type: "GET",
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:"funcionesMain.php",
	data:"action=verificaMantto",
	beforeSend:function(){ 
		$("#cargadorApp").show().html('<img src="../img/cargador (2).gif">'); 
	},
	success:function(datos){
		$("#cargadorApp").html("Listo");
		$("#verificaMantto").show().html(datos);
	},
	timeout:90000000,
	error:function() { $("#verificaMantto").show().html('Error: El sistema no puede localizar los archivos necesarios.'); }
	});
}
function vActNuevas(){
	$("#desv").show();
	$.ajax({
	async:true,
	type: "POST",
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:"funcionesMain.php",
	data:"action=verificaActNuevas",
	beforeSend:function(){ 
		$("#cargadorApp").show().html('<img src="../img/cargador (2).gif">'); 
	},
	success:function(datos){
		$("#cargadorApp").html("Listo");
		$("#numeroActualizacionesActuales").show().html(datos);
	},
	timeout:90000000,
	error:function() { $("#verificaMantto").show().html('Error: El sistema no puede localizar los archivos necesarios.'); }
	});
}
function vActSistema(){
	/*div="msgNuevasReqs";
	url="funcionesMostrar2.php";
	parametros="action=buscarNuevas";
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);*/
	$("#desv").show();
	$.ajax({
	async:true,
	type: "POST",
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:"funcionesMostrar2.php",
	data:"action=buscarNuevas",
	beforeSend:function(){ 
		$("#cargadorApp").show().html('<img src="../img/cargador (2).gif">'); 
	},
	success:function(datos){
		$("#cargadorApp").html("Listo");
		$("#divActSistema").show().html(datos);
	},
	timeout:90000000,
	error:function() { $("#divActSistema").show().html('Error: El sistema no puede localizar los archivos necesarios.'); }
	});
}
function verificaTeclaImeiBusquedaPrincipal(evento){
	if(evento.which==13){
		var imei=$("#txtBusquedaImeiPrincipal").attr("value");
		$("#txtBusquedaImeiPrincipal").attr("value","");
		$("#divBusquedaPrincipal").show();
		var filtro=$("input[name='filtroBusqueda']:checked").val();
		//alert(imei+" "+filtro);
		ajaxApp("divResultadosBusquedaPrincipal","mod_busqueda/controladorEnsamble.php","action=buscarEquipo&imei="+imei+"&filtro="+filtro,"POST");		
	}
}
function mostrarBuscadorEquipos(){
	$("#buscadorEquiposUI").show();
	$("#txtBusquedaImeiPrincipal").focus();
}
function cerrarBusquedaPrincipal(){
	//divVentanaFlotanteFuncional
	$("#buscadorEquiposUI").hide();
}
function ajaxApp(divDestino,url,parametros,metodo){	
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		$("#"+divDestino).show().html("<br><br><br><br>Buscando en Base de Datos"); 
	},
	success:function(datos){ 		
		$("#"+divDestino).show().html(datos);		
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}
function mostrarFiltros(){
	$("#filtrosBusqueda").show();	
}
function cerrarFiltros(){
	$("#filtrosBusqueda").hide();
}
function abrirFormBug(){
	$("#frmContenedorBug").show();
	ajaxApp("divFormularioBug","funcionesMain.php","action=mostrarFormBug","GET");
}
function cerrarFormbug(){
	$("#frmContenedorBug").hide	();
}
function enviarInfo(){
	var mensaje=$("#txtDes").val();
	if(mensaje != ""){
		ajaxApp("divFormularioBug","funcionesMain.php","action=guardarFormBug&mensaje="+mensaje,"POST");	
	}else{
		alert("Escriba una descripcion breve de su problema.");	
	}	
}
function mostrarPerfilUsuario(){
	//alert("Perfil");
	ajaxApp("cargaPerfil","funcionesMain.php","action=verPerfil","POST");
}
function vSesion(){
	ajaxApp("session","vSesion.php","","POST");
}