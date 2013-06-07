<?
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
		
		public function contarEquiposDesensamble(){
			$sqlIng="SELECT distinct count( DISTINCT detalle_ing.id_radio ) AS total FROM detalle_ing INNER JOIN equipos ON detalle_ing.id_radio = equipos.id_radio WHERE (id_proc =2 AND f_registro = '".date("Y-m-d")."') AND equipos.status = 'WIP'";			
			$resIng=mysql_query($sqlIng,$this->conectarBd());			
			$rowIng=mysql_fetch_array($resIng);			
			echo "Capturados hoy:<br> <div style='text-align:left;margin:5px;'>Ok &nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;".$rowIng["total"]."</div>";
		}
		
		
		public function actualizaInformacionDesensamble($usrDesensamble,$proceso,$idElemento,$valores){
			$objDetalle=new funcionesComunes();
			//se verifica que exista en la Base de Datos
			$existe=$objDetalle->buscarImei($valores);
			$estaEnviado=$objDetalle->buscarImeiEnviadoProceso($valores);
			$noEnviar=$objDetalle->buscarNoEnviar($valores);
			$scrapPorEnviar=$objDetalle->buscarImeiScrapPorEntregar($valores);
			if($existe==0){
				$msgCaja="Imei NO EXISTE";
				$color="red";
				$fuente="white";
				$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
				return;	
			}else if($estaEnviado==1){
				$msgCaja="Equipo ENVIADO";
				$color="red";
				$fuente="white";
				$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
				return;	
			}else if($noEnviar==1){
				$msgCaja="Equipo NO ENVIAR";
				$color="red";
				$fuente="white";
				$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
				return;
			}else if($scrapPorEnviar==1){
				$msgCaja="SCRAP POR ENVIAR";
				$color="red";
				$fuente="white";
				$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
				return;
			}else{
				$sqlC="select statusProceso,statusDesensamble from equipos where imei='".$valores."'";
				$resC=mysql_query($sqlC,$this->conectarBd());
				$rowC=mysql_fetch_array($resC);
				if($rowC['statusProceso']=="Desensamble" && $rowC['statusDesensamble']=="N/A"){
					$sqlRadio="UPDATE equipos set statusDesensamble='OK' WHERE imei='".$valores."'";
					$resRadio=mysql_query($sqlRadio,$this->conectarBd());
					if($resRadio){
						$msgCaja="Registro ACTUALIZADO";
						$color="green";
						$fuente="white";
						$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);						
						//se guarda el detalle
						$objDetalle->guardaDetalleSistema($proceso,$usrDesensamble,$valores);
						echo "<script type='text/javascript'> contarEquiposDes(); </script>";
						return;
					}else{						
						$msgCaja="Error al ACTUALIZAR";
						$color="red";
						$fuente="white";
						$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
						return;
					}				
				}else{
					$msgCaja="VERIFIQUE EQUIPO";
					$color="orange";
					$fuente="black";
					$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
					return;
				}
			}
		}
		
		public function mensajesCaja($idElemento,$msgCaja,$color,$fuente){
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').value='".$msgCaja."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.background='".$color."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.color='".$fuente."'; </script>";
		}
		
		public function actualizaDatosEquipoEnsamble($imei){
			echo "<br><br>Validando Datos...........................";
			$validacion=$this->validaStatusBaseDatos($imei);
			echo "<br><br>Procesando Datos...........................";
			if($validacion==true){			
				echo "<br>".$sqlEquipoEnsamble="UPDATE equipos set statusIngenieria='ING_OK',statusProceso='Empaque' where imei='".$imei."'";
				$resEquipoEnsamble=mysql_query($sqlEquipoEnsamble,$this->conectarBd());
				if(mysql_affected_rows() >=1 ){
					echo "<script type='text/javascript'> alert('Equipo actualizado en la Base de Datos'); limpiaCaja();</script>";
				}else{
					echo "<script type='text/javascript'> alert('Error al actualizar la informacion del equipo on imei ($imei)'); limpiaCaja();</script>";
				}
			}else{
				echo "<br><br><strong style='color:#F00;font-size:16px;'>Verifique el status del imei ($imei) y/o Tarjeta.</strong>";
				echo "<script type='text/javascript'>limpiaCaja();</script>";
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
		
		
		public function actualizaDatosDesensamble($equipos,$proceso,$usuarioEnsamble,$filtro){
			$objDetalle=new funcionesComunes();
			//se extrae el id del radio
			echo "<br>Procesando Datos...........................";
			$equipos=explode(",",$equipos);
			for($i=0;$i<count($equipos);$i++){
				//se verifica que exista en la Base de Datos
				$existe=$objDetalle->buscarImei($equipos[$i]);
				$estaEnviado=$objDetalle->buscarImeiEnviado($equipos[$i]);
				if($existe==1){
					//se verifica si el equipo proviene de desensamble
					$sqlC="select statusProceso,statusDesensamble from equipos where imei='".$equipos[$i]."'";
					$resC=mysql_query($sqlC,$this->conectarBd());
					$rowC=mysql_fetch_array($resC);
					//echo "Estatus Desensamble ".$rowC['statusDesensamble'];
					//se verifica que el equipo no este clasificado como NO ENVIAR
					$noRegistros=$objDetalle->buscarNoEnviar($equipos[$i]);
					if($noRegistros==0){
						if($rowC['statusProceso']=="Desensamble" && $rowC['statusDesensamble']=="N/A"){		
							$sqlRadio="UPDATE equipos set statusDesensamble='".$filtro."' WHERE imei='".$equipos[$i]."'";
							$resRadio=mysql_query($sqlRadio,$this->conectarBd());
							if($resRadio){
								echo "<br> -> Registro(s) Actualizado(s)";
								$objDetalle->guardaDetalleSistema($proceso,$usuarioEnsamble,$equipos[$i]);
							}else{
								echo "<br> -> Registro(s) No Actualizado(s)";
							}				
						}else{
							echo "<br>Verifique la informaci&oacute;n del equipo con imei <strong>(".$equipos[$i].")</strong>.<br>";
						}
					}else{
						echo "<p><span style='color:#FF0000'>El imei (".$equipos[$i].") ESTA CLASIFICADO COMO NO ENVIAR, RETIRELO Y ENTREGUELO AL ALMACEN.</span></p>";
					}
				}else if($estaEnviado==1){
					echo "<p><span style='color:blue'>El imei (".$equipos[$i].") SE ENCUENTRA ENVIADO.</span></p>";
				}else{
					echo "<p><span style='color:blue'>El imei (".$equipos[$i].") NO SE ENCUENTRA EN LA BASE DE DATOS.</span></p>";
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