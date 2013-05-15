// JavaScript Document
function ajaxApp(divDestino,url,parametros,metodo){	
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		$("#"+divDestino).show().html("<p>Cargando...</p>"); 
	},
	success:function(datos){	
		$("#cargadorApp").hide();
		$("#"+divDestino).show().html(datos);
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}
function calendarizacionMes(mes,anio,diaActual){
	div="calendarizacionMes";
	url="controlador.php";
	parametros="action=calendarizacion&mes="+mes+"&anio="+anio+"&diaActual="+diaActual;
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);
}
function resumen(mes,anio,diaActual){
	div="resumen";
	url="controlador.php";
	parametros="action=resumen&mes="+mes+"&anio="+anio+"&diaActual="+diaActual;
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);
}
function resumenStatus(mes,anio,diaActual){
	div="resumen";
	url="controlador.php";
	parametros="action=resumenStatus&mes="+mes+"&anio="+anio+"&diaActual="+diaActual;
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);
}
function verResumen(status,modelo,tipoStatus){
	ajaxApp("resumenStatus","controlador.php","action=mostrarResumen&status="+status+"&modelo="+modelo+"&tipoStatus="+tipoStatus,"POST");
}
function verResumenstatus(status,div){
	ajaxApp(div,"controlador.php","action=mostrarResumenStatus&status="+status+"&div="+div,"POST");
}
function verResumenStatusProceso(status,div){
	ajaxApp(div,"controlador.php","action=mostrarResumenStatusProceso&status="+status+"&div="+div,"POST");
}
function Pagina(nroPagina,status,modelo,tipoStatus){
	ajaxApp("resumenStatus","controlador.php","action=mostrarResumen&status="+status+"&pag="+nroPagina+"&modelo="+modelo+"&tipoStatus="+tipoStatus,"POST");
}
function cerrarDiv(div){
	$("#"+div).hide();
}
function mostrarLotes(){
	ajaxApp("resumen","controlador.php","action=mostrarLotes","POST");
}
function verDetalleLote(lote,div){
	ajaxApp(div,"controlador.php","action=verLote&lote="+lote,"POST");
}
function verResumenLoteModelo(lote,idModelo){
	ajaxApp("resumenStatus","controlador.php","action=verResumenLoteModelo&lote="+lote+"&modelo="+idModelo,"POST");
}
function enviadoFolio(){
	ajaxApp("resumen","controlador.php","action=verResumenEnviadoFolio","POST");
}
function verResumenEnviadosModelos(folio,divEnviado){
	ajaxApp(divEnviado,"controlador.php","action=verModeloEnviadosFolio&folio="+folio+"&idEnviado="+divEnviado,"POST")
}
function cerrarDivModeloEnviadosFolio(divEnviado){
	$("#"+divEnviado).hide();
}
function verResumenEnviadosFolioDetalle(folio,modelo,filtro){
	ajaxApp("resumenStatus","controlador.php","action=verResumenEnviadosFolioDetalle&folio="+folio+"&modelo="+modelo+"&filtro="+filtro,"POST");
}
function PaginaResumenEnviadosFolio(nroPagina,folio,modelo,filtro){
	ajaxApp("resumenStatus","controlador.php","action=verResumenEnviadosFolioDetalle&folio="+folio+"&modelo="+modelo+"&filtro="+filtro+"&pag="+nroPagina,"POST");
}