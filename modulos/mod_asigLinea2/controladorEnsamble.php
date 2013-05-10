<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){
		case "actualizaDatosAlmacenLinea":
			print_r($_POST);
			$equipos=$_POST['equipos'];
			$objEnsamble->actualizaDatosAlmacenLinea($equipos,$_POST['proceso'],$_POST['id_usuarioEnsamble']);
		break;
		case "actualizaEquipoEnsamble":
			$objEnsamble->actualizaDatosEquipoEnsamble($_POST['imei']);
		break;
		case "actualizaDatosAsignaLinea":
			//print_r($_POST);
			$usrAsigLinea=$_POST["usrAsigLinea"];
			$proceso=$_POST["proceso"];
			$idElemento=$_POST["idElemento"];
			$valores=$_POST["valores"];
			$objEnsamble->actualizaAsignacionLinea($usrAsigLinea,$proceso,$idElemento,$valores);
		break;
		case "contarAsigLinea":
			$objEnsamble->contarEquiposAsignadosLinea();
		break;
	}
?>