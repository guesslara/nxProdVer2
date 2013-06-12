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
function nuevoCI(){
	ajaxApp("detalleEmpaque","controladorEnsamble.php","action=mostrarForm","POST");
}
function buscarImei(evento,cajaBusqueda){
	if(evento.which==13){
		if(cajaBusqueda=="Donante"){
			var imei=$("#txtImeiDonante").val(); //se recupera el imei y se busca en la base de datos
			var div="divDonante";
		}else if(cajaBusqueda=="Receptor"){
			var imei=$("#txtImeiReceptor").val(); //se recupera el imei y se busca en la base de datos
			var div="divReceptor";
		}
		
		ajaxApp(div,"controladorEnsamble.php","action=buscarDonante&imei="+imei+"&div="+div,"POST");
	}
}
function guardarCI(){
	var usuario=$("#txtIdUsuarioEmpaque").val();
	var imeiDonante=$("#txtIdRadioDonante").val();
	var imeiReceptor=$("#txtIdRadioReceptor").val();
	var observaciones=$("#txtObservaciones").val();
	
	//alert("Usuario "+usuario+"\n\nImei Donante "+imeiDonante+"\n\nImei Receptor "+imeiReceptor+"\n\nObservaciones "+observaciones);
	parametros="action=guardarCI&usuario="+usuario+"&imeiDonante="+imeiDonante+"&imeiReceptor="+imeiReceptor+"&observaciones="+observaciones;
	ajaxApp("divGuardadoCI","controladorEnsamble.php",parametros,"POST");
}