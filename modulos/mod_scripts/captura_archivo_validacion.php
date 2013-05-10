<?php
//se recibe el id de la agrupacion en la base de datos
if(!empty($_POST)){ 
	//print_r($_POST); //exit;
	if($_POST["ac"]=="subir_datos"){
//		set_time_limit(0);
		$m_renglones=explode('|_|',$_POST["datos"]);
		$link=conectarBd();
		$i=0;
		?><textarea style="width:98%; font-size:12px;" rows="10"><?php
		foreach($m_renglones as $renglonX){
			//echo "<br>$renglonX";
			$m_celdas=explode('@_@',$renglonX);
			//echo "<br>";	print_r($m_celdas);			
			$id_validacion=$_POST["id_validacion"];
			//se actualiza la validacion con el mensaje y se validan los datos de la captura			
			$sqlValida1="\nSELECT id,id_empaque from empaque_items where imei='".$m_celdas[0]."' AND serial='".$m_celdas[1]."' AND sim='".$m_celdas[2]."'";
			$sql="INSERT INTO empaque_validaciones_archivo (id_empaque_validaciones,imei,serial,sim,modelo,lote,mfgdate,nota) VALUES ('".$id_validacion."'.'".$m_celdas[0]."','".$m_celdas[1]."','".$m_celdas[2]."','".$m_celdas[3]."','".$m_celdas[4]."','".$m_celdas[5]."','".$m_celdas[6]."'); ";
			$resValida1=mysql_query($sqlValida1,$link);
			$rowValida1=mysql_fetch_array($resValida1);
			if(mysql_num_rows($resValida1)==0){				
				echo "\nVerificar imei: ".$m_celdas[0];
			}else{
				//se actualiza la informacion del imei
				$sqlActualiza="\nUPDATE empaque_items set statusValidacion='".$m_celdas[6]."',statusEntrega='OK' WHERE id='".$rowValida1["id"]."'";
				$resActualiza=mysql_query($sqlActualiza,$link);
				if(mysql_affected_rows() >= 1){
					echo "\nRegistro Actualizado";
					//modificacion se inserta el nuevo registro
					$sql="INSERT INTO empaque_validaciones_archivo (id_empaque_validaciones,imei,serial,sim,modelo,lote,mfgdate,nota) VALUES ('".$id_validacion."','".$m_celdas[0]."','".$m_celdas[1]."','".$m_celdas[2]."','".$m_celdas[3]."','".$m_celdas[4]."','".$m_celdas[5]."','".$m_celdas[6]."'); ";
					$res=mysql_query($sql,$link);
					if($res){
						echo "\nRegistro Insertado";
					}else{
						echo "Registro NO INSERTADO";
					}
				}else{
					echo "\nRegistro NO ACTUALIZADO";
				}
			}			
			$i+=1;
		}
		//echo "\nRegistros insertados: ".$i;;
		?></textarea><?php
	}
	exit;
}
	
	function conectarBd(){
		require("../../includes/config.inc.php");
		$link=mysql_connect($host,$usuario,$pass);
		if($link==false){
			echo "Error en la conexion a la base de datos";
		}else{
			mysql_select_db($db);
			return $link;
		}				
	}
