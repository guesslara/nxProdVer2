<?
	session_start();
	include ("../../includes/cabecera.php");
	include("../../includes/txtApp.php");
	$proceso="Ensamble";
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
html,body,document{position:absolute;margin:0px;height:100%; margin:0px; width:100%;}
.div{color:red; text-align:center;font-weight:bold;}
</style>
<script type="text/javascript">
$(document).ready(function(){	
	document.getElementById("txtImeiEnsamble").focus(); contadorGrid=0; 
});
document.onkeypress=function(elEvento){
	var evento=elEvento || window.event;
	var codigo=evento.charCode || evento.keyCode;
	var caracter=String.fromCharCode(codigo);
	if(codigo==27){
		cerrarVentanaValidacion();
	}
}
</script>
<div id="contenedorTest" style="height:100%; width:100%; overflow:hidden; background:#CCC;">	
	<div id="detalleUsuarios" style=" position:relative;background:#FFF; border:1px solid #CCC; font-size:14px; height:99%; width:100%; overflow:auto;"><br />
    <input type="hidden" name="txtProcesoEnsamble" id="txtProcesoEnsamble" value="<?=$proceso;?>" />
    <input type="hidden" name="txtIdUsuarioEnsamble" id="txtIdUsuarioEnsamble" value="<?=$_SESSION[$txtApp['session']['idUsuario']];?>" />
    <table width="900" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#999;">
        <tr>
        	<td colspan="2"><div id="datosFormularioEnsamble" style="height:auto; background:#FF9; border:1px solid #F90; font-size:12px;"></div></td>
        </tr>
        <tr>
            <td colspan="2" align="left" style="background:#000; color:#FFF; height:25px;">B&uacute;squeda de Equipos Introduzca Imei</td>
        </tr>
        <tr>
            <td colspan="2" width="427">&nbsp;
		<input type="text" name="txtImeiEnsamble" id="txtImeiEnsamble" onkeypress="verificaTeclaImeiEnsamble(event)" size="35" style="font-size:32px; width:350px; height:45px;" />
		<select name="cboFiltroBusqueda" id="cboFiltroBusqueda" style="font-size:20px;">
			<option value="imei" selected="selected">Imei</option>
			<option value="serial">Serial</option>
		</select>
		<input type="button" value="Buscar" onclick="buscarRegistros()" style="width:200px; font-size:14px; height:45px;" />
	    </td>
            <!--<td width="271"></td>-->
        </tr>
        <tr>
            <td colspan="2" align="center"><form name="frmEquiposEnsamble" id="frmEquiposEnsamble">
            <div id="contenedorListado" style="width:900px; height:550px; border:1px solid #000; background:#FFF; overflow:auto;">
                <div id="div_grid_ensamble" style="text-align:left;"></div>
            </div></form>
            </td>
        </tr>
        <tr style="background:#000; color:#FFF;">
            <td><div id="agregado" style="width:200px;"></div> </td>
            <td align="right"><!--<input type="button" value="Actualizar Informaci&oacute;n" onclick="procesaFormulario()" />--></td>
            <td width="0"></td>
        </tr>
    </table>
    </div>
</div>
<div id="transparenciaGeneral" style="display:none;">
	<div id="ventanaDialogo" class="ventanaDialogo">
    	<div id="barraTitulo1VentanaDialogo">IQe Sisco Verificaci&oacute;n...<div id='btnCerrarVentanaDialogo'><a href='#' onclick="accionesVentana('ventanaDialogo','0')" title="Cerrar Ventana Dialogo"><img src="../../img/close.gif" border="0" /></a></div></div>
        <div id="msgVentanaDialogo"></div>
        <br><form name='frmVerificaUsuario' id='frmVerificaUsuario' action='' method='post'><table border='0' width='98%' cellpading='1' cellspacing='1'><tr><td align='right'><span style='color:#000;'>Usuario:</span></td><td align='center'><input type='text' name='txtUsuarioMod' id='txtUsuarioMod' /></td></tr><tr><td colspan='2'>&nbsp;</td></tr><tr><td align='right'><span style='color:#000;'>Password:</span></td><td align='center'><input type='password' name='txtPassMod' id='txtPassMod' /></td></tr><tr><td colspan='2'>&nbsp;<div id='verificacionUsuario' class='div'>&nbsp;</div></td></tr><tr><td colspan='2' align='center'><input type='button' value='<< Continuar >>' onclick='verificaUsuario()'></td></tr></table></form>
    </div>
</div>
<?
include ("../../includes/pie.php");
?>

