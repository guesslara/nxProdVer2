<?php
	include("modeloRecibo.php");
	include("modeloAvanzado.php");
	$objRecibo=new modeloRecibo();
	
	
	if($_GET['action']=="capturaAvanzada"){
		$idRecibo=$_GET['idRecibo'];
		$modeloCaptura=$_GET['modelo'];
		$usuarioSist=$_GET['usuarioCaptura'];
		$objRecibo->datosIniciales($idRecibo,$modeloCaptura,$usuarioSist);
	}
	
	if($_GET['action']=="recibirEquipos"){
		//print_r($_GET);
		$usuarioCaptura=$_GET['usuarioCaptura'];
		$modelo=$_GET['modelo'];
		$mov=$_GET['mov'];
		$cantidad=$_GET['cantidad'];
		$objRecibo->datosIniciales($usuarioCaptura,$modelo,$mov,$cantidad);
	}

	if($_POST['action']=="mostrarCaptura"){
		//print_r($_POST);
		$objReciboAvanzado=new modeloAvanzado();
		$mov=$_POST['mov'];
		$modelo=$_POST['modelo'];
		$recibe=$_POST['recibe'];
		$proceso=$_POST['proceso'];
		$lote=$_POST['lote'];
		$clave=$_POST['clave'];
		$cantidad=$_POST['cantidad'];
		$clasificacion=$_POST['clasificacion'];
		//$cerrar=$_POST['cerrar'];
		$objReciboAvanzado->mostrarCaptura($modelo,$recibe,$proceso,$lote,$clave,$mov,$cantidad,$clasificacion);
	}
	
	if($_POST['action']=="registrarDatos"){
		//print_r($_POST);
		$objReciboAvanzado=new modeloAvanzado();
		$modelo=$_POST['modelo'];
		$usuarioRecibe=$_POST['usuarioRecibe'];
		$proceso=$_POST['proceso'];
		$lote=$_POST['lote'];
		$clave=$_POST['clave'];
		$bdCode=$_POST['bdCode'];
		$serial=$_POST['serial'];
		$imei=$_POST['imei'];
		$mov=$_POST['mov'];
		$cantidad=$_POST['cantidad'];
		$objReciboAvanzado->registrarDatos($modelo,$usuarioRecibe,$proceso,$lote,$clave,$bdCode,$serial,$imei,$mov,$cantidad);
	}
	
	if($_POST['action']=="registrarDatos2"){
		$objReciboAvanzado=new modeloAvanzado();
		$modelo=$_POST['modelo'];
		$usuarioRecibe=$_POST['usuarioRecibe'];
		$proceso=$_POST['proceso'];
		$lote=$_POST['lote'];
		$clave=$_POST['clave'];
		$mov=$_POST['mov'];
		$cantidad=$_POST['cantidad'];
		$elementos=$_POST['elementos'];
		$clasificacion=$_POST['clasificacion'];
		$objReciboAvanzado->registrarDatos2($modelo,$usuarioRecibe,$proceso,$lote,$clave,$mov,$cantidad,$elementos,$clasificacion);
	}
	
	if($_GET['action']=="opcionesBusquedaRecibo"){
		//print_r($_GET);
		$objRecibo->opcionesBusquedaRecibo();
	}
	
	if($_POST['action']=="buscarEquipos"){
	//	print_r($_POST);
		$fecha1=$_POST['fecha1'];
		$fecha2=$_POST['fecha2'];
		/*$txtModeloBusquedaRecibo=$_POST['txtModeloBusquedaRecibo'];
		$txtImeiBusquedaRecibo=$_POST['txtImeiBusquedaRecibo'];
		$txtSerialBusquedaRecibo=$_POST['txtSerialBusquedaRecibo'];
		$txtBDCodeBusquedaRecibo=$_POST['txtBDCodeBusquedaRecibo'];
		$txtLoteBusquedaRecibo=$_POST['txtLoteBusquedaRecibo'];*/
		$objRecibo->buscarEquipos($fecha1,$fecha2);
	}
	if($_GET['action']=="contador"){
		//print_r($_GET);
		$objReciboAvanzado=new modeloAvanzado();
		$modelo=$_GET['modelo'];
		$mov=$_GET['mov'];
		$objReciboAvanzado->contador($modelo,$mov);
	}
	if($_POST['action']=="exportaCajaInterna"){
?>
		<script type="text/javascript">
        	window.location.href="exportaCajaInterna.php?cajaInterna="+<?=$_POST['cajaInterna'];?>;
        </script>
<?		
	}
	
	if($_POST['action']=="validarNoEnviar"){
		$objReciboAvanzado=new modeloAvanzado();
		$objReciboAvanzado->validarNoEnviar($_POST['imeis']);
	}
	
	if($_POST['action']=="validarObsoletos"){
		$objReciboAvanzado=new modeloAvanzado();
		$objReciboAvanzado->validarObsoletos($_POST['series']);
	}
	
	if($_POST['action']=="buscarLote"){
		$objRecibo->mostrarResumenBusquedaLote($_POST['lote']);
	}
	
	if($_POST['action']=="mostrarResumenBusqueda"){
		$objRecibo->mostrarResumenBusqueda($_POST['lote'],$_POST['modelo']);
	}
?>