<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){
		case "actualizaDatos":
			print_r($_POST);
			$equipos=$_POST['equipos'];
			$objEnsamble->actualizaDatos($equipos,$_POST['proceso'],$_POST['id_usuarioEnsamble']);
		break;
		case "guardarEmpaque":
			print_r($_POST);
			$objEnsamble->capturaEquiposCaja($_POST['fecha'],$_POST['txtTecnico'],$_POST['txtEntrega'],$_POST['modelo']);
		break;
		case "guardaItemsEmpaque":
			print_r($_POST);
			$objEnsamble->capturaEquiposCajaItems($_POST['imei'],$_POST['sim'],$_POST['id_empaque'],$_POST['id_caja']);
		break;
		case "listarCapturas":
			$objEnsamble->listarCapturas();
		break;
		case "verDetalleEmpaque":
			$objEnsamble->verDetalleEmpaque($_POST['idEmpaque']);
		break;
		case "guardarCaja":
			$objEnsamble->guardaCaja($_POST['caja'],$_POST['idEmpaque']);
		break;
		case "muestraInfoCaja":
			$objEnsamble->consultarCajasItems($_POST['idEmpaque'],$_POST['idCaja']);
		break;
	}
?>