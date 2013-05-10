<?
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
		
		
		public function consultarCajasItems($idEmpaque,$idCaja){
			$sqlListar="select * from empaque_items where id_empaque='".$idEmpaque."' and id_caja='".$idCaja."'";
			$resListar=mysql_query($sqlListar,$this->conectarBd());
			if(mysql_num_rows($resListar)==0){
				echo "<br>La caja esta vacia.";
			}else{
?>
				<table border="0" cellpadding="1" cellspacing="1" width="98%" style=" background:#FFF;margin:5px; border:1px solid #000;">					
					<tr>
                    	<td colspan="3" style="font-size:12px; font-weight:bold; height:25px; padding:5px;">Contenido de la Caja <?=$idCaja;?> | [ <a href="#" onclick="actualizaSimNextel()" style="color:#0033FF;">Actualizar Informaci&oacute;n</a> ]</td>
                    </tr>
                    <tr>
						<td width="20%" align="center" style="background:#000; color:#FFF;">Imei</td>
						<td width="24%" align="center" style="background:#000; color:#FFF;">Sim</td>
                        <td width="24%" align="center" style="background:#000; color:#FFF;">Serial</td>
                        <td width="23%" align="center" style="background:#000; color:#FFF;">Lote</td>
						<td width="9%" align="center" style="background:#000; color:#FFF;">Acciones</td>
					</tr>
<?				
				$i=0;
				while($rowItems=mysql_fetch_array($resListar)){
					$divInfo="divInfo".$i;
					$sqlDatos="select * from equipos where imei='".$rowItems['imei']."'";
					$resDatos=mysql_query($sqlDatos,$this->conectarBd());
					if(mysql_num_rows($resDatos)==0){
						$serial="N/A";
						$lote="N/A";
					}else{
						$rowDatos=mysql_fetch_array($resDatos);
						$serial=$rowDatos['serial'];
						$lote=$rowDatos['lote'];
					}
?>
					<tr>
						<td style="font-size:12px; height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowItems['imei'];?></td>
						<td style="font-size:12px; height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowItems['sim'];?></td>
                        <td style="font-size:12px; height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$serial;?></td>
                        <td style="font-size:12px; height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$lote;?></td>
						<td style="font-size:12px; height:25px; text-align:center; border-bottom:1px solid #CCC;">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5"><div id=""></div></td>
					</tr>
<?					
				}
?>
				</table><br />
<?				
			}		
		}
		
		
		public function guardaCaja($caja,$idEmpaque){
			echo "<br>".$sqlListar="INSERT INTO caja_empaque (id_empaque,caja) VALUES ('".$idEmpaque."','".$caja."')";
			$resListar=mysql_query($sqlListar,$this->conectarBd());
			if($resListar){
				echo "Caja Guardada";
?>
				<script type="text/javascript"> verMas('<?=$idEmpaque;?>'); </script>
<?				
			}else{
				echo "Error al guardar la caja";
			}
		}
		
		public function verDetalleEmpaque($idEmpaque){
			$sqlListar="select * from empaque where id='".$idEmpaque."'";
			$resListar=mysql_query($sqlListar,$this->conectarBd());
			$rowListar=mysql_fetch_array($resListar);
			/**************************************************************************/
			$sqlCajas="Select * from caja_empaque where id_empaque='".$idEmpaque."'";
			$resCajas=mysql_query($sqlCajas,$this->conectarBd());
			/**************************************************************************/
			$sqlModelo="select * from cat_modradio where id_modelo='".$rowListar['modelo']."'";
			$resModelo=mysql_query($sqlModelo,$this->conectarBd());
			$rowModelo=mysql_fetch_array($resModelo);
?>
			<div style="height:30px; padding:5px; background:#F0F0F0; border:1px solid #CCCCCC;">
				<a href="#">Exportar listado</a>
			</div>
			<table border="0" align="center" cellpadding="1" cellspacing="1" width="550" style="margin:25px; font-size:12px;">
            	<tr>
					<td colspan="4"><div id="mensajesEmpaque" style="border:1px solid #FFCC00; background:#FFFFCC; height:30px;"></div></td>
				</tr>
				<tr>
                	<td colspan="4" style="font-size:14px;">Empaque interno: <?=$rowListar['id'];?></td>
                </tr>
                <tr>
					<td colspan="4" style="font-size:14px;">Detalle de la entrega: <?=$rowListar['entrega'];?></td>
				</tr>
				<tr>
                	<td align="center" style="background:#f0f0f0; border:1px solid #CCC;">Fecha</td>
                    <td align="center" style="background:#f0f0f0; border:1px solid #CCC;">Entrega</td>
                    <td align="center" style="background:#f0f0f0; border:1px solid #CCC;">Modelo</td>
                    <td align="center" style="background:#f0f0f0; border:1px solid #CCC;"><--></td>
                </tr>
				<tr>
					<td align="center" style="border:1px solid #CCC;"><?=$rowListar['fecha'];?></td>
					<td align="center" style="border:1px solid #CCC;"><?=$rowListar['entrega'];?></td>
					<td align="center" style="border:1px solid #CCC;"><?=$rowModelo['modelo'];?></td>
					<td align="center" style="border:1px solid #CCC;">
						<input type="hidden" name="txtEmpaque" id="txtEmpaque" value="<?=$idEmpaque;?>" />
						<input type="button" value="Nueva caja" onclick="nuevaCaja()" style=" text-align:center;height:45px; width:100px;" />
					</td>					
				</tr>
			</table><br />
			
			<table border="0" cellpadding="1" cellspacing="1" width="650" style="margin-left:25px; font-size:10px;">
				<tr>
					<td colspan="2">Cajas en esta entrega:</td>
				</tr>
				<tr>
					<td colspan="2"><hr style="background:#666666;" /></td>
				</tr>
<?
			if(mysql_num_rows($resCajas)==0){
?>
				<tr>
					<td colspan="2">No hay cajas asociadas a esta entrega</td>
				</tr>
<?				
			}else{
?>
				<tr>
					<td width="18%" align="center" style="background:#000; color:#FFF;">Caja</td>
					<td width="82%" align="center" style="background:#000; color:#FFF;"><--></td>
				</tr>
<?			
				$i=0;
				$color="#F0F0F0";
				while($rowCajas=mysql_fetch_array($resCajas)){
					$idInfoCaja="divCaja".$i;
?>
				<tr>
					<td align="center" style="height:25px; background:<?=$color;?>;"><?=$rowCajas['caja'];?></td>
					<td align="left" style="height:25px; background:<?=$color;?>;">&nbsp;
						<a href="#" onclick="capturarDetalleCaja('<?=$rowListar['fecha'];?>','<?=$_SESSION['id_usuario_nx'];?>','<?=$rowListar['entrega']?>','<?=$rowCajas['caja'];?>','<?=$rowListar['modelo']?>','<?=$idEmpaque;?>')" style="text-decoration:none; color:#0066FF;">Capturar detalle caja</a> |
						<a href="#" onclick="infoCaja('<?=$idEmpaque;?>','<?=$rowCajas['caja'];?>','<?=$idInfoCaja;?>')" style="text-decoration:none; color:#0066FF;">Ver info de caja</a>						
					</td>
				</tr>
				<tr>
					<td colspan="4" style="background:#999;"><div id="<?=$idInfoCaja;?>"></div></td>
				</tr>
<?				
					($color=="#F0F0F0") ? $color="#FFF" : $color="#F0F0F0";
					$i+=1;
				}	
			}
?>				
			</table>
<?			
		}

		public function listarCapturas(){
			$sqlListar="select * from empaque ORDER BY id DESC";
			$resListar=mysql_query($sqlListar,$this->conectarBd());
			if(mysql_num_rows($resListar)==0){
				echo "No existen registros en la Base de Datos";
			}else{
?>
				<table border="0" cellpadding="1" cellspacing="1" width="99%" style="margin:3px; font-size:10px;">
                	<tr>
                    	<td align="center" style="background:#000; color:#FFF; height:20px;">Id</td>
                        <td align="center" style="background:#000; color:#FFF; height:20px;">Fecha</td>
                        <td align="center" style="background:#000; color:#FFF; height:20px;">Entrega</td>
                        <td align="center" style="background:#000; color:#FFF; height:20px;">Modelo</td>
                        <td><--></td>
                    </tr>
<?				
				$color="#F0F0F0";
				while($rowListar=mysql_fetch_array($resListar)){
					//modelo
					$sqlModelo="select * from cat_modradio where id_modelo='".$rowListar['modelo']."'";
					$resModelo=mysql_query($sqlModelo,$this->conectarBd());
					$rowModelo=mysql_fetch_array($resModelo);
?>
					<tr>
                    	<td align="center" style="height:25px; background:<?=$color;?>;"><?=$rowListar['id'];?></td>
                        <td align="center" style="height:25px; background:<?=$color;?>;"><?=$rowListar['fecha'];?></td>
                        <td align="center" style="height:25px; background:<?=$color;?>;"><?=$rowListar['entrega'];?></td>
                        <td align="center" style="height:25px; background:<?=$color;?>;"><?=$rowModelo['modelo'];?></td>
                        <td align="center" style="height:25px; background:<?=$color;?>;"><a href="#" onclick="verMas('<?=$rowListar['id'];?>')">Ver</a></td>
                    </tr>					
<?					
					($color=="#F0F0F0") ? $color="#FFF" : $color="#F0F0F0";
				}
?>
				</table>
<?				
			}
		}
		
		
		public function capturaEquiposCajaItems($imei,$sim,$id_empaque,$id_caja){
			$validarStatus=$this->validarStatus($imei);
			if($validarStatus==true){	
				echo "<br>".$sqlItems="INSERT INTO empaque_items (id_empaque,imei,sim,id_caja) values('".$id_empaque."','".$imei."','".$sim."','".$id_caja."')";
				$resItems=mysql_query($sqlItems,$this->conectarBd());
				if($resItems){
					echo "<br>Equipo con Imei ($imei) y Sim ($sim) guardado.";
?>					
				<script type="text/javascript">
					armarGridCaptura('<?=$imei;?>','<?=$sim;?>');
				</script>
<?
				}else{
					echo "<br>Error al guardar la informacion del equipo";
				}
			}else{
				echo "<br><br><strong style='color:#F00;font-size:16px;'>Verifique el status del imei ($imei) y/o Tarjeta.</strong>";
				echo "<script type='text/javascript'>limpiaCajas();</script>";
			}
		}
		
		public function validarStatus($imei){
			$sqlImei="SELECT * from equipos where imei='".$imei."'";
			$resImei=mysql_query($sqlImei,$this->conectarBd());
			$rowImei=mysql_fetch_array($resImei);
			if($rowImei['status']=="En Proceso" && $rowImei['statusProceso']=="Empaque" && $rowImei['statusDesensamble']=="OK" && $rowImei['statusIngenieria']=="ING_OK"){
				$validacion=true;
			}else{
				$validacion=false;
			}
			return $validacion;
		}
		
		public function capturaEquiposCaja($fecha,$txtTecnico,$txtEntrega,$modelo){
			//se inserta en la tabala de empaque para informacion
			echo "<br>".$sqlEmpaque="INSERT INTO empaque (fecha,tecnico,entrega,modelo) values ('".$fecha."','".$txtTecnico."','".$txtEntrega."','".$modelo."')";
			$resEmpaque=mysql_query($sqlEmpaque,$this->conectarBd());
			if($resEmpaque){
				echo "<br><br><strong>Registro guardado, continuando con la captura de equipos....</strong>";
				//se recupera el id de empaque y se coloca en un campo oculto
				$sql_id = "SELECT LAST_INSERT_ID() as id FROM empaque";
				$res_id=mysql_query($sql_id,$this->conectarBd());
				$row_id=mysql_fetch_array($res_id);
				echo "<script type='text/javascript'> alert('Empaque interno No: ".$row_id['id']."'); listarCapturas();</script>";
			}else{
				echo "Error al ejecutar la consulta, la caja no pudo ser guardada";
			}
		}
		
		
		function guardar_Registro($idEvento,$fecha){
			//$idEvento=$idEvento;
			//$fecha=$fecha;
			//$idEvento=$idEvento;
			//$fechas=$fechas;
			//echo $fechas;
			$link=$this->Conexion();
			//echo $idEvento;
			//echo $fechas;
			$fecha=explode(",",$fecha);
			for($i=0;$i<count($fecha);$i++){
				echo $sqlGuarda="INSERT INTO calendario_boletines (id_evento,fecha) values('".$idEvento."','".$fecha[$i]."')";	
				$resultGuarda=mysql_query($sqlGuarda,$link);
				if($resultGuarda==true){
					echo "<br>Registro Agregado.<br>";				
				}else{
					echo "<br>Error al Guardar.<br>";				
				}
			}	
			
		}
		
		
	}//fin de la clase
?>