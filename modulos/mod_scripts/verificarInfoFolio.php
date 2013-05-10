<script language="javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<style type="text/css">
    .xl65{mso-style-parent:style0;mso-number-format:"\@";}
</style>
<?php
    if($_GET["action"]=="buscarFolio"){
	$folio=$_POST["cboFolio"];
	verificarFolioInfo($folio);
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
    
    function verificarFolioInfo($folio){
	//se extrae el detalle de cada folio
	$sqlFolio="select * from archivo_Cliente where lote='".$folio."'";
	$resFolio=mysql_query($sqlFolio,conectarBd());
	if(mysql_num_rows($resFolio)==0){
	    echo "( 0 ) registros en el folio"; return;
	}else{
	    echo "<table border='1' cellpading='1' cellspacing='1' width='800'>";
	    echo "<tr>
		    <td>Id</td>
		    <td>Imei Cliente</td>
		    <td>Serial Cliente</td>
		    <td>Folioi Cliente</td>
		    <td>&nbsp;</td>
		    <td>Imei IQ</td>
		    <td>Serial IQ</td>
		    <td>Folio IQ</td>
		</tr>";
	    $i=1;
	    while($rowFolio=mysql_fetch_array($resFolio)){
		$sqlIq="select imei,serial,lote from equipos where (lote='".$folio."' and imei='".$rowFolio["imei"]."') and serial='".$rowFolio["serial"]."'";				
		$resIq=mysql_query($sqlIq,conectarBd());
		if(mysql_num_rows($resIq)==0){
		    $sqlIq="select imei,serial,lote from equipos_enviados where (lote='".$folio."' and imei='".$rowFolio["imei"]."') and serial='".$rowFolio["serial"]."'";
		    $resIq=mysql_query($sqlIq,conectarBd());
		}
		$rowIq=mysql_fetch_array($resIq);
		if($rowFolio["imei"] == $rowIq["imei"]){
		    $imeiIq=$rowIq["imei"];
		    $fondo="green";
		    $fuente="#fff";
		}else{
		    $imeiIq="NOK";
		    $fondo="red";
		    $fuente="#fff";
		}
		if(strtoupper($rowFolio["serial"]) == strtoupper($rowIq["serial"])){
		    $serialIq=$rowIq["serial"];
		    $fondoSerial="green";
		    $fuenteSerial="#fff";
		}else{
		    $serialIq="NOK";
		    $fondoSerial="red";
		    $fuenteSerial="#fff";
		}
		echo "<tr>
		    <td>".$i."</td>
		    <td>".$rowFolio["imei"]."</td>
		    <td>".$rowFolio["serial"]."</td>
		    <td>".$rowFolio["lote"]."</td>
		    <td>&nbsp;</td>
		    <td style='background:".$fondo.";color:".$fuente."'>".$imeiIq."</td>
		    <td style='background:".$fondoSerial.";color:".$fuenteSerial."'>".strtoupper($serialIq)."</td>
		    <td>".$rowIq["lote"]."</td>
		</tr>";
		$i+=1;
	    }
	    echo "</table>";
	}
    }
?>
<br>
<!--<div style="float:left;margin-left:50px;">Imei's Actualizados</div><div style="float:left;margin-left:90px;">Imei's NO ENCONTRADOS</div><div style="float:left;margin-left:70px;">Imei's NO ACTUALIZADOS</div><div style="clear:both;">&nbsp;</div>
<div id="datosCorrectos" class="xl65" style="border:1px solid #FF0000;height:400px;width:250px;float:left;overflow:auto;"></div>
<div id="datosIncorrectos" style="border:1px solid #FF0000;height:400px;width:250px; margin-left:10px;float:left;overflow:auto;"></div>
<div id="datosNoActualizados" style="border:1px solid #FF0000;height:400px;width:250px; margin-left:10px;float:left;overflow:auto;"></div>-->
<form name="" id="" action="<?=$_SERVER["PHP_SELF"];?>?action=buscarFolio" method="POST">
<?
    $sqlFolios="SELECT COUNT( * ) AS `Filas` , `lote` FROM `archivo_Cliente` GROUP BY `lote` ORDER BY `lote`";
    $resFolios=mysql_query($sqlFolios,conectarBd());
?>
    Selecionar folio: <select name="cboFolio" id="">
	<option value="" selected="selected">Selecciona</option>
<?    
    while($rowFolios=mysql_fetch_array($resFolios)){
?>
	<option value="<?=$rowFolios["lote"];?>"><?=$rowFolios["lote"];?></option>
<?
    }
?>
    </select><input type="submit" value="Verificar">
</form><br>
