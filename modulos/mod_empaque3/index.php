<?
	session_start();
	include("../../includes/cabecera.php");
	include("../../includes/txtApp.php");
	$proceso="Empaque";
	$procesoEnvio="Marcado como ENVIADO";
	//echo $_SESSION['id_usuario_nx'];
	if(!isset($_SESSION[$txtApp['session']['idUsuario']])){
		echo "<script type='text/javascript'> alert('Su sesion ha terminado por inactividad'); window.location.href='../mod_login/index.php'; </script>";
		exit;
	}else{
		//se extrae el proceso
		$sqlProc="SELECT * FROM cat_procesos WHERE descripcion='".$proceso."'";
		$resProc=mysql_query($sqlProc,conectarBd());
		$rowProc=mysql_fetch_array($resProc);
		$proceso=$rowProc['id_proc'];
		$sqlProc1="SELECT * FROM cat_procesos WHERE descripcion='".$procesoEnvio."'";
		$resProc1=mysql_query($sqlProc1,conectarBd());
		$rowProc1=mysql_fetch_array($resProc1);
		$proceso1=$rowProc1['id_proc'];
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
<link rel="stylesheet" type="text/css" href="css/estilosEmpaque.css" />
<script type="text/javascript" src="js/funcionesEnsamble.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<!--se incluyen los recursos para el grid-->
<script type="text/javascript" src="../../recursos/grid/grid.js"></script>
<link rel="stylesheet" type="text/css" href="../../recursos/grid/grid.css" />
<!--fin inclusion grid-->
<!--se incluyen los recursos para las ventanas-->
<script type="text/javascript" src="../../clases/jquery.dragndrop.js"></script>
<script type="text/javascript" src="../../recursos/dragdrop/dragdrop.js"></script>
<link rel="stylesheet" type="text/css" href="../../recursos/dragdrop/estilosDragDrop.css" />
<!--fin de las ventanas-->
<link rel="stylesheet" type="text/css" media="all" href="js/calendar-green.css"  title="win2k-cold-1" />
  <!-- librería principal del calendario -->
<script type="text/javascript" src="js/calendar.js"></script>
  <!-- librería para cargar el lenguaje deseado -->
<script type="text/javascript" src="js/calendar-es.js"></script>
   <!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
<script type="text/javascript" src="js/calendar-setup.js"></script>
<!--<link  type="text/css" rel="stylesheet" href="../../css/main.css" />-->
<style type="text/css">

</style>
<script type="text/javascript">
	$(document).ready(function(){
		redimensionar();
		listarCapturas('capturas');
	});
	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var anchoDiv=$("#contenedorEnsamble3").width();
		var altoCuerpo=altoDiv-52;
		$("#detalleEmpaque").css("height",altoCuerpo+"px");
		$("#ventanaEnsambleContenido2").css("height",altoCuerpo+"px");
		$("#detalleEmpaque").css("width",(anchoDiv-280)+"px");
		$("#ventanaEnsambleContenido2").css("width",(anchoDiv-200)+"px");
		$("#infoEnsamble3").css("height",altoCuerpo+"px");
	}
	
	window.onresize=redimensionar;

	document.onkeypress=function(elEvento){
		var evento=elEvento || window.event;
		var codigo=evento.charCode || evento.keyCode;
		var caracter=String.fromCharCode(codigo);
		if(codigo==27){
			cerrarVentanaValidacion();
		}
	}
