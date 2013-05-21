<?php
    /*
     *Clase para finalizar los equipos en la base de datos con lo cual se cumple el ciclo en el sistema de los mismos
     *la primera actualizacion es para poder marcar los equipos en la base de datos, y despues por otro metodo finalizarlos completamente en la base de datos
     *Autor: Gerardo Lara
     *Fecha: 7 de Diciembre 2012
     *Version 1.0.0   
    */
    class finalizarEquipos{
        
        private function conectarBd(){
            require("../../includes/config.inc.php");
	    $link=mysql_connect($host,$usuario,$pass);
	    if($link==false){
		echo "Error en la conexion a la base de datos";
	    }else{
		mysql_select_db($db);
		return $link;
	    }				
	}
                
        private function guardaDetalleSistema($proceso,$usuarioSistema,$imei){	    	    	    
	    $sqlDetalle="INSERT INTO detalle_ing (id_proc,id_personal,id_radio,f_registro,h_registro) VALUES ('".$proceso."','".$usuarioSistema."','".$imei."','".date("Y-m-d")."','".date("H:i:s")."')";
	    $resDetalle=@mysql_query($sqlDetalle,$this->conectarBd())or die(mysql_error());
	    if($resDetalle){
		echo "<p>Detalle guardado</p>";
	    }else{
		echo "<p>Error al guardar el detalle</p>";
	    }
	    return $resDetalle;
	}
	
	public function marcarEquiposEnviados($idEntregaInterna,$conceptoEntrega,$idUsuarioActual,$procesoEnvio){
	    echo "Procesando los equipos de la entrega: ".$conceptoEntrega."<br>";
	    //se extraen los ids de la entrega interna
	    $sqlItems="SELECT * FROM entregas_nextel_items WHERE id_entrega='".$idEntregaInterna."'";
	    $resItems=mysql_query($sqlItems,$this->conectarBd());
	    if(mysql_num_rows($resItems)==0){
		echo "<br>No existen equipos Capturados en la entrega.";
	    }else{
		while($rowItems=mysql_fetch_array($resItems)){
		    //echo "<br>".$rowItems["id_radio"];
		    //se extrae el imei del id del radio listado
		    $sqlImei="SELECT imei FROM equipos WHERE id_radio='".$rowItems["id_radio"]."'";
		    $resImei=mysql_query($sqlImei,$this->conectarBd());
		    if(mysql_num_rows($resImei)==0){
			echo "Imei no encontrado";
		    }else{
			$rowImei=mysql_fetch_array($resImei);			
			//se busca en la tabla empaque_items y se actualiza la fecha de manufactura
			$sqlItemsEmpaque="SELECT id,mfgdate,sim FROM empaque_items WHERE imei='".$rowImei["imei"]."'";
			$resItemsEmpaque=mysql_query($sqlItemsEmpaque,$this->conectarBd());
			$rowItemsEmpaque=mysql_fetch_array($resItemsEmpaque);
			//echo "<br>".$rowItemsEmpaque["mfgdate"];
			//se actualiaz el mfgdate
			$sqlActFecha="UPDATE equipos set mfgdate='".$rowItemsEmpaque["mfgdate"]."',sim='".$rowItemsEmpaque["sim"]."',status='ENVIADO',statusProceso='ENVIADO' WHERE id_radio='".$rowItems["id_radio"]."'";
			$resActFecha=mysql_query($sqlActFecha,$this->conectarBd());
			if(mysql_affected_rows() >= 1){//se actualizo la informacion
			    echo "<br>Imei-> ".$rowImei["imei"]." ACTUALIZADO.";
			    //se inserta el detalle de la operacion en la base de datos
			    $this->guardaDetalleSistema($procesoEnvio,$idUsuarioActual,$rowItems["id_radio"]);
			}else{
			    echo "<br>Imei-> ".$rowImei["imei"]." NO ACTUALIZADO.";
			}
		    }
		}
	    }
	}
	
	public function finalizarEquiposBD($idEntregaInterna){
            echo $idEntregaInterna;
            //se extraen las entregas asociadas a la entrega interna
            $sqlEntregasAsoc="SELECT id_entregas FROM empaque_validaciones WHERE id='".$idEntregaInterna."'";
            $resEntregasAsoc=mysql_query($sqlEntregasAsoc,$this->conectarBd());
            $rowEntregasAsoc=mysql_fetch_array($resEntregasAsoc);
            echo "<br>Extrayendo entregas relacionadas.";
            $arrayEntregasAsoc=explode(",",$rowEntregasAsoc["id_entregas"]);
            for($i=0;$i<count($arrayEntregasAsoc);$i++){
                //se extraen los imei's por entregas capturadas
                $sqlItemsEntregas="SELECT * FROM empaque_items WHERE id_empaque='".$arrayEntregasAsoc[$i]."'";
                $resItemsEntregas=mysql_query($sqlItemsEntregas,$this->conectarBd());
                if(mysql_num_rows($resItemsEntregas)!=0){
                    echo "<br>Preparandose para actualizar<br>";
                    //se procede a actualizar la informacion de los equipos en la base de datos con el campo mfgdate
                    while($rowItemsEntregas=mysql_fetch_array($resItemsEntregas)){
                        if($rowItemsEntregas["statusEntrega"]=="OK"){
                            //se busca el imei en la tabla principal y se actualiza la informacion capturada
                            //se actualizan los status del equipo                            
                            $sqlActualizaEquipo="UPDATE equipos set mfgdate='".$rowItemsEntregas["mfgdate"]."',status='ENVIADO',statusProceso='ENVIADO' WHERE imei='".$rowItemsEntregas["imei"]."'";
                            $resActualizaEquipo=mysql_query($sqlActualizaEquipo,$this->conectarBd());
                            if($resActualizaEquipo){
                                echo "Imei: ".$rowItemsEntregas["imei"]."actualizado.<br>";
                            }else{
                                echo "Imei: ".$rowItemsEntregas["imei"]."NO ACTUALIZADO.<br>";
                            }
                        }//fin if
                    }//fin while
                }//fin if                
            }//fin for
            //despues de actualizar los imeis en la base de datos se procede a extraerlos para poder insertarlos en la tabla EQUIPOS_ENVIADOS
            echo "<br>Extrayendo datos.....<br>";
            /*
             continuacion..............
            */
            
        }//fin funcion
    }//fin de la clase
?>