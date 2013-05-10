<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){		
		case "actualizaDatosAsignaDesensamble":
			//print_r($_POST);
			$usrAsignaDesensamble=$_POST["usrAsignaDesensamble"];
			$proceso=$_POST["proceso"];
			$idElemento=$_POST["idElemento"];
			$valores=$_POST["valores"];
			$objEnsamble->actualizaEnvioDesensamble($usrAsignaDesensamble,$proceso,$idElemento,$valores);
		break;
		case "contarAsigDes":
			$objEnsamble->contarEquiposAsignadosDes();
		break;
	}
?>