//setInterval("procesarDatosGrid()",5000);
</script>
<!--<div id="cargadorEmpaque" class="cargadorEmpaque">Cargando...</div>-->
<input type="hidden" name="txtProcesoEmpaque" id="txtProcesoEmpaque" value="<?=$proceso;?>" />
<input type="hidden" name="txtProcesoEmpaqueEnvio" id="txtProcesoEmpaqueEnvio" value="<?=$proceso1;?>" />
<input type="hidden" name="txtIdUsuarioEmpaque" id="txtIdUsuarioEmpaque" value="<?=$_SESSION[$txtApp['session']['idUsuario']];?>" />
<div id="contenedorEnsamble">
	<div id="contenedorEnsamble3">
		<div id="barraOpcionesEnsamble">
			<div class="opcionesEnsamble" onclick="nuevaEntrega()" title="Capturar Equipo OK">Nueva Captura</div>
			<div class="opcionesEnsamble" onclick="enviarAValidar()" title="Enviar a Validar">Enviar a Validar</div>
			<!--<div class="opcionesEnsambleFinalizar" onclick="generarVentana()" title="Finalizar Entregas">Finalizar Entregas</div>-->
			<div id="cargadorEmpaque" style="float:right;width:200px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:13px;text-align:right;"></div>
		</div>
		<div id="infoEnsamble3">
			<div id="mostrarCapturasEmpaqueDiv" class="tabEmpaqueListadoCapturasFocus" onclick="listarCapturas('capturas')">Capturas</div>
			<div id="mostrarValidacionEmpaqueDiv" class="tabEmpaqueListadoCapturas" onclick="listarCapturas('validaciones')">Validaci&oacute;n</div><div style="clear:both;"></div>
			<div id="listadoEmpaque" style="border:1px solid #e1e1e1;background:#fff; height:95%;width:97%;font-size:12px;margin:3px;overflow: auto;"></div>
			<!--<div id="infoCapturaFlex" style="border:1px solid #e1e1e1;background:#fff; height:100px;width:180px;font-size:12px;text-align:left;margin:0 auto 0 auto;"></div>
			<div id="infoEquiposIng" style="border:1px solid #e1e1e1;background:#fff; height:220px;width:180px;font-size:20px;text-align:center;margin:0 auto 0 auto;"></div>
			<input type="hidden" id="txtOpcionFlex" name="txtOpcionFlex" value="" />-->
		</div>
		<div id="detalleEmpaque" class="ventanaEnsambleContenido"></div>
		<div id="ventanaEnsambleContenido2" class="ventanaEnsambleContenido" style="display:none;"></div>
		<div style="clear:both;"></div>
		<!--<div id="barraInferiorEnsamble">			
			<div id="erroresCaptura"></div>
			<div id="opcionCancelar"><input type="button" onclick="cancelarCaptura()" value="Cancelar" style=" width:100px; height:30px;padding:5px;background:#FF0000;color:#FFF;border:1px solid #FF0000;font-weight:bold;" /></div>
		</div>-->
	</div>
</div>
<div id="transparenciaGeneral" style="display:none;">	
		<div id="capturaCaja" class="ventanaDialogo">
    		<div id="barraTitulo1VentanaDialogo">Captura de Equipos</div><br />
		<input type="hidden" name="txtIdCaja" id="txtIdCaja" />
			<input type="hidden" name="idEmpaqueCaptura" id="idEmpaqueCaptura" />
		<table width="800" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#999; font-size:12px; font-family:Verdana, Geneva, sans-serif;">        
			<tr>
			    <td colspan="2" align="left"><form name="frmEquiposEnsamble" id="frmEquiposEnsamble">
			    <div id="contenedorListado" style="width:950px; height:470px; border:1px solid #000; background:#FFF; overflow:auto;">
				<div id="div_grid_ensamble" style="text-align:left;width:710px; float:left; overflow:auto; height:460px; border:1px solid #CCC;"></div>
				<div style="width:200px; float:right; margin-right:1px; text-align:center; border:1px solid #CCCCCC; background:#f0f0f0; height:460px;"><br /><br />
				    <input type="button" value="Actualizar" onclick="procesaFormulario()" style=" width:100px;height:30px; font-size:10px;" /><br /><br />
				    <input type="button" value="Cancelar" onclick="cancelarCaptura()" style=" width:100px;height:30px; font-size:10px; background:#FF0000; color:#FFF; font-weight:bold;" /><br /><br />
				    <!--<input type="button" value="Nueva Captura" onclick="nuevacaptura()" style=" width:100px;height:30px; font-size:10px;  font-weight:bold;" /><br /><br />-->
				    <div id="datosAsignaForm" style="width:183px; height:100px; border:1px solid #CCC; text-align:left; margin:8px;"></div>
				    <span style="font-size:14px;"><strong>Caja:</strong></span>
				    <div id="cajaInfoCaptura" style="border:1px solid #000; width:120px; height:50px; background:#FFF; font-size:36px; margin-left:40px;"></div><br />
				    Equipos en el Listado:<br /><br />
				    <div id="agregado" style="width:200px;font-size:22px;font-weight:bold;"></div>
				</div>
			    </div></form>
			    </td>
			</tr>
			<tr>
			    <td colspan="2"><div id="erroresCaptura" style="height:60px; width:800px; font-size:10px; background:#FFC; overflow:auto;">&nbsp;</div></td>
			</tr>
		</table>
		</div>
    </div>
	
