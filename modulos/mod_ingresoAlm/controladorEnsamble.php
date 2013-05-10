<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){
		case "actualizaDatosAlmacen":
			//print_r($_POST);
			$equipos=$_POST['equipos'];
			$objEnsamble->actualizaDatosAlmacen($equipos,$_POST['proceso'],$_POST['id_usuarioEnsamble'],$_POST['filtro']);
		break;		
	}
?>