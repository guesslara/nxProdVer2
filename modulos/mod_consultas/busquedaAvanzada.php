<?
	session_start();include 
	("../../includes/cabecera.php");
	include("../../includes/txtApp.php");
	if(!isset($_SESSION[$txtApp['session']['idUsuario']])){
		echo "<script type='text/javascript'> window.location.href='../cerrar_sesion.php'; </script>";
		exit;
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
<script type="text/javascript" src="js/funcionesBusquedaAvanzada.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="js/calendar-green.css"  title="win2k-cold-1" />
  <!-- librería principal del calendario -->
<script type="text/javascript" src="js/calendar.js"></script>
  <!-- librería para cargar el lenguaje deseado -->
<script type="text/javascript" src="js/calendar-es.js"></script>
   <!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
<script type="text/javascript" src="js/calendar-setup.js"></script>
<link  type="text/css" rel="stylesheet" href="../../css/main.css" />
<style type="text/css">
html,body,document{position:absolute;margin:0px;height:100%; width:100%; margin:0px; overflow:hidden; font-family:Verdana, Geneva, sans-serif;}
#contenedorEnsamble{height:99%; position:relative;margin:0 auto 0 auto; width:99.5%; overflow:auto; background:#CCC;border:1px solid #000;}
#contenedorEnsamble3{width:99%;height:99%;background:#FFF;border:1px solid #000;margin:3px auto 0 auto;}
#barraOpcionesEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
.opcionesEnsamble{border:1px solid #000;background:#FFF;height:20px;padding:5px;width:100px;text-align:center;float:left;margin-left:3px;}
.opcionesEnsamble:hover{background:#e1e1e1;cursor:pointer;}
.ventanaEnsambleContenido{background:#fff;border:1px solid #CCC;overflow:auto;width:99.8%; float:left;}
#barraInferiorEnsamble{height:33px;padding:5px;background:#f0f0f0;border:1px solid #CCC;}
#opcionFlex{border:1px solid #CCC;font-size:12px;font-weight:bold;background:#FFF;height:20px;padding:5px;width:100px;text-align:center;float:left;margin-left:3px;}
#opcionCancelar{border:1px solid #CCC;font-size:12px;font-weight:bold;background:#FFF;width:100px;text-align:center;float:right;margin-left:3px;}
#erroresCaptura{float:left; margin-left:3px; height:20px;padding:5px; width:500px; background:#FFF;border:1px solid #000;overflow:auto;}
#infoEnsamble3{width:215px;border:1px solid #CCC;background:#f0f0f0;float:right;}
#msgFlexCaptura{border:1px solid #000;background-color:#FFF;height:150px;width:300px;position:absolute;left:50%;top:50%;margin-left:-150px;margin-top:-75px;z-index:4;}
#advertencia{height:20px;padding:5px;background:#000;color:#FFF; text-align:left;font-size:12px;}
#msgCargador{border:1px solid #CCC;background-color:#FFF;height:50px;width:50px;position:absolute;left:50%;top:50%;margin-left:-25px;margin-top:-25px;z-index:4;}
.datosTablaBusqueda{border:1px solid #CCC;background:#f0f0f0;height:20px;padding:5px;text-align:left;font-weight:bold;}
.datosTablaBusquedaResultados{border:1px solid #CCC;background:#f0f0f0;height:20px;padding:5px;text-align:center;font-weight:bold;}
.datosTablaBusquedaResultadosConsulta{border-bottom:1px solid #CCC;height:20px;padding:5px;text-align:center;}
.opcionMenu{float:left;width:200px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:13px;font-size:16px;text-align:center;margin-left:5px;}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		redimensionar();
                mostrarFormulario();
		//contarEquiposIng();
	});
	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var altoCuerpo=altoDiv-48;
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
			<div class="opcionMenu">B&uacute;squeda avanzada</div>
                        <div class="opcionMenu" onclick="mostrarFormDatos()" style="cursor:pointer;">Cambiar Parametros</div>
		</div>
		<div id="ventanaEnsambleContenido" class="ventanaEnsambleContenido"></div>
		<div id="ventanaEnsambleContenido2" class="ventanaEnsambleContenido" style="display:none;"></div>
		<!--<div id="infoEnsamble3"><br>
			<div id="infoLineaCaptura" style="border:1px solid #e1e1e1;background:#fff; height:100px;width:180px;font-size:24px;text-align:center;margin:0 auto 0 auto;"></div>
			<div id="infoCapturaFlex" style="border:1px solid #e1e1e1;background:#fff; height:100px;width:180px;font-size:12px;text-align:left;margin:0 auto 0 auto;"></div>
			<div id="infoEquiposIng" style="border:1px solid #e1e1e1;background:#fff; height:220px;width:180px;font-size:20px;text-align:center;margin:0 auto 0 auto;"></div>
			<input type="hidden" id="txtOpcionFlex" name="txtOpcionFlex" value="" />
		</div>-->
		<div style="clear:both;"></div>
		<!--<div id="barraInferiorEnsamble">			
			<div id="erroresCaptura"></div>
			<div id="opcionCancelar"><input type="button" onclick="cancelarCaptura()" value="Cancelar" style=" width:100px; height:30px;padding:5px;background:#FF0000;color:#FFF;border:1px solid #FF0000;font-weight:bold;" /></div>
		</div>-->
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
<div id="cargando" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; background:url(../../img/desv.png) repeat">
	<div id="msgCargador"><div style="padding:10px;"><img src="../../img/cargador.gif" border="0" /></div></div>
</div>
<?
include ("../../includes/pie.php");
?>

