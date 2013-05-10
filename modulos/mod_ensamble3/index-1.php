<?
	session_start();
	include("../../includes/cabecera.php");
	$proceso="Ingenieria";
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
	  
	  $sqlLineas="select id,nombre from lineas";
	  $resLineas=mysql_query($sqlLineas,conectarBd());
	  if(mysql_num_rows($resLineas)==0){ echo "<script type='text/javascript'> alert('Atencion, no existen lineas para poder clasificar en la Base de Datos.\n\nCapture las lineas antes de continuar.');</script>";  }
?>
<script type="text/javascript" src="js/funcionesEnsamble.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<link  type="text/css" rel="stylesheet" href="../../css/main.css" />
<style type="text/css">
html,body,document{position:absolute;margin:0px;height:100%; width:100%; margin:0px; overflow:hidden; font-family:Verdana, Geneva, sans-serif;}
#contenedorEnsamble{height:99%; position:relative;margin:0 auto 0 auto; width:99.5%; overflow:hidden; background:#CCC;border:1px solid #000;}
#contenedorEnsamble3{width:1024px;height:99%;background:#FFF;border:1px solid #000;margin:3px auto 0 auto;}
#barraOpcionesEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
.opcionesEnsamble{border:1px solid #000;background:#FFF;height:20px;padding:5px;width:100px;text-align:center;float:left;margin-left:3px;}
#ventanaEnsambleContenido{background:#e1e1e1;border:1px solid #CCC;}
#barraInferiorEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		redimensionar();
		var tab=2;
	});
	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var altoCuerpo=altoDiv-95;
		$("#ventanaEnsambleContenido").css("height",altoCuerpo+"px");
	}
	
	window.onresize=redimensionar;
</script>	
    <input type="hidden" name="txtProcesoEnsamble" id="txtProcesoEnsamble" value="<?=$proceso;?>" />
    <input type="hidden" name="txtIdUsuarioEnsamble" id="txtIdUsuarioEnsamble" value="<?=$_SESSION['id_usuario_nx'];?>" />
<div id="contenedorEnsamble">
	<div id="contenedorEnsamble3">
		<div id="barraOpcionesEnsamble">
			<div class="opcionesEnsamble">Equipo OK</div>
			<div class="opcionesEnsamble">Capturar SCRAP</div>
		</div>
		<div id="ventanaEnsambleContenido" style="">DIV 1</div>
		<div id="barraInferiorEnsamble">DIV 2</div>
	</div>
</div>
<!--    
    <table width="800" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#999; font-size:12px; font-family:Verdana, Geneva, sans-serif;">        
        <tr>
            <td colspan="2" align="left" style="background:#000; color:#FFF; height:25px;">Ingenier&iacute;a - Captura de Imei</td>
        </tr>		
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
        <tr>
        	<td colspan="2"><div style="float:left; width:230px; text-align:center; font-weight:bold;">Imei</div><div style="float:left; width:300px; text-align:center;font-weight:bold;">Sim</div></td>
        </tr>
        <tr>            
            <td width="586">&nbsp;
            	<input type="text" name="txtImeiEnsamble" id="txtImeiEnsamble" onkeypress="verificaTeclaImeiEnsamble(event)" style="font-size:30px; width:255px; height:45px;" />
				<input type="text" name="txtSimEnsamble" id="txtSimEnsamble" onkeypress="verificaTeclaSimEnsamble(event)" style="font-size:30px; width:260px; height:45px;" /></td>
            <td width="200"><input id="btnRegistrarImei" type="button" value="Registrar" onclick="registrarDatos()" style="width:200px; font-size:14px; height:45px;" /></td>
        </tr>
        <tr>
            <td colspan="2" align="left"><form name="frmEquiposEnsamble" id="frmEquiposEnsamble">
            <div id="contenedorListado" style="width:800px; height:380px; border:1px solid #000; background:#FFF; overflow:auto;">
                <div id="div_grid_ensamble" style="text-align:left;width:585px; float:left; overflow:auto; height:380px;"></div>
                <div style="width:200px; float:right; margin-right:1px; text-align:center; border:1px solid #CCCCCC; background:#f0f0f0; height:378px;"><br /><br />
                	<input type="button" value="Actualizar" onclick="procesaFormulario()" style=" width:100px;height:30px; font-size:10px;" /><br /><br />
                    <input type="button" value="Cancelar" onclick="cancelarCaptura()" style=" width:100px;height:30px; font-size:10px; background:#FF0000; color:#FFF; font-weight:bold;" /><br /><br />
                    <input type="button" value="Nueva Captura" onclick="nuevacaptura()" style=" width:100px;height:30px; font-size:10px;  font-weight:bold;" /><br /><br />
                    Seleccione Linea:<br /><br />
                      <select name="cboLinea" id="cboLinea" style="width:130px;">
                      <option value="Selecciona" selected="selected">Selecciona linea</option>
<?
				while($rowLineas=mysql_fetch_array($resLineas)){
?>
					  <option value="<?=$rowLineas["id"];?>"><?=$rowLineas["nombre"];?></option>
<?				
				}
?>
                    </select><br /><br />
                    Clasificadas por<br /><br />
                    <select name="cboClasificacion" id="cboClasificacion" style="width:130px;">
                        <option value="Selecciona" selected="selected">Selecciona...</option>
                        <option value="ING_OK">OK</option>
                        <option value="SCRAP">SCRAP</option>
                    </select><br /><br />
                    Equipos en el Listado:<br /><br />
                    <div id="agregado" style="width:200px;font-size:22px;font-weight:bold;"></div>
                </div>
            </div></form>
            </td>
        </tr>
        <tr style="background:#000; color:#FFF;">
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
-->
<?
include ("../../includes/pie.php");
?>

