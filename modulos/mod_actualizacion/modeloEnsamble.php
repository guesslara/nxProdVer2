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
		
		public function verificaUsuarioSistema($usuarioV,$passV){//metodo para la verificaion del usuario en la Base de datos			
			//$datos=new array("0","0");
			$sqlVer="Select * from userdbnextel where usuario='".$usuarioV."' and pass='".md5($passV)."'";
			$resVer=mysql_query($sqlVer,$this->conectarBd());
			$rowVer=mysql_fetch_array($resVer);
			$datos[0]=mysql_num_rows($resVer);
			if(mysql_num_rows($resVer)==0){
				echo "Informaci&oacute;n Incorrecta";				
			}else{
				$datos[1]=$rowVer["nombre"]." ".$rowVer["apaterno"];
				$datos[2]=$rowVer["ID"];
				echo "<script type='text/javascript'>document.getElementById('txtUsuario').value='".$usuarioV."'; </script>";
				echo "<script type='text/javascript'> $(\"#ventanaDialogo\").hide(); </script>";
				echo "<script type='text/javascript'> $(\"#transparenciaGeneral\").hide(); </script>";
				//se manda la informacion a una caja de texto en el formulario para su uso
				echo "<script type='text/javascript'> document.getElementById('txtNombreModifico').value='".$rowVer["nombre"]." ".$rowVer["apaterno"]."'; </script>";
			}
			return $datos;
		}
		
		public function actualizaReg($imei,$serial,$lote,$sim,$clave,$status,$statusProceso,$statusDesensamble,$statusDiagnostico,$statusAlmacen,$statusIngenieria,$statusEmpaque,$statusIQ,$id,$usuarioMod,$passMod){			
			$objFunciones=new funcionesComunes();
			if($id==""){
				echo "Error, verifique la informacion proporcionada";
			}else{
				//se llama a la funcion para que verifique al usuario
				$datos=$this->verificaUsuarioSistema($usuarioMod,$passMod);
				if($datos[0]==0){
					echo "<br><br>Error, verifique la informaci&oacute;n.";
					return;
				}else if($datos[0]==1){
					//campo donde depositara el nombre de usuario
					echo $datos[1];				
					echo "<br><br>Respaldando Informaci&oacute;n.........<br>";
					//se extrae la informacion de equipo y se inserta en la tabla de los cambios
					$sql="select * from equipos where id_radio='".$id."'";
					$resSql=mysql_query($sql,$this->conectarBd());
					if(mysql_num_rows($resSql)==0){
						echo "<script type='text/javascipt'> alert('Error, verifique la informacion del Equipo introducido.'); </script>";
					}else{
						echo "<br>Extrayendo la informacion del equipo";
						$rowEquipo=mysql_fetch_array($resSql);
						//se inserta el respaldo
					$sqlRes1="insert into equipos_modificaciones(id_personal,id_modelo,imei,serial,bdcode,sim,lote,mfgdate,status,status_nextel,statusProceso,statusDesensamble,statusDiagnostico,statusAlmacen,statusIngenieria,statusEmpaque,statusIQ,lineaEnsamble,f_recibo,h_recibo,observaciones,repeticiones,num_movimiento,cajaInterna,facturar,tipoEquipo,activo,nombre,fecha,hora)";
					$sqlRes2=" values('".$rowEquipo["id_personal"]."',
									  '".$rowEquipo["id_modelo"]."',
									  '".$rowEquipo["imei"]."',
									  '".$rowEquipo["serial"]."',
									  '".$rowEquipo["bdcode"]."',
									  '".$rowEquipo["sim"]."',
									  '".$rowEquipo["lote"]."',
									  '".$rowEquipo["mfgdate"]."',
									  '".$rowEquipo["status"]."',
									  '".$rowEquipo["status_nextel"]."',
									  '".$rowEquipo["statusProceso"]."',
									  '".$rowEquipo["statusDesensamble"]."',
									  '".$rowEquipo["statusDiagnostico"]."',
									  '".$rowEquipo["statusAlmacen"]."',
									  '".$rowEquipo["statusIngenieria"]."',
									  '".$rowEquipo["statusEmpaque"]."',
									  '".$rowEquipo["statusIQ"]."',
									  '".$rowEquipo["lineaEnsamble"]."',
									  '".$rowEquipo["f_recibo"]."',
									  '".$rowEquipo["h_recibo"]."',
									  '".$rowEquipo["observaciones"]."',
									  '".$rowEquipo["repeticiones"]."',
									  '".$rowEquipo["num_movimiento"]."',
									  '".$rowEquipo["cajaInterna"]."',
									  '".$rowEquipo["facturar"]."',
									  '".$rowEquipo["tipoEquipo"]."',
									  '".$rowEquipo["activo"]."',
									  '".$datos[1]."',
									  '".date("Y-m-d")."',
									  '".date("H:i:s")."')";
						$sql=$sqlRes1.$sqlRes2;
						$res=mysql_query($sql,$this->conectarBd());
						if($res){
							echo "<br>Actualizando informacion del Equipo";
							$sqlUp="update equipos set imei='".$imei."',serial='".$serial."',lote='".$lote."',sim='".$sim."',mfgdate='".$clave."',status='".$status."',statusProceso='".$statusProceso."',statusDesensamble='".$statusDesensamble."',statusDiagnostico='".$statusDiagnostico."',statusAlmacen='".$statusAlmacen."',statusIngenieria='".$statusIngenieria."',statusEmpaque='".$statusEmpaque."',statusIQ='".$statusIQ."' where id_radio='".$id."'";
							$resUp=mysql_query($sqlUp,$this->conectarBd());
							if(mysql_affected_rows() >= 1){
								echo "<br><br>Informacion Actualizada";
								//se actualiza el detalle de la informacion para una posterior consulta
								$objFunciones->guardaDetalleSistema("10",$datos["2"],$imei);
?>
								<script type="text/javascript">					
									var campos=new Array("#txtImei","#txtSerial","#txtLote","#txtClave","#txtStatus","#txtProceso1","#txtDesensamble1","#txtDiagnostico1","#txtAlmacen1","#txtIngenieria1","#txtEmpaque1","#txtIq1");
									var combos=new Array("txtProceso","txtDesensamble","txtDiagnostico","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","");
									$("#txtId").attr("value","");
									for(var i=0;i<11;i++){
										$(campos[i]).attr("value","");
									}
									$("#agregado").html("");
									
									document.getElementById("txtImeiEnsamble").focus();
								</script>
<?
							}else{
								echo "<br><br>Sin cambios en el registro";
							}
						}else{
							echo "<br>Error al respaldar la informacion";
						}
					}
				}
			}
			
		}
		/*else if($esScrap==1){
				echo "<script type='text/javascript'> alert('El imei (".$imei.") se ha clasificado como SCRAP'); </script>";
				return;
			}*/
		public function buscarEquipo($imei){
			$objFunciones=new funcionesComunes();
			//se verfican los posibles status en la Base de Datos
			$estaEnBd=$objFunciones->buscarImei($imei);
			$esNoEnviar=$objFunciones->buscarNoEnviar($imei);
			$estaEnviado=$objFunciones->buscarImeiEnviadoProceso($imei);
			$esScrap=$objFunciones->buscarImeiScrap($imei);
			
			if($estaEnBd==0){
				echo "<script type='text/javascript'> alert('El imei (".$imei.") no existe en la Base de Datos'); </script>";
				return;
			}else if($esNoEnviar==1){
				echo "<script type='text/javascript'> alert('El imei (".$imei.") esta Clasificado como NO ENVIAR'); </script>";
				return;
			}else if($estaEnviado==1){
				echo "<script type='text/javascript'> alert('El imei (".$imei.") ya se ha ENVIADO'); </script>";
				return;
			}else{				
				$sqlBuscar="select * from equipos where imei='".$imei."'";
				$resBuscar=mysql_query($sqlBuscar,$this->conectarBd());
				if(mysql_num_rows($resBuscar)==0){
?>			
					<script type="text/javascript"> $("#agregado").html("Error verifique la informacion"); </script>
<?				
				}else{
					$rowBuscar=mysql_fetch_array($resBuscar);				
?>
				
				<table width="600" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#FFF;">
					<tr>
						<td colspan="2" align="center">
            	
				<input type="hidden" name="txtId" id="txtId" value="<?=$rowBuscar['id_radio']?>" />
				<table width="98%" border="0" cellpadding="1" cellspacing="1" style="margin:5px;">					
					<tr>
					<td colspan="2" style="background:#CCC; height:30px;padding:5px; text-align:left; font-size:12px; font-weight:bold;">Modificaci&oacute;n de Datos...</td>
				    </tr>
				    <tr>
					<td colspan="2"><div style="height:25px; padding:5px; border-bottom:1px solid #FC0;border-top:1px solid #FC0; background:#FFC; font-size:12px; font-weight:bold;">Advertencia:El cambio de la informaci&oacute;n ser&aacute; respaldado automaticamente y se generar&aacute; un hist&oacute;rico sobre el radio.</div></td>
				    </tr>
				    <tr>
					<td colspan="2">&nbsp;</td>
				    </tr>				    
				    <tr>
					<td width="27%" style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Imei</td>
					<td width="73%"><input type="text" name="" id="txtImei" readonly="readonly" value="<?=$rowBuscar['imei'];?>" onkeypress="verificaTeclaImeiM(event,1)" /></td>
				    </tr>
				    <tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Serial</td>
					<td><input type="text" name="" id="txtSerial" readonly="readonly" value="<?=$rowBuscar['serial'];?>" onkeypress="verificaTeclaSerial(event,1)" /></td>
				    </tr>
				    <tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Sim</td>
					<td><input type="text" name="" id="txtSim" readonly="readonly" value="<?=$rowBuscar['sim'];?>" onkeypress="verificaTeclaLote(event,2)" /></td>
				    </tr>
				    <tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Lote</td>
					<td><input type="text" name="" id="txtLote" readonly="readonly" value="<?=$rowBuscar['lote'];?>" onkeypress="verificaTeclaLote(event,2)" /></td>
				    </tr>
				    <tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">MFGDate</td>
					<td><input type="text" name="" id="txtClave" readonly="readonly" value="<?=$rowBuscar['mfgdate'];?>" onkeypress="verificaTeclaClave(event,3)" /></td>
				    </tr>
				    <tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Status</td>
					<td><input type="text" name="" id="txtStatus" readonly="readonly" value="<?=$rowBuscar['status'];?>" onkeypress="verificaTeclaStatus(event,4)" readonly="readonly" style="width:100px;" /></td>
				    </tr>
				    <tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Status Proceso</td>
					<td>&nbsp;&nbsp;Valor actual:<input type="text" name="" id="txtProceso1" value="<?=$rowBuscar['statusProceso'];?>" onkeypress="verificaTeclaProceso(event,5)" style="width:100px;" readonly="readonly" />&nbsp;&nbsp;Nuevo Valor:
						<select name="" id="txtProceso" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="Recibo">RECIBO</option>
						<option value="Desensamble">DESENSAMBLE</option>
						<option value="Diagnostico">DIAGNOSTICO</option>
						<option value="Almacen">ALMACEN</option>
						<option value="Ingenieria">INGENIER&Iacute;A</option>
						<option value="Empaque">EMPAQUE</option>
						<option value="Empacado">EMPACADO</option>
						<option value="SCRAP">SCRAP</option>						
					    </select>
					</td>
				    </tr>
				    <tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Status Desensamble</td>
					<td>&nbsp;&nbsp;Valor actual:<input type="text" name="" id="txtDesensamble1" value="<?=$rowBuscar['statusDesensamble'];?>" onkeypress="verificaTeclaDesensamble(event,6)" style="width:100px;" readonly="readonly" />&nbsp;&nbsp;Nuevo Valor:
						<select name="" id="txtDesensamble" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="OK">OK</option>                                
					    </select>
					</td>
				    </tr>
							<tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Status Diagnostico </td>
					<td>&nbsp;&nbsp;Valor actual:<input type="text" name="" id="txtDiagnostico1" value="<?=$rowBuscar['statusDiagnostico'];?>" onkeypress="verificaTeclaDesensamble(event,6)" style="width:100px;" readonly="readonly" />&nbsp;&nbsp;Nuevo Valor:
						<select name="" id="txtDiagnostico" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="OK">OK</option>
						<option value="SCRAP">SCRAP</option>
					    </select>
					</td>
				    </tr>
							<tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Status Almacen </td>
					<td>&nbsp;&nbsp;Valor actual:<input type="text" name="" id="txtAlmacen1" value="<?=$rowBuscar['statusAlmacen'];?>" onkeypress="verificaTeclaDesensamble(event,6)" style="width:100px;" readonly="readonly" />&nbsp;&nbsp;Nuevo Valor:
						<select name="" id="txtAlmacen" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="Almacenado">ALMACENADO</option>
						<option value="Asignado">ASIGNADO</option>
					    </select>
					</td>
				    </tr>
				    <tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Status Ingenieria</td>
					<td>&nbsp;&nbsp;Valor actual:<input type="text" name="" id="txtIngenieria1" value="<?=$rowBuscar['statusIngenieria'];?>" onkeypress="verificaTeclaIngenieria(event,7)" style="width:100px;" readonly="readonly" />&nbsp;&nbsp;Nuevo Valor:
						<select name="" id="txtIngenieria" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="ING_OK">ING_OK</option>
						<option value="SCRAP">SCRAP</option>                                
					    </select>
					</td>
				    </tr>
							<tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Status Empaque </td>
					<td>&nbsp;&nbsp;Valor actual:<input type="text" name="" id="txtEmpaque1" value="<?=$rowBuscar['statusEmpaque'];?>" onkeypress="verificaTeclaDesensamble(event,6)" style="width:100px;" readonly="readonly" />&nbsp;&nbsp;Nuevo Valor:
						<select name="" id="txtEmpaque" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="Empacado">EMPACADO</option>
						<option value="Validado">VALIDADO</option>
					    </select>
					</td>
				    </tr>
							<tr>
					<td style="border-bottom:1px solid #666; height:30px; margin-bottom:5px;">Status IQ </td>
					<td>&nbsp;&nbsp;Valor actual:<input type="text" name="" id="txtIq1" value="<?=$rowBuscar['statusIQ'];?>" onkeypress="verificaTeclaDesensamble(event,6)" style="width:100px;" readonly="readonly" />&nbsp;&nbsp;Nuevo Valor:
						<select name="" id="txtStatusIQ" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="ENVIADO">ENVIADO</option>
					    </select>
					</td>
				    </tr>
				    <tr>
					<td colspan="2">&nbsp;</td>
				    </tr>                    
				</table>				
							
					</td>
			</tr>
			<tr style="background:#000; color:#FFF;">
			    <td width="427"><div id="agregado" style="width:400px;"></div> </td>
			    <td width="271" align="right"><input type="button" value="Actualizar" id="btnActualizar" onclick="accionesVentana('ventanaDialogo','1')" style="width:200px; font-size:14px; height:35px;" /></td>
			</tr>
		    </table>
<?				
				}
			}
		}
	}//fin de la clase
?>