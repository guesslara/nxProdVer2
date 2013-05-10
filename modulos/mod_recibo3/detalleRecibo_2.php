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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
body{font-family:Verdana, Geneva, sans-serif; font-size:10px;}
</style>
</head>

<body>
<?
	if($opcion=="bdCodeRepetido"){
		$opcion="bdCode";
	}
	echo $sql_Valor="SELECT * FROM equipos WHERE ".$opcion."='".$valor."'";
	$res_Valor=mysql_query($sql_Valor,conectarBd());
?>
	<table width="100%" border="0" cellpadding="1" cellspacing="1">
    	<tr>
        	<td colspan="5">Resultados encontrados: <?=mysql_num_rows($res_Valor);?></td>
        </tr>
        <tr>
        	<td style="border:1px solid #999; background:#CCC; height:30px;">BDCode</td>
            <td style="border:1px solid #999; background:#CCC; height:30px;">Imei</td>
            <td style="border:1px solid #999; background:#CCC; height:30px;">Serial</td>
            <td style="border:1px solid #999; background:#CCC; height:30px;">Modelo</td>
            <td style="border:1px solid #999; background:#CCC; height:30px;">Status Gral</td>            
        </tr>
<?
	while($row_Valor=mysql_fetch_array($res_Valor)){
?>
		<tr>
        	<td style="border-bottom:1px solid #CCC; border-right:1px solid #CCC; height:20px;"><?=$row_Valor['bdcode'];?></td>
            <td style="border-bottom:1px solid #CCC; border-right:1px solid #CCC; height:20px;"><?=$row_Valor['imei'];?></td>
            <td style="border-bottom:1px solid #CCC; border-right:1px solid #CCC; height:20px;"><?=$row_Valor['serial'];?></td>
            <td style="border-bottom:1px solid #CCC; border-right:1px solid #CCC; height:20px;"><?=$row_Valor['id_modelo'];?></td>
            <td style="border-bottom:1px solid #CCC; border-right:1px solid #CCC; height:20px;"><?=$row_Valor['status'];?></td>
        </tr>
<?	
	}
?>
    </table>
<?
?>
</body>
</html>