?>
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Carga Din&aacute;mica del Archivo de Validacion Nextel !</title>
-->
<script language="javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<script language="javascript">
$(document).ready(function (){ 
	//alert("OK");
	//actualiza_alto_capas(); 
	$("#txt_captura_texto").attr("value","");
});
function actualiza_alto_capas(){
	/*
	var document_ancho=$("#div_main").width();
	var document_alto=$("#div_main").height();
	var alto_cuerpo=document_alto-40;
	var ancho_b=document_ancho-220;
	
	var alto_textarea=alto_cuerpo-30;
	$("#div_a").css("height",alto_cuerpo+"px");	
	$("#div_b").css("height",alto_cuerpo+"px");
	
	$("#div_b").css("width",ancho_b+"px");
	$("#txt_archivo_excel").css("width","180px");
	$("#txt_archivo_excel").css("height",alto_textarea+"px");	
	*/
}
window.onresize=actualiza_alto_capas;
function ajax(capa,datos,ocultar_capa){
	if (!(ocultar_capa==""||ocultar_capa==undefined||ocultar_capa==null)) { $("#"+ocultar_capa).hide(); }
	var url="<?=$_SERVER['PHP_SELF']?>";
	$.ajax({
		async:true, type: "POST", dataType: "html", contentType: "application/x-www-form-urlencoded",
		url:url, data:datos, 
		beforeSend:function(){ 
			$("#"+capa).show().html('<center>Procesando, espere un momento.</center>'); 
		},
		success:function(datos){ 
			$("#"+capa).show().html(datos); 
		},
		timeout:90000000,
		error:function() { $("#"+capa).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}

 
function dame_encabezado_tabla(){
	var mi_html='<table align="left" width="98%" cellpadding="1" cellspacing="0" class="tableValidacion">';
	mi_html+='<tr>';
		mi_html+='<th class="th1">Imei</th>';
		mi_html+='<th class="th1">Serie</th>';
		mi_html+='<th class="th1">Sim</th>';
		mi_html+='<th class="th1">Modelo</th>';
		mi_html+='<th class="th1">Folio</th>';
		mi_html+='<th class="th1">Fecha</th>';
		mi_html+='<th class="th1">NOTA</th>';
	mi_html+='</tr>';
	mi_html+='</table>';	
	return mi_html;
}
function dame_encabezado_tabla2(){
	var mi_html='';
	mi_html+='<tr>';
		mi_html+='<th class="th1">ID Validacion</th>';
		mi_html+='<th class="th1">Imei</th>';
		mi_html+='<th class="th1">Serie</th>';
		mi_html+='<th class="th1">Sim</th>';
		mi_html+='<th class="th1">Modelo</th>';
		mi_html+='<th class="th1">Folio</th>';
		mi_html+='<th class="th1">Fecha</th>';
		mi_html+='<th class="th1">Nota</th>';
		mi_html+='</tr>';
	return mi_html;
}
function dame_datos_textarea_x(){
	var valores=$("#txt_captura_texto").attr("value");
	if(valores==''||valores==' '||valores==undefined||valores==null){
		alert("Pegue los datos en el area de captura.");
		return '';
	}
	var post_value='';
	var nuevos_valores='';
		nuevos_valores=valores.replace(/\n/gi,'|_|');
		nuevos_valores=nuevos_valores.replace(' ','');
		nuevos_valores=nuevos_valores.replace(/\r/gi,'|_|');			
	
	var valores_renglones=nuevos_valores.split("|_|");
	var valores_renglon_celdas;
	var valores_renglon_celdas_matriz;
	
	for(var i=0;i<valores_renglones.length;i++){
		valores_renglon_celdas=valores_renglones[i];
		valores_renglon_celdas=valores_renglon_celdas.replace(/\t/gi,'@_@');
		valores_renglon_celdas=valores_renglon_celdas.replace(' ','');
		valores_renglon_celdas=valores_renglon_celdas.replace(',',' ');
		valores_renglon_celdas=valores_renglon_celdas.replace(';',' ');
		if(!(valores_renglon_celdas==''||valores_renglon_celdas==' '||valores_renglon_celdas==undefined||valores_renglon_celdas==null)){
			(post_value=='')?post_value=valores_renglon_celdas:post_value+='|_|'+valores_renglon_celdas;
		}
	}
	//alert(post_value);
	return post_value;
}
function vista_previa(){
	var datos_txt=dame_datos_textarea_x();
	//var lote=$("#txt_id_lote0").val();
	if(datos_txt==''||datos_txt==' '||datos_txt==undefined||datos_txt==null){
		alert("Pegue los datos en el area de captura.");
		return;
	}
	
	var valores_renglones=datos_txt.split("|_|");
		var valores_renglon_celdas_matriz;
		var tr_mi_renglon;
	$("#div_area").html('<table align="center" id="tbl_01" border="0" cellpadding="2" cellspacing="0"></table>');
	$("#tbl_01").append(dame_encabezado_tabla2());	
	for(var i=0;i<valores_renglones.length;i++){
		//alert(valores_renglones[i]);
		
		valores_renglon_celdas_matriz=valores_renglones[i].split("@_@");
		tr_mi_renglon='<tr class="tr_hover"><td class="td1">'+<?=$_GET["id_validacion"];?>+'</td>';
		for(var i2=0;i2<valores_renglon_celdas_matriz.length;i2++){
			tr_mi_renglon+='<td class="td1">'+valores_renglon_celdas_matriz[i2]+'</td>';
		}
		tr_mi_renglon+='</tr>';
		$("#tbl_01").append(tr_mi_renglon);
	}
}
function subir_datos(){
	var datos_txt=dame_datos_textarea_x();
	//var lote=$("#txt_id_lote0").val();
	if(datos_txt==''||datos_txt==' '||datos_txt==undefined||datos_txt==null){
		alert("Pegue los datos en el area de captura.");
		return;
	}
	/*if(lote==''||lote==' '||lote==undefined||lote==null){
		alert("Capture el Lote.");
		return;
	}*/
	var datos_x="ac=subir_datos&id_validacion="+<?=$_GET["id_validacion"];?>+"&datos="+datos_txt;
	//alert(datos_x);
	if(confirm("Desea subir la información ? ")) ajax('div_resultados',datos_x);
}
</script>
<style type="text/css">
#txt_captura_texto{ width:98%; height:80px; }
.tableValidacion{ border-left:#ccc 1px solid; border-top:#ccc 1px solid; margin-top:5px; margin-bottom:5px; }
.th1{ border-right:#ccc 1px solid; border-bottom:#ccc 1px solid; background-color:#efefef; font-weight:bold; text-align:center; }
.td1{ border-right:#ccc 1px solid; border-bottom:#ccc 1px solid;text-align:center; }
.tr_hover:hover{ background-color:#efefcc;}

</style>
<!--</head>
<body>-->
<div id="div_main">
	<div id="div_encabezado" style="background:#000; color:#FFF; height:20px; padding:7px; font-size:12px;">Carga Din&aacute;mica para la validaci&oacute;n: <?=$_GET["id_validacion"];?></div>
	<div id="div_menu" style="border:1px solid #CCC; background:#f0f0f0; height:25px; padding:7px;">
		<a href="#" onClick="vista_previa()" style="font-size:12px; color:#03F;">Vista previa</a> | 
		<a href="#" onClick="subir_datos()" style="font-size:12px; color:#03F;">Subir Datos</a>
	</div>
	<div id="div_captura"><br />		
		Copie los datos de la siguiente manera :		
		<div id="div_tabla_encabezado1"></div>
		<script type="text/javascript"> $("#div_tabla_encabezado1").html(dame_encabezado_tabla()); </script>
		<textarea id="txt_captura_texto"></textarea>
	</div>
	<div id="div_resultados"></div>
	<div id="div_area"></div>
</div>
<!--</body>
</html>-->

