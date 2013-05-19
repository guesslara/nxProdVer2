<?php
    include("../../clases/claseGrid3.php");
    include("../../includes/config.inc.php");
    //session_start();
    //print_r($_SESSION);
    //print_r($_GET);
    $url=$_SERVER["PHP_SELF"];
    $status=$_GET["status"];
    $modelo=$_GET["modelo"];
    $tipo=$_GET["tipoStatus"];
    $grid= new grid3($host,$usuario,$pass,$db);
    if($tipo=="status"){
          $campoStatus="status";
    }else if($tipo=="proceso"){
          $campoStatus="statusProceso";
    }
    /*Lista de Parametros*/
    $registrosAMostrar=25;
    //nombres de las columnas del Grid
    $camposTitulo=array("Modelo","Imei","Serial","Sim","Folio","Status","Status Proceso","MFGDate");
    //los campos de la tabla
    $campos=" modelo,imei,serial,sim,lote,status,statusProceso,mfgdate";
    //campo por el que se van a ordenar
    $campoOrden="id_radio";
    $condiciones="--";
    //tabla
    $from=" FROM equipos INNER JOIN cat_modradio ON equipos.id_modelo = cat_modradio.id_modelo";
    if($condiciones=="--"){
        if($_SESSION[clausulaWhere]==""){
            $where=" WHERE ".$campoStatus." = '".$status."'";
            //se almacena el where en una variable de sesion
            $_SESSION["clausulaWhere"]=$where;    
        }else{
            $where=$_SESSION["clausulaWhere"];
        }
        
    }
    
    $tituloReporte="Listado de equipos por Status ".$status;
    $fechaInicial="";
    $fechaTermino="";
    $tipoOrden=" ASC ";
    if(empty($_REQUEST["pagina"])){
        $pagina=0;
    }else{
        $pagina=$_REQUEST["pagina"];
    }	    
    /*Fin de los parametros*/
    $grid->mostrarListado($camposTitulo,$fechaInicial,$fechaTermino,$campos,$campoOrden,$tipoOrden,$condiciones,$registrosAMostrar,$from,$where,$tituloReporte,$pagina,$url);
	
?>