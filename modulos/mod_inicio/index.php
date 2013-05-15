<?
	session_start();
	$mes=date("m");
	$anio=date("Y");
	$diaActual=date("d");	
	include("../../includes/cabecera.php");
?>
<style type="text/css">
body{margin:0; height:100%;}
</style>
<script type="text/javascript" src="js/funcionesInicio.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/main.css" />
<script>

	$(document).ready(function (){
		altoDoc=$(document).height();
		//calendarizacionMes('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>');
		resumen('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>');
		document.getElementById("resumen").style.height=(altoDoc-71)+"px";
		//document.getElementById("calendarizacionMes").style.height=(altoDoc-330)+"px";
		document.getElementById("resumenStatus").style.height=(altoDoc-10)+"px";
		
	});	
</script>
    <table id="tablaInicio" width="99%" border="0" cellpadding="1" cellspacing="1" style="border:1px solid #000;">
      <tr>
        <td width="30%" valign="top">
          <div style="border:1px solid #000;width:99%; margin-left:3px; margin-top:10px; margin-bottom:10px;">
            <div style="border:1px solid #333; background:#000; font-size:12px; color:#fff; height:20px; font-weight:bold;">Resumen</div>
            <div style="height:17px; padding:5px; background:#f0f0f0; border:1px solid #CCC; color:#000;">
		<a href="#" onclick="resumen('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>');" title="Ver Lotes">Resumen Sistema</a> |
		<a href="#" onclick="resumenStatus('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>');" title="Ver Lotes">Resumen Proceso</a> |
		<a href="#" onclick="mostrarLotes()" title="Ver Folios">Folios</a> |
		<a href="#" onclick="enviadoFolio()" title="Enviados por Folio">Enviados por Folio</a>
	    </div>
            <div id="resumen" style="width:100%; background:#FFF;overflow:auto; height:50%;"></div>
          </div>
        </td>
        <td width="70%" valign="top" style=" height:50%;">
        <!--<div style="border:1px solid #000; width:99%; margin-left:3px; margin-top:10px; margin-bottom:10px;">
            <div style="border:1px solid #333; background:#000; font-size:12px; color:#fff; height:20px; font-weight:bold;">Mes Actual</div>
            <div id="calendarizacionMes" style="width:99%; height:50%;overflow:auto;"></div>
          </div>--> <div id="resumenStatus" style="height:97%; border:1px solid #CCC; overflow:auto;">&nbsp;</div>       </td>            
      </tr>
    </table>
 <?
 include ("../../includes/pie.php");
 ?>
