<?
	session_start();
	include("../../includes/cabecera.php");
	include("../../includes/txtApp.php");
	$proceso="Ingenieria";
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
<link  type="text/css" rel="stylesheet" href="../../css/main.css" />
<style type="text/css">
html,body,document{position:absolute;margin:0px;height:100%; width:100%; font-family:Verdana, Arial, Helvetica, sans-serif; overflow:auto;}
#contenedorModificacion{border:1px solid #CCC;overflow:auto;}
#VistaPreviaMod{border:1px solid #000; float:left; margin:5px;width:380px;height:98%; background:#CCC;}
#detalleVistaPrevia{border1px solid #FF0000; margin:3px; background:#FFF; height:92%;overflow:auto;}
#transparenciaGeneral{background:url(../../img/desv.png) repeat;position: absolute; left: 0; top: 0; width: 100%; height: 100%; z-index:20;}
#barraTitulo1VentanaDialogo{ height:20px; padding:5px; color:#FFF; font-size:12px; background:#000; font-family:Verdana, Geneva, sans-serif;}
#btnCerrarVentanaDialogo{ float:right;}
.ventanaDialogo{
	border:1px solid #000;
	background-color:#FFF;
	height:200px;
	width:300px;
	position:absolute;
	left:50%;
	top:50%;
	margin-left:-150px;
	margin-top:-100px;        
	/*z-index:1;*/
	/*sombra*/
	-webkit-box-shadow:10px 10px 5px #CCC;
	-moz-box-shadow:10px 10px 5px #CCC;
	filter: shadow(color=#CCC, direction=135,strength=2);
}
.div{ font-size:12px; color:#F00; font-weight:bold; text-align:center; font-family:Verdana, Geneva, sans-serif;}
</style>
<script type="text/javascript">
$(document).ready(function(){
	document.getElementById("txtImeiEnsamble").focus(); contadorGrid=0;
	altoDoc=$(document).height();	
	document.getElementById("contenedorModificacion").style.height=(altoDoc-9)+"px";
	document.getElementById("divModificacion").style.height=(altoDoc-110)+"px";
	//document.getElementById("VistaPreviaMod").style.height=(altoDoc-140)+"px";
});
</script>
<div id="contenedorModificacion">
	<div style="float:left;border:1px solid #CCC; height:99.5%; margin-left:10px;">
		<input type="hidden" name="txtProcesoEnsamble" id="txtProcesoEnsamble" value="<?=$proceso;?>" />
		<input type="hidden" name="txtIdUsuarioEnsamble" id="txtIdUsuarioEnsamble" value="<?=$_SESSION[$txtApp['session']['idUsuario']];?>" /><br />
		<table width="600" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#999;">
			<tr>
				<td colspan="2"><div id="datosFormularioEnsamble" style="height:auto; background:#FF9; border:1px solid #F90; font-size:12px;;"></div></td>
			</tr>
			<tr>
			    <td colspan="2" align="left" style="background:#000; color:#FFF; height:25px;">Buscar Imei</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;
					<input type="text" name="txtImeiEnsamble" id="txtImeiEnsamble" onkeypress="verificaTeclaImeiEnsamble(event,1984)" size="35" style="font-size:32px; width:350px; height:35px;" />
					<input type="button" value="Buscar" onclick="buscarRegistros()" style="width:200px; font-size:14px; height:35px;" />
				</td>
			</tr>
			<tr>
				<td colspan="2"><div id="divModificacion" style=" background:#FFF;border:1px solid #000; height:50%;"></div></td>
			</tr>
		</table>
	</div>
	<div id="VistaPreviaMod">
		<div style="height:25px; padding:5px; background:#000; color:#FFF;">Detalles</div>
		<div id="detalleVistaPrevia"></div>
	</div>
</div>
<div id='transparenciaGeneral' style="display:none;">
	<div id="ventanaDialogo" class="ventanaDialogo">
    	<div id="barraTitulo1VentanaDialogo">IQe Sisco Verificaci&oacute;n...<div id='btnCerrarVentanaDialogo'><a href='#' onclick="accionesVentana('ventanaDialogo','0')" title="Cerrar Ventana Dialogo"><img src="../../img/close.gif" border="0" /></a></div></div>
        <div id="msgVentanaDialogo"></div>
        <br><form name='frmVerificaUsuario' id='frmVerificaUsuario' action='' method='post'><table border='0' width='98%' cellpading='1' cellspacing='1'><tr><td align='right'><span style='color:#000;'>Usuario:</span></td><td align='center'><input type='text' name='txtUsuarioMod' id='txtUsuarioMod' /></td></tr><tr><td colspan='2'>&nbsp;</td></tr><tr><td align='right'><span style='color:#000;'>Password:</span></td><td align='center'><input type='password' name='txtPassMod' id='txtPassMod' /></td></tr><tr><td colspan='2'>&nbsp;<div id='verificacionUsuario' class='div'>&nbsp;</div></td></tr><tr><td colspan='2' align='center'><input type='button' value='<< Continuar >>' onclick='verificaUsuario()'></td></tr></table></form>
    </div>
</div>
<?
include ("../../includes/pie.php");
?>

