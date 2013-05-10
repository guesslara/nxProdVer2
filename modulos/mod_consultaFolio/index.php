<?
	session_start();
	$mes=date("m");
	$anio=date("Y");
	$diaActual=date("d");	
	include("../../includes/cabecera.php");
	include("../../includes/txtApp.php");
	if(!isset($_SESSION[$txtApp['session']['idUsuario']])){
		echo "<script type='text/javascript'> window.location.href='../cerrar_sesion.php'; </script>";
		exit;
	}
?>
<style type="text/css">
html,body,document{position:absolute;margin:0px;height:100%; width:100%; margin:0px; overflow:hidden; font-family:Verdana, Geneva, sans-serif;}
#contenedorEnsamble{height:99%; position:relative;margin:0 auto 0 auto; width:99.5%; overflow:auto; background:#CCC;border:1px solid #000;}
#contenedorEnsamble3{width:99%;height:99%;background:#FFF;border:1px solid #000;margin:3px auto 0 auto;}
#barraOpcionesEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
.opcionesEnsamble{border:1px solid #000;background:#FFF;height:20px;padding:5px;width:100px;text-align:center;float:left;margin-left:3px;}
.opcionesEnsamble:hover{background:#e1e1e1;cursor:pointer;}
.ventanaEnsambleContenido{background:#fff;border:1px solid #CCC;overflow:auto;width:99.2%; float:left;margin-left: 5px;}
#barraInferiorEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
#opcionFlex{border:1px solid #CCC;font-size:12px;font-weight:bold;background:#FFF;height:20px;padding:5px;width:100px;text-align:center;float:left;margin-left:3px;}
#opcionCancelar{border:1px solid #CCC;font-size:12px;font-weight:bold;background:#FFF;width:100px;text-align:center;float:right;margin-left:3px;}
#erroresCaptura{float:left; margin-left:3px; height:20px;padding:5px; width:500px; background:#FFF;border:1px solid #000;overflow:auto;}
#infoEnsamble3{width:20%;border:1px solid #CCC;background:#f0f0f0;float:left;}
#msgFlexCaptura{border:1px solid #000;background-color:#FFF;height:150px;width:300px;position:absolute;left:50%;top:50%;margin-left:-150px;margin-top:-75px;z-index:4;}
#advertencia{height:20px;padding:5px;background:#000;color:#FFF; text-align:left;font-size:12px;}
.titulosTablaInfo1{background: #F0F0F0;color: #000;font-size: 14px;height: 20px;padding: 5px;border: 1px solid #CCC;text-align: center;font-weight: bold;}
.titulosTablaInfo2{background: #E1E1E1;color: #000;font-size: 14px;height: 20px;padding: 5px;border: 1px solid #CCC;text-align: center;font-weight: bold;}
.titulosTablaResumen{background: #000;color: #FFF;height: 20px;padding: 5px;text-align: center;}
.listadoFoliosGrid1{background: #FFF;border-bottom: 1px solid #CCC;border-right: 1px solid #CCC;height: 18px;padding: 5px;text-align:center;}
.listadoFoliosGrid1:hover{background: #BECDFD;}
.listadoFoliosGrid2{background: #F0F0F0;border-bottom: 1px solid #CCC;border-right: 1px solid #CCC;height: 18px;padding: 5px;text-align:center;}
.listadoFoliosGrid2:hover{background: #BECDFD;}
</style>
<script type="text/javascript" src="js/funcionesInicio.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/main.css" />
<script>
	$(document).ready(function(){
		altoDoc=$(document).height();
		//calendarizacionMes('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>');
		resumen('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>');
		//document.getElementById("resumen").style.height=(altoDoc-35)+"px";
		//document.getElementById("calendarizacionMes").style.height=(altoDoc-330)+"px";
		//document.getElementById("resumenStatus").style.height=(altoDoc-10)+"px";
		/********************/
		redimensionar();
		mostrarFolios();
	});
	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var altoCuerpo=altoDiv-95;
		$("#resumenStatus").css("height",altoCuerpo+"px");
		$("#ventanaEnsambleContenido2").css("height",altoCuerpo+"px");
		$("#infoEnsamble3").css("height",altoCuerpo+"px");
	}
	
	window.onresize=redimensionar;
</script>
<div id="contenedorEnsamble">
	<div id="contenedorEnsamble3">
		<div id="barraOpcionesEnsamble">
			<div class="opcionesEnsamble" style="width: 200px;font-size: 14px;">Folios Nextel</div><!--onclick="mostrarAdvertenciaCaptura()"-->
			<!--<div class="opcionesEnsamble" onclick="mostrarTab('ventanaEnsambleContenido2')" title="Capturar SCRAP">Capturar SCRAP</div>-->
			<div style="float:right;width:200px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:13px;text-align:right;"></div>
		</div>		
		<!--<div id="infoEnsamble3"><br>
			<div id="infoLineaCaptura" style="border:1px solid #e1e1e1;background:#fff; height:95%;width:93%;font-size:12px;text-align:center;margin:0 auto 0 auto;">
				<div style="background: #000;color: #FFF;height: 20px;padding: 5px;">Informaci&oacute;n</div>
				<div id="resumen" style="height: 93%;text-align: left;font-size: 10px;overflow: auto;border: 0px solid #000;"></div>
			</div>-->
			<!--<div id="infoCapturaFlex" style="border:1px solid #e1e1e1;background:#fff; height:100px;width:180px;font-size:12px;text-align:left;margin:0 auto 0 auto;"></div>-->
			<!--<div id="infoEquiposIng" style="border:1px solid #e1e1e1;background:#fff; height:220px;width:180px;font-size:20px;text-align:center;margin:0 auto 0 auto;"></div>-->
		<!--	<input type="hidden" id="txtOpcionFlex" name="txtOpcionFlex" value="" />
		</div>-->
		<div id="resumenStatus" class="ventanaEnsambleContenido"></div>
		<div id="ventanaEnsambleContenido2" class="ventanaEnsambleContenido" style="display:none;"></div>
		<div style="clear:both;"></div>
		<div id="barraInferiorEnsamble">			
			<div id="erroresCaptura"></div>
			<!--<div id="opcionCancelar"><input type="button" onclick="cancelarCaptura()" value="Cancelar" style=" width:100px; height:30px;padding:5px;background:#FF0000;color:#FFF;border:1px solid #FF0000;font-weight:bold;" /></div>-->
		</div>
	</div>
</div>
<?
	include ("../../includes/pie.php");
?>