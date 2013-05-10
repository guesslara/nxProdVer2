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
function verificaTeclaImeiEnsamble(evento,caja){
	if(evento.which==13 && caja=="1984"){
		buscarRegistros();
	}
}
function buscarRegistros(){
	var imeiEnsamble=document.getElementById("txtImeiEnsamble").value;
	//alert(imeiEnsamble);
	if(imeiEnsamble=="" || imeiEnsamble==null){
		alert("Introduzca un numero de Imei valido.");
	}else{
		ajaxApp("divModificacion","controladorEnsamble.php","action=buscarEquipo&imei="+imeiEnsamble,"POST");
	}
	document.getElementById("txtImeiEnsamble").value="";
}
function verificaTeclaImeiM(evento,caja){
	if(evento.which==13 && caja==1){
		document.getElementById("txtSerial").focus();
	}
}
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
	var sim=document.getElementById("txtSim").value;
	var clave=document.getElementById("txtClave").value;	
	var status=document.getElementById("txtStatus").value;
	//se verifica si algun valor de los combos fue cambiado ya que los demas campos son textos
	if($("#txtProceso").val()=="Seleccione"){
		statusProceso=document.getElementById("txtProceso1").value;
	}else{
		statusProceso=document.getElementById("txtProceso").value;
	}
	if($("#txtDesensamble").val()=="Seleccione"){
		statusDesensamble=document.getElementById("txtDesensamble1").value;
	}else{
		statusDesensamble=document.getElementById("txtDesensamble").value;
	}
	if($("#txtDiagnostico").val()=="Seleccione"){
		statusDiagnostico=document.getElementById("txtDiagnostico1").value;
	}else{
		statusDiagnostico=document.getElementById("txtDiagnostico").value;
	}
	if($("#txtAlmacen").val()=="Seleccione"){
		statusAlmacen=document.getElementById("txtAlmacen1").value;
	}else{
		statusAlmacen=document.getElementById("txtAlmacen").value;
	}
	if($("#txtIngenieria").val()=="Seleccione"){
		statusIngenieria=document.getElementById("txtIngenieria1").value;
	}else{
		statusIngenieria=document.getElementById("txtIngenieria").value;
	}
	if($("#txtEmpaque").val()=="Seleccione"){
		statusEmpaque=document.getElementById("txtEmpaque1").value;
	}else{
		statusEmpaque=document.getElementById("txtEmpaque").value;
	}
	if($("#txtStatusIQ").val()=="Seleccione"){
		statusIQ=document.getElementById("txtIq1").value;
	}else{
		statusIQ=document.getElementById("txtStatusIQ").value;
	}
	
	var id=document.getElementById("txtId").value;	
	
	var parametros="action=actualizaReg&imei="+imei+"&serial="+serial+"&lote="+lote+"&sim="+sim+"&clave="+clave+"&status="+status+"&statusProceso="+statusProceso+"&statusDesensamble="+statusDesensamble+"&statusDiagnostico="+statusDiagnostico+"&statusAlmacen="+statusAlmacen+"&statusIngenieria="+statusIngenieria+"&statusEmpaque="+statusEmpaque+"&statusIQ="+statusIQ+"&id="+id;
	//alert(parametros);
	//ajaxApp("agregado","controladorEnsamble.php",parametros,"POST");
	return parametros;
}
function accionesVentana(div,opc){
	var id=document.getElementById("txtId").value;
	if(id==""){
		alert("Error, verifique la informacion Introducida");
	}else{
		if(opc==0){
			$("#ventanaDialogo").hide();
			$("#transparenciaGeneral").hide();
		}else if(opc==1){
			$("#txtUsuarioMod").attr("value","");
			$("#txtPassMod").attr("value","");
			$("#"+div).show();
			$("#transparenciaGeneral").show();
			$("#txtUsuarioMod").focus();
		}
	}
}
function verificaUsuario(){
	var id=document.getElementById("txtId").value;
	if(id==""){
		alert("Error, verifique la informacion Introducida");
	}else{
		var usuarioMod=document.getElementById('txtUsuarioMod').value;
		var passmod=document.getElementById('txtPassMod').value;
		if((usuarioMod=='') || (usuarioMod==null) || (passmod=='')){
			alert('Escriba su nombre de usuario y password para poder continuar');
		}else{
			$("#ventanaDialogo").hide();
			$("#transparenciaGeneral").hide();
			var parametros=actualizaRegistro();
			parametros=parametros+"&usuarioMod="+usuarioMod+"&passMod="+passmod;
			ajaxApp('detalleVistaPrevia','controladorEnsamble.php',parametros,'POST');
		}
	}
}