<?
	include("../../includes/txtApp.php");
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
	$sqlModificaciones="SELECT * FROM cambiossistema ORDER BY id DESC";
	$resModificaciones=mysql_query($sqlModificaciones,conectarBd());	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>...::Listado de Correcciones::...</title>
<style type="text/css">
<!--
.titulo1{margin-left:10px;font-weight:bold;font-size:14px;}
.titulo2{margin-left:20px;font-weight:bold;}
.lista{margin-left:30px;}	
-->
</style> 
</head>

<body>
<div style="border:#CCCCCC solid thin; background-color:#F0F0F0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;">
<p class="titulo1">Listado de Actualizaciones Sisco - <?=$txtApp['appPrincipal']['tituloPrincipalApp'];?></p>

<?
	while($rowModificaciones=mysql_fetch_array($resModificaciones)){
		if($rowModificaciones['status']=="Nueva"){
			$img="<img src='../../img/alert.png' width='32' height='32' />";
		}else{
			$img="<img src='../../img/clean.png' width='32' height='32' />";
		}
?>
		<p class="titulo2"><?=$img." ".$rowModificaciones['titulo']." ".$rowModificaciones['fecha'];?></p>
		<p class="lista"><?=$rowModificaciones['descripcion'];?></p>
<?		
	}
?>
</body>
</html>