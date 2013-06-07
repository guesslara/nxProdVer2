<?
	/*
	Clase para poder Asignar el equipo a Desensamble
	Se modifica la forma de gurdar implementando el nuevo grid en la captura de la informacion
	
	10-04-2012
	
	Autor:Gerardo Lara
	*/
	include("../../clases/funcionesComunes.php");
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
		
		public function contarEquiposAsignadosDes(){
			$sqlIng="SELECT distinct count( DISTINCT detalle_ing.id_radio ) AS total FROM detalle_ing INNER JOIN equipos ON detalle_ing.id_radio = equipos.id_radio WHERE (id_proc =11 AND f_registro = '".date("Y-m-d")."') AND equipos.status = 'WIP'";			
			$resIng=mysql_query($sqlIng,$this->conectarBd());			
			$rowIng=mysql_fetch_array($resIng);			
			echo "Capturados hoy:<br> <div style='text-align:left;margin:5px;'>Ok &nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;".$rowIng["total"]."</div>";
		}
		
		public function actualizaEnvioDesensamble($usrAsignaDesensamble,$proceso,$idElemento,$valores){
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
							$sqlRadio="SELECT id_radio,status,statusProceso FROM equipos WHERE imei='".$valores."'";
							$resRadio=mysql_query($sqlRadio,$this->conectarBd());
							$rowRadio=mysql_fetch_array($resRadio);
							$id_Radio=$rowRadio['id_radio'];
							if($rowRadio['statusProceso']=="Recibo"){
								$sqlActEquipo="UPDATE equipos set statusProceso='Desensamble' where imei='".$valores."'";
								$resActEquipo=mysql_query($sqlActEquipo,$this->conectarBd());
								if($resActEquipo){
									$msgCaja="Equipo Actualizado";
									$color="green";
									$fuente="white";
									$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
									$objFunciones->guardaDetalleSistema($proceso,$usrAsignaDesensamble,$valores);
									echo "<script type='text/javascript'> contarEquiposAsigDes(); </script>";
									return;
								}else{
									$msgCaja="Error al Actualizar";
									$color="orange";
									$fuente="white";
									$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
									return;	
								}
							}else{
								$msgCaja="Imei en otro Proceso";
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
		
		public function actualizaDatosEquipo($imeiEnsamble,$proceso,$idUsuarioProceso,$txtCantidadporCapturar){
			echo "<br>Procesando Datos...........................";
			$sqlRadio="SELECT id_radio,status,statusProceso FROM equipos WHERE imei='".$imeiEnsamble."'";
			$resRadio=mysql_query($sqlRadio,$this->conectarBd());
			$rowRadio=mysql_fetch_array($resRadio);
			$id_Radio=$rowRadio['id_radio'];
			//se prepara el detalle de la informacion
			if(mysql_num_rows($resRadio) == 0){
				echo "<br><br>Error, verifique la informacion introducida.<br><br>";
			}else{
				if($rowRadio['statusProceso']=="Recibo"){			
					$sql_insert1="INSERT INTO detalle_ing (id_proc,id_personal,id_radio,status,id_falla,f_registro,h_registro,observaciones)";
					$sql_insert2=" VALUES('".$proceso."','".$idUsuarioProceso."','".$id_Radio."','--','0','".date("Y-m-d")."','".date("H:i:s")."','---')";
					$sqlEnsamble=$sql_insert1.$sql_insert2;
					$resEnsamble=mysql_query($sqlEnsamble,$this->conectarBd());
					//cambiar el status del equipo
					$sqlActEquipo="UPDATE equipos set statusProceso='Desensamble' where imei='".$imeiEnsamble."'";
					$resActEquipo=mysql_query($sqlActEquipo,$this->conectarBd());
					//se verifica que solo permita la cantidad permitida en el movimiento
					//$sqlContador="SELECT COUNT(*) AS Total FROM equipos WHERE num_movimiento='".$mov."' AND id_modelo='".$modelo."'";
					//$resContador=mysql_query($sqlContador,$this->conectarBd());
					//$rowContador=mysql_fetch_array($resContador);
					//echo"<br>".
					//$contador=$rowContador["Total"];					
?>
			<script type="text/javascript">	vaciarContenidoImei();</script>	
<?					
					if($resActEquipo){
						echo "<br><br>Imei <strong>($imeiEnsamble)</strong> Actualizado<br><br>";
					}else{
						echo "<br><br>Error al actualizar la informacion del IMEI<br><br>";
					}
				}else{
					echo "<br><br>Error, el Radio que esta usando ya se encuentra en otro Proceso.<br><br>";
				}
			}
		}
		
		private function imeiRepetido($imeiEnsamble){
			$sqlBuscarImei="SELECT * FROM equipos WHERE imei='".$imeiEnsamble."'";
			$resRadio=mysql_query($sqlBuscarImei,$this->conectarBd());
			$numeroRep=mysql_num_rows($resRadio);
			return $numeroRep;
		}
		
		public function actualizaDatos($equipos,$proceso,$usuarioEnsamble){			
			//se extrae el id del radio
			echo "<br>Procesando Datos...........................";
			$equipos=explode(",",$equipos);
			for($i=0;$i<count($equipos);$i++){
				$sqlRadio="SELECT id_radio FROM equipos WHERE imei='".$equipos[$i]."'";
				$resRadio=mysql_query($sqlRadio,$this->conectarBd());
				$rowRadio=mysql_fetch_array($resRadio);
				$id_Radio=$rowRadio['id_radio'];
				//se prepara el detalle de la informacion
				$sql_insert1="INSERT INTO detalle_ing (id_proc,id_personal,id_radio,status,id_falla,f_registro,h_registro,observaciones)";
				$sql_insert2=" VALUES('".$proceso."','".$usuarioEnsamble."','".$id_Radio."','--','0','".date("Y-m-d")."','".date("H:i:s")."','---')";
				$sqlEnsamble=$sql_insert1.$sql_insert2;
				$resEnsamble=mysql_query($sqlEnsamble,$this->conectarBd());
				//cambiar el status del equipo
				$sqlActEquipo="UPDATE equipos set status='Desensamble' where imei='".$equipos[$i]."'";
				$resActEquipo=mysql_query($sqlActEquipo,$this->conectarBd());
			}			
		}
	}//fin de la clase
?>