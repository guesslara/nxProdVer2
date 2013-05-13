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
<link rel="stylesheet" type="text/css" href="css/estilosEmpaque.css" />
<script type="text/javascript">
	$(document).ready(function(){
            redimensionar();
            $("#txtImeiEnsamble").attr("value","");
            $("#txtImeiEnsamble").focus();
	});
	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var anchoDiv=$("#contenedorEnsamble3").width();
		var altoCuerpo=altoDiv-52;
		$("#detalleEmpaque").css("height",altoCuerpo+"px");
		$("#ventanaEnsambleContenido2").css("height",altoCuerpo+"px");
		$("#detalleEmpaque").css("width",(anchoDiv-3)+"px");
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
</script>
<style type="text/css">
.estiloBuscar{float: left;width: 158px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:12px;text-align:center;}
.estiloBuscar:hover{cursor: pointer;background: #CCC;}
.estilosResultados{float:left;width:150px;height:20px;padding:5px;background:#F0F0F0;border:1px solid #CCC;font-size:11px;text-align:left;color: #333;}
.estilosResultados:hover{background: #CCC;cursor: pointer;}
.estilosTitulos{float: left;margin-top: 4px;}
.estiloNroDatos{float: right;border: 0px solid #000;margin: 4px 4px;color: #333;}
</style>
<div id="contenedorEnsamble">
	<div id="contenedorEnsamble3">
		<div id="barraOpcionesEnsamble">                    
                    <div id="" style="float:left;width:305px;height:20px;padding:5px;text-align:left;margin-top: -5px;border: 0px solid #FF0000;">
			<input type="text" name="txtImeiEnsamble" id="txtImeiEnsamble" onkeypress="verificaTeclaImeiEnsamble(event)" size="35" style="font-size:28px; width:300px; height:25px;" />			
		    </div>
		    <div style="float: left;border: 0px solid #FF0000;margin-top: 2px;width: 80px;">
			<select name="cboFiltroBusqueda" id="cboFiltroBusqueda" style="font-size:16px;height: 25px;">
				<option value="imei" selected="selected">Imei</option>
				<option value="serial">Serial</option>
			</select>
		    </div>
                    <div class="estiloBuscar" onclick="buscarRegistros()">Buscar</div>
                    <div id="cargadorEmpaque" style="float:right;width:200px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:15px;text-align:right;">B&uacute;squeda de Equipos</div>
		</div>
		<!--<div id="infoEnsamble3">			-->
		    <!--<div id="listadoEmpaque" style="border:1px solid #e1e1e1;background:#fff; height:99%;width:97%;font-size:12px;margin:3px;overflow: auto;">-->
                        <!--Opciones para los imeis-->                        
                        <!--Listado de Imei's<textarea id="txt_archivo_excel" cols="20" rows="30" style="height:95%;"></textarea>-->
                        <!--Fin de las Opciones-->
                    <!--</div>-->
		<!--</div>-->
		<div id="detalleEmpaque" class="ventanaEnsambleContenido" style="overflow: hidden;">
                    <!--<div id="" class="estilosResultados" onclick="mostrarTab('eqProceso')">
			<div class="estilosTitulos">Eq. Proceso</div>
			<div id="totalEquiposProceso" class="estiloNroDatos">#</div>
		    </div>
                    <div id="" class="estilosResultados" onclick="mostrarTab('eqEnviado')">
			<div class="estilosTitulos">Eq. Enviado</div>
			<div id="totalEquiposEnviados" class="estiloNroDatos">#</div>
		    </div>
                    <div id="" class="estilosResultados" onclick="mostrarTab('eqNoEncontrado')">
			<div class="estilosTitulos">No Encontrados</div>
			<div id="totalEquiposNoEncontrados" class="estiloNroDatos">#</div>
		    </div>-->
                    <div id="eqProceso" style="border: 1px solid #CCC;width: 99.3%;height: 99%;margin: 3px 3px 3px 3px;overflow: auto;"></div>
                    <div id="eqEnviado" style="display: none;border: 1px solid #CCC;width: 99.3%;height: 94.5%;margin: 33px 3px 3px 3px;overflow: auto;"></div>
                    <div id="eqNoEncontrado" style="display: none;border: 1px solid #CCC;width: 99.3%;height: 94.5%;margin: 33px 3px 3px 3px;overflow: auto;"></div>
                </div>
		<div id="ventanaEnsambleContenido2" class="ventanaEnsambleContenido" style="display:none;"></div>
		<div style="clear:both;"></div>
		<!--<div id="barraInferiorEnsamble">			
			<div id="erroresCaptura"></div>
			<div id="opcionCancelar"><input type="button" onclick="cancelarCaptura()" value="Cancelar" style=" width:100px; height:30px;padding:5px;background:#FF0000;color:#FFF;border:1px solid #FF0000;font-weight:bold;" /></div>
		</div>-->
	</div>
</div>
<!--
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
            <td align="right"></td>
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
-->
<?
include ("../../includes/pie.php");
?>

