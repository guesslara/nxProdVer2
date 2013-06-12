<?
	session_start();
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

		public function guardarEquipoCI($usuario,$imeiDonante,$imeiReceptor,$observaciones){
			//se prepara la insercion en la base de datos
			$sqlCI="INSERT INTO equipos_ci (id_radio_donante,id_radio_receptor,observaciones,id_usuario,fecha,hora) VALUES ('".$imeiDonante."','".$imeiReceptor."','".$observaciones."','".$usuario."','".date("Y-m-d")."','".date("H:i:s")."')";
			$resCI=mysql_query($sqlCI,$this->conectarBd());
			if($resCI){
				echo "<script type='text/javascript'> alert('Informacion Actualizada Correctamente.'); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Error al Guardar la Asignacion del C.I.'); </script>";
			}
		}
		
		public function mostrarResumen($imei,$div){
			$sql="SELECT * FROM equipos WHERE imei='".$imei."' AND status in ('WIP','WIP2')";
			$res=mysql_query($sql,$this->conectarBd());
			if(mysql_num_rows($res)==0){
				echo "( 0 ) registros encontrados.";
			}else{
				$row=mysql_fetch_array($res);
				if($div=="divDonante"){
?>
				<script type="text/javascript"> $("#txtImeiDonante").attr("value",""); $("#txtImeiDonante").focus(); </script>
				<input type="hidden" name="txtIdRadioDonante" id="txtIdRadioDonante" value="<?=$row["id_radio"];?>">
<?				
				}else if($div=="divReceptor"){
?>
				<script type="text/javascript"> $("#txtImeiReceptor").attr("value",""); $("#txtImeiReceptor").focus(); </script>
				<input type="hidden" name="txtIdRadioReceptor" id="txtIdRadioReceptor" value="<?=$row["id_radio"];?>">
<?				
				}				
?>
				<fieldset style="width: 340px;"><legend>Detalles:</legend>				
				<table border="0" cellpadding="1" cellspacing="1" width="330">
					<tr>
						<td width="80" colspan="2" style="border: 1px solid #CCC;background: #f0f0f0;height: 15px;padding: 5px;">Imei</td>
						<td width="150">&nbsp;<?=$row["imei"];?></td>
					</tr>
					<tr>
						<td colspan="2" style="border: 1px solid #CCC;background: #f0f0f0;height: 15px;padding: 5px;">Serial</td>
						<td>&nbsp;<?=$row["serial"];?></td>
					</tr>
					<tr>
						<td colspan="2" style="border: 1px solid #CCC;background: #f0f0f0;height: 15px;padding: 5px;">Folio</td>
						<td>&nbsp;<?=$row["lote"];?></td>
					</tr>
					<tr>
						<td colspan="2" style="border: 1px solid #CCC;background: #f0f0f0;height: 15px;padding: 5px;">Status</td>
						<td>&nbsp;<?=$row["status"];?></td>
					</tr>
				</table>
				</fieldset>
<?				
			}			
		}
		
		public function mostrarForm(){
?>
			<script type="text/javascript"> $("#txtImeiDonante").focus(); </script>
			<br>			
			<div id="divGuardadoCI"></div>
			<table border="0" cellpadding="1" cellspacing="1" width="800" style="font-size: 12px;margin: 5px;">
				<tr>
					<td colspan="2" style="height: 25px;padding: 5px;background: #000;color: #FFF;">Cambio de Identidad de Equipos</td>
				</tr>
				<tr>
					<td width="400" valign="top">
						<div style="height: 20px;padding: 5px;border: 1px solid #CCC;background: #e1e1e1;">Imei Donante</div>
						<div style="border: 1px solid #666;">
							<table width="97%" cellpadding="1" cellspacing="1">
								<tr>
									<td width="30%" style="text-align: left;">Imei Donante:</td>
									<td width="67%" style="text-align: left;"><input type="text" name="txtImeiDonante" id="txtImeiDonante" onkeyup="buscarImei(event,'Donante')"></td>
								</tr>
								<tr>
									<td colspan="2">										
										<div id="divDonante" style="margin: 0px;height: auto;border:0px solid #CCC;">
										</div>
									</td>
								</tr>
							</table>
					</td>
					<td width="400" valign="top">
						<div style="height: 20px;padding: 5px;border: 1px solid #CCC;background: #e1e1e1;">Imei Receptor</div>
						<div style="border: 1px solid #666;">
							<table width="97%" cellpadding="1" cellspacing="1">
								<tr>
									<td width="30%" style="text-align: left;">Imei Receptor:</td>
									<td width="67%" style="text-align: left;"><input type="text" name="txtImeiReceptor" id="txtImeiReceptor" onkeyup="buscarImei(event,'Receptor')"></td>
								</tr>
								<tr>
									<td colspan="2">
										<div id="divReceptor" style="margin: 0px;height:auto;border:0px solid #CCC;">
												
										</div>										
									</td>
								</tr>
								<tr>
									<td colspan="2"><br>Observaciones:<br><br>
										<textarea name="txtObservaciones" id="txtObservaciones" cols="40" rows="3"></textarea>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr style="background: #000;"></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right;">
						<input type="reset" value="Cancelar" style="height: 33px;padding: 5px;">
						<input type="button" onclick="guardarCI()" value="Guardar C.I." style="height: 33px;padding: 5px;">
					</td>
				</tr>
			</table>
			
			<div id="divDetalleGuardado"></div>
<?
		}
	}//fin de la clase
?>