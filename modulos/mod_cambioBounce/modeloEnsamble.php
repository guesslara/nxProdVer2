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

		public function guardarReemplazo($imei,$imeiB,$serialB,$tipoCambio){
			$sql="SELECT id_radio FROM equipos WHERE imei='".$imei."'";
			$res=mysql_query($sql,$this->conectarBd());
			$row=mysql_fetch_array($res);
			$sql="INSERT INTO reemplazosBounce (id_radio,imei,serial,tipoCambio) VALUES ('".$row["id_radio"]."','".$imeiB."','".$serialB."','".$tipoCambio."','".date("Y-m-d")."','".date("H:i:s")."')";
			$res=mysql_query($sql,$this->conectarBd());
			if($res){
				if($tipoCambio=="reemplazoBounce"){
					$status="BOUNCE";
				}else if($tipoCambio=="equipoPruebas"){
					$status="E. PRUEBAS";
				}
				$sql="UPDATE equipos set status='".$status."',statusProceso='".$status."' WHERE id_radio='".$row["id_radio"]."'";
				$res=mysql_query($sql,$this->conectarBd());
				if($res){
					echo "Actualizacion y Registro Guardado";
					echo "<script type='text/javascript'> nuevaReemplazo(); </script>";
				}else{
					echo "Ocurrieron errores al actualizar la informacion";
				}
				
			}else{
				echo "Error al realizar la operacion";
			}
		}
		
		public function mostrarResumen($imeiBounce){
			$sql="SELECT * FROM equipos WHERE imei='".$imeiBounce."' AND status in ('WIP','WIP2')";
			$res=mysql_query($sql,$this->conectarBd());
			if(mysql_num_rows($res)==0){
				echo "( 0 ) registros encontrados.";
			}else{
				$row=mysql_fetch_array($res);
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
				<script type="text/javascript"> $("#txtImeiBounce").focus(); </script>
<?
			}			
		}
		
		public function mostrarForm(){
?>
			<script type="text/javascript"> $("#txtImeiBusquedaBounce").focus(); </script>
			<br><table border="0" cellpadding="1" cellspacing="1" width="800" style="font-size: 12px;margin: 10px;">
				<tr>
					<td colspan="2">Reemplazo de Equipos</td>
					<td>&nbsp;</td>
					<td colspan="2">Datos Equipo Bounce</td>
				</tr>
				<tr>
					<td style="border: 1px solid #CCC;background: #f0f0f0;height: 15px;padding: 5px;">Raz&oacute;n:</td>
					<td colspan="4">
						<select name="cboTipoCambio" id="cboTipoCambio">
							<option value="" selected="selected">Selecciona...</option>
							<option value="reemplazoBounce">Reemplazo Bounce</option>
							<option value="equipoPruebas">Equipo para Pruebas</option>
						</select>
					</td>
				</tr>				
				<tr>
					<td width="150" style="border: 1px solid #CCC;background: #f0f0f0;height: 15px;padding: 5px;">Imei Proceso:</td>
					<td width="350"><input type="text" name="txtImeiBusquedaBounce" id="txtImeiBusquedaBounce" onkeyup="mostrarResumenImei(event)"></td>
					<td>&nbsp;</td>
					<td width="160" style="border: 1px solid #CCC;background: #f0f0f0;height: 15px;padding: 5px;">Imei Bounce:</td>
					<td width="350"><input type="text" name="txtImeiBounce" id="txtImeiBounce" onkeyup="cambiarEvento(event,'txtSerialBounce')"></td>
				</tr>
				<tr>
					<td width="150">&nbsp;</td>
					<td width="350">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="160" style="border: 1px solid #CCC;background: #f0f0f0;height: 15px;padding: 5px;">Serial Bounce:</td>
					<td width="350"><input type="text" name="txtSerialBounce" id="txtSerialBounce" onkeyup="cambiarEvento(event,'btnGuardarReemplazo')"></td>
				</tr>				
				<tr>
					<td colspan="2"><div id="detalleResumenImei"></div></td>
					<td>&nbsp;</td>
					<td colspan="2"><div id="detalleResumenImeiB"></div></td>
				</tr>
				<tr>
					<td colspan="5"><hr style="background: #666"></td>
				</tr>
				<tr>
					<td colspan="5" style="text-align: right;"><input type="button" onclick="guardarReemplazo()" id="btnGuardarReemplazo" value="Guardar Reemplazo"></td>
				</tr>
			</table><br>
			<div id="divDetalleGuardado"></div>
<?
		}
	}//fin de la clase
?>