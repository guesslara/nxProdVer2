<?
	//print_r($_POST);
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){
		
		case "actualizaDatos":
			print_r($_POST);
			$equipos=$_POST['equipos'];
			$objEnsamble->actualizaDatos($equipos,$_POST['proceso'],$_POST['id_usuarioEnsamble']);
		break;
		case "actualizaEquipoEnsamble":
			$objEnsamble->actualizaDatosEquipoEnsamble($_POST['imei']);
		break;
		case "buscarEquipo":
			$objEnsamble->buscarEquipo2($_POST['imei'],$_POST["filtro"]);
		break;
		case "actualizaReg":			
			$objEnsamble->actualizaReg($_POST['imei'],$_POST['serial'],$_POST['lote'],$_POST['clave'],$_POST['status'],$_POST['statusProceso'],$_POST['statusDesensamble'],$_POST['statusIngenieria'],$_POST['id']);
		break;
		case "verificaUsuario":			
			$objEnsamble->verificaUsuario($_POST['usuarioMod'],$_POST['passMod']);
		break;
	}
?>