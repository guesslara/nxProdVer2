<?
	$opcion=$_GET['opcion'];
	$valor=$_GET['valor'];

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


	include("../../includes/conectarbase.php");
	switch($opcion){
		case "bdCodeRepetido":
			$opcion="bdcode";
		break;
		case "serialRepetido":
			$opcion="serial";
		break;
		case "imeiRepetido":
			$opcion="imei";
		break;
	}
	$sql_Valor="SELECT * FROM equipos WHERE ".$opcion."='".$valor."'";
	$res_Valor=mysql_query($sql_Valor,conectarBd());
?>
	<table width="100%" border="0" cellpadding="1" cellspacing="1">
    	<tr>
        	<td>Resultados encontrados: <?=mysql_num_rows($res_Valor);?></td>
        </tr>
        <tr>
        	<!--<td style="border:1px solid #999; background:#CCC; height:30px;"><?$opcion;?></td> --> 
            <td style="border:1px solid #999; background:#CCC; height:30px;"><?=$opcion;?></td>            
        </tr>
<?
	while($row_Valor=mysql_fetch_array($res_Valor)){
?>
		<tr>
        	<td style="border-bottom:1px solid #CCC; border-right:1px solid #CCC; height:20px;"><a href="detalleRecibo_2.php?opcion=<?=$opcion;?>&valor=<?=$valor;?>" target="_blank" style="color:#00F;"><?=$row_Valor[$opcion];?></a></td>
        </tr>
<?	
	}
?>
    </table>
<?
?>