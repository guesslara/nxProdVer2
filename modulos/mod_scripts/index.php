<?
	session_start();
	include("../../includes/cabecera.php");
	$proceso="Empaque";
	//echo $_SESSION['id_usuario_nx'];
	if(!isset($_SESSION['id_usuario_nx'])){
		header("../mod_login/index.php");
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
<!--<link  type="text/css" rel="stylesheet" href="../../css/main.css" />-->
<style type="text/css">
html,body,document{position:absolute;margin:0px;height:100%; width:100%; margin:0px; overflow:hidden; font-family:Verdana, Geneva, sans-serif;}
</style>
<script type="text/javascript">
$(document).ready(function(){	
	document.getElementById("txtImeiEnsamble").focus(); contadorGrid=0; 
	var altoDoc=altoDoc=$(document).height();		
	document.getElementById("listadoEmpaque").style.height=(altoDoc-50)+"px";
	document.getElementById("detalleScripts").style.height=(altoDoc-50)+"px"
});
</script>
<div id="contenedorEmpaque" style="height:100%; position:relative; width:100%; overflow:hidden; background:#CCC;">
	<div style="height:25px; padding:5px; background:#CCC; border:1px solid #666;">&nbsp;</div>
<div id="detalleUsuarios" style="background:#FFF; border:1px solid #CCC; font-size:14px; height:99%; width:100%; overflow:auto; border:1px solid #F00;">
    <input type="hidden" name="txtProcesoEnsamble" id="txtProcesoEnsamble" value="<?=$proceso;?>" />
    <input type="hidden" name="txtIdUsuarioEnsamble" id="txtIdUsuarioEnsamble" value="<?=$_SESSION['id_usuario_nx'];?>" />
	
    
    <div id="listadoPanel" style="display:block;">
		<table border="0" cellpadding="1" cellspacing="1" style="width:99.5%;border:1px solid #0000FF; background:#FFF;">
			<tr>
				<td width="22%" valign="top">
				<div id="listadoEmpaque" style="background:#FFF; overflow:auto;"><br />
<?
				if($_SESSION['id_usuario_nx']==1 || $_SESSION['id_usuario_nx']==38){
?>
					<p>- <a href="captura_archivo_no_enviar.php" target="detalleScripts1" style="color:#06F; font-size:12px; text-decoration:none;">Subir Archivo de No Enviar</a></p>
					<p>- <a href="capturaEquiposNuevos.php" target="detalleScripts1" style="color:#06F; font-size:12px; text-decoration:none;">Subir Equipos Nuevos a BD</a></p>
					<p>- <a href="subir_archivo_cliente.php" target="detalleScripts1" style="color:#06F; font-size:12px; text-decoration:none;">Subir Archivo del Cliente a BD</a></p>
					<p><hr style="background:#999;" /></p>
					<p>- <a href="../mod_consultas/multiconsulta2.php" target="detalleScripts1" style="color:#06F; font-size:12px; text-decoration:none;">Multiconsulta de Equipos</a></p>
					<p>- <a href="../mod_consultaCliente/" target="_blank" style="color:#06F; font-size:12px; text-decoration:none;">Informaci&oacute;n Cliente / IQ</a></p>
					<p><hr style="background:#999;" /></p>					
<?php
				}
				if($_SESSION['id_usuario_nx']==1 || $_SESSION['id_usuario_nx']==38 || $_SESSION['id_usuario_nx']==37){
?>
					<p>- <a href="marcarEquiposCI.php" target="detalleScripts1" style="color:#06F; font-size:12px; text-decoration:none;">Marcar Equipos CI</a></p>
<?php
				}
?>					
				</div>
				</td>
				<td width="78%">
					<!--<div id="detalleEmpaque" style="background:#FFF; overflow:auto;border:1px solid #F00;"></div>-->
                    <iframe name="detalleScripts1" id="detalleScripts" style="background:#FFF; width:99%; overflow:auto;border:1px solid #F00;">Iframe no soportado por el navegador.</iframe>
					<div id="capturaFact" style=" display:none;background:#FFF; overflow:auto; display:none;border:1px solid #F00;">H"</div>
				</td>
			</tr>
		</table>
    </div>
    <div id="datosIniciales" style="display:none;"><br />
		<table width="800" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#FFF; border:1px solid #CCC;">
		  <tr>
			<td colspan="4" style="background:#000; color:#FFF; height:25px; font-weight:bold;">Empaque - Nextel</td>
		  </tr>
		  <tr>
			<td width="188" style="background:#f0f0f0; border:1px solid #CCC;">Fecha</td>
			<td width="208"><input type="text" name="txtFecha" id="txtFecha" value="<?=date("Y-m-d");?>" readonly="readonly" /></td>
			<td colspan="2">&nbsp;</td> 
		  </tr>
          <tr>
          	<td style="background:#f0f0f0; border:1px solid #CCC;">T&eacute;cnico</td>
            <td colspan="3"><input type="text" name="txtTecnico" id="txtTecnico" value="<?=$_SESSION['nombre_nx']." ".$_SESSION['apellido_nx'];?>" readonly="readonly" style="width:300px;" /></td>
          </tr>
		  <tr>
			<td style="background:#f0f0f0; border:1px solid #CCC;">Entrega</td>
			<td><input type="text" name="txtEntrega" id="txtEntrega" /></td>
			<td colspan="2">&nbsp;</td>
		  </tr>
		  <tr>
			<td style="background:#f0f0f0; border:1px solid #CCC;">Modelo</td>
			<td>
			<select name="cboModelo" id="cboModelo">
				<option value="" selected="selected">Selecciona...</option>
<?
				//se extrae el catalogo de modelos
				$sqlModelo="select * from cat_modradio";
				$resModelo=mysql_query($sqlModelo,conectarBd());
				if(mysql_num_rows($resModelo)==0){
					echo "No hay modelos en la Base de Datos";
				}else{
					while($rowModelo=mysql_fetch_array($resModelo)){
?>
				<option value="<?=$rowModelo['id_modelo'];?>"><?=$rowModelo['modelo'];?></option>
<?				
					}
				}
?>				
			</select>
			</td>
			<td colspan="2">&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan="4"><hr style="background:#999999;" /></td>
		  </tr>      
		  <tr>
			<td colspan="3">&nbsp;</td>
			<td width="306" align="right"><input type="button" value="Guardar Informaci&oacute;n" onclick="guardaMovimiento()" /></td>
		  </tr>
		</table><br /><br />
	</div>
    <div id="notificaciones" style="display:none;">
	<table width="900" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#999;">
		<tr>
			<td><div id="resultado" style="background:#FFFFCC; border:#FFCC00; height:auto;"></div></td>
		</tr>
	</table>
    </div><br />	
	<div id="capturaCaja" style="display:none;">
		<table width="700" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#999;">
		  <tr>
			<td colspan="3"><div id="erroresCaptura" style="background:#FFFFCC; border:#FFCC00; height:auto;"></div></td>
		  </tr>
		  <tr>
			<!--<td align="left" style="background:#000; color:#FFF; height:25px;">BDCode</td>-->
			<td colspan="3" align="left" style="background:#000; color:#FFF; height:25px;">Imei</td>
		  </tr>
		  <tr>
			<!--<td><input type="text" name="txtBDCode" id="txtBDCode" size="60" value="NUF3722A00H63XAN6RR4AN000100000358380364ADN02YK" /></td>-->
			<td width="427"><input type="text" name="txtImeiEnsamble" id="txtImeiEnsamble" onkeypress="verificaTeclaImeiEnsamble(event)" size="35" style="font-size:32px; width:350px; height:45px;" /></td>
			<td width="271"><input type="text" name="txtSerialEnsamble" id="txtSerialEmpaque" onkeypress="verificaTeclaSerialEnsamble(event)" size="35" style="font-size:32px; width:350px; height:45px;" /></td>
			<td><!--<input type="button" value="Registrar" onclick="colocaDatosGuiEnsamble()" style="width:200px; font-size:14px; height:45px;" />--></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center"><form name="frmEquiposEnsamble" id="frmEquiposEnsamble">
			  <div id="contenedorListado" style="width:700px; height:380px; border:1px solid #000; background:#FFF; overflow:auto;">
				<div id="div_grid_ensamble" style="text-align:left;"></div>
			  </div>
			</form></td>
			<td valign="top" style="font-size:20px; text-align:center;">
				<input type="hidden" name="txtIdCaja" id="txtIdCaja" />
				<input type="hidden" name="idEmpaqueCaptura" id="idEmpaqueCaptura" />
				<div id="capturados" style="font-size:12px; text-align:left;"></div>
				<div id="fechaEmpaque" style="font-size:12px; text-align:left;"></div>
				<div id="modeloEmpaque" style="font-size:12px; text-align:left;"></div>Caja:
				<div id="cajaInfoCaptura" style="border:1px solid #000; width:188px; height:125px; background:#FFF; font-size:36px;"></div>				
			</td>
		  </tr>
		  <tr style="background:#000; color:#FFF;">
			<td><div id="agregado" style="width:200px;"></div></td>
			<td align="right">&nbsp;</td>
			<td width="0">&nbsp;<input type="button" value="Capturar caja nueva" onclick="" style="width:200px; font-size:14px; height:45px;" /></td>
		  </tr>
		</table>
	</div>
  </div>
</div>
<?
include ("../../includes/pie.php");
?>

