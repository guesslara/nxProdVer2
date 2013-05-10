<?
	session_start();
	include("../../includes/cabecera.php");
	include("../../includes/txtApp.php");
	$proceso="Almacen";
	$proceso1=$proceso;
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
</style>
<script type="text/javascript">
$(document).ready(function(){	
document.getElementById("txtImeiEnsamble").focus(); contadorGrid=0; 
});
</script>
<br />
    <input type="hidden" name="txtProcesoEnsamble" id="txtProcesoEnsamble" value="<?=$proceso;?>" />
    <input type="hidden" name="txtIdUsuarioEnsamble" id="txtIdUsuarioEnsamble" value="<?=$_SESSION[$txtApp['session']['idUsuario']];?>" />
    <table width="700" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#999;">
        <tr>
        	<td colspan="2"><div id="datosFormularioEnsamble" style="height:auto; background:#FF9; border:1px solid #F90; font-size:12px;"></div></td>
        </tr>
		<tr>
			<td colspan="2" style="height:25px; padding:5px; text-align:left; background:#F0F0F0; border:1px solid #CCC;">Proceso: <?=$proceso1;?></td>
		</tr>
        <tr>
            <!--<td align="left" style="background:#000; color:#FFF; height:25px;">BDCode</td>-->
            <td colspan="2" align="left" style="background:#000; color:#FFF; height:25px;">Ingreso de Equipos al Almac&eacute;n</td>
        </tr>
		<!--<tr>
			<td colspan="2" style="margin-left:5px; padding:6px;">&nbsp;Clasificadas por 
			<select name="cboClasificacion" id="cboClasificacion" onchange="habilitaElementos()">
				<option value="">Selecciona...</option>
				<option value="OK">OK</option>
				<option value="NOK">NOK</option>
			</select>
			</td>
		</tr>-->
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
        <tr>
            <!--<td><input type="text" name="txtBDCode" id="txtBDCode" size="60" value="NUF3722A00H63XAN6RR4AN000100000358380364ADN02YK" /></td>-->
            <td width="427">&nbsp;<input type="text" name="txtImeiEnsamble" id="txtImeiEnsamble" onkeypress="verificaTeclaImeiEnsamble(event)" disabled="disabled" size="35" style="font-size:32px; width:350px; height:45px;" /></td>
            <td width="271"><!--<input type="text" name="txtSerialEnsamble" id="txtSerialEnsamble" onkeypress="verificaTeclaSerialEnsamble(event)" size="35" style="font-size:18px;" />--><input id="btnRegistrarImei" type="button" value="Registrar" onclick="registrarDatos()" style="width:200px; font-size:14px; height:45px;" /></td>
        </tr>
        <tr>
            <td colspan="2" align="left"><form name="frmEquiposEnsamble" id="frmEquiposEnsamble">
            <div id="contenedorListado" style="width:700px; height:380px; border:1px solid #000; background:#FFF; overflow:auto;">
                <div id="div_grid_ensamble" style="text-align:left;width:482px; float:left; overflow:auto; height:376px; border:1px solid #CCC;"></div>
                <div style="width:200px; float:right; margin-right:1px; text-align:center; border:1px solid #CCCCCC; background:#f0f0f0; height:370px; margin:3px;">
					<br /><br /><input type="button" value="Nueva Captura" id="btnNuevaCaptura" onclick="nuevaCaptura()" style=" width:100px;height:30px; font-size:10px;" />
					<br /><br /><input type="button" id="btnActualizar" value="Actualizar" onclick="procesaFormulario()" disabled="disabled" style=" width:100px;height:30px; font-size:10px;" />
					<br /><br /><input type="button" id="btnCancelar" value="Cancelar" onclick="cancelarCaptura()" disabled="disabled" style=" width:100px;height:30px; font-size:10px; background:#FF0000; color:#FFF; font-weight:bold;" />
                    <br /><br />
		    Recibir:<br><br>
		    <select name="cboFiltroRecibo" id="cboFiltroRecibo">
			<option value="Selecciona">Selecciona...</option>
			<option value="Proceso">Proceso Normal</option>
			<option value="Scrap">Scrap</option>
		    </select><br><br>
                    Equipos en el Listado:<br /><br />
                    <div id="agregado" style="width:200px;font-size:22px;font-weight:bold;"></div>
                </div>
            </div></form>
            </td>
        </tr>
        <tr style="background:#000; color:#FFF;">
            <td></td>
            <td align="right"><!--<inpcut type="button" value="Actualizar Informaci&oacute;n" onclick="procesaFormulario()" />--></td>
            <td width="0"></td>
    </table>
<?
include ("../../includes/pie.php");
?>

