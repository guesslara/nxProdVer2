<?
	include("../../includes/cabecera.php");
?>
<link type="text/css" rel="stylesheet" href="../../css/main.css"  />
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/funcionesRecibo2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
	var altoDocRecibo=$(document).height();
	document.getElementById("detalleReciboListadoBusqueda_izq").style.height=(altoDocRecibo-43)+"px";
	//document.getElementById("detalleReciboListadoBusqueda").style.height=(altoDocRecibo-43)+"px";
	document.getElementById("detalleListadoRecibo").style.height=(altoDocRecibo-100)+"px";
	});
</script>
<table border="0" cellpadding="1" cellspacing="1" style="height:99.5%; width:100%; background:#FFF;">
    <tr>
    	<td colspan="2" style="height:22px; border-bottom:1px solid #999; background:#CCC; padding:4px;">        	
            <div style="float:left; padding:4px; margin-left:5px;">            
            	Buscar por Lote:<input type="text" name="txtBusquedaLote" id="txtBusquedaLote" /><input type="button" value="Buscar" onclick="buscarPorLote()" />
            </div>
        </td>
    </tr>
    <tr>
    	<td width="15%" valign="top">
		<div id="detalleReciboListadoBusqueda" style="height:48%; width:100%; border:1px solid #CCC; overflow:auto;">&nbsp;</div>
		<!--<div style="height:49%; width:100%; border:1px solid #000; margin-top:5px;">
			<div style="background:#000; color:#FFF; height:20px; padding:5px;">Opciones:</div>
			<div id="divOpcionesLinks" style="background:#E1E1E1;border:1px solid #f0f0f0; height:92.5%; overflow:auto;top:25px;">
				
			</div>
		</div>-->
	</td>
    	<td width="85%" valign="top" style="height:100%;">
        	<div id="detalleReciboListadoBusqueda_izq" style="height:83%; width:99.5%; border:1px solid #CCC; overflow:auto;">&nbsp;</div>
            <div id="detalleListadoRecibo" style="height:100%; width:100%; border:1px solid #CCC; overflow:auto; display:none;">&nbsp;</div>
        </td>
    </tr>
</table>
<div id="cargando" style=" display:none;position: absolute; left: 0; top: 0; width: 100%; height: 100%; background: url(../../img/desv.png) repeat">
	<div id="msgCargador"><div style="padding:15px;"><img  src="../../img/cargador.gif" border="0" /></div></div>
</div>
<?
	include("../../includes/pie.php");
?>