</div>

<div id="capturaFinal" class="transparenciaGeneral" style="display:none;">	
	<div id="capturaCaja" class="ventanaDialogoCapturaFinal">
    		<div id="barraTitulo1VentanaDialogoCapturaFinal"></div><br />
		<input type="hidden" name="txtIdCaja" id="txtIdCaja" />
		<input type="hidden" name="idEmpaqueCaptura" id="idEmpaqueCaptura" />
		<input type="hidden" name="idEmpaqueValidacion" id="idEmpaqueValidacion" />
		<table width="500" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#999; font-size:12px; font-family:Verdana, Geneva, sans-serif;">        
			<tr>
				<td colspan="2" align="left"><form name="frmEquiposEnsamble" id="frmEquiposEnsamble">
				<div id="contenedorListado" style="width:595px; height:470px; border:1px solid #000; background:#FFF; overflow:auto;">
				<div id="div_grid_capturaFinal" style="text-align:left;width:380px; float:left; overflow:auto; height:460px; border:1px solid #CCC;"></div>
				<div style="width:200px; float:right; margin-right:1px; text-align:center; border:1px solid #CCCCCC; background:#f0f0f0; height:460px;"><br /><br />
				    <!--<input type="button" value="Actualizar" onclick="procesaFormulario()" style=" width:100px;height:30px; font-size:10px;" /><br /><br />-->
				    <input type="button" value="Cancelar" onclick="cancelarCapturaFinal()" style=" width:100px;height:30px; font-size:10px; background:#FF0000; color:#FFF; font-weight:bold;" /><br /><br />				    
				    <!--<div id="datosAsignaFormCapturaFinal" style="width:183px; height:100px; border:1px solid #CCC; text-align:left; margin:8px;"></div>-->
				    <span style="font-size:14px;"><strong>Modelo:</strong></span>
				    <div id="datosAsignaFormCapturaFinal" style="border:1px solid #000; width:auto; height:50px; background:#FFF; font-size:32px; margin-left:5px;"></div><br />
				    Caja:<br /><br />
				    <div id="agregadoCajaCapturaFinal" style="width:200px;font-size:22px;font-weight:bold;"></div>
				    Equipos agregados:<br /><br />
				    <!--<div id="agregadoCajaCapturaFinalEnCaja" style="width:200px;font-size:22px;font-weight:bold;"></div>-->
				    <input type="text" name="agregadoCajaCapturaFinalEnCaja" id="agregadoCajaCapturaFinalEnCaja" style="width:150px;font-size:22px;font-weight:bold;">
				</div>
			    </div></form>
			    </td>
			</tr>
			<tr>
			    <td colspan="2"><div id="erroresCapturaEmpaqueFinal" style="height:60px; width:585px; font-size:10px; background:#FFC; overflow:auto;">&nbsp;</div></td>
			</tr>
		</table>
		</div>
    </div>
	
