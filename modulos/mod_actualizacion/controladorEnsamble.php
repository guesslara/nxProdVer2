<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	switch($_POST['action']){
		case "buscarEquipo":			
			$objEnsamble->buscarEquipo($_POST['imei']);
		break;
		case "actualizaReg":
			//print_r($_POST);
			$imei=$_POST["imei"];
			$serial=$_POST["serial"];
			$lote=$_POST["lote"];
			$sim=$_POST["sim"];
			$clave=$_POST["clave"];
			$status=$_POST["status"];
			$statusProceso=$_POST["statusProceso"];
			$statusDesensamble=$_POST["statusDesensamble"];
			$statusDiagnostico=$_POST["statusDiagnostico"];
			$statusAlmacen=$_POST["statusAlmacen"];
			$statusIngenieria=$_POST["statusIngenieria"];
			$statusEmpaque=$_POST["statusEmpaque"];
			$statusIQ=$_POST["statusIQ"];
			$id=$_POST["id"];
			$usuarioMod=$_POST["usuarioMod"];
			$passMod=$_POST["passMod"];
			$objEnsamble->actualizaReg($imei,$serial,$lote,$sim,$clave,$status,$statusProceso,$statusDesensamble,$statusDiagnostico,$statusAlmacen,$statusIngenieria,$statusEmpaque,$statusIQ,$id,$usuarioMod,$passMod);
		break;
	}
?>