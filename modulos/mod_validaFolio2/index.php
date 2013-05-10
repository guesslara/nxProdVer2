<?
	session_start();include 
	("../../includes/cabecera.php");
	include("../../includes/txtApp.php");
	$proceso="Linea de Produccion";
	if(!isset($_SESSION[$txtApp['session']['idUsuario']])){
		echo "<script type='text/javascript'> window.location.href='../cerrar_sesion.php'; </script>";
		exit;
	}else{
		//se extrae el proceso
		$sqlProc="SELECT * FROM cat_procesos WHERE descripcion='".$proceso."'";
		$resProc=mysql_query($sqlProc,conectarBd());
		$rowProc=mysql_fetch_array($resProc);
		$proceso=$rowProc['id_proc'];
	}
	//consulta de los folios del cliente
	$sqlCliente="SELECT COUNT( * ) AS `Filas` , `lote` FROM `archivo_Cliente` GROUP BY `lote` ORDER BY `lote` DESC";
	$resCliente=mysql_query($sqlCliente,conectarBd());
	//consulta los folios de IQ
	$sqlIq="SELECT COUNT( * ) AS `Filas` , `lote` FROM `equipos` GROUP BY `lote` ORDER BY `lote` DESC";
	$resIq=mysql_query($sqlIq,conectarBd());
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
<script type="text/javascript" src="js/funcionesEnsamble.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<!--<link  type="text/css" rel="stylesheet" href="../../css/main.css" />-->
<script type="text/javascript">
	$(document).ready(function(){
		redimensionar();
	});
	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var anchoDiv=$("#contenedorEnsamble3").width();
		var altoCuerpo=altoDiv-52;
		$("#detalleValidaciones").css("height",altoCuerpo+"px");
		$("#ventanaEnsambleContenido2").css("height",altoCuerpo+"px");
		$("#detalleValidaciones").css("width",(anchoDiv-3)+"px");
		$("#ventanaEnsambleContenido2").css("width",(anchoDiv-3)+"px");
		$("#infoEnsamble3").css("height",altoCuerpo+"px");
	}
	
	window.onresize=redimensionar;
