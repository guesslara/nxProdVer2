<?
	session_start();
	include("../../includes/txtApp.php");
	//print_r($_SESSION[$txtApp['session']['idUsuario']]);
	if(!isset($_SESSION[$txtApp['session']['idUsuario']])){
		header("../mod_login/index.php");
		exit;
	}
	$mes=date("m");
	$anio=date("Y");
	$diaActual=date("d");
	include("../../includes/cabecera.php");
?>
<style type="text/css">
body{margin:0; height:100%;overflow:auto;}
</style>
<script type="text/javascript" src="js/funcionesInicio.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/main.css" />

<style type="text/css">
html,body,document{position:absolute;margin:0px;height:100%; width:100%; margin:0px; overflow:hidden; font-family:Verdana, Geneva, sans-serif;}
#contenedorEnsamble{height:99%; position:relative;margin:0 auto 0 auto; width:99.5%; overflow:hidden; background:#CCC;border:1px solid #000;}
#contenedorEnsamble3{width:99%;height:99%;background:#FFF;border:1px solid #000;margin:3px auto 0 auto;}
#barraOpcionesEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
.opcionesEnsamble{border:1px solid #000;background:#FFF;height:20px;padding:5px;width:120px;text-align:center;float:left;margin-left:3px;font-size: 12px;}
.opcionesEnsamble:hover{background:#e1e1e1;cursor:pointer;}
.ventanaEnsambleContenido{background:#fff;border:1px solid #CCC;overflow:auto;float:left;}
#barraInferiorEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
#opcionFlex{border:1px solid #CCC;font-size:12px;font-weight:bold;background:#FFF;height:20px;padding:5px;width:100px;text-align:center;float:left;margin-left:3px;}
#opcionCancelar{border:1px solid #CCC;font-size:12px;font-weight:bold;background:#FFF;width:100px;text-align:center;float:right;margin-left:3px;}
#erroresCaptura{float:left; margin-left:3px; height:20px;padding:5px; width:500px; background:#FFF;border:1px solid #000;overflow:auto;}
#infoEnsamble3{width:350px;border:1px solid #CCC;background:#f0f0f0;float:left;}
#msgFlexCaptura{border:1px solid #000;background-color:#FFF;height:150px;width:300px;position:absolute;left:50%;top:50%;margin-left:-150px;margin-top:-75px;z-index:4;}
#advertencia{height:20px;padding:5px;background:#000;color:#FFF; text-align:left;font-size:12px;}
#transparenciaGeneral{background:url(../../img/desv.png) repeat;position: absolute; left: 0; top: 0; width: 100%; height: 100%; z-index:20;}
.transparenciaGeneral{background:url(../../img/desv.png) repeat;position: absolute; left: 0; top: 0; width: 100%; height: 100%; z-index:20;}
#barraTitulo1VentanaDialogo{ height:20px; padding:5px; color:#FFF; font-size:12px; background:#000;}
.barraTitulo1VentanaDialogoValidacion{ height:20px; padding:5px; color:#FFF; font-size:12px; background:#000;}
#btnCerrarVentanaDialogo{ float:right;}
.btnOpcionesInicio{height: 15px;padding: 5px;border: 1px solid #CCC;background: #f0f0f0;font-size: 10px;width: 89%;margin: 5px;text-align: left;}
.btnOpcionesInicio:hover{background: #FFF;cursor: pointer;}
.btnOpcionesInicioSub{height: 96%;padding: 5px;border: 1px solid #CCC;background: #fff;font-size: 10px;width: 94%;margin: 5px;text-align: left;overflow: auto;}
.btnOpcionesInicioSubBoton{height: 20px;padding: 7px;border: 1px solid #CCC;background: #f0f0f0;font-size: 10px;width: 89%;margin: 5px;text-align: left;}
.btnOpcionesInicioSubBoton:hover{background: #CCC;cursor: pointer;}
.divOpcionesResumen{height: auto;padding: 5px;border: 1px solid #CCC;background: #fff;font-size: 10px;width: 89%;margin: 2px 5px 5px 5px;text-align: left;overflow: auto;font-size: 10px;}
</style>
<script>
	$(document).ready(function (){
		redimensionar();
		resumen('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>');		
	});
	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var anchoDiv=$("#contenedorEnsamble3").width();		
		var altoCuerpo=altoDiv-50;
		var anchoCuerpo=anchoDiv-355;
		$("#resumen").css("height",altoCuerpo+"px");
		$("#ventanaEnsambleContenido").css("height",altoCuerpo+"px");
		//$("#detalleEmpaque").css("width",(anchoDiv-220)+"px");		
		$("#resumen").css("width",(anchoCuerpo-5)+"px");
		$("#infoEnsamble3").css("height",altoCuerpo+"px");
		$("#ventanaEnsambleContenido2").css("height",altoCuerpo+"px");
		$("#ventanaEnsambleContenido2").css("width",(anchoCuerpo)+"px");
		$("#ventanaConsultasFrame").css("height",altoCuerpo+"px");
		$("#ventanaConsultasFrame").css("width",(anchoCuerpo)+"px");
	}
	
	window.onresize=redimensionar;
</script>
<div id="contenedorEnsamble">
	<div id="contenedorEnsamble3">
		<div id="barraOpcionesEnsamble">						
			<div class="opcionesEnsamble" onclick="resumen('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>')" title="Resumen del Sistema">Resumen</div>
			<div class="opcionesEnsamble" onclick="resumenStatus('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>')" title="Resumen por Proceso">Resumen Proceso</div>
			<div class="opcionesEnsamble" onclick="mostrarLotes()" title="Capturar SCRAP">Folios</div>
			<div style="float:right;width:200px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:14px;text-align:left;">Inicio</div>
		</div>
		<div id="infoEnsamble3">			
			<div id="listadoEmpaque" style="border:1px solid #e1e1e1;background:#e1e1e1; height:98.5%;width:97%;font-size:12px;margin:3px;overflow: auto;">
				<!--<div class="btnOpcionesInicio" onclick="resumen('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>')">Resumen</div>-->
				<!--<div id="btnOpcionesInicioSub" class="btnOpcionesInicioSub"></div>-->
				<!--<div class="btnOpcionesInicio" onclick="resumenStatus('<?=$mes;?>','<?=$anio;?>','<?=$diaActual;?>')">Resumen Proceso</div>
				<div class="btnOpcionesInicio">Enviado por Folio</div>-->
			</div>
			<!--<div id="infoCapturaFlex" style="border:1px solid #e1e1e1;background:#fff; height:100px;width:180px;font-size:12px;text-align:left;margin:0 auto 0 auto;"></div>
			<div id="infoEquiposIng" style="border:1px solid #e1e1e1;background:#fff; height:220px;width:180px;font-size:20px;text-align:center;margin:0 auto 0 auto;"></div>
			<input type="hidden" id="txtOpcionFlex" name="txtOpcionFlex" value="" />-->
		</div>
		<div id="resumen" class="ventanaEnsambleContenido"></div>
		<div id="ventanaEnsambleContenido2" class="ventanaEnsambleContenido" style="border: 0px solid green;display: none;">
			<iframe src="grafica1.php" style="border: 0px solid green;width: 68%;height: 99%;overflow: auto;">El navegador no acepta iframes</iframe>
		</div>
		<iframe id="ventanaConsultasFrame" class="ventanaEnsambleContenido" style="border: 1px solid #CCC;display: none;"></iframe>
		<div style="clear:both;"></div>
		<!--<div id="barraInferiorEnsamble">			
			<div id="erroresCaptura"></div>
			<div id="opcionCancelar"><input type="button" onclick="cancelarCaptura()" value="Cancelar" style=" width:100px; height:30px;padding:5px;background:#FF0000;color:#FFF;border:1px solid #FF0000;font-weight:bold;" /></div>
		</div>-->
	</div>
</div>
<?
 include ("../../includes/pie.php");
?>