</div>


<div id="transparenciaGeneral1" class="transparenciaGeneral" style="display:none;">
	<div id="divListadoCapturaValidacion" class="ventanaDialogo">
		<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n de la Captura<div id="btnCerrarVentanaDialogo"><a href="#" onclick="cerrarVentanaValidacion()" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
		<div id="listadoEmpaqueValidacion" style="border:1px solid #CCC; margin:4px; font-size:10px;height:93%; overflow:auto;"></div>
	</div>
</div>	
<div id="divVerificacionEquipoValidacion" class="ventanaDialogoVerificacionEquipoValidacion" style="display:none;">
	<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n <input type="hidden" name="txtVentanaValidacion" id="txtVentanaValidacionID" value=""><div id="btnCerrarVentanaDialogo"><a href="#" onclick="ventanaDialogoVerificacionEquipoValidado()" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
	<div id="listadoEmpaqueVerificacionValidacion" style="border:1px solid #CCC; margin:4px; font-size:10px;height:93%; overflow:auto;"></div>
</div>
<div id="divVerificacionEquipoEntregas" class="ventanaDialogoVerificacionEquipoEntregas" style="display:none;">
	<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n <input type="hidden" name="txtVentanaValidacion" id="txtVentanaValidacionID" value=""><div id="btnCerrarVentanaDialogo"><a href="#" onclick="ventanaDialogoVerificacionEquipoEntrega()" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
	<div id="listadoEmpaqueVerificacionValidacionEntregas" style="border:1px solid #CCC; margin:4px; font-size:10px;height:90%; overflow:auto;"></div>
</div>
<div id="divVerificacionEquipoEnviado" class="ventanaDialogoVerificacionEquipoEnviado" style="display:none;">
		<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n<div id="btnCerrarVentanaDialogo"><a href="#" onclick="ventanaDialogoVerificacionEquipoEnviado()" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
		<div id="listadoEmpaqueVerificacionEnviado" style="border:1px solid #CCC; margin:4px; font-size:10px;height:93%; overflow:auto;"></div>
	</div>
<div id="notificaciones" style=" display:none; text-align:center;position:absolute; width:300px; height:35px;z-index:1000;background:#F5F6CE; color:#000; left:40%; top:100px; padding:10px;"></div>
<div id="cargadorGeneral" class="transparenciaGeneral" style="display: none;">
	<div id="cargador" style=" display:block; text-align:center;position:absolute; width:150px; height:35px;z-index:1000;background:#FFF; color:#000; left:40%; top:300px; padding:10px;"><img src="../../img/cargador.gif" border="0"></div>
</div>
<div id="transparenciaGeneral10" class="transparenciaGeneral" style="display:none;">
	<div id="divListadoCapturaFinalizacion" class="ventanaDialogoFinalizacion">
		<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n de la Captura<div id="btnCerrarVentanaDialogo"><a href="#" onclick="cerrarVentanaFinalizacion1()" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
		<div id="listadoEmpaqueFinalizacion" style="border:1px solid #CCC; margin:4px; font-size:10px;height:87%; overflow:auto;"></div>
	</div>
</div>
<div id="opcionFormFlex" style="display:none;position: absolute; left: 0; top: 0; width: 100%; height: 100%; background: url(../../img/desv.png) repeat;">
	<div id="msgFlexCaptura">
		<div id="advertencia">Advertencia...</div>
		<div style="height:118px;width:99.5%;padding:5px;text-align:center;font-size:12px;">
			<br><br>¿Desea capturar los Equipos con Flex Nuevo?<br><br>		
			<input type="button" id="btnFormFlexSi" value="S&iacute;" onclick="colocaValorFlex('nuevo')">
			<input type="button" id="btnFormFlexNo" value="No" onclick="colocaValorFlex('procesado')">	
		</div>
	</div>
</div>
<?
include ("../../includes/pie.php");
?>