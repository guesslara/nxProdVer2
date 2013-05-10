<?
	/*
	Clase modificada en Abril de 2012 para poder guardar el Flex con los equipos.
	Se añadio la posibilidad de capturar de forma masiva mediante ajax en la APP
	Se reescribio por completo la clase y el modulo en general, reutilizando algunas funciones y segmentos de codigo necesario
	
	Autor:Gerardo Lara
	
	*/
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
		
		public function validaStatusBaseDatos($imei){
			$sqlImei="SELECT * from equipos where imei='".$imei."'";
			$resImei=mysql_query($sqlImei,$this->conectarBd());			
			return mysql_num_rows($resImei);
		}
		
		public function contarEquiposIngenieria(){
			$sqlIng="SELECT distinct count( DISTINCT detalle_ing.id_radio ) AS total FROM detalle_ing INNER JOIN equipos ON detalle_ing.id_radio = equipos.id_radio WHERE (id_proc =4 AND f_registro = '".date("Y-m-d")."') AND equipos.status IN ('WIP','Validando','ENVIADO')";
			$sqlScrap="SELECT distinct count( DISTINCT detalle_ing.id_radio ) AS total1 FROM detalle_ing INNER JOIN equipos ON detalle_ing.id_radio = equipos.id_radio WHERE (id_proc =4 AND f_registro = '".date("Y-m-d")."') AND equipos.status = 'SCRAP'";
			$sqlLineas="SELECT count( DISTINCT detalle_ing.id_radio ) AS total,equipos.lineaEnsamble AS linea FROM detalle_ing INNER JOIN equipos ON detalle_ing.id_radio = equipos.id_radio WHERE (id_proc =4 AND f_registro = '".date("Y-m-d")."') AND equipos.status IN ('WIP','Validando','ENVIADO') GROUP BY equipos.lineaEnsamble";
			$resIng=mysql_query($sqlIng,$this->conectarBd());
			$resScrap=mysql_query($sqlScrap,$this->conectarBd());
			$resLineas=mysql_query($sqlLineas,$this->conectarBd());
			$rowIng=mysql_fetch_array($resIng);
			$rowScrap=mysql_fetch_array($resScrap);
			echo "Capturados hoy:<br> <div style='text-align:left;margin:5px;'>Ok &nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;".$rowIng["total"]."<br>Scrap &raquo;&nbsp;".$rowScrap["total1"]."<br>---------------<br>";
			while($rowLinea=mysql_fetch_array($resLineas)){
				echo "Linea ".$rowLinea["linea"]."&raquo;&nbsp;".$rowLinea["total"]."<br>";	
			}
			echo "</div>";
		}
		
		public function actualizaEquipoIngenieriaScrap($proceso,$usrEnsamble,$linea,$idElemento,$valores){
			$objFunciones=new funcionesComunes();
			$equipos[0]=$valores;
			if(strlen($equipos[0]) < 15){
				$msgCaja="Verifique Imei";
				$color="red";
				$fuente="white";
				$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
				return;
			}else{
				$encontrados=$this->validaStatusBaseDatos($equipos[0]);
				if($encontrados==0){						
					$msgCaja="Imei NO EXISTE";
					$color="red";
					$fuente="white";
					$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
					return;
				}else{
					$encontrados=$this->validaStatusBaseDatos($equipos[0]);
					if($encontrados==0){						
						$msgCaja="Imei NO EXISTE";
						$color="red";
						$fuente="white";
						$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
						return;
					}else{
						//se extrae la info del radio
						$sqlRadio="select * from equipos where imei='".$equipos[0]."'";
						$resRadio=mysql_query($sqlRadio,$this->conectarBd());
						$rowRadio=mysql_fetch_array($resRadio);
						//primera validacion que el imei no este marcado como enviado
						if($rowRadio["status"]=="ENVIADO"){								
							$msgCaja="Imei ENVIADO";
							$color="red";
							$fuente="white";
							$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
							return;
						}else if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Empaque" && $rowRadio["statusIngenieria"]=="ING_OK"){
							//si el equipo ya paso por ingenieria y se hizo una segunda pasada con el scanner								
							$msgCaja="Equipo ya CLASIFICADO";
							$color="orange";
							$fuente="black";
							$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
							return;
						}else{
							//se valida que ninguno de los status este marcado en la base de datos
							if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Desensamble" || $rowRadio["statusProceso"]=="Recibo" && $rowRadio["statusDesensamble"]=="N/A" && $rowRadio["statusDiagnostico"]=="N/A" && $rowRadio["statusAlmacen"]=="N/A" && $rowRadio["statusIngenieria"]=="N/A" && $rowRadio["statusEmpaque"]=="N/A"){
								//se procede a la actualizacion del equipo rellenando los status faltantes
								//$sqlUpdate="UPDATE equipos set status='SCRAP',statusProceso='Ingenieria',statusDesensamble='OK',statusDiagnostico='OK',statusAlmacen='Asignado',sim='".$equipos[1]."',statusIngenieria='SCRAP',lineaEnsamble='".$linea."' where imei='".$equipos[0]."'";
								//SE MODIFICA EL QUERY PARA PODER PONER LOS EQUIPOS NE WIP2
								$sqlUpdate="UPDATE equipos set status='WIP2',statusProceso='Ingenieria',statusDesensamble='OK',statusDiagnostico='OK',statusAlmacen='Asignado',sim='".$equipos[1]."',statusIngenieria='SCRAP',lineaEnsamble='".$linea."' where imei='".$equipos[0]."'";
								$insertaControl="general";
							}else if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Ingenieria"){
								//si la primera condicion no se cumple entonces se actualiza solo es status que hace falta
								//$sqlUpdate="UPDATE equipos set status='SCRAP',statusProceso='Ingenieria',statusIngenieria='SCRAP',lineaEnsamble='".$linea."' where imei='".$equipos[0]."'";
								$sqlUpdate="UPDATE equipos set status='WIP2',statusProceso='Ingenieria',statusIngenieria='SCRAP',lineaEnsamble='".$linea."' where imei='".$equipos[0]."'";
								$insertaControl="proceso";
							}
							//se valida que el equipo no este en NO_ENVIAR
							$regsNoEnviar=$objFunciones->buscarNoEnviar($equipos[0]);
							if($regsNoEnviar==0){
								//echo "<br>".$sqlUpdate."<br>";
								$resRadio=mysql_query($sqlUpdate,$this->conectarBd());
								if($resRadio){										
									$msgCaja="Equipo Actualizado";
									$color="green";
									$fuente="white";
									$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
									if($insertaControl=="proceso"){
										//se inserta el detalle para el seguimiento del equipo										
										$objFunciones->guardaDetalleSistema($proceso,$usrEnsamble,$equipos[0]);	
									}else{
										$objFunciones->guardaDetalleSistema(2,$usrEnsamble,$equipos[0]);
										$objFunciones->guardaDetalleSistema(3,$usrEnsamble,$equipos[0]);
										$objFunciones->guardaDetalleSistema(8,$usrEnsamble,$equipos[0]);
										$objFunciones->guardaDetalleSistema(3,$usrEnsamble,$equipos[0]);
										$objFunciones->guardaDetalleSistema(4,$usrEnsamble,$equipos[0]);
									}
									echo "<script type='text/javascript'> contarEquiposIng(); </script>";
									return;
								}else{									
									$msgCaja="Error al Actualizar";
									$color="orange";
									$fuente="black";
									$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
								}
							}else{
								$msgCaja="Equipo NO ENVIAR";
								$color="orange";
								$fuente="black";
								$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);									
							}
						}
					}
				}
			}
		}
		
		public function actualizaEquipoIngenieria($proceso,$usrEnsamble,$linea,$filtroFlex,$idElemento,$valores){
			$objFunciones=new funcionesComunes();
			$equipos=explode(",",$valores);
			/*echo "<br>0 -> ".$equipos[0];
			echo "<br>";
			echo "1 -> ".$equipos[1];*/
			if(strlen($equipos[0]) < 15){
				$msgCaja="Verifique Imei";
				$color="red";
				$fuente="white";
				$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
				return;
			}else{
				//se verifica la sim
				if(strlen($equipos[1]) < 15){
					$msgCaja="Verifique Sim";
					$color="red";
					$fuente="white";
					$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
					return;
				}else{
					$encontrados=$this->validaStatusBaseDatos($equipos[0]);
					if($encontrados==0){						
						$msgCaja="Imei NO EXISTE";
						$color="red";
						$fuente="white";
						$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
						return;
					}else{
						//echo "<br>paso ambas validaciones";
						$sqlSimBd="select * from equipos where sim='".$equipos[1]."'";
						$resSimBd=mysql_query($sqlSimBd,$this->conectarBd());						
						if(mysql_num_rows($resSimBd) >= 1){
							$msgCaja="SIM REPETIDA";
							$color="red";
							$fuente="white";
							$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
							return;
						}else{
							//se extrae la info del radio
							$sqlRadio="select * from equipos where imei='".$equipos[0]."'";
							$resRadio=mysql_query($sqlRadio,$this->conectarBd());
							$rowRadio=mysql_fetch_array($resRadio);
							//primera validacion que el imei no este marcado como enviado
							if($rowRadio["status"]=="ENVIADO"){								
								$msgCaja="Imei ENVIADO";
								$color="red";
								$fuente="white";
								$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
								return;
							}else if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Empaque" && $rowRadio["statusIngenieria"]=="ING_OK"){
								//si el equipo ya paso por ingenieria y se hizo una segunda pasada con el scanner								
								$msgCaja="Equipo ya CLASIFICADO";
								$color="orange";
								$fuente="black";
								$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
								return;
							}else{
								//se valida que ninguno de los status este marcado en la base de datos
								if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Desensamble" || $rowRadio["statusProceso"]=="Recibo" && $rowRadio["statusDesensamble"]=="N/A" && $rowRadio["statusDiagnostico"]=="N/A" && $rowRadio["statusAlmacen"]=="N/A" && $rowRadio["statusIngenieria"]=="N/A" && $rowRadio["statusEmpaque"]=="N/A"){
									//se procede a la actualizacion del equipo rellenando los status faltantes
									$sqlUpdate="UPDATE equipos set statusProceso='Empaque',statusDesensamble='OK',statusDiagnostico='OK',statusAlmacen='Asignado',sim='".$equipos[1]."',statusIngenieria='ING_OK',lineaEnsamble='".$linea."',flexNuevo='".$filtroFlex."' where imei='".$equipos[0]."'";
									$insertaControl="general";
								}else if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Ingenieria"){
									//si la primera condicion no se cumple entonces se actualiza solo es status que hace falta
									$sqlUpdate="UPDATE equipos set statusProceso='Empaque',statusIngenieria='ING_OK',sim='".$equipos[1]."',lineaEnsamble='".$linea."',flexNuevo='".$filtroFlex."' where imei='".$equipos[0]."'";
									$insertaControl="proceso";
								}
								//se valida que el equipo no este en NO_ENVIAR
								$regsNoEnviar=$objFunciones->buscarNoEnviar($equipos[0]);
								if($regsNoEnviar==0){
									//echo "<br>".$sqlUpdate."<br>";
									$resRadio=mysql_query($sqlUpdate,$this->conectarBd());
									if($resRadio){										
										$msgCaja="Equipo Actualizado";
										$color="green";
										$fuente="white";
										$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);										
										if($insertaControl=="proceso"){
											//se inserta el detalle para el seguimiento del equipo										
											$objFunciones->guardaDetalleSistema($proceso,$usrEnsamble,$equipos[0]);	
										}else{
											$objFunciones->guardaDetalleSistema(2,$usrEnsamble,$equipos[0]);
											$objFunciones->guardaDetalleSistema(3,$usrEnsamble,$equipos[0]);
											$objFunciones->guardaDetalleSistema(8,$usrEnsamble,$equipos[0]);
											$objFunciones->guardaDetalleSistema(3,$usrEnsamble,$equipos[0]);
											$objFunciones->guardaDetalleSistema(4,$usrEnsamble,$equipos[0]);
										}
										echo "<script type='text/javascript'> contarEquiposIng(); </script>";
										return;
									}else{										
										$msgCaja="Error al Actualizar";
										$color="orange";
										$fuente="black";
										$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);
									}
								}else{
									$msgCaja="Equipo NO ENVIAR";
									$color="orange";
									$fuente="black";
									$this->mensajesCaja($idElemento,$msgCaja,$color,$fuente);									
								}
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
		
		public function actualizaDatosIngenieria($equipos,$proceso,$usuarioEnsamble,$linea,$filtro){	
			//$objDetalle=new guardaDetalle();
			$objFunciones=new funcionesComunes();
			//se extrae el id del radio
			echo "<br>Procesando Datos...........................<br>";
			$equipos=explode(",",$equipos);
			for($i=0;$i<count($equipos);$i++){
				/*modificacion para poder insertar los equipos y actualizarlos*/
				$arrayEquipos=explode("|",$equipos[$i]);
				//echo "Imei:".$arrayEquipos[0]."<br>";
				//echo "Sim:".$arrayEquipos[1]."<br>";
				//se busca que el imei exista en la base de datos
				$encontrados=$this->validaStatusBaseDatos($arrayEquipos[0]);
				if($encontrados==0){
					echo "<p>El imei (".$arrayEquipos[0].") no existe en la Base de Datos.</p>";
				}else{
					//proceso de cambio e insercion en la base de datos
					//se extrae la info del radio
					$sqlRadio="select * from equipos where imei='".$arrayEquipos[0]."'";
					$resRadio=mysql_query($sqlRadio,$this->conectarBd());
					$rowRadio=mysql_fetch_array($resRadio);
					//primera validacion que el imei no este marcado como enviado
					if($rowRadio["status"]=="ENVIADO"){						
						echo "<p>El imei (".$arrayEquipos[0].") ya esta marcado como ENVIADO, verifique la informacion.</p>";
						return;
					}else if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Empaque" && $rowRadio["statusIngenieria"]=="ING_OK"){
						//si el equipo ya paso por ingenieria y se hizo una segunda pasada con el scanner
						echo "<p>El imei (".$arrayEquipos[0].") ya esta clasificado por <strong>INGENIERIA</strong>, verifique la informacion.</p>";
					}else{		
						//se valida que ninguno de los status este marcado en la base de datos
						if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Desensamble" || $rowRadio["statusProceso"]=="Recibo" && $rowRadio["statusDesensamble"]=="N/A" && $rowRadio["statusDiagnostico"]=="N/A" && $rowRadio["statusAlmacen"]=="N/A" && $rowRadio["statusIngenieria"]=="N/A" && $rowRadio["statusEmpaque"]=="N/A"){
							//se procede a la actualizacion del equipo rellenando los status faltantes
							if($filtro=="SCRAP"){
								$sqlUpdate="UPDATE equipos set status='SCRAP',statusProceso='Ingenieria',statusDesensamble='OK',statusDiagnostico='OK',statusAlmacen='Asignado',sim='".$arrayEquipos[1]."',statusIngenieria='".$filtro."',lineaEnsamble='".$linea."' where imei='".$arrayEquipos[0]."'";
							}else{
								$sqlUpdate="UPDATE equipos set statusProceso='Empaque',statusDesensamble='OK',statusDiagnostico='OK',statusAlmacen='Asignado',sim='".$arrayEquipos[1]."',statusIngenieria='".$filtro."',lineaEnsamble='".$linea."' where imei='".$arrayEquipos[0]."'";
							}
						}else if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Ingenieria"){
							//si la primera condicion no se cumple entonces se actualiza solo es status que hace falta
							if($filtro=="SCRAP"){
								$sqlUpdate="UPDATE equipos set status='SCRAP',statusProceso='Ingenieria',statusIngenieria='".$filtro."',sim='".$arrayEquipos[1]."',lineaEnsamble='".$linea."' where imei='".$arrayEquipos[0]."'";
							}else{
								$sqlUpdate="UPDATE equipos set statusProceso='Empaque',statusIngenieria='".$filtro."',sim='".$arrayEquipos[1]."',lineaEnsamble='".$linea."' where imei='".$arrayEquipos[0]."'";
							}
						}
						//se valida que el equipo no este en NO_ENVIAR
						$regsNoEnviar=$objFunciones->buscarNoEnviar($equipos[0]);
						if($regsNoEnviar==0){
							//echo "<br>".$sqlUpdate."<br>";
							$resRadio=mysql_query($sqlUpdate,$this->conectarBd());
							if($resRadio){
								echo "<p> -> Registro Actualizado</p>";
								//se inserta el detalle para el seguimiento del equipo
								$objFunciones->guardaDetalleSistema($proceso,$usuarioEnsamble,$equipos[$i]);
							}else{
								echo "<p> -> Registro No Actualizado</p>";
							}
						}else{
							echo "<p><span style='color:#FF0000'>El imei (".$arrayEquipos[0].") ESTA CLASIFICADO COMO NO ENVIAR, retirelo y entreguelo al ALMACEN.</span></p>";
						}
					}
				}
				
				//se verifica si el equipo proviene de desensamble
				/*$sqlC="select statusDesensamble,statusDiagnostico from equipos where imei='".$equipos[$i]."'";
				$resC=mysql_query($sqlC,$this->conectarBd());
				$rowC=mysql_fetch_array($resC);
				//echo "Estatus Desensamble ".$rowC['statusDesensamble'];
				if($rowC['statusDesensamble']=="OK" && $rowC['statusDiagnostico']=="OK"){		
					$sqlRadio="UPDATE equipos set statusIngenieria='ING_OK',statusProceso='Empaque',lineaEnsamble='".$linea."' WHERE imei='".$equipos[$i]."'";					
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
				}*/
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
		public function contAntEqui(){
			?>
			<div id="headQuery" style="width: 88%;height: 20px;color: #000;background: #EEE; text-align: center; font-size: 10px;padding: 5px; margin: 3px;font-weight: bold;">CONSULTA ANTERIORES</div>
			<div id="contenidoQ" style="width: 88%; height: auto; color: #000; font-size: 12px;">
				<form id="muestra">
				<table>
					<tr>
						<th>De:</th>
						<td>
							<input type="text" name="fechaIni" id="fechaIni" style="width: 80px;"/><input type="button" id="lanzador1" value="..." />
							<!-- script que define y configura el calendario-->
							<script type="text/javascript">
							Calendar.setup({
								inputField     :    "fechaIni",      // id del campo de texto
								ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
								button         :    "lanzador1"   // el id del botón que lanzará el calendario
							});
							</script>
						</td>
					</tr>
					<tr>
						<th >A:</th>
						<td>
							<input type="text" name="fechaFin" id="fechaFin" style="width: 80px;"/><input type="button" id="lanzador2" value="..." />
							<!-- script que define y configura el calendario-->
							<script type="text/javascript">
							Calendar.setup({
								inputField     :    "fechaFin",      // id del campo de texto
								ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
								button         :    "lanzador2"   // el id del botón que lanzará el calendario
							});
							</script>
						</td>
					</tr>
					<tr>
						<th colspan=3><input type="button" name="Acept" id="Acept" value="VER" onclick="muestrAnt();"/></th>
					</tr>
				</table></form>
			</div>
			<?
		}
		public function showRes($fechaIni,$fechaFin){
			$sqlLineas="SELECT count( DISTINCT detalle_ing.id_radio ) AS total,equipos.lineaEnsamble AS linea FROM detalle_ing INNER JOIN equipos ON detalle_ing.id_radio = equipos.id_radio WHERE (f_registro between '".$fechaIni."' AND '".$fechaFin."') AND(id_proc =4) AND equipos.status IN ('WIP','Validando','ENVIADO') GROUP BY equipos.lineaEnsamble";
			$resLineas=mysql_query($sqlLineas,$this->conectarBd());
			$sqlLineaSend="SELECT count( DISTINCT detalle_ing.id_radio ) AS total,equipos_enviados.lineaEnsamble AS linea FROM detalle_ing INNER JOIN equipos_enviados ON detalle_ing.id_radio = equipos_enviados.id_radio WHERE (f_registro between '".$fechaIni."' AND '".$fechaFin."')AND(id_proc =4) AND equipos_enviados.status IN ('WIP','Validando','ENVIADO') GROUP BY equipos_enviados.lineaEnsamble";
			$exeLineaSend=mysql_query($sqlLineaSend,$this->conectarBd());
			$suma=0;$suma2=0;
			?>
				<div id="headTa" style="width: 97%; height: 20px;font-size: 12px; text-align: center; background: #EEE;font-weight: bold; margin: 3px;clear: both;">Capturados del <?=$fechaIni?> al <?=$fechaFin;?></div>
				<div id="headQ" style="width: 97%; height: auto;font-size: 12px; text-align: center; background: #fff;margin: 3px;clear: both;overflow: auto;">
					<p style="text-align: center; font-weight: bold;">Equipo Proc.</p>
					<table>
					<?
					while($rowLinea=mysql_fetch_array($resLineas)){
						?>
							<tr>
								<th>Linea <?=$rowLinea["linea"];?></th>
								<td><?=$rowLinea["total"];?></td>
								<td><a href="#" onclick="muestraMod('<?=$rowLinea['linea']?>');queryMod('<?=$rowLinea['linea']?>','<?=$fechaIni?>',<?$fechaFin?>);" style="color:blue;text-decoration: none;" title="Ver por Modelos"><img src="../../img/add.png" border="0" /></a></td>
								<td><div id="close<?=$rowLinea['linea']?>"></div></td>
							</tr>
							<tr>
								<td colspan=4>
									<div id="modL<?=$rowLinea['linea']?>" style="display: none;">
										hola
									</div>
								</td>
								
							</tr>
						
						<?
						$suma=$suma+$rowLinea["total"];
					}
					?></table>
					
				</div>
				<div id="headTotal" style="width: 97%; height: 20px;font-size: 12px; text-align: center; background: #EEE;font-weight: bold; margin: 3px;clear: both;">Total periodo:<?=$suma?></div>
			<?
			?>
				<div id="headSend" style="width: 97%; height: auto;font-size: 12px; text-align: center; background: #fff;margin: 3px;clear: both;overflow: auto;">
					<p style="text-align: center; font-weight: bold;">Equipos Enviados</p>
					<table>
					<?
					while($rowSend=mysql_fetch_array($exeLineaSend)){
						?>
							<tr>
								<th>Linea <?=$rowSend["linea"];?></th>
								<td><?=$rowSend["total"];?></td>
								<td><a href="#" onclick="" style="color:blue;text-decoration: none;" title="Ver por Modelos"><img src="../../img/add.png" border="0" /></a></td>
							</tr>
						
						<?
						$suma2=$suma2+$rowSend["total"];
					}
					?></table>
					
				</div>
				<div id="headTotal" style="width: 97%; height: 20px;font-size: 12px; text-align: center; background: #EEE;font-weight: bold; margin: 3px;clear: both;">Total periodo:<?=$suma2?></div>
			<?
		}
		public function queryMod($line){
			$sqlMod="SELECT COUNT(DISTINCT detalle_ing.id_radio) AS totalM,modelo,lineaEnsamble AS Linea FROM equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo inner join detalle_ing on equipos.id_radio=detalle_ing.id_radio WHERE (f_registro between '".$fechaIni."' AND '".$fechaFin."')AND(id_proc =4) AND equipos.status IN ('WIP','Validando','ENVIADO') AND lineaEnsamble=";
		}
	}//fin de la clase
?>