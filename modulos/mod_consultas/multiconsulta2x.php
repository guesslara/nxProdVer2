<?php
if(!empty($_POST)){ 
	//print_r($_POST); exit;
	
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
	
	
	if($_POST["action"]=="buscar_nds_xxx"){
		$link=conectarBd();
		$m_indices=explode(",",$_POST["m_indices"]);
		$m_data=explode(",",$_POST["data"]);
		$sql_in=str_replace(",","','",$_POST["data"]);
		//echo "<br>".
		$sql="select id_radio,modelo,imei,serial,sim,lote,status,status_nextel 
			from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo 
			where imei in ('$sql_in') ORDER by imei DESC";	
		if ($res=mysql_query($sql,$link)){
			$ndr=mysql_num_rows($res);
			echo "<div align='center'>$ndr resultado(s).</div>";
			if ($ndr>0){
				?><script language="javascript">
					crear_tabla(); 
					$("#tbl_01").show();
				</script><?php
				
				while($reg=mysql_fetch_array($res)){
					$sqlNoEnviar="select * from equipos_no_enviar where imei='".$reg["imei"]."'";
					$resNoEnviar=mysql_query($sqlNoEnviar,$link);
					$rowNoEnviar=mysql_fetch_array($resNoEnviar);
					if($rowNoEnviar['imei']==""){
						$noEnviar="Verificado";
					}else{
						$noEnviar="No Enviar";
					}
?>
					<script language="javascript">
					agregar_filaX('<?=$reg["id_radio"]?>','<?=$reg["modelo"]?>','<?=$reg["imei"]?>','<?=$reg["serial"]?>','<?=$reg["sim"]?>','<?=$reg["lote"]?>','<?=$reg["status"]?>','<?=$noEnviar?>');
					</script><?php
					
				}
			}else{ /*echo "<div>sin resultados ";*/ }
		} else{ echo "<br>Error SQL (".mysql_error($link).")."; exit;	}			
	}
	if($_POST["action"]=="buscar_nds"){
		$m_indices=explode(",",$_POST["m_indices"]);
		$m_data=explode(",",$_POST["data"]);
		$sql_in=str_replace(",","','",$_POST["data"]);
		//echo "<br>".
		$sql="SELECT 
			reg_lote1.id_lote0, reg_lote1.producto_modelo,
			cat_modelos.modelo AS dmodelo,
			reg_lote2.id_lote2, reg_lote2.a, reg_lote2.activo, 
			reg_ot.id_ot, reg_ot.status0, reg_ot.status1, reg_ot.obs  
		FROM reg_lote1, reg_lote2, reg_ot, cat_modelos
		WHERE 
			cat_modelos.id_modelo=reg_lote1.producto_modelo
			AND reg_lote1.id_lote1=reg_lote2.id_lote1
			AND reg_lote2.id_lote2=reg_ot.items
			AND reg_lote2.a IN ('$sql_in')
		;";
		if ($res=mysql_query($sql,$link)){
			$ndr=mysql_num_rows($res);
			echo "<div align='center'>$ndr resultado(s).</div>";
			if ($ndr>0){
				while($reg=mysql_fetch_array($res)){
					$indice_en_data=array_search($reg["a"],$m_data); 
					($reg["activo"])?$activo_d='SI':$activo_d='NO';

					$id_envioX="";
					$sql2="SELECT id_envio FROM reg_envios_items WHERE id_ot='".$reg["id_ot"]."' LIMIT 1; ";
					if ($res2=mysql_query($sql2,$link)){
						while($reg2=mysql_fetch_array($res2)){
							$id_envioX=$reg2[0];
						}	
					} else die("<br>Error SQL (".mysql_error($link).")."); 					
					
					$id_cajaX="";
					$sql3="SELECT id_envio_caja FROM reg_envio_caja_items WHERE id_ot='".$reg["id_ot"]."' LIMIT 1; ";
					if ($res3=mysql_query($sql3,$link)){
						while($reg3=mysql_fetch_array($res3)){
							$id_cajaX=$reg3[0];
						}	
					}else die("<br>Error SQL (".mysql_error($link).").");
					?>
					<script language="javascript">
						$("#txt_nds_<?=$indice_en_data?>").css("color","green");
					$("#txt_act_<?=$indice_en_data?>").text('<?=$activo_d?>');	
					
					$("#txt_lot_<?=$indice_en_data?>").text(<?=$reg["id_lote0"]?>);
					$("#txt_mod_<?=$indice_en_data?>").text('<?=$reg["dmodelo"]?>');
					
					$("#txt_iot_<?=$indice_en_data?>").text(<?=$reg["id_ot"]?>);
					$("#txt_sta_<?=$indice_en_data?>").text('<?=$reg["status0"]?>');	
					$("#txt_stb_<?=$indice_en_data?>").text('<?=$reg["status1"]?>');	
					
					$("#txt_env_<?=$indice_en_data?>").text('<?=$id_envioX?>');
					$("#txt_caj_<?=$indice_en_data?>").text('<?=$id_cajaX?>');	
					</script><?php
				}
			}else{ /*echo "<div>sin resultados ";*/ }
		} else{ echo "<br>Error SQL (".mysql_error($link).")."; exit;	}			
	}
	exit;
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Multiconsulta de Equipos de Cable.</title>
<style type="text/css">
body, document, html{ position:absolute; width:100%; height:100%; background-color:#fff; font-family: Arial, Helvetica, sans-serif; margin:0px 0px 0px 0px; color:#000; font-size:small; }
#div_main{ position:absolute; width:100%; height:100%; margin:0px 0px 0px 0px; }
	#div_titulo{ position:relative; height:20px; text-align:center; font-size:large; background-color:#efefef;  }
	#div_menu{ position:relative; height:20px; text-align:center; background-color:#efefef;  }
	#div_a{ position:relative; height:auto; width:200px; text-align:center; float:left; background-color:#efefef; overflow:auto; }
	#div_b{ position:relative; height:auto; text-align:justify; float:left; overflow:auto; }
	
#tbl_01{ display:none; }
table{ border-left:#ccc 1px solid; border-top:#ccc 1px solid; margin-top:5px; margin-bottom:5px; }
th{ /**/ border-right:#ccc 1px solid; border-bottom:#ccc 1px solid; background-color:#efefef; font-weight:bold; text-align:center; }
td{ /**/ border-right:#ccc 1px solid; border-bottom:#ccc 1px solid;text-align:center; }

.nds{ width:auto; text-align:left; }
.act{ width:50px; text-align:center; }
.iot{ width:50px; text-align:right; color:#0000FF; }
.sta{ width:100px; text-align:left; }
.stb{ width:100px; text-align:left; }
.env{ text-align:right; }

.tr_hover:hover{ background-color:#efefcc;}
</style>
<script language="javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<script language="javascript">
$(document).ready(function (){ 
	//alert("OK");
	actualiza_alto_capas(); 
	$("#txt_archivo_excel").attr("value","");
	$("#txt_archivo_excel").focus();
});
function actualiza_alto_capas(){
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
function crear_tabla(){
	//alert("crear_tabla()");alert(mi_html);	
	var mi_html='<table align="center" id="tbl_01" border="0" cellpadding="2" cellspacing="0">';
		mi_html+='<tr>';
			mi_html+='<th>#</th>';
			mi_html+='<th>id</th>';
			mi_html+='<th>Modelo</th>';
			mi_html+='<th>Imei</th>';
			mi_html+='<th>Serie</th>';
			mi_html+='<th>Sim</th>';
			mi_html+='<th>Folio</th>';
			mi_html+='<th>Status</th>';
			mi_html+='<th>Status Nextel</th>';			
		mi_html+='</tr>';
	mi_html+='</table>';
	$("#div_tabla").html(mi_html);
		
}
function agregar_filaX(id_lote2,nds,act,lot,mod,iot,st0,st1,ien,ica){
	var no_filas = document.getElementById("tbl_01").rows.length;
	var id_fila_activa=no_filas-1;
	var mi_html='<tr class="tr_hover">';
		mi_html+='<td align="center"><b>'+no_filas+'</b></td>';
		
		mi_html+='<td id="txt_nds_'+id_fila_activa+'" class="iot" >'+id_lote2+'</td>';
		
		mi_html+='<td id="txt_nds_'+id_fila_activa+'" class="nds" >'+nds+'</td>';
		mi_html+='<td id="txt_act_'+id_fila_activa+'" class="act" >'+act+'</td>';
		mi_html+='<td id="txt_lot_'+id_fila_activa+'" class="act" >'+lot+'</td>';
		mi_html+='<td id="txt_mod_'+id_fila_activa+'" class="sta" >'+mod+'</td>';
		mi_html+='<td id="txt_iot_'+id_fila_activa+'" class="iot" >'+iot+'</td>';
		mi_html+='<td id="txt_sta_'+id_fila_activa+'" class="sta" >'+st0+'</td>';
		mi_html+='<td id="txt_stb_'+id_fila_activa+'" class="stb" >'+st1+'</td>';
		
		
	mi_html+='</tr>';
	$("#tbl_01").append(mi_html);
}
function buscar_valores_x(){
	obtener_valores_textarea();
}
function obtener_valores_textarea(){
	var valores=$("#txt_archivo_excel").attr("value");
	if(valores==''||valores==' '||valores==undefined||valores==null) return;
	
	$("#tbl_01").show();
	var nuevos_valores='';
		nuevos_valores=valores.replace(/\n/gi,',');
		nuevos_valores=nuevos_valores.replace(' ','');
		nuevos_valores=nuevos_valores.replace(/\r/gi,',');			
	
	var datos="action=buscar_nds_xxx&data="+nuevos_valores;
	ajax("div_resultados",datos);	
}

function buscar_valores_grid(activo_inactivo){
	var no_filas = document.getElementById("tbl_01").rows.length;
	if(no_filas<=1) return;
	--no_filas;
	var id_fila_activa=no_filas-1;
	var m_indices=new Array();
	var m_nds=new Array();
	for(var i=0;i<no_filas;i++){	
		m_indices.push(i);
		m_nds.push($("#txt_nds_"+i).text());
	}
	//alert(m_nds);
	var datos="action=buscar_nds&activo="+activo_inactivo+"&m_indices="+m_indices+"&data="+m_nds;
	ajax("div_resultados",datos);
}
</script>
</head>
<body>
<div id="div_main">
	<div id="div_titulo">Multiconsulta de Equipos.</div>
	<div id="div_menu">
		<a href="#" id="lin_02" onClick="buscar_valores_x()">buscar</a>
	</div>
	<div id="div_a">
		<div>Imei(s) a buscar: </div>
		<textarea id="txt_archivo_excel" cols="20" rows="30"></textarea>
	</div>
	<div  id="div_b">
		<div id="div_tabla">...</div>
		<div id="div_resultados"></div>
	</div>
</div>
</body>
</html>