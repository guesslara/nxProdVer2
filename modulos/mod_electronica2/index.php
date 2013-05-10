<?
	session_start();include 
	("../../includes/cabecera.php");
	include("../../includes/txtApp.php");
	$proceso="Tarjeta diagnosticada";
	$proceso1=$proceso;
	if(!isset($_SESSION[$txtApp['session']['idUsuario']])){
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
	 $id_usuario=$_SESSION[$txtApp['session']['idUsuario']];
?>
<script type="text/javascript" src="js/funcionesEnsamble.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<!--se incluyen los recursos para el grid-->
<script type="text/javascript" src="../../recursos/grid/grid.js"></script>
<link rel="stylesheet" type="text/css" href="../../recursos/grid/grid.css" />
<!--fin inclusion grid-->
<link  type="text/css" rel="stylesheet" href="../../css/main.css" />
<style type="text/css">
html,body,document{position:absolute;margin:0px;height:100%; width:100%; margin:0px; overflow:hidden; font-family:Verdana, Geneva, sans-serif;}
#contenedorEnsamble{height:99%; position:relative;margin:0 auto 0 auto; width:99.5%; overflow:auto; background:#CCC;border:1px solid #000;}
#contenedorEnsamble3{width:1024px;height:99%;background:#FFF;border:1px solid #000;margin:3px auto 0 auto;}
#barraOpcionesEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
.opcionesEnsamble{border:1px solid #000;background:#FFF;height:20px;padding:5px;width:100px;text-align:center;float:left;margin-left:3px;}
.opcionesEnsamble:hover{background:#e1e1e1;cursor:pointer;}
.ventanaEnsambleContenido{background:#fff;border:1px solid #CCC;overflow:auto;width:800px; float:left;}
#barraInferiorEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
#opcionFlex{border:1px solid #CCC;font-size:12px;font-weight:bold;background:#FFF;height:20px;padding:5px;width:100px;text-align:center;float:left;margin-left:3px;}
#opcionCancelar{border:1px solid #CCC;font-size:12px;font-weight:bold;background:#FFF;width:100px;text-align:center;float:right;margin-left:3px;}
#erroresCaptura{float:left; margin-left:3px; height:20px;padding:5px; width:500px; background:#FFF;border:1px solid #000;overflow:auto;}
#infoEnsamble3{width:215px;border:1px solid #CCC;background:#f0f0f0;float:right;}
#capturaCatalogoFallas{border:1px solid #000;background-color:#CCC;height:400px;width:450px;position:absolute;left:50%;top:50%;margin-left:-225px;margin-top:-200px;z-index:4;text-align: center;}
#advertencia{height:20px;padding:5px;background:#000;color:#FFF; text-align:left;font-size:12px;}
/******************/
.bordesFilaTablaFallas{border-right: 1px solid #CCC;text-align: right;}
.divCatFallas{height: 200px; width: 300px;overflow: auto;}
.divSeparador{border: 1px dotted #000;}
.divContenedorImeiCatalogoFallas{height: 23px;border: 0px solid #ff0000;}
.divImeiCatalogoFallas{float: left;text-align: center;width: 148px;height: 15px;padding: 3px;border: 1px solid #000;background: #CCC;color: #000;font-weight: bold;}
#listadoImeisAReparacion{float: left;overflow: auto;height: 95%;width: 98%;border: 0px solid #ff0000;}
.cuadroContadorDivFallas{float: left;width: 40px;height: 15px;padding: 3px;border: 1px solid #000;background: #CCC;color: #000;}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		redimensionar();
		contarEquiposDiag();
		cargaCatalogoFallas();
	});
	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var altoCuerpo=altoDiv-95;
		$("#ventanaEnsambleContenido").css("height",altoCuerpo+"px");
		$("#ventanaEnsambleContenido2").css("height",altoCuerpo+"px");
		$("#infoEnsamble3").css("height",altoCuerpo+"px");
	}
	
	window.onresize=redimensionar;
</script>
    <input type="hidden" name="txtProcesoDiagnostico" id="txtProcesoDiagnostico" value="<?=$proceso;?>" />
    <input type="hidden" name="txtIdUsuarioDiagnostico" id="txtIdUsuarioDiagnostico" value="<?=$id_usuario;?>" />
<div id="contenedorEnsamble">
	<div id="contenedorEnsamble3">
		<div id="barraOpcionesEnsamble">
			<div class="opcionesEnsamble" onclick="mostrarTab('ventanaEnsambleContenido')" title="Capturar Equipo OK">Capturar OK</div>
			<div class="opcionesEnsamble" onclick="mostrarTab('ventanaEnsambleContenido2')" title="Capturar WIP2">Capturar WIP2</div>
			<div style="float:right;width:200px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:13px;font-size:16px;text-align:center;margin-right:217px;">Diagnostico Tarjetas</div>
		</div>
		<div id="ventanaEnsambleContenido" class="ventanaEnsambleContenido" style="display:block;"></div>
		<div id="ventanaEnsambleContenido2" class="ventanaEnsambleContenido" style="display:none;">
			<!--<div class="divContenedorImeiCatalogoFallas">
				<div class="divImeiCatalogoFallas">Imei</div>
				<div class="divImeiCatalogoFallas">Resultado</div>
			</div>-->
			<!--<div id="listadoImeisAReparacion"></div>-->
			<!--<div style="float: left;height: 99.5%;width: 50%;border: 1px solid blue;margin-left: 3px;">
				<div style="background: #000;color: #FFF;height: 15px;padding: 5px;">Cat&aacute;logo de Fallas</div>
				<div id="divCatalogoFallas" style="overflow: auto;height: 95.5%;border: 1px solid #green;"></div>
			</div>-->			
		</div>
		<div id="infoEnsamble3" style="overflow: auto;"><br>
			<div id="infoLineaCaptura" style="border:1px solid #e1e1e1;background:#fff; height:100px;width:180px;font-size:24px;text-align:center;margin:0 auto 0 auto;"></div>
			<div id="infoCapturaFlex" style="border:1px solid #e1e1e1;background:#fff; height:100px;width:180px;font-size:12px;text-align:left;margin:0 auto 0 auto;"></div>
			<div id="infoEquiposDiag" style="border:1px solid #e1e1e1;background:#fff; height:220px;width:180px;font-size:20px;text-align:center;margin:0 auto 0 auto;"></div>
			<input type="hidden" id="txtOpcionFlex" name="txtOpcionFlex" value="" />
		</div>
		<div style="clear:both;"></div>
		<div id="barraInferiorEnsamble">			
			<div id="erroresCaptura"></div>
			<div id="opcionCancelar"><input type="button" onclick="cancelarCaptura()" value="Cancelar" style=" width:100px; height:30px;padding:5px;background:#FF0000;color:#FFF;border:1px solid #FF0000;font-weight:bold;" /></div>
		</div>
	</div>
</div>
<div id="opcionFormFlex" style="display:none;position: absolute; left: 0; top: 0; width: 100%; height: 100%; background: url(../../img/desv.png) repeat;">
	<div id="capturaCatalogoFallas">
		<div id="advertencia">
			<div style="float: left;">Cat&aacute;logo de Fallas</div>
			<div style="float: right;"><a href="#" onclick="cerrarCatalogoFallas()"><img src="../../img/close.gif" border="0"></a></div>
		</div>
		<div id="divCatalogoFallas" style="border: 0px solid #FF0000;background: #FFF;height:80%;width:97%;padding:5px;text-align:left;font-size:12px;overflow: auto;">
			
		</div>
		<input type="hidden" name="txtImeiWip2" id="txtImeiWip2" />
		<input type="hidden" name="txtImeiWip2Res" id="txtImeiWip2Res" />
		<input type="button" value="Guardar Informaci&oacute;n" style="margin-top: 5px;height: 28px;background: #FFF;color: #000;font-weight: bold;" onclick="guardaDatosWip2()">
	</div>
</div>    
<!--
    <table width="700" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#999;">
        <tr>
			<td colspan="2" style="height:25px; padding:5px; text-align:left; background:#F0F0F0; border:1px solid #CCC;">Proceso: <?=$proceso1;?></td>
		</tr>
		<tr>            
            <td colspan="2" align="left" style="background:#000; color:#FFF; height:25px;">Captura de Imei</td>
        </tr>
        <tr>            
            <td width="481">
				<input type="text" name="txtImeiEnsamble" id="txtImeiEnsamble" onkeypress="verificaTeclaImeiEnsamble(event)" size="35" style="font-size:32px; width:350px; height:45px;" />
				<input type="radio" name="rdbFiltroTarjeta" id="rdbFiltroTarjeta" value="OK" checked="checked" />OK 
				<input type="radio" name="rdbFiltroTarjeta" id="rdbFiltroTarjeta" value="SCRAP" />SCRAP
		  </td>
            <td width="217">
          <input type="button" value="Registrar" onclick="registrarDatos()" style="width:200px; font-size:14px; height:45px;" /></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><form name="frmEquiposEnsamble" id="frmEquiposEnsamble">
            <div id="contenedorListado" style="width:700px; height:380px; border:1px solid #000; background:#FFF; overflow:auto;">
                <div id="div_grid_ensamble" style="text-align:left;width:482px; float:left; overflow:auto; height:376px; border:1px solid #CCC;"></div>
                <div style="width:200px; float:right; margin-right:1px; text-align:center; border:1px solid #CCCCCC; background:#f0f0f0; height:370px; margin:3px;">			
			<br /><br /><input type="button" id="btnActualizar" value="Actualizar" onclick="procesaFormulario()" style=" width:100px;height:30px; font-size:10px;" />
			<br /><br /><input type="button" id="btnCancelar" value="Cancelar" onclick="cancelarCaptura()" style=" width:100px;height:30px; font-size:10px; background:#FF0000; color:#FFF; font-weight:bold;" />
			<br /><br /><input type="button" id="btnCancelar" value="Nueva Captura" onclick="resetForm()" style=" width:100px;height:30px; font-size:10px; font-weight:bold;" />
            <br /><br />
                    Equipos en el Listado:<br /><br />
                    <div id="agregado" style="width:200px;font-size:22px;font-weight:bold;"></div>
		</div>
            
            </div></form>
            </td>
        </tr>
        <tr style="background:#000; color:#FFF;">
            <td></td>
            <td align="right"></td>
            <td width="0"></td>
        </tr>
    </table>
-->
<?
include ("../../includes/pie.php");
?>

