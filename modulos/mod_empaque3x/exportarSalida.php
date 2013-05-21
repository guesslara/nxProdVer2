<?
	$n=$_GET['n'];	
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
	//se extrae la informacion de la entrega
	$sqlEntrega="SELECT * FROM (entregas_nextel INNER JOIN cat_destinos ON entregas_nextel.destino = cat_destinos.id) INNER JOIN cat_modradio ON entregas_nextel.id_modelo = cat_modradio.id_modelo WHERE entregas_nextel.id = '".$n."'";
	$resEntrega=mysql_query($sqlEntrega,conectarBd());
	$rowEntrega=mysql_fetch_array($resEntrega);
	//se extraen los items de la entrega
	$sqlEntregaItems="SELECT entregas_nextel_items.id AS idEntregasItems, id_entrega, equipos.id_radio AS idRadioEquipo, imei, serial, sim, lote, modelo, numeroCajaFinal
	FROM (entregas_nextel_items INNER JOIN equipos ON entregas_nextel_items.id_radio = equipos.id_radio) INNER JOIN cat_modradio ON equipos.id_modelo = cat_modradio.id_modelo
	WHERE id_entrega = '".$n."' ORDER BY numeroCajaFinal";
	$resEntregaItems=mysql_query($sqlEntregaItems,conectarBd());
	if(mysql_num_rows($resEntregaItems)==0){
		$sqlEntregaItems="SELECT entregas_nextel_items.id AS idEntregasItems, id_entrega, equipos_enviados.id_radio AS idRadioEquipo, imei, serial, sim, lote, modelo, numeroCajaFinal
		FROM (entregas_nextel_items INNER JOIN equipos_enviados ON entregas_nextel_items.id_radio = equipos_enviados.id_radio) INNER JOIN cat_modradio ON equipos_enviados.id_modelo = cat_modradio.id_modelo
		WHERE id_entrega = '".$n."' ORDER BY numeroCajaFinal";
		$resEntregaItems=mysql_query($sqlEntregaItems,conectarBd());
	}
	//mensaje de PO's
	if(substr($rowEntrega["po"],5)=="3619"){					
		$msgFact="FACT 2 DLS";
	}else if(substr($rowEntrega["po"],5)=="2390"){
		$msgFact="FACT 2 DLS";
	}else if(substr($rowEntrega["po"],5)=="2388" || substr($rowEntrega["po"],5)=="0002"){
		$msgFact="FACT MAQUILA";
	}
	$nombreArchivo="entrega_".$rowEntrega["concepto"]."_".date("Y-m-d");
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=$nombreArchivo.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	$direccionImagen="http://localhost//2012/Noviembre/nextelServidor/nextel2011/img/LogoII.gif";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formato de Entregas Proceso Nextel</title>
<script type="text/javascript">//window.print();</script>
<style type="text/css">
<!--
body{margin-top:0px;margin-bottom:0px;margin-left:0px;margin-right:0px;}
.contenedor{margin-left:10px;margin-right:10px;margin-top:10px;margin-bottom:10px;/*border:#000000 solid thin;*/width:21.5cm;height:28cm;}	
.fuenteCabecera{/*font-family:"Times New Roman", Times, serif;*/font-family:Verdana, Geneva, sans-serif;font-size:12px;}
.fuenteDireccion{/*font-family:"Times New Roman", Times, serif;*/font-family:Verdana, Geneva, sans-serif;font-size:9px;}
.estiloDatosProv{/*font-family:"Times New Roman", Times, serif;*/font-family:Verdana, Geneva, sans-serif;font-size:10px;font-weight:bold;border:#000000 solid thin;}
.cursiva{font-style:italic;}
.estiloCuadroCompleto{border: .5px solid #000;background:#CCC;font-family:Verdana, Geneva, sans-serif;font-size:6px;text-align:center;}
.datosItemsReq{font-family:Verdana, Geneva, sans-serif;font-size:9px;text-align:center;border-right:#000000 solid thin;}
.datosItemsReq1{font-family:Verdana, Geneva, sans-serif;font-size:9px;text-align:left;border-right:#000000 solid thin;}
.datosItemsReq2{margin-right:4px;font-family:Verdana, Geneva, sans-serif;font-size:9px;text-align:right;border-right:#000000 solid thin;}
.cuadro{border:1px solid #000;}
.xl65{
	mso-style-parent:style0;
 	mso-number-format:"\@";
}
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
}
body {
	margin-left: 1px;
	margin-top: 1px;
	margin-right: 1px;
	margin-bottom: 1px;
}
-->
</style>
</head>

<body>
<div align="center" class="contenedor">
<table width="816" border="0">
  <tr>
    <td colspan="2" rowspan="2" style="border:2px solid #000;">&nbsp;</td>
    <td colspan="2" style="border:2px solid #000;"><div align="center">REVISION:00</div></td>
    <td colspan="3" style="border:2px solid #000;"><div align="center">CLAVE:IQFO750403</div></td>
    <td colspan="2" style="border:2px solid #000;"><div align="center">EMISION:10/09/08</div></td>
  </tr>
  <tr>
    <td height="50" colspan="5" style="border:2px solid #000;"><div align="center">FORMATO DE ENTREGAS PROCESO NEXTEL</div></td>
    <td colspan="2" style="border:2px solid #000;"><div align="center">PAGINA 1 DE 1</div></td>
  </tr>
  <tr>
    <td width="70">&nbsp;</td>
    <td width="51">&nbsp;</td>
    <td width="121">&nbsp;</td>
    <td width="99">&nbsp;</td>
    <td colspan="4"><div align="center"><strong>SALIDA DE IQ ELECTRONICS</strong></div></td>
    <td width="116">&nbsp;</td>
  </tr>
  <tr>
    <td><strong><em>P.O:</em></strong></td>
    <td colspan="2"><?=$rowEntrega["po"];?></td>
    <td>&nbsp;</td>
    <td width="114">&nbsp;</td>
    <td width="64">&nbsp;</td>
    <td width="55">&nbsp;</td>
    <td width="68">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><em>RELEASE</em></strong></td>
    <td colspan="2"><?=$rowEntrega["releaseEntrega"]." - ".$rowEntrega["modelo"];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="center"><strong>Fecha:</strong></div></td>
    <td style="text-align:center;border-bottom:1.5px solid #000;">&nbsp;<?=$rowEntrega["fecha"];?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><strong>CONCEPTO:</strong></td>
    <td colspan="3"><strong><?=$rowEntrega["concepto"];?></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="5">DE EQUIPOS PROCESADOS OK, PROGRAMA DE REFURBISH</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="5"><strong>DESTINO: DEPARTAMENTO DE FULFILMENT NEXTEL</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td style="border-bottom:1.5px solid #000;"><div align="center"><?=$rowEntrega["destino"];?></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2"><div align="right">      <strong>CANTIDAD:</strong>    </div></td>
    <td style="border-bottom:1.5px solid #000;"><div align="center"><?=$rowEntrega["cantidad"];?></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="5" style="border-bottom:1.5px solid #000;">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="center"> <strong>(<?=$msgFact;?>)</strong>    </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="5"><div align="center"><strong>RECIBIO: NOMBRE Y FIRMA</strong></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style="text-align:center; font-size:9px; font-weight:bold;background:#CCC;border:1px solid #000;">NO</td>
    <td style="text-align:center; font-size:9px; font-weight:bold;background:#CCC;border:1px solid #000;">IMEI</td>
    <td style="text-align:center; font-size:9px; font-weight:bold;background:#CCC;border:1px solid #000;">SERIAL</td>
    <td style="text-align:center; font-size:9px; font-weight:bold;background:#CCC;border:1px solid #000;">SIM ASIGNADA</td>
    <td style="text-align:center; font-size:9px; font-weight:bold;background:#CCC;border:1px solid #000;">ITEM</td>
    <td style="text-align:center; font-size:9px; font-weight:bold;background:#CCC;border:1px solid #000;">LOTE</td>
    <td style="text-align:center; font-size:9px; font-weight:bold;background:#CCC;border:1px solid #000;">Num. CAJA</td>
    <td>&nbsp;</td>
  </tr>
<?
	if(mysql_num_rows($resEntregaItems) == 0){
		echo "No existen Items relacionados a la entrega Actual ".$rowEntrega["concepto"];
	}else{
		$i=1;
		//se imprimen los datos para visualizarlos en la entrega
		while($rowEntregaItems=mysql_fetch_array($resEntregaItems)){
?>
  <tr>
    <td></td>
    <td style="text-align:center; font-size:10px; height: 12px;"><?=$i;?></td>
    <td class="xl65" style="text-align:center; font-size:10px; height: 12px;"><?=$rowEntregaItems["imei"];?></td>
    <td style="text-align:center; font-size:10px; height: 12px;"><?=strtoupper($rowEntregaItems["serial"]);?></td>
    <td class="xl65" style="text-align:center; font-size:10px; height: 12px;"><?=$rowEntregaItems["sim"];?></td>
    <td style="text-align:center; font-size:10px; height: 12px;"><?=$rowEntregaItems["modelo"];?></td>
    <td style="text-align:center; font-size:10px; height: 12px;"><?=$rowEntregaItems["lote"];?></td>
    <td style="text-align:center; font-size:10px; height: 12px;"><?=$rowEntregaItems["numeroCajaFinal"];?></td>
    <td></td>
  </tr>
<?
			$i+=1;
		}
	}
?>
</table>
</div>
</body>
</html>

