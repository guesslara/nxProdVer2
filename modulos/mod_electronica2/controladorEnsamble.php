<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){
		case "actualizaDatos":
			//print_r($_POST);
			$equipos=$_POST['equipos'];
			$objEnsamble->actualizaDatos($equipos,$_POST['proceso'],$_POST['id_usuarioEnsamble'],$_POST['filtro']);
		break;
		case "actualizaStatusEquipo":
			//print_r($_POST);
			$objEnsamble->actualizaDatosEquipo($_POST['imeiEnsamble'],$_POST['status'],$_POST['proceso'],$_POST['idusuarioSistema']);
		break;
		case "actualizaDatosActualizaDatosDiagnostico":
			//print_r($_POST);
			$objEnsamble->actualizaDatosEquipo2($_POST["usrDiagnostico"],$_POST["proceso"],$_POST["filtro"],$_POST["idElemento"],$_POST["valores"]);
		break;
		case "cargaCatalogoFallas":
			//print_r($_POST);
			$objEnsamble->cargaCatalogoFallas();
		break;
		case "guardarDiagWip2":
			//print_r($_POST);
			$objEnsamble->guardarEquiposWip2($_POST["txtImeiWip2"],$_POST["fallas"],$_POST["cajaRespuesta"],$_POST["procesoDiagnostico"],$_POST["idUsuarioProceso"]);
		break;
		case "contarDiag":
			$objEnsamble->contarEquiposDiagnostico();
		break;
	}
?>