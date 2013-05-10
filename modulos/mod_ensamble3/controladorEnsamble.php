<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){
		case "actualizaDatosIngenieria":
			//print_r($_POST);
			$proceso=$_POST["proceso"];
			$usrEnsamble=$_POST["usrEnsamble"];
			$linea=$_POST["linea"];
			$filtroFlex=$_POST["filtroFlex"];
			$idElemento=$_POST["idElemento"];
			$valores=$_POST["valores"];
			//$equipos=$_POST['equipos'];
			//$objEnsamble->actualizaDatosIngenieria($equipos,$_POST['proceso'],$_POST['id_usuarioEnsamble'],$_POST['linea'],$_POST['filtro']);
			$objEnsamble->actualizaEquipoIngenieria($proceso,$usrEnsamble,$linea,$filtroFlex,$idElemento,$valores);
		break;
		case "actualizaDatosIngenieriaScrap":
			//print_r($_POST);
			$proceso=$_POST["proceso"];
			$usrEnsamble=$_POST["usrEnsamble"];
			$linea=$_POST["linea"];
			$idElemento=$_POST["idElemento"];
			$valores=$_POST["valores"];
			$objEnsamble->actualizaEquipoIngenieriaScrap($proceso,$usrEnsamble,$linea,$idElemento,$valores);
		break;
		case "contarIng":
			$objEnsamble->contarEquiposIngenieria();
		break;
		case "contAntEqui":
			$objEnsamble->contAntEqui();
		break;
		case "showRes":
			$fechaIni=$_POST['fechaIni'];
			$fechaFin=$_POST['fechaFin'];
			$objEnsamble->showRes($fechaIni,$fechaFin);
		break;
	}
?>