</script>
<style type="text/css">
html,body,document{position:absolute;margin:0px;height:100%; width:100%; margin:0px; overflow:hidden; font-family:Verdana, Geneva, sans-serif;}
#contenedorEnsamble{height:99%; position:relative;margin:0 auto 0 auto; width:99.5%; overflow:auto; background:#CCC;border:1px solid #000;}
#contenedorEnsamble3{width:99%;height:99%;background:#FFF;border:1px solid #000;margin:3px auto 0 auto;}
#barraOpcionesEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
.opcionesEnsamble{border:1px solid #000;background:#FFF;height:20px;padding:5px;width:auto;text-align:center;float:left;margin-left:3px;font-size: 12px;}
.opcionesEnsamble:hover{background:#e1e1e1;cursor:pointer;}
.ventanaEnsambleContenido{background:#fff;border:1px solid #CCC;overflow:auto;width:92%; float:left;}
#barraInferiorEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
#opcionFlex{border:1px solid #CCC;font-size:12px;font-weight:bold;background:#FFF;height:20px;padding:5px;width:100px;text-align:center;float:left;margin-left:3px;}
#opcionCancelar{border:1px solid #CCC;font-size:12px;font-weight:bold;background:#FFF;width:100px;text-align:center;float:right;margin-left:3px;}
#erroresCaptura{float:left; margin-left:3px; height:20px;padding:5px; width:500px; background:#FFF;border:1px solid #000;overflow:auto;}
#infoEnsamble3{width:270px;border:1px solid #CCC;background:#f0f0f0;float:left;}
#msgFlexCaptura{border:1px solid #000;background-color:#FFF;height:150px;width:300px;position:absolute;left:50%;top:50%;margin-left:-150px;margin-top:-75px;z-index:4;}
#advertencia{height:20px;padding:5px;background:#000;color:#FFF; text-align:left;font-size:12px;}
#transparenciaGeneral{background:url(../../img/desv.png) repeat;position: absolute; left: 0; top: 0; width: 100%; height: 100%; z-index:20;}
.transparenciaGeneral{background:url(../../img/desv.png) repeat;position: absolute; left: 0; top: 0; width: 100%; height: 100%; z-index:10000;}
#barraTitulo1VentanaDialogo{ height:20px; padding:5px; color:#FFF; font-size:12px; background:#000;}
#barraTitulo1VentanaDialogoCapturaFinal{ height:20px; padding:5px; color:#FFF; font-size:12px; background:#000;}
.barraTitulo1VentanaDialogoValidacion{ height:20px; padding:5px; color:#FFF; font-size:12px; background:#000;}
#btnCerrarVentanaDialogo{ float:right;}
.ventanaDialogo{border:1px solid #000;background-color:#FFF;height:600px;width:1000px;position:absolute;left:50%;top:50%;margin-left:-500px;margin-top:-300px;z-index:1;/*sombra*/-webkit-box-shadow:10px 10px 5px #CCC;-moz-box-shadow:10px 10px 5px #CCC;filter: shadow(color=#CCC, direction=135,strength=2);}
.ventanaDialogoCapturaFinal{border:1px solid #000;background-color:#FFF;height:600px;width:600px;position:absolute;left:50%;top:50%;margin-left:-300px;margin-top:-300px;z-index:1;/*sombra*/-webkit-box-shadow:10px 10px 5px #CCC;-moz-box-shadow:10px 10px 5px #CCC;filter: shadow(color=#CCC, direction=135,strength=2);}
.ventanaDialogoVerificacionEquipoEnviado{border:1px solid #000;background-color:#FFF;height:550px;width:600px;position:absolute;left:50%;top:50%;margin-left:-300px;margin-top:-225px;z-index:21;/*sombra*/-webkit-box-shadow:10px 10px 5px #CCC;-moz-box-shadow:10px 10px 5px #CCC;filter: shadow(color=#CCC, direction=135,strength=2);}
.ventanaDialogoVerificacionEquipoValidacion{
	border:1px solid #000;
	background-color:#FFF;
	height:600px;
	width:900px;
	position:absolute;
	left:50%;
	top:50%;
	margin-left:-450px;
	margin-top:-300px;        
	z-index:21;
	/*sombra*/
	-webkit-box-shadow:10px 10px 5px #CCC;
	-moz-box-shadow:10px 10px 5px #CCC;
	filter: shadow(color=#CCC, direction=135,strength=2);
}
.ventanaDialogoVerificacionEquipoEntregas{
	border:1px solid #000;
	background-color:#FFF;
	height:400px;
	width:400px;
	position:absolute;
	left:50%;
	top:50%;
	margin-left:-200px;
	margin-top:-200px;        
	z-index:21;
	/*sombra*/
	-webkit-box-shadow:10px 10px 5px #CCC;
	-moz-box-shadow:10px 10px 5px #CCC;
	filter: shadow(color=#CCC, direction=135,strength=2);
}
.validaOk{background:green;color:#FFF;}
.validaNok{background:#FF0000;color:#FFF;}
.cargadorEmpaque{width:200px; height:20px;background:#F5F6CE; color:#000;top:40px;z-index:999;text-align: left;padding:10px;font-size:10px;display:none;}
.tabEmpaqueListadoCapturas{float:left;width:80px;background:#e1e1e1;margin-left:3px;border:1px solid #CCC;font-size:10px; height:10px;padding:4px;cursor:pointer;}
.tabEmpaqueListadoCapturas:hover{background:#FFF;}
.tabEmpaqueListadoCapturasFocus{float:left;width:100px;background:#000;margin-left:3px;border:1px solid #000;font-size:11px;color:#FFF; height:10px;padding:4px;cursor:pointer;}
.resultadosListaEmpaque{height:60px;width: 93%;border:1px solid #CCC;font-size:10px;}
.resultadosListaEmpaque:hover{border:1px solid #000;}
.resultadosListaEmpaqueValidaciones{height:85px;width: 93%;border:1px solid #CCC;font-size:10px;}
.resultadosListaEmpaqueValidaciones:hover{border:1px solid #000;}
.resultadosEntregas{text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;}
.resultadosEntregas:hover{background: #BECDFD;}
.resultadosEntregasCajas{background: #BECDFD;}
.resultadosEntregasCajas:hover{background: #FFF;}
.opcionesEnsambleFinalizar{border: 1px solid #FFF;background: red;color: #FFF;font-size: 12px;font-weight: bold;height: 18px;padding: 5px;width: 150px;text-align: center;position: absolute;left: 700px;top: 50px;}
.opcionesEnsambleFinalizar:hover{background:#FFF;color: #000;cursor:pointer;}
</style>

<input type="hidden" name="txtProcesoEmpaque" id="txtProcesoEmpaque" value="<?=$proceso;?>" />
<input type="hidden" name="txtIdUsuarioEmpaque" id="txtIdUsuarioEmpaque" value="<?=$_SESSION[$txtApp['session']['idUsuario']];?>" />
<div id="contenedorEnsamble">
	<div id="contenedorEnsamble3">
		<div id="barraOpcionesEnsamble">
			<div class="opcionesEnsamble" title="Capturar Equipo OK">Folio Cliente:
			<select name="cboCliente" id="cboCliente">
				<option value="">Selecciona...</option>
<?
		while($rowCliente=mysql_fetch_array($resCliente)){
?>
				<option value="<?=$rowCliente["lote"];?>"><?=$rowCliente["lote"];?></option>
<?
		}
?>
			</select>&nbsp;Folio IQ:
			<select name="cboIq" id="cboIq">
				<option value="">Selecciona...</option>
<?
		while($rowIq=mysql_fetch_array($resIq)){
?>
				<option value="<?=$rowIq["lote"];?>"><?=$rowIq["lote"];?></option>
<?
		}
?>			
			</select>
			<input type="button" value="Mostrar" onclick="mostrarFolioSeleccionado()">
			</div>
			<div class="opcionesEnsamble" onclick="validarInfo()" title="Validar Informaci&oacute;n">Validar Informaci&oacute;n</div>
			<div class="opcionesEnsamble" onclick="mostrarResumen()" title="Mostrar Res&uacute;men">Mostrar Res&uacute;men</div>
			<!--<div class="opcionesEnsambleFinalizar" onclick="finalizarEnviosEntregasFinal()" title="Finalizar Entregas">Finalizar Entregas</div>-->
			<div id="cargadorEmpaque" style="float:right;width:200px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:13px;text-align:right;"></div>
		</div>
		<!--<div id="infoEnsamble3">
			<div id="mostrarCapturasEmpaqueDiv" class="tabEmpaqueListadoCapturasFocus" onclick="listarCapturas('capturas')">Capturas</div>
			<div id="mostrarValidacionEmpaqueDiv" class="tabEmpaqueListadoCapturas" onclick="listarCapturas('validaciones')">Validaci&oacute;n</div><div style="clear:both;"></div>
			<div id="listadoEmpaque" style="border:1px solid #e1e1e1;background:#fff; height:95%;width:97%;font-size:12px;margin:3px;overflow: auto;"></div>			
		</div>-->
		<div id="detalleValidaciones" class="ventanaEnsambleContenido"></div>
		<div id="ventanaEnsambleContenido2" class="ventanaEnsambleContenido" style="display:none;"></div>
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

