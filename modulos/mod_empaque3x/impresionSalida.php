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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Impresi&oacute;n de Formato de Entrega</title>
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
-->
</style>
</head>

<body>
<div align="center" class="contenedor">
	<table width="800" border="1" cellspacing="0" cellpadding="0" class="fuenteCabecera">
	  <tr>
	    <td width="11%" rowspan="2" valign="top" align="center"><img src="../../img/LogoII.gif" alt="logo" width="83" height="59" border="0" /></td>
	    <td width="12%" height="10" align="center">REVISION: 00</td>
	    <td width="54%" height="10" align="center">CLAVE: IQF0750403</td>
	    <td width="23%" height="10" align="center">EMISION:10/09/08</td>
	  </tr>
	  <tr>
	    <td colspan="2"><div align="center">FORMATO DE ENTREGAS PROCESO NEXTEL</div></td>
	    <td><div align="center">P&Aacute;GINA 1 DE 1</div></td>
	  </tr>
	</table>
	
	<table width="800" border="0" cellspacing="0" cellpadding="0" class="fuenteDireccion">
	  <tr>
		<td colspan="3" style="font-size: 12px;font-weight:normal;font-family: verdana; text-align: right;"><div style="margin-right: 140px;">SALIDA DE IQ ELECTRONICS</div></td>
	  </tr>
	  <tr>
	    <td width="11%" align="center">
	    <div style="text-align: left;font-size: 9px; font-weight: normal;">
		<i>P.O:</i>		
	    </div>
	    </td>
	    <td>&nbsp;<?=$rowEntrega["po"];?></td>
	    <td>&nbsp;</td>
	  </tr>
	  <tr>
	    <td width="11%" align="center">
	    <div style="text-align: left;font-size: 9px; font-weight: normal;">		
		<i>RELEASE</i>&nbsp;
	    </div>
	    </td>
	    <td>&nbsp;<?=$rowEntrega["releaseEntrega"]." - ".$rowEntrega["modelo"];?></td>
	    <td>&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan="3">&nbsp;</td>
	  </tr>  
	</table>
	<table width="800" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td>
		<table width="750" border="0" cellspacing="0" cellpadding="0" style="margin-left:44.5px;font-family: Verdana;">
			<tr>
				<td width="190">&nbsp;</td>
				<td width="190">&nbsp;</td>
				<td width="190" style="text-align: right;font-size: 12px;">Fecha:</td>
				<td width="180" style="font-size: 12px; font-family: Verdana;text-decoration: underline;text-align: center;">______<?=$rowEntrega["fecha"];?>_______</td>
			</tr>
			<tr>
				<td style="font-size: 12px;">CONCEPTO:</td>
				<td style="font-size: 13px;">&nbsp;<?=$rowEntrega["concepto"];?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" style="font-size: 12px;"><div style="margin-left: 25px;">DE EQUIPOS PROCESADOS OK, PROGRAMA DE REFURBISH</div></td>			
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" style="font-size: 12px;">DESTINO:   DEPARTAMENTO DE FULFILMENT NEXTEL</td>			
				<td style="font-size: 12px;text-decoration: underline;">______<?=$rowEntrega["destino"];?>_______</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style="text-align: right;font-size: 12px;">CANTIDAD:</td>
				<td style="text-align: center;font-size: 12px;text-decoration: underline;">__________<?=$rowEntrega["cantidad"];?>_________</td>
			</tr>
			<tr>
				<td colspan="4">_______________________________________________</td>			
			</tr>
			<tr>
				<td colspan="3" style="text-align: center;font-size: 12px;font-weight: bold;">RECIBIO: NOMBRE Y FIRMA</td>			
				<td style="text-align: center;font-size: 10px;"><?=$msgFact;?></td>
			</tr>		
		</table>
	    </td>    
	  </tr>  
	</table>
<br />
<div style="margin-left: 50px;">
	<table width="570" border="1" cellspacing="0" cellpadding="0" class="cuadro" align="left" style="border: 1px solid #000;">
		<tr>
			<td width="41" class="estiloCuadroCompleto">NO</td>
			<td width="78" class="estiloCuadroCompleto">IMEI</td>
			<td width="98" class="estiloCuadroCompleto">SERIAL</td>
			<td width="87" class="estiloCuadroCompleto">SIM ASIGNADA</td>
			<td width="50" class="estiloCuadroCompleto">ITEM</td>
			<td width="45" class="estiloCuadroCompleto">LOTE</td>
			<td width="40" class="estiloCuadroCompleto">Num. CAJA</td>
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
			<td style="height: 7px;font-size: 7px;text-align: center;"><?=$i;?></td>
			<td class="xl65" style="height: 7px;font-size: 7px;text-align: center;"><?=$rowEntregaItems["imei"];?></td>
			<td style="height: 7px;font-size: 7px;text-align: center;"><?=strtoupper($rowEntregaItems["serial"]);?></td>
			<td class="xl65" style="height: 7px;font-size: 7px;text-align: center;"><?=$rowEntregaItems["sim"];?></td>
			<td style="height: 7px;font-size: 7px;text-align: center;"><?=$rowEntregaItems["modelo"];?></td>
			<td style="height: 7px;font-size: 7px;text-align: center;"><?=$rowEntregaItems["lote"];?></td>
			<td style="height: 7px;font-size: 7px;text-align: center;"><?=$rowEntregaItems["numeroCajaFinal"];?></td>
		</tr>
<?
			$i+=1;
		}
	}
	for($i=0;$i<50;$i++){
?>		
		
<?
	}
?>
	</table><br><br>
</div>
<br /><br /><br>
</div>
</body>
</html>

