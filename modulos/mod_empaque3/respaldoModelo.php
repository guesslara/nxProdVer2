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

		public function guardarEquipoEmpaqueItems($idEmpaque,$idCaja,$valores,$idElemento){
			//se instancia el objeto
			$objEquipo=new funcionesComunes();
			//echo "<br>".$idElemento="#".$idElemento;
			echo "<br>".$idElemento=$idElemento;
			$valores=explode(",",$valores);
			echo "<br>Imei: ".$valores[0];
			echo "<br>Sim: ".$valores[1];
			$estaEnviado=$objEquipo->buscarImeiEnviado($valores[0]);
			$esNoEnviar=$objEquipo->buscarNoEnviar($valores[0]);
			$estaEnBd=$objEquipo->buscarImei($valores[0]);
			$estaEmpacado=$objEquipo->buscarImeiEmpacado($valores[0]);
			$esScrap=$objEquipo->buscarImeiScrap($valores[0]);
			if($estaEnBd==0){
				$msgCaja="EQUIPO no existe en Base de Datos";
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
			}else if($estaEmpacado==1){
				$msgCaja="Equipo empacado";
				$color="red";
				$fuente="white";
				//return;
			}else if($esScrap==1){
				$msgCaja="Equipo marcado como Scrap";
				$color="red";
				$fuente="white";
			}else{
				//se extrae la informacion del equipo
				echo "<br>".$sqlEquipo="SELECT * from equipos where imei='".$valores[0]."'";
				$resEquipo=mysql_query($sqlEquipo,$this->conectarBd());
				$rowRadio=mysql_fetch_array($resEquipo);
				if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Empaque" && $rowRadio["statusIngenieria"]=="ING_OK"){
					//se inserta en el detalle de la caja y se actualiza el equipo
					echo "<br>".$sqlActualiza="update equipos set statusEmpaque='EMPACADO' WHERE imei='".$valores[0]."'";
					//consulta de insercion
					echo "<br>".$sqlItems="INSERT INTO empaque_items (id_empaque,imei,sim,id_caja) values('".$idEmpaque."','".$valores[0]."','".$valores[1]."','".$idCaja."')";
					$resItems=mysql_query($sqlItems,$this->conectarBd());
				}else if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Desensamble" && $rowRadio["statusDesensamble"]=="N/A" && $rowRadio["statusDiagnostico"]=="N/A" && $rowRadio["statusAlmacen"]=="N/A" && $rowRadio["statusIngenieria"]=="N/A"){
					//se inserta el detalle de la caja y se actualiza la info del equipo
					echo "<br>".$sqlActualiza="UPDATE equipos set statusProceso='Empaque',statusDesensamble='OK',statusDiagnostico='OK',statusAlmacen='Asignado',sim='".$valores[1]."',statusIngenieria='ING_OK' where imei='".$valores[0]."'";
					//consulta de insercion
					echo "<br>".$sqlItems="INSERT INTO empaque_items (id_empaque,imei,sim,id_caja) values('".$idEmpaque."','".$valores[0]."','".$valores[1]."','".$idCaja."')";
				}else if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Empaque" && $rowRadio["statusEmpaque"]=="EMPACADO"){
					$msgCaja="Equipo empacado en otro envio";
					$color="red";
					$fuente="white";
				}
				//se ejecutan las consultas
				$resActualizaEquipo=mysql_query($sqlActualiza,$this->conectarBd());
				if($resActualizaEquipo){
					//se procede a insertar el item del empaque
					$resItemEmpaque=mysql_query($sqlItems,$this->conectarBd());
					if($resItemEmpaque){
						$msgCaja="Guardado";
						$color="green";
						$fuente="white";
					}else{
						$msgCaja="Actualizado / Error";
						$color="red";
						$fuente="white";
					}
				}else{
					$msgCaja="No Actualizado";
					$color="red";
					$fuente="white";
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
		
		public function nuevaEntrega(){
?>
            <table width="600" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#FFF; border:1px solid #CCC;">
                      <tr>
                        <td colspan="4" style="background:#000; color:#FFF; height:25px; font-weight:bold;">Empaque - Nextel</td>
                      </tr>
                      <tr>
                        <td width="84" style="background:#f0f0f0; border:1px solid #CCC;">Fecha</td>
                        <td width="242"><input type="text" name="txtFecha" id="txtFecha" value="<?=date("Y-m-d");?>" readonly="readonly" /></td>
                        <td colspan="2">&nbsp;</td> 
                      </tr>
                      <tr>
                        <td style="background:#f0f0f0; border:1px solid #CCC;">T&eacute;cnico</td>
                        <td colspan="3"><input type="text" name="txtTecnico" id="txtTecnico" value="<?=$_SESSION['nombre_nx']." ".$_SESSION['apellido_nx'];?>" readonly="readonly" style="width:300px;" /></td>
                      </tr>
                      <tr>
                        <td style="background:#f0f0f0; border:1px solid #CCC;">Entrega</td>
                        <td><input type="text" name="txtEntrega" id="txtEntrega" /></td>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td style="background:#f0f0f0; border:1px solid #CCC;">Modelo</td>
                        <td>
                        <select name="cboModelo" id="cboModelo">
                            <option value="" selected="selected">Selecciona...</option>
<?
				//se extrae el catalogo de modelos
				$sqlModelo="select * from cat_modradio";
				$resModelo=mysql_query($sqlModelo,$this->conectarBd());
				if(mysql_num_rows($resModelo)==0){
					echo "No hay modelos en la Base de Datos";
				}else{
					while($rowModelo=mysql_fetch_array($resModelo)){
?>
				<option value="<?=$rowModelo['id_modelo'];?>"><?=$rowModelo['modelo'];?></option>
<?				
					}
				}
?>				
                        </select>
                        </td>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="4"><hr style="background:#999999;" /></td>
                      </tr>      
                      <tr>
                        <td colspan="3">&nbsp;</td>
                        <td width="265" align="right"><input type="button" value="Guardar Informaci&oacute;n" onclick="guardaMovimiento()" /></td>
                      </tr>
                    </table><br /><br />
<?		
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
			<div style="height:25px; padding:7px; background:#F0F0F0; border:1px solid #CCCCCC;">
				<a onclick="exportarArchivoValidacion('<?=$rowListar['id'];?>')" href="#" title="Exportar Archivo de Validacion" style="color:#03F;">Exportar Archivo de Validaci&oacute;n</a>
			</div>
			<table border="0" align="center" cellpadding="1" cellspacing="1" width="650" style="margin:25px; font-size:12px; font-family:Verdana, Geneva, sans-serif;">            	
				<tr>
                	<td width="173" style="font-size:14px;background:#000; color:#FFF; height:25px; padding:5px;">Empaque interno:</td>
                	<td colspan="3" style="font-size:14px;background:#000; color:#FFF; height:25px; padding:5px;"><?=$rowListar['id'];?></td>
                	<td width="112" style="font-size:14px;background:#000; color:#FFF; height:25px; padding:5px; text-align:center; font-weight:bold;">Modelo</td>
                </tr>
                <tr>
					<td style="font-size:14px; background:#CCC; height:25px;">Detalle de la entrega:</td>
					<td colspan="3" style="font-size:14px; background:#CCC; height:25px;"><?=$rowListar['entrega'];?></td>
					<td rowspan="3" valign="middle" style="font-size:28px; background:#CCC; height:25px; text-align:center; font-weight:bold;"><?=$rowModelo['modelo'];?></td>
				</tr>
				<tr>
				  <td align="center" style="background:#CCC; height:25px; padding:5px; font-weight:bold;">Fecha</td>
                	<td width="113" align="center" style="background:#CCC; height:25px; padding:5px; font-weight:bold;">Entrega</td>
                    <td colspan="2" align="center" style="background:#CCC; height:25px; padding:5px; font-weight:bold;">Usuario</td>
                </tr>
				<tr>
				  <td align="center" style="border-bottom:1px solid #CCC; height:25px; padding:5px;">&nbsp;<?=$rowListar['fecha'];?></td>
					<td align="center" style="border-bottom:1px solid #CCC; height:25px; padding:5px;"><?=$rowListar['entrega'];?></td>
					<td colspan="2" align="center" style="border-bottom:1px solid #CCC; height:25px; padding:5px;"></td>
				</tr>
                <tr>
                	<td colspan="5" align="right">
                    	<input type="hidden" name="txtEmpaque" id="txtEmpaque" value="<?=$idEmpaque;?>" />
						<input type="button" value="Nueva caja" onclick="nuevaCaja()" style=" width:120px;height:30px; border:1px solid #000; font-size:10px; background:#06F; color:#FFF; font-weight:bold;" />
                    </td>
                </tr>
</table><br />
			
			<table border="0" cellpadding="1" cellspacing="1" width="650" style="margin-left:25px; font-size:10px; font-family:Verdana, Geneva, sans-serif;">
				<tr>
					<td colspan="3" style="height:25px; padding:5px; font-weight:bold; font-size:12px;">Cajas en esta entrega:</td>
				</tr>
				<tr>
					<td colspan="3"><hr style="background:#666666;" /></td>
				</tr>
<?
			if(mysql_num_rows($resCajas)==0){
?>
				<tr>
					<td colspan="3" style="height:25px; padding:5px; font-weight:bold; font-size:12px; color:#F00;">No hay cajas asociadas a esta entrega</td>
				</tr>
<?				
			}else{
?>
				<tr>
					<td width="9%" align="center" style="background:#000; color:#FFF; height:25px; padding:5px; font-weight:bold;">Caja</td>
					<td width="25%" align="center" style="background:#000; color:#FFF; height:25px; padding:5px; font-weight:bold;">Cant Equipos</td>
					<td width="66%" align="center" style="background:#000; color:#FFF; height:25px; padding:5px; font-weight:bold;">Acciones</td>
				</tr>
<?			
				$i=0;
				$color="#E1E1E1";
				while($rowCajas=mysql_fetch_array($resCajas)){
					$idInfoCaja="divCaja".$i;
					$sqlEquiposCaja="SELECT count( * ) as total FROM `empaque_items` WHERE id_caja = '".$rowCajas['caja']."' ";
					$resEquiposCaja=mysql_query($sqlEquiposCaja,$this->conectarBd());
					$rowEquiposCaja=mysql_fetch_array($resEquiposCaja);
?>
				<tr>
					<td align="center" style="height:25px; padding:5px; background:<?=$color;?>;font-weight:bold; font-size:14px;"><?=$rowCajas['caja'];?></td>
					<td align="left" style="height:25px; font-size:14px; text-align:center; font-weight:bold; background:<?=$color;?>;"><?=$rowEquiposCaja["total"];?></td>
					<td align="center" style="height:25px; font-size:12px; background:<?=$color;?>; height:25px; padding:5px;">&nbsp;
						<a href="#" onclick="infoCaja('<?=$idEmpaque;?>','<?=$rowCajas['caja'];?>','<?=$idInfoCaja;?>')" style="text-decoration:none; color:#0066FF;">Ver caja</a> | 
                        <a href="#" onclick="capturarDetalleCaja('<?=$rowListar['fecha'];?>','<?=$_SESSION['id_usuario_nx'];?>','<?=$rowListar['entrega']?>','<?=$rowCajas['caja'];?>','<?=$rowListar['modelo']?>','<?=$idEmpaque;?>')" style="text-decoration:none; color:#0066FF;">Capturar equipos en esta Caja</a>												
					</td>
				</tr>
				<tr>
					<td colspan="5" style="background:#999;"><div id="<?=$idInfoCaja;?>"></div></td>
				</tr>
<?				
					($color=="#E1E1E1") ? $color="#FFF" : $color="#E1E1E1";
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
                        <td align="center" style="background:#000; color:#FFF; height:20px;"><--></td>
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
			if($rowImei['statusProceso']=="Empaque" && $rowImei['statusDesensamble']=="OK" && $rowImei['statusIngenieria']=="ING_OK"){
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
				echo "<script type='text/javascript'> $('#detalleEmpaque').html(''); </script>";
			}else{
				echo "Error al ejecutar la consulta, la caja no pudo ser guardada";
			}
		}
		
		public function prueba(){
?>
	<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
	<script type="text/javascript">
		function verificaValor(valor){
			if(valor=="No"){				
				$("#cinco").show();//se muestra el div
			}else{
				$("#cinco").hide();//se oculta el div			
			}
		}
	</script>
	<form name="formularioPregunta5"  id="formularioPregunta5">
      <tr>
        <th width="423" bgcolor="#009999" class="textos_preguntas" scope="col">5. En su percepci&oacute;n, &iquest;Han sido  alcanzadas sus expectativas?</th>
        <th width="437" bgcolor="#009999" class="textos_preguntas" scope="col">En caso de ser no, &iquest;Porqu&eacute;?</th>
      </tr>
      <tr>
        <th height="93" valign="top" class="textos_preguntas" scope="col"><p><span id="spryradio5">
          <label>
            <input type="radio" name="respuesta5" value="Si" id="RadioGroup1_6" onclick="verificaValor('Si')">
            Si</label>
          <br>
          <label>
            <input type="radio" name="respuesta5" value="No" id="RadioGroup1_7" onclick="verificaValor('No')">
            No</label>
          <br>
          <label for="respuesta5">
            <input type="radio" name="respuesta5" id="respuesta9" value="Superada" onclick="verificaValor('Superada')">
            Superada</label>
		<br>
		<span class="radioRequiredMsg">Selecciona sola una opci&oacute;n.</span></span></p></th>

        <th class="textos_preguntas" scope="col">&nbsp;<div id="cinco" style="display:none;"><textarea name="expectativas_no" cols="60" rows="4" class="textos_relleno" id="expectativas_no"></textarea></div></th>
      </tr>
      </form>		
<?		
		}
		
	}//fin de la clase
	
	
	//$objP=new modeloEnsamble();
	//$objP->prueba();
?>