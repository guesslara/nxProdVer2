<?php
include("modelo.php");
	$objNextel=new modeloNextel();
	
	switch($_GET['action']){
		case "calendarizacion":
			$mes=$_GET['mes'];
			$anio=$_GET['anio'];
			$diaActual=$_GET['diaActual'];
			$objNextel->calendarizacion($mes,$anio,$diaActual);		
		break;
		case "resumen":
			$mes=$_GET['mes'];
			$anio=$_GET['anio'];
			$diaActual=$_GET['diaActual'];
			$objNextel->resumen($mes,$anio,$diaActual);
		break;
		case "resumenStatus":
			$mes=$_GET['mes'];
			$anio=$_GET['anio'];
			$diaActual=$_GET['diaActual'];
			$objNextel->resumenStatus($mes,$anio,$diaActual);
		break;
	}
	
	switch($_POST['action']){
		case "mostrarResumen":
			$objNextel->mostrarResumen($_POST['status'],$_POST['modelo'],$_POST["tipoStatus"]);
		break;
		case "mostrarResumenStatus":
			$objNextel->mostrarResumenModeloStatus($_POST['status'],$_POST['div']);
		break;
		case "mostrarResumenStatusProceso":
			$objNextel->mostrarResumenModeloStatusProceso($_POST['status'],$_POST['div']);
		break;
		case "mostrarLotes":
			$objNextel->mostrarLotes();
		break;
		case "verLote":
			$objNextel->mostrarLotesDetalle($_POST['lote']);
		break;
		case "verResumenLoteModelo":
			$objNextel->verResumenLoteModelo($_POST['lote'],$_POST['modelo']);
		break;
		case "verResumenEnviadoFolio":
			$objNextel->verResumenEnviadoFolio();
		break;
		case "verModeloEnviadosFolio":
			//print_r($_POST);
			$objNextel->verModeloEnviadosFolio($_POST["folio"],$_POST["idEnviado"]);
		break;
		case "verResumenEnviadosFolioDetalle":
			//print_r($_POST);
			$objNextel->mostrarResumenEnviadosFolio($_POST["folio"],$_POST["modelo"],$_POST["filtro"]);
		break;
	}
	
?>

