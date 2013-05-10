<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){
		case "actualizaDatosDesensamble":
			//print_r($_POST);
			$equipos=$_POST['equipos'];
			$objEnsamble->actualizaDatosDesensamble($equipos,$_POST['proceso'],$_POST['id_usuarioEnsamble'],$_POST['filtro']);
		break;
		case "actualizaEquipoEnsamble":
			$objEnsamble->actualizaDatosEquipoEnsamble($_POST['imei']);
		break;
		case "contarDes":
			$objEnsamble->contarEquiposDesensamble();
		break;
		case "actualizaDatosDesensamble2":
			//print_r($_POST);
			$usrDesensamble=$_POST["usrDesensamble"];
			$proceso=$_POST["proceso"];
			$idElemento=$_POST["idElemento"];
			$valores=$_POST["valores"];			
			$objEnsamble->actualizaInformacionDesensamble($usrDesensamble,$proceso,$idElemento,$valores);
		break;
	}
?>