<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){
		case "mostrarDatosfolio":
			//print_r($_POST);
			$objEnsamble->mostrarDatosFolios($_POST["folioCliente"],$_POST["folioIq"]);
		break;
		case "mostrarResumen":
			print_r($_POST);
			$objEnsamble->mostrarResumenValidacion($_POST["datosFolio"]);
		break;
	}
?>