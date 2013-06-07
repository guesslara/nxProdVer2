<?
	header("Content-Type: text/html; charset=iso-8859-1");
	//include("../../clases/guardaDetalle.php");
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
		
		public function contarEquiposDiagnostico(){
			$sqlIng="SELECT distinct count( DISTINCT detalle_ing.id_radio ) AS total FROM detalle_ing INNER JOIN equipos ON detalle_ing.id_radio = equipos.id_radio WHERE id_proc =14 AND f_registro = '".date("Y-m-d")."' AND equipos.status = 'WIP'";
			$sqlScrap="SELECT distinct count( DISTINCT detalle_ing.id_radio ) AS total1 FROM detalle_ing INNER JOIN equipos ON detalle_ing.id_radio = equipos.id_radio WHERE ((id_proc =14 AND f_registro = '".date("Y-m-d")."') AND equipos.status = 'WIP2')";
			$resIng=mysql_query($sqlIng,$this->conectarBd());			
			$resScrap=mysql_query($sqlScrap,$this->conectarBd());
			$rowIng=mysql_fetch_array($resIng);
			$rowScrap=mysql_fetch_array($resScrap);
			echo "Capturados hoy:<br> <div style='text-align:left;margin:5px;'>Ok &nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;".$rowIng["total"]."<br>WIP2 &nbsp;&raquo;&nbsp;".$rowScrap["total1"]."</div>";

		}
		
		public function guardarEquiposWip2($txtImeiWip2,$fallas,$cajaRespuesta,$procesoSistema,$idUsuarioProceso){
			//se instancia el objeto
			$objEquipo=new funcionesComunes();						
			$estaEnviado=$objEquipo->buscarImeiEnviado($txtImeiWip2);
			$esNoEnviar=$objEquipo->buscarNoEnviar($txtImeiWip2);
			$estaEnBd=$objEquipo->buscarImei($txtImeiWip2);			
			$esScrap=$objEquipo->buscarImeiScrap($txtImeiWip2);
			$scrapPorEntregar=$objEquipo->buscarImeiScrapPorEntregar($txtImeiWip2);
			if($estaEnBd==0){
				$msgCaja="EQUIPO NO EXISTE";
				$color="red";
				$fuente="white";
				//return;
			}else if($estaEnviado==1){//
				$msgCaja="Equipo enviado";
				$color="red";
				$fuente="white";
				//return;
			}else if($esNoEnviar==1){
				$msgCaja="Equipo no Enviar";
				$color="red";
				$fuente="white";
				//return;
			}else if($esScrap==1){
				$msgCaja="Equipo marcado como Scrap";
				$color="red";
				$fuente="white";
			}else if($scrapPorEntregar==1){
				$msgCaja="SCRAP POR ENTREGAR";
				$color="red";
				$fuente="white";
			}else{
				//se extrae la informacion del equipo
				echo "<br>".$sqlEquipo="SELECT * from equipos where imei='".$txtImeiWip2."'";
				$resEquipo=mysql_query($sqlEquipo,$this->conectarBd());
				$rowRadio=mysql_fetch_array($resEquipo);
				if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Diagnostico Tarjetas" && $rowRadio["statusDiagnostico"]=="N/A" && $rowRadio["statusDesensamble"]!="N/A"){
					//echo "Procede";
					//se procede a realizar la modificacion en la base de datos
					$sqlActualiza="update equipos set status='WIP2',statusDiagnostico='WIP2',statusProceso='Diagnosticado' WHERE imei='".$txtImeiWip2."'";
					$resActualizaEquipo=mysql_query($sqlActualiza,$this->conectarBd());
					if($resActualizaEquipo){
						//inserta registro en la tabla
						$fallas=explode(",",$fallas);
						for($i=0;$i<count($fallas);$i++){
							$sqlFalla="INSERT INTO equipos_reparacion (id_radio,id_falla) VALUES ('".$rowRadio["id_radio"]."','".$fallas[$i]."')";
							$resFalla=mysql_query($sqlFalla,$this->conectarBd());
							if($resFalla){
								echo "Falla Guardada";
							}else{
								echo "Error al Guardar la(s) Falla(s)";
								exit;
							}
						}
						//se guarda el detalle del radio
						$objEquipo->guardaDetalleSistema($procesoSistema,$idUsuarioProceso,$txtImeiWip2);						
						$msgCaja="Guardado";
						$color="green";
						$fuente="white";						
					}else{
						$msgCaja="No Actualizado";
						$color="red";
						$fuente="white";
					}					
				}else{
					$msgCaja="Verifique el Equipo";
					$color="orange";
					$fuente="black";
				}				
				//se escribe el resultado en el elemento indicado
				echo "<script type='text/javascript'>document.getElementById('".$cajaRespuesta."').value='".$msgCaja."'; </script>";
				echo "<script type='text/javascript'>document.getElementById('".$cajaRespuesta."').style.background='".$color."'; </script>";
				echo "<script type='text/javascript'>document.getElementById('".$cajaRespuesta."').style.color='".$fuente."'; </script>";
			}
			echo "<script type='text/javascript'>document.getElementById('".$cajaRespuesta."').value='".$msgCaja."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$cajaRespuesta."').style.background='".$color."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$cajaRespuesta."').style.color='".$fuente."'; </script>";
		}
		
		public function cargaCatalogoFallas(){
			$sqlCatFalla="SELECT * FROM `cat_falla`";
			$resCatFalla=mysql_query($sqlCatFalla,$this->conectarBd());
			if(mysql_num_rows($resCatFalla)==0){
				echo "( 0 ) registros encontrados.";
			}else{
				$i=0;
				echo "<form name='frmCatalogoFallas' id='frmCatalogoFallas'>";
				while($rowCatFalla=mysql_fetch_array($resCatFalla)){
					$nombreCheck="nombreCheck".$i;
					echo "<div style='height:15px;padding:5px;'><input id='".$nombreCheck."' type='checkbox' value='".$rowCatFalla["id_falla"]."' /><label for='".$nombreCheck."'>".$rowCatFalla["descripcion"]."</label></div>";
					$i+=1;
				}
				echo "</form><br>";
			}
		}
		
		public function actualizaDatosEquipo2($usrDiagnostico,$proceso,$filtro,$idElemento,$valores){
			//se instancia el objeto
			$objEquipo=new funcionesComunes();						
			$estaEnviado=$objEquipo->buscarImeiEnviado($valores);
			$esNoEnviar=$objEquipo->buscarNoEnviar($valores);
			$estaEnBd=$objEquipo->buscarImei($valores);			
			$esScrap=$objEquipo->buscarImeiScrap($valores);						
			if($estaEnBd==0){
				$msgCaja="EQUIPO NO EXISTE";
				$color="red";
				$fuente="white";
				//return;
			}else if($estaEnviado==1){//
				$msgCaja="Equipo enviado";
				$color="red";
				$fuente="white";
				//return;
			}else if($esNoEnviar==1){
				$msgCaja="Equipo no Enviar";
				$color="red";
				$fuente="white";
				//return;
			}else if($esScrap==1){
				$msgCaja="Equipo marcado como Scrap";
				$color="red";
				$fuente="white";
			}else{
				//se extrae la informacion del equipo
				echo "<br>".$sqlEquipo="SELECT * from equipos where imei='".$valores."'";
				$resEquipo=mysql_query($sqlEquipo,$this->conectarBd());
				$rowRadio=mysql_fetch_array($resEquipo);
				if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Diagnostico Tarjetas" && $rowRadio["statusDiagnostico"]=="N/A" && $rowRadio["statusDesensamble"]!="N/A"){
					echo "Procede";
					//se procede a realizar la modificacion en la base de datos
					echo "<br>".$sqlActualiza="update equipos set status='WIP',statusDiagnostico='OK',statusProceso='Diagnosticado' WHERE imei='".$valores."'";
					$resActualizaEquipo=mysql_query($sqlActualiza,$this->conectarBd());
					if($resActualizaEquipo){
						//se guarda el detalle del radio
						$objEquipo->guardaDetalleSistema($proceso,$usrDiagnostico,$valores);
						$msgCaja="Guardado";
						$color="green";
						$fuente="white";
						echo "<script type='text/javascript'> contarEquiposDiag(); </script>";
					}else{
						$msgCaja="No Actualizado";
						$color="red";
						$fuente="white";
					}					
				}else{
					$msgCaja="Verifique el Equipo";
					$color="orange";
					$fuente="black";
				}
				//se escribe el resultado en el elemento indicado
				echo "<script type='text/javascript'>document.getElementById('".$idElemento."').value='".$msgCaja."'; </script>";
				echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.background='".$color."'; </script>";
				echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.color='".$fuente."'; </script>";
			}
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').value='".$msgCaja."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.background='".$color."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.color='".$fuente."'; </script>";
		}
		
		public function actualizaDatosEquipo($imeiEnsamble,$status,$proceso,$idusuarioSistema){
			$objDetalle=new guardaDetalle();
			echo "<br>Procesando Datos...........................<br>";
			//verificar si existe algun imei repetido
			$repeticiones=$this->imeiRepetido($imeiEnsamble);
			//se compara y se guarda en la base de datos			
			if($repeticiones > 1 || $repeticiones==0){
				echo "<br><br>Error, verifique la informaci&oacute;n del IMEI en la Base de Datos.<br>";
?>
				<script type="text/javascript"> limpiaDatos();</script>
<?				
			}else{
				//verificar que el imei este en desensamble
				$sqlImei="SELECT statusProceso FROM equipos WHERE imei='".$imeiEnsamble."'";
				$resImei=mysql_query($sqlImei,$this->conectarBd());
				$rowImei=mysql_fetch_array($resImei);
				if($rowImei['statusProceso']=="Diagnostico Tarjetas"){								
					/********************************************************/
					echo "<br>Actualizando Datos...........................<br>";
					
					if($status=="OK"){
						$status1="En Proceso";
						$statusProceso="waitALM";
						$statusDesensamble="OK";
					}else if($status=="NOK"){
						$status1="Scrap";
						$statusProceso="waitALM";
						$statusDesensamble="NOK";
					}				
					//echo "<br>".$sqlActualiza="UPDATE equipos set status='".$status1."',statusProceso='".$statusProceso."',statusDesensamble='".$statusDesensamble."'  WHERE imei='".$imeiEnsamble."'";
					$sqlActualiza="UPDATE equipos set statusProceso='Diagnosticado',statusDiagnostico='".$statusDesensamble."'  WHERE imei='".$imeiEnsamble."'";
					$res=mysql_query($sqlActualiza,$this->conectarBd());
					if($res){
						echo "<br><br>Registro Actualizado.<br><br>";
						//se inserta el detalle para el seguimiento del equipo
						$objDetalle->guardaDetalleSistema($proceso,$idusuarioSistema,$imeiEnsamble);						
?>
						<script type="text/javascript"> limpiaDatos();</script>
<?
					}else{
						echo "<br><br>Error al actualizar la informaci&oacute;n.<br>";
?>
						<script type="text/javascript"> limpiaDatos();</script>
<?						
					}
				}else{
					echo "Verifique la informacion del equipo, ya que el IMEI no se encuentra en Desensamble";
				}
			}
		}
		
		private function imeiRepetido($imeiEnsamble){
			$sqlBuscarImei="SELECT * FROM equipos WHERE imei='".$imeiEnsamble."'";
			$resRadio=mysql_query($sqlBuscarImei,$this->conectarBd());
			$numeroRep=mysql_num_rows($resRadio);
			return $numeroRep;
		}
		
		public function actualizaDatos($equipos,$proceso,$usuarioEnsamble,$filtro){			
			$objDetalle=new guardaDetalle();
			//se extrae el id del radio
			echo "<br>Procesando Datos...........................";
			$equipos=explode(",",$equipos);
			for($i=0;$i<count($equipos);$i++){
				//se verifica que exista el imei en la Base de Datos
				$repeticiones=$this->imeiRepetido($equipos[$i]);
				
				if($repeticiones!=0){
					$sqlRadio="SELECT statusDiagnostico FROM equipos WHERE imei='".$equipos[$i]."'";
					$resRadio=mysql_query($sqlRadio,$this->conectarBd());
					$rowRadio=mysql_fetch_array($resRadio);
					$statusDiagnostico=$rowRadio['statusDiagnostico'];
					
					if($statusDiagnostico!="N/A"){
						echo "El imei (".$equipos[$i].") ya se encuentra Actualizado";
					}else{
						//se procede a la actualizacion de los equipos
						//verificar que el imei este en desensamble
						$sqlImei="SELECT statusProceso FROM equipos WHERE imei='".$equipos[$i]."'";
						$resImei=mysql_query($sqlImei,$this->conectarBd());
						$rowImei=mysql_fetch_array($resImei);
						//if($rowImei['statusProceso']=="Diagnostico Tarjetas"){
						if($filtro=="SCRAP"){
							$sqlActualiza="UPDATE equipos set status='".$filtro."',statusProceso='Diagnosticado',statusDiagnostico='".$filtro."' WHERE imei='".$equipos[$i]."'";
						}else{
							$sqlActualiza="UPDATE equipos set statusProceso='Diagnosticado',statusDiagnostico='".$filtro."' WHERE imei='".$equipos[$i]."'";
						}
							$res=mysql_query($sqlActualiza,$this->conectarBd());
							if($res){
								echo "<br>Registro Actualizado.<br>";
								//se inserta el detalle para el seguimiento del equipo
								$objDetalle->guardaDetalleSistema($proceso,$usuarioEnsamble,$equipos[$i]);						
							}else{
								echo "<br><br>Error al actualizar la informaci&oacute;n del imei (".$equipos[$i].").<br>";
							}
						//}else{
						//	echo "Verifique la informacion del equipo, ya que el IMEI no se encuentra en Desensamble";
						//}
					}
				}else{
					echo "<br>Error, el imei (".$equipos[$i].") no existe en la Base de Datos.<br>";
				}
			}			
		}
	}//fin de la clase
?>