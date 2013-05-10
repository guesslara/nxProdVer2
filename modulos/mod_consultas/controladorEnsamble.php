<?
	include("modeloEnsamble.php");
	$objBusquedaAvanzada=new modeloBusquedaAvanzada();
	switch($_POST['action']){
		case "mostrarFormulario":
			//print_r($_POST);			
			$objBusquedaAvanzada->mostrarFormulario();
		break;
		case "buscar":
			//print_r($_POST);
			$id_modelo=$_POST["id_modelo"];
			$imei=$_POST["imei"];
			$serial=$_POST["serial"];
			$sim=$_POST["sim"];
			$folio=$_POST["lote"];
			$mfgdate=$_POST["mfgdate"];
			$status=$_POST["status"];
			$statusProceso=$_POST["statusProceso"];
			$statusDesensamble=$_POST["statusDesensamble"];
			$statusDiagnostico=$_POST["statusDiagnostico"];
			$statusAlmacen=$_POST["statusAlmacen"];
			$statusIngenieria=$_POST["statusIngenieria"];
			$statusEmpaque=$_POST["statusEmpaque"];
			$lineaEnsamble=$_POST["lineaEnsamble"];
			$fecha1=$_POST["f_recibo"];
			$fecha2=$_POST["f_recibo2"];
			$numMov=$_POST["num_Movimiento"];
			$tipoEquipo=$_POST["tipoEquipo"];
			$flexNuevo=$_POST["flexNuevo"];
			$objBusquedaAvanzada->buscarDatos($id_modelo,$imei,$serial,$sim,$folio,$mfgdate,$status,$statusProceso,$statusDesensamble,$statusDiagnostico,$statusAlmacen,$statusIngenieria,$statusEmpaque,$lineaEnsamble,$fecha1,$fecha2,$numMov,$tipoEquipo,$flexNuevo);
		break;
	}
?>