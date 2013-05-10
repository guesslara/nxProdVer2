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
function mostrarFormulario(){
	ajaxApp("ventanaEnsambleContenido","controladorEnsamble.php","action=mostrarFormulario","POST");
}
function busquedaAvanzada(){
	//recuperacion de los valores
	var id_modelo=$("#id_modelo").val();
	var imei=$("#imei").val();
	var serial=$("#serial").val();
	var sim=$("#sim").val();
	var lote=$("#lote").val();
	var mfgdate=$("#mfgdate").val();
	var status=$("#status").val();
	var statusProceso=$("#statusProceso").val();
	var statusDesensamble=$("#statusDesensamble").val();
	var statusDiagnostico=$("#statusDiagnostico").val();
	var statusAlmacen=$("#statusAlmacen").val();
	var statusIngenieria=$("#statusIngenieria").val();
	var statusEmpaque=$("#statusEmpaque").val();
	var lineaEnsamble=$("#lineaEnsamble").val();
	var f_recibo=$("#f_recibo").val();
	var f_recibo2=$("#f_recibo2").val();
	var num_movimiento=$("#num_movimiento").val();
	var tipoEquipo=$("#tipoEquipo").val();
	var flexNuevo=$("#flexNuevo").val();
	/*divs*/
	$("#ventanaEnsambleContenido").hide();//se oculta el primer div	
	$("#ventanaEnsambleContenido2").show();//se muestra el segundo div
	var valores="action=buscar&f_recibo="+f_recibo+"&fecha2="+f_recibo2+"&id_modelo="+id_modelo+"&imei="+imei+"&serial="+serial+"&sim="+sim+"&lote="+lote+"&mfgdate="+mfgdate+"&status="+status+"&statusProceso="+statusProceso+"&statusDesensamble="+statusDesensamble+"&statusDiagnostico="+statusDiagnostico+"&statusAlmacen="+statusAlmacen+"&statusIngenieria="+statusIngenieria+"&statusEmpaque="+statusEmpaque+"&lineaEnsamble="+lineaEnsamble+"&num_movimiento="+num_movimiento+"&tipoEquipo="+tipoEquipo+"&flexNuevo="+flexNuevo;
	//alert(valores);parametrosConsulta
	ajaxApp("ventanaEnsambleContenido2","controladorEnsamble.php",valores,"POST")
}
function mostrarFormDatos(){
	$("#ventanaEnsambleContenido").show();//se oculta el primer div	
	$("#ventanaEnsambleContenido2").hide();//se muestra el segundo div
}
function PaginaBusquedaAvanzada(pag){
	var id_modelo=$("#id_modelo").val();
	var imei=$("#imei").val();
	var serial=$("#serial").val();
	var sim=$("#sim").val();
	var lote=$("#lote").val();
	var mfgdate=$("#mfgdate").val();
	var status=$("#status").val();
	var statusProceso=$("#statusProceso").val();
	var statusDesensamble=$("#statusDesensamble").val();
	var statusDiagnostico=$("#statusDiagnostico").val();
	var statusAlmacen=$("#statusAlmacen").val();
	var statusIngenieria=$("#statusIngenieria").val();
	var statusEmpaque=$("#statusEmpaque").val();
	var lineaEnsamble=$("#lineaEnsamble").val();
	var f_recibo=$("#f_recibo").val();
	var f_recibo2=$("#f_recibo2").val();
	var num_movimiento=$("#num_movimiento").val();
	var tipoEquipo=$("#tipoEquipo").val();
	var flexNuevo=$("#flexNuevo").val();
	/*divs*/
	//$("#ventanaEnsambleContenido").hide();//se oculta el primer div	
	$("#ventanaEnsambleContenido2").show();//se muestra el segundo div
	var valores="action=buscar&f_recibo="+f_recibo+"&fecha2="+f_recibo2+"&id_modelo="+id_modelo+"&imei="+imei+"&serial="+serial+"&sim="+sim+"&lote="+lote+"&mfgdate="+mfgdate+"&status="+status+"&statusProceso="+statusProceso+"&statusDesensamble="+statusDesensamble+"&statusDiagnostico="+statusDiagnostico+"&statusAlmacen="+statusAlmacen+"&statusIngenieria="+statusIngenieria+"&statusEmpaque="+statusEmpaque+"&lineaEnsamble="+lineaEnsamble+"&num_movimiento="+num_movimiento+"&tipoEquipo="+tipoEquipo+"&flexNuevo="+flexNuevo+"&pag="+pag;
	//alert(valores);parametrosConsulta
	ajaxApp("ventanaEnsambleContenido2","controladorEnsamble.php",valores,"POST")
}