<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	//print_r($_POST);
	switch($_POST['action']){
		case "mostrarForm":
			$objEnsamble->mostrarForm();
		break;
		case "buscarDonante":
			$objEnsamble->mostrarResumen($_POST["imei"],$_POST["div"]);
		break;
		case "guardarCI":
			//print_r($_POST);
			$objEnsamble->guardarEquipoCI($_POST["usuario"],$_POST["imeiDonante"],$_POST["imeiReceptor"],$_POST["observaciones"]);
		break;
	}
?>