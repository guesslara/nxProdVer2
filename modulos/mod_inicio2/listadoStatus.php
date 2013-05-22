<?php
    session_start();
    include("../../clases/claseGrid3.php");
    include("../../includes/config.inc.php");
    include("../../includes/txtApp.php");
    
    if(!isset($_SESSION[$txtApp['session']['idUsuario']])){
	echo "<script type='text/javascript'> alert('Su Sesion ha caducado por inactividad'); </script>";	
	exit;
    }
       
    if($_GET["action"]=="resultados"){	
	$param=explode(",",$_GET["parametros"]);	
    }else{	
	$nombreParametros=array("status","modelo","tipoStatus");
	for($i=0;$i<count($_GET);$i++){	    
	    $param[]=$_GET[$nombreParametros[$i]];
	}	
    }
    
    if($param[2]=="status"){
          $campoStatus="status";
    }else if($param[2]=="proceso"){
          $campoStatus="statusProceso";
    }    
    
    if(isset($_SESSION[clausulaWhere])){	
	unset($_SESSION[clausulaWhere]);
    }
    
    $grid= new grid3($host,$usuario,$pass,$db);
    
    /*Lista de Parametros*/
    $registrosAMostrar=25;
    //nombres de las columnas del Grid
    $camposTitulo=array("Modelo","Imei","Serial","Sim","Folio","Status","Status Proceso","MFGDate");
    //los campos de la tabla
    $campos=" modelo,imei,serial,sim,lote,status,statusProceso,mfgdate";    
    $campoOrden="id_radio";//campo por el que se van a ordenar
    $condiciones="--";    
    $from=" FROM equipos INNER JOIN cat_modradio ON equipos.id_modelo = cat_modradio.id_modelo";
    if($condiciones=="--"){
        if($_SESSION[clausulaWhere]==""){	    
	    if($param[1]=="S/M"){
		$where=" WHERE ".$campoStatus." = '".$param[0]."'";
	    }else{
		$where=" WHERE ".$campoStatus." = '".$param[0]."' AND equipos.id_modelo='".$param[1]."'";
	    }            
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
    $grid->mostrarListado($camposTitulo,$fechaInicial,$fechaTermino,$campos,$campoOrden,$tipoOrden,$condiciones,$registrosAMostrar,$from,$where,$tituloReporte,$pagina,$param);	
?>