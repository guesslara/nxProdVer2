<?
	/*
	Clase para poder Asignar el equipo a Ingenieria
	Se modifica la forma de gurdar implementando el nuevo grid en la captura de la informacion
	
	19-04-2012
	
	Autor:Gerardo Lara
	*/
	include("../../clases/funcionesComunes.php");
	//include("../../clases/guardaDetalle.php");
	class modeloEnsamble{
		
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
		
		public function validaStatusBaseDatos($imei){
			$sqlImei="SELECT * from equipos where imei='".$imei."'";
			$resImei=mysql_query($sqlImei,$this->conectarBd());
			$rowImei=mysql_fetch_array($resImei);
			if($rowImei['statusProceso']=="waitING" && $rowImei['statusDesensamble']=="OK" && $rowImei['statusIngenieria']==""){
				$validacion=true;
			}else{
				$validacion=false;
			}
			return $validacion;
		}
		
		public function contarEquiposAsignadosLinea(){
			$sqlIng="SELECT distinct count( DISTINCT detalle_ing.id_radio ) AS total FROM detalle_ing INNER JOIN equipos ON detalle_ing.id_radio = equipos.id_radio WHERE (id_proc =12 AND f_registro = '".date("Y-m-d")."') AND equipos.status = 'WIP'";			
			$resIng=mysql_query($sqlIng,$this->conectarBd());			
			$rowIng=mysql_fetch_array($resIng);			
			echo "Capturados hoy:<br> <div style='text-align:left;margin:5px;'>Ok &nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;".$rowIng["total"]."</div>";
		}
		
		public function actualizaAsignacionLinea($usrAsigLinea,$proceso,$idElemento,$valores){
			$objFunciones=new funcionesComunes();
			if(strlen($valores) < 15){
				$msgCaja="Verifique Imei";
				$color="red";
				$fuente="white";
				$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
				return;
			}else{
				$encontrado=$objFunciones->buscarImei($valores);
				if($encontrado==0){
					$msgCaja="Imei NO EXISTE";
					$color="red";
					$fuente="white";
					$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
					return;	
				}else{
					$scrap=$objFunciones->buscarImeiScrap($valores);
					if($scrap==1){
						$msgCaja="Imei SCRAP";
						$color="red";
						$fuente="white";
						$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
						return;	
					}else{
						$scrapPorEnviar=$objFunciones->buscarImeiScrapPorEntregar($valores);
						
						if($scrapPorEnviar==1){
							$msgCaja="SCRAP POR ENVIAR";
							$color="red";
							$fuente="white";
							$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
							return;
						}else{						
							//se verifica si el equipo proviene de desensamble
							$sqlC="select statusDesensamble,statusDiagnostico,statusAlmacen from equipos where imei='".$valores."'";
							$resC=mysql_query($sqlC,$this->conectarBd());
							$rowC=mysql_fetch_array($resC);
							//echo "Estatus Desensamble ".$rowC['statusDesensamble'];
							if($rowC['statusDesensamble']=="OK" && $rowC['statusDiagnostico']=="OK" && $rowC['statusDiagnostico']!="SCRAP" && $rowC['statusAlmacen']=="Almacenado"){		
								$sqlRadio="UPDATE equipos set statusAlmacen='Asignado',statusProceso='Ingenieria' WHERE imei='".$valores."'";					
								$resRadio=mysql_query($sqlRadio,$this->conectarBd());
								if($resRadio){
									echo "<br> -> Registro Actualizado";
									$msgCaja="Equipo Actualizado";
									$color="green";
									$fuente="white";
									$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
									//se inserta el detalle para el seguimiento del equipo
									$objFunciones->guardaDetalleSistema($proceso,$usrAsigLinea,$valores);
									echo "<script type='text/javascript'> contarEquiposAsigLinea(); </script>";
								}else{
									$msgCaja="Error al Actualizar";
									$color="orange";
									$fuente="white";
									$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
									return;	
								}				
							}else{
								//echo "<br>Verifique la informaci&oacute;n del equipo con imei <strong>(".$equipos[$i].")</strong>.<br>";
								$msgCaja="Verifique la informacion";
								$color="red";
								$fuente="white";
								$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
								return;
							}
						
						}
					}
				}
			}
		}
		
		public function mensajesCaja($idElemento,$msgCaja,$color,$fuente){
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').value='".$msgCaja."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.background='".$color."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.color='".$fuente."'; </script>";
		}
		
		public function actualizaDatosAlmacenLinea($equipos,$proceso,$usuarioEnsamble){	
			$objDetalle=new guardaDetalle();
			//se extrae el id del radio
			echo "<br>Procesando Datos...........................";
			$equipos=explode(",",$equipos);
			for($i=0;$i<count($equipos);$i++){
				//se verifica si el equipo proviene de desensamble
				$sqlC="select statusDesensamble,statusDiagnostico from equipos where imei='".$equipos[$i]."'";
				$resC=mysql_query($sqlC,$this->conectarBd());
				$rowC=mysql_fetch_array($resC);
				//echo "Estatus Desensamble ".$rowC['statusDesensamble'];
				if($rowC['statusDesensamble']=="OK" && $rowC['statusDiagnostico']=="OK" && $rowC['statusDiagnostico']!="SCRAP"){		
					$sqlRadio="UPDATE equipos set statusAlmacen='Asignado',statusProceso='Ingenieria' WHERE imei='".$equipos[$i]."'";					
					$resRadio=mysql_query($sqlRadio,$this->conectarBd());
					if($resRadio){
						echo "<br> -> Registro Actualizado";
						//se inserta el detalle para el seguimiento del equipo
						$objDetalle->guardaDetalleSistema($proceso,$usuarioEnsamble,$equipos[$i]);
					}else{
						echo "<br> -> Registro No Actualizado";
					}				
				}else{
					echo "<br>Verifique la informaci&oacute;n del equipo con imei <strong>(".$equipos[$i].")</strong>.<br>";
				}
				//$id_Radio=$rowRadio['id_radio'];
				/*
				$sql_insert1="INSERT INTO detalle_ing (id_proc,id_personal,id_radio,status,id_falla,f_registro,h_registro,observaciones)";
				$sql_insert2=" VALUES('".$proceso."','".$usuarioEnsamble."','','Ok','0','".date("Y-m-d")."','".date("H:i:s")."','---')";
				echo "<br>".$sqlEnsamble=$sql_insert1.$sql_insert2;
				*/
			}
?>
			<script type="text/javascript"> resetForm(); </script>
<?			
		}
	}//fin de la clase
?>