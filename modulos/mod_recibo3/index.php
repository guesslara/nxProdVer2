<?php
	session_start();
	/*echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";*/
	include("../../includes/txtApp.php");	
	//if(!isset($_SESSION['id_usuario_nx'])){
	//	echo "<script type='text/javascript'>alert('Su sesion ha caducado, por favor ingrese de nuevo al Sistema.'); window.location.href='mod_login/index.php';<script>";
	//}
	include("../../includes/cabecera.php");
	$action=$_GET['action'];
?>
<link type="text/css" rel="stylesheet" href="../../css/main.css"  />
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/funcionesRecibo2.js"> </script>
<!--<script type="text/javascript" src="../../recursos/calendario/js/datepicker/datepicker.js"></script>-->
<!--<link rel="stylesheet" type="text/css" href="../../recursos/calendario/css/datepicker/datepicker.css"  />-->
<script type="text/javascript">
	$(document).ready(function(){
	var altoDocRecibo=$(document).height();
	document.getElementById("detalleRecibo").style.height=(altoDocRecibo-43)+"px";
	document.getElementById("detalleListadoRecibo").style.height=(altoDocRecibo-100)+"px";
	});
</script>
<style type="text/css">
.estiloContador{float:left;width:20px;background:#CCC;border:1px solid #CCC;height:15px;padding:4px;font-weight:bold; font-size:10px;}
.estiloCheck{float:left;border:1px solid #CCC;background:#FFF;}
.estiloImei{float:left;width:100px;height:15px;border:1px solid #CCC;background:#FFF;padding:4px; font-size:10px; text-transform:uppercase;}
.estiloSerial{float:left;width:95px;height:15px;border:1px solid #CCC;background:#FFF;padding:4px; font-size:10px; text-transform:uppercase;}
.estiloBdCode{float:left;width:300px; height:15px;border:1px solid #CCC;background:#FFF;padding:4px; font-size:10px; text-transform:uppercase;}
.validacion1{float:left;width:75px; height:15px; border:1px solid #CCC;background:#FFF; padding:4px; font-size:10px; text-align:center;}
.validacion1Activa{float:left;width:75px; height:15px; border:1px solid #CCC;background:#FF9900; padding:4px; font-size:10px; text-align:center; font-weight:bold;}
.validacion1ActivaValidado{float:left;width:75px; height:15px; border:1px solid #000;background:#9AFE2E; padding:4px; font-size:10px; text-align:center; font-weight:bold;}
.validacion2{float:left;width:75px; height:15px; border:1px solid #CCC;background:#FFF; padding:4px; font-size:10px; text-align:center;}
.validacion2Activa{float:left;width:75px; height:15px; border:1px solid #FF0000;background:#FFFF66; padding:4px; font-size:10px; text-align:center; font-weight:bold;}
.validacion2ActivaValidado{float:left;width:75px; height:15px; border:1px solid #000;background:#9AFE2E; padding:4px; font-size:10px; text-align:center; font-weight:bold;}
</style>
<table border="0" cellpadding="1" cellspacing="1" style="height:99.5%; width:100%; background:#FFF;">
<?
	if($action=="captura"){		
		//echo "<br>movimiento: ".$mov=$_GET['mov'];
		//echo "<br>id modelo: ".$modelo=$_GET['modelo'];
		//echo "<br>cantidad: ".$cantidad=$_GET['cantidad'];
		//echo "<br>Id del usuario: ".$_SESSION['id_usuario_nx'];
?>
  		<script type="text/javascript">
			//alert("1");
			recibirEquipos('<?=$_SESSION['id_usuario_nx'];?>','<?=$modelo=$_GET['modelo'];?>','<?=$mov=$_GET['mov'];?>','<?=$cantidad=$_GET['cantidad'];?>');
		</script>
<?
	}else{
		
		
?>

	<tr>
    	<td style="height:22px; border-bottom:1px solid #999; padding:4px; float:left;">
        	<a href="#" onclick="cambiaVentana('1')" style="color:#000;"><div id="recibo1" class="cambiarAplicacion" style="background:#CCC;">1</div></a>
            <a href="#" onclick="cambiaVentana('2')" style="color:#000;"><div id="recibo2" class="cambiarAplicacion">2</div></a>
            <div style="float:left; padding:4px; margin-left:10px;">
            
            	<a href="javascript:recibirEquipos('<?=$_SESSION[$txtApp['session']['idUsuario']];?>','<?=$modelo['modelo'];?>','<?=$mov=$_GET['mov'];?>','<?=$cantidad=$_GET['cantidad'];?>');" style="color:#00F;">Recibir Equipos</a> | 
	            <!--<a href="javascript: mostrarCalendarios('calendario3')" style="color:#00F;">Listar Equipos</a> |-->
                <a href="consulta.php" target="_blank" style="color:#00F;">Listar Equipos</a> |
		<a href="../mod_capturaImeiDes2/index.php" style="color:#00F;">Enviar a Desensamble</a> |
                Exportar equipos del Movimiento:<input type="text" name="txtCajaInternaExportar" id="txtCajaInternaExportar" style="width:50px;" onkeyup="exportarCajaInterna(event)" /><input type="button" value="Exportar" onclick="exportarCajaInterna()" />
            </div>
        </td>
    </tr>
<?
	}
?>
    <tr>
    	<td valign="top" style="height:100%;">
        	<div id="detalleRecibo" style="height:83%; width:100%; border:1px solid #CCC; overflow:auto;">&nbsp;</div>
            <div id="archivoExportar"></div>
            <div id="detalleListadoRecibo" style="height:100%; width:100%; border:1px solid #CCC; overflow:auto; display:none;">&nbsp;</div>
        </td>
    </tr>
</table>
<div id="cargando" style=" display:none;position: absolute; left: 0; top: 0; width: 100%; height: 100%; background: url(../../img/desv.png) repeat">
	<div id="msgCargador"><div style="padding:15px;"><img  src="../../img/cargador.gif" border="0" /></div></div>
</div>

<div id="calendario1" style="display:none;">
	<div id="desv">
    	<div id="msgCalendario1" ><br /><br />&nbsp;&nbsp;&nbsp;
    		<label for="dp-4">Seleccione la Fecha</label> :<br /><br />&nbsp;&nbsp;
			<input type="text" class="dateformat-Y-ds-m-ds-d show-weeks statusformat-l-cc-sp-d-sp-F-sp-Y" id="dp-1" name="dp-1" value="" style="width:100px;" readonly="readonly" />&nbsp;&nbsp;
            <input type="text" class="dateformat-Y-ds-m-ds-d show-weeks statusformat-l-cc-sp-d-sp-F-sp-Y" id="dp-2" name="dp-2" value="" style="width:100px;" readonly="readonly" />
            <input type="button" value="Buscar" style="width:60px;" onclick="consultaReporte()" />
            <input type="button" value="Cerrar" style="width:60px;" onclick="ocultaCalendarios('calendario1')" />
    	</div>
    </div>
</div>
<div id="calendario2" style="display:none;">
	<div id="desv">
    	<div id="msgCalendario2">
        	<div style="float:right; margin-top:5px;"><a href="#" onclick="ocultaCalendarios('calendario2')" style="text-decoration:none;"><img src="../../img/tb_close.gif"  border="0" /></a></div><br /><br />&nbsp;&nbsp;&nbsp;
    		<label for="dp-4">Seleccione la Fecha</label> :<br /><br />&nbsp;&nbsp;
			<input type="text" class="dateformat-Y-ds-m-ds-d show-weeks statusformat-l-cc-sp-d-sp-F-sp-Y" id="dp-3" name="dp-3" value="" style="width:100px;" readonly="readonly" />&nbsp;&nbsp;
            <input type="text" class="dateformat-Y-ds-m-ds-d show-weeks statusformat-l-cc-sp-d-sp-F-sp-Y" id="dp-4" name="dp-4" value="" style="width:100px;" readonly="readonly" />            
            <div id="tecnicosDesensambleReporte" style="overflow:auto; border:1px solid #999; margin:10px 5px 10px 5px; height:180px;"></div>
            <div style="float:right; margin-right:5px;"><input type="button" value="Buscar" style="width:60px;" onclick="consultaReporteTecnico()" /></div>
    	</div>
    </div>
</div>
<div id="calendario3" style="display:none;">
	<div id="desv">
    	<div id="msgCalendario3">
        	<div style="float:right; margin-top:5px;"><a href="#" onclick="ocultaCalendarios('calendario3')" style="text-decoration:none;"><img src="../../img/tb_close.gif" border="0" /></a></div><br /><br />&nbsp;&nbsp;&nbsp;
    		<label for="dp-4">Seleccione la Fecha de B&uacute;squeda</label> :<br /><br />&nbsp;&nbsp;
			<input type="text" class="dateformat-Y-ds-m-ds-d show-weeks statusformat-l-cc-sp-d-sp-F-sp-Y" id="dp-5" name="dp-5" value="" style="width:100px;" readonly="readonly" />&nbsp;&nbsp;
            <input type="text" class="dateformat-Y-ds-m-ds-d show-weeks statusformat-l-cc-sp-d-sp-F-sp-Y" id="dp-6" name="dp-6" value="" style="width:100px;" readonly="readonly" />            
            <!--<div id="opcionesBusquedaRecibo" style="overflow:auto; border:1px solid #999; margin:10px 5px 10px 5px; height:260px;"></div>-->
            <div style="float:right; margin-right:20px;"><input type="button" value="Buscar Equipos" onclick="consultaReporteReciboListado()" /></div>
    	</div>
    </div>
</div>   
<?
	
include("../../includes/pie.php");
?>
