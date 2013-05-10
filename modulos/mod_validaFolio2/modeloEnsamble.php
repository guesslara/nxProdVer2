<?
	include("../../clases/guardaDetalle.php");
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
		
		public function mostrarResumenValidacion($folioCliente){
			$sqlC="SELECT COUNT( * ) AS `Filas` , `modelo` FROM `archivo_Cliente` WHERE lote='".$folioCliente."' GROUP BY `modelo` ORDER BY `Filas`";
			$resC=mysql_query($sqlC,$this->conectarBd());
			if(mysql_num_rows($resC)==0){
				echo "Error al mostrar la informacion del Cliente.";
			}else{
?>
				<br>
				<div style="border: 1px solid #666;width: 900px;margin: 0 auto 0 auto;">
					<table align="center" border="1" cellpadding="1" cellspacing="1" width="400" style="font-size: 12px;">
						<tr>
							<td colspan="2">Informaci&oacute;n del Cliente - Folio <?=$folioCliente;?></td>
						</tr>
						<tr>
							<td width="100">Modelo</td>
							<td width="300">Cantidad</td>
						</tr>
<?
					$i=0;
					while($rowC=mysql_fetch_array($resC)){
						$i+=$rowC["Filas"];
?>
						<tr>
							<td><?=$rowC["modelo"];?></td>
							<td><?=$rowC["Filas"];?></td>
						</tr>
<?
					}
?>
						<tr>
							<td colspan="2"><hr style="background: #000;"></td>
						</tr>
						<tr>
							<td>Total</td>
							<td><?=$i;?></td>
						</tr>
					</table><br><br>
					<table align="center" border="1" cellpadding="1" cellspacing="1" width="400" style="font-size: 12px;">
						<tr>
							<td colspan="2">Informaci&oacute;n IQ - Folio <?=$folioCliente;?></td>
						</tr>
						<tr>
							<td width="100">Modelo</td>
							<td width="300">Cantidad</td>
						</tr>
					</table>
				</div>
<?
			}
		}
		
		public function mostrarDatosFolios($folioCliente,$folioIq){
			//consultas
			$sqlC="select * from archivo_Cliente where lote='".$folioCliente."'";
			$resC=mysql_query($sqlC,$this->conectarBd());			
?>
			<table border="1" cellpadding="1" cellspacing="1" width=98%>
				<tr>
					<td>#</td>
					<td>Imei Nextel</td>
					<td>Serial Nextel</td>
					<td>&nbsp;</td>
					<td>Imei Iq</td>
					<td>Serial Iq</td>
					<td>&nbsp;</td>
					<td>Validacion Imei</td>
					<td>Validacion Serial</td>
				</tr>
<?
			$i=1; $j=0;
			while($rowC=mysql_fetch_array($resC)){
				//se extrae el imei en la tabla equipos
				$sqlI="select imei,serial,lote from equipos where imei='".$rowC["imei"]."' and serial='".$rowC["serial"]."' and lote in ('".$folioIq."','".$folioIq."-C"."')";
				$resI=mysql_query($sqlI,$this->conectarBd());
				$rowI=mysql_fetch_array($resI);
				if(mysql_num_rows($resI)==0){
					//se ejecuta una segunda consulta
					$sqlx="select imei,lote from equipos where imei='".$rowC["imei"]."' and lote in ('".$folioIq."','".$folioIq."-C"."')";
					$resx=mysql_query($sqlx,$this->conectarBd());
					if(mysql_num_rows($resx)==0){//si no se encuentra se busca el serial caso contrario la variable imei se coloca
						$sqly="select serial,lote from equipos where serial='".$rowC["serial"]."' and lote in ('".$folioIq."','".$folioIq."-C"."')";
						$resy=mysql_query($sqly,$this->conectarBd());
						if(mysql_num_rows($resy)==0){
							$imei="0"; $serial="0";
						}else{
							$rowy=mysql_fetch_array($resy);
							$imei="0"; $serial=$rowy["serial"];
						}						
					}else{
						$rowx=mysql_fetch_array($resx);
						$imei=$rowx["imei"]; $serial="0";						
					}
				}else{
					$imei=$rowI["imei"]; $serial=$rowI["serial"];
				}
				$nombreCI="txtCI_".$j; $nombreCS="txtCS_".$j;
				$nombreII="txtII_".$j; $nombreIS="txtIS_".$j;
				$nombreRI="txtRI_".$j; $nombreRS="txtRS_".$j;
?>
				<tr>
					<td><?=$i;?></td>
					<td><input type="text" name="<?=$nombreCI;?>" id="<?=$nombreCI;?>" value="<?=$rowC["imei"];?>"></td>
					<td><input type="text" name="<?=$nombreCS;?>" id="<?=$nombreCS;?>" value="<?=strtoupper($rowC["serial"]);?>"></td>
					<td>&nbsp;</td>
					<td><input type="text" name="<?=$nombreII;?>" id="<?=$nombreII;?>" value="<?=$imei;?>"></td>
					<td><input type="text" name="<?=$nombreIS;?>" id="<?=$nombreIS;?>" value="<?=strtoupper($serial);?>"></td>
					<td>&nbsp;</td>
					<td><input type="text" name="<?=$nombreRI;?>" id="<?=$nombreRI;?>"></td>
					<td><input type="text" name="<?=$nombreRS;?>" id="<?=$nombreRS;?>"></td>
				</tr>
<?
				$i+=1; $j+=1;
			}
?>
			</table><input type="hidden" name="txtTotalIndiceJ" id="txtTotalIndiceJ" value="<?=$j;?>">
<?
		}
	}//fin de la clase
	
	//$obj=new modeloEnsamble();
	//$obj->IngresaEncuesta("123456");
?>