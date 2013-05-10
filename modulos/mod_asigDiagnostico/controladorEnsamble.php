<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){
		case "actualizaDatosAlmacenDiagnostico":
			//print_r($_POST);
			$equipos=$_POST['equipos'];
			$objEnsamble->actualizaDatosAlmacenDiagnostico($equipos,$_POST['proceso'],$_POST['id_usuarioEnsamble'],$_POST['txtProcesoAsig']);
		break;
		case "actualizaEquipoEnsamble":
			$objEnsamble->actualizaDatosEquipoEnsamble($_POST['imei']);
		break;
	}
?>