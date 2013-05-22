<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	//print_r($_POST);
	switch($_POST['action']){
		case "mostrarReemplazo":
			$objEnsamble->mostrarForm();
		break;
		case "mostrarResumen":
			$objEnsamble->mostrarResumen($_POST["imei"]);
		break;
		case "guardarDetalle":
			//print_r($_POST);
			$objEnsamble->guardarReemplazo($_POST["imeiProceso"],$_POST["imeiBounce"],$_POST["serialBounce"]);
		break;
	}
?>