<?
	include("../../clases/funcionesComunes.php");
	class modeloBusquedaAvanzada{
		
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
		
		public function buscarDatos($id_modelo,$imei,$serial,$sim,$folio,$mfgdate,$status,$statusProceso,$statusDesensamble,$statusDiagnostico,$statusAlmacen,$statusIngenieria,$statusEmpaque,$lineaEnsamble,$fecha1,$fecha2,$numMov,$tipoEquipo,$flexNuevo){
			$RegistrosAMostrar=25;
			$i=0;
			//estos valores los recibo por GET
			if(isset($_POST['pag'])){
			  $RegistrosAEmpezar=($_POST['pag']-1)*$RegistrosAMostrar;
			  $PagAct=$_POST['pag'];
			//caso contrario los iniciamos
			}else{
			  $RegistrosAEmpezar=0;
			  $PagAct=1;
			}
			$where ="";
			foreach($_POST as $nombre_campo=>$valor){
				$asignacion="\$".$nombre_campo."='".$valor."';";
				eval($asignacion);
				if($nombre_campo=="id_modelo"){
					$nombre_campo="equipos.id_modelo";
				}
				if($valor != "" && $valor !="Seleccione" && $nombre_campo!="pag"){
					if($where==""){
						if($nombre_campo!="action"){
							if($nombre_campo == "f_recibo" && $valor != ""){
								$where=$nombre_campo." BETWEEN '".$valor."'";
							}else{
								$where=$nombre_campo." LIKE '%".$valor."%'";
							}
						}					
					}else{
						if($nombre_campo!="action"){
							if($nombre_campo == "fecha2" ){
								$where.=" AND '".$valor."'";
							}else{
								$where.=" AND ".$nombre_campo." LIKE '%".$valor."%'";
							}
						}
					}
				}
			}
			$sqlBuscar="SELECT * FROM equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo WHERE ".$where." LIMIT ".$RegistrosAEmpezar.", ".$RegistrosAMostrar;
			$sqlBuscar1="SELECT * FROM equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo WHERE ".$where;
			//echo "<br><br>".$sqlBuscar;
			//echo "<div style='height:28px;padding:5px;background:#f0f0f0;border:1px solid #CCC;text-align:left;width:100%;'><div onclick='mostrarFormDatos()' style='border:1px solid #000;background:#FFF;width:180px;height:15px;padding:5px;'>Cambiar Parametros de B&uacute;squeda</div></div>";
			
			//$resBuscar=@mysql_query($sqlBuscar,$this->conectarBd()) or die(mysql_error());
			
			$rs=mysql_query($sqlBuscar,$this->conectarBd());
			$rs1=mysql_query($sqlBuscar1,$this->conectarBd());
			
			//******--------determinar las páginas---------******//
			$NroRegistros=@mysql_num_rows($rs1) or die("<br>( 0 ) registros encontrados<br>");
			$PagAnt=$PagAct-1;
			$PagSig=$PagAct+1;
			$PagUlt=$NroRegistros/$RegistrosAMostrar;
			
			//verificamos residuo para ver si llevará decimales
			$Res=$NroRegistros%$RegistrosAMostrar;
			// si hay residuo usamos funcion floor para que me devuelva la parte entera, SIN REDONDEAR, y le sumamos una unidad para obtener la ultima pagina
			if($Res>0) $PagUlt=floor($PagUlt)+1;
			
			
			if($NroRegistros==0){
				echo "<br>( 0 ) registros encontrados.<br>";
			}else{
?>
				<input type="hidden" name="" id="id_modelo" value="<?=$id_modelo;?>" />
				<input type="hidden" name="" id="imei" value="<?=$imei;?>" />
				<input type="hidden" name="" id="serial" value="<?=$serial;?>" />
				<input type="hidden" name="" id="sim" value="<?=$sim;?>" />
				<input type="hidden" name="" id="lote" value="<?=$folio;?>" />
				<input type="hidden" name="" id="mfgdate" value="<?=$mfgdate;?>" />
				<input type="hidden" name="" id="status" value="<?=$status;?>" />
				<input type="hidden" name="" id="statusProceso" value="<?=$statusProceso;?>" />
				<input type="hidden" name="" id="statusDesensamble" value="<?=$statusDesensamble;?>" />
				<input type="hidden" name="" id="statusDiagnostico" value="<?=$statusDiagnostico;?>" />
				<input type="hidden" name="" id="statusAlmacen" value="<?=$statusAlmacen;?>" />
				<input type="hidden" name="" id="statusIngenieria" value="<?=$statusIngenieria;?>" />
				<input type="hidden" name="" id="statusEmpaque" value="<?=$statusEmpaque;?>" />
				<input type="hidden" name="" id="lineaEnsamble" value="<?=$lineaEnsamble;?>" />
				<input type="hidden" name="" id="f_recibo" value="<?=$fecha1;?>" />
				<input type="hidden" name="" id="f_recibo2" value="<?=$fecha2;?>" />
				<input type="hidden" name="" id="num_movimiento" value="<?=$numMov;?>" />
				<input type="hidden" name="" id="tipoEquipo" value="<?=$tipoEquipo;?>" />
				<input type="hidden" name="" id="flexNuevo" value="<?=$flexNuevo;?>" />
				
				<table border="0" align="center" cellpadding="1" cellspacing="1" width="980">
					<tr>
						<td colspan="22" style="height:28px;padding:5px;background:#f0f0f0;border:1px solid #CCC;text-align:left;">							
							<a href="javascript:PaginaBusquedaAvanzada('1')" title="Primero" style="cursor:pointer; text-decoration:none;">|&lt;</a>&nbsp;
<?
				if($PagAct>1){ 
?>
							<a href="javascript:PaginaBusquedaAvanzada('<?=$PagAnt;?>')" title="Anterior" style="cursor:pointer; text-decoration:none;">&lt;&lt;</a>&nbsp;
<?
				}
				echo "<strong>".$PagAct."/".$PagUlt."</strong>";
				if($PagAct<$PagUlt){
?>
							<a href="javascript:PaginaBusquedaAvanzada('<?=$PagSig;?>')" title="Siguiente" style="cursor:pointer; text-decoration:none;">&gt;&gt;</a>&nbsp;
<?
				}
?>     
							<a href="javascript:PaginaBusquedaAvanzada('<?=$PagUlt;?>')" title="Ultimo" style="cursor:pointer; text-decoration:none;">&gt;|</a>&nbsp;
							<strong>| Resultados encontrados: <?=$NroRegistros;?></strong>
						</td>
					</tr>					
					<tr>
						<td class="datosTablaBusquedaResultados" style="position:relative;">ID</td>
						<td class="datosTablaBusquedaResultados">Modelo</td>
						<td class="datosTablaBusquedaResultados">Imei</td>
						<td class="datosTablaBusquedaResultados">Serial</td>
						<td class="datosTablaBusquedaResultados">Sim</td>
						<td class="datosTablaBusquedaResultados">Folio</td>
						<td class="datosTablaBusquedaResultados">MFGDATE</td>
						<td class="datosTablaBusquedaResultados">Status</td>
						<td class="datosTablaBusquedaResultados">Status Proceso</td>
						<td class="datosTablaBusquedaResultados">Status Desensamble</td>
						<td class="datosTablaBusquedaResultados">Status Diagnostico</td>
						<td class="datosTablaBusquedaResultados">Status Almacen</td>
						<td class="datosTablaBusquedaResultados">Status Ingenier&iacute;a</td>
						<td class="datosTablaBusquedaResultados">Status Empaque</td>
						<td class="datosTablaBusquedaResultados">Status IQ</td>
						<td class="datosTablaBusquedaResultados">Linea Ensamble</td>
						<td class="datosTablaBusquedaResultados">Fecha Recibo</td>
						<td class="datosTablaBusquedaResultados">Hora Recibo</td>
						<td class="datosTablaBusquedaResultados">Movimiento</td>
						<td class="datosTablaBusquedaResultados">Proceso</td>
						<td class="datosTablaBusquedaResultados">Tipo de Equipo</td>
						<td class="datosTablaBusquedaResultados">Flex Nuevo</td>
					</tr>
<?
				$color="#e1e1e1";
				while($rowBuscar=mysql_fetch_array($rs)){
?>
					<tr style="background:<?=$color;?>;">
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["id_radio"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["modelo"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["imei"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["serial"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["sim"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["lote"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["mfgdate"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["status"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["statusProceso"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["statusDesensamble"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["statusDiagnostico"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["statusAlmacen"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["statusIngenieria"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["statusEmpaque"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["statusIQ"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["lineaEnsamble"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["f_recibo"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["h_recibo"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["num_movimiento"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["facturar"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["tipoEquipo"];?></td>
						<td class="datosTablaBusquedaResultadosConsulta"><?=$rowBuscar["flexNuevo"];?></td>
					</tr>
<?
					($color=="#e1e1e1") ? $color="#FFF" : $color="#e1e1e1";
				}
?>
				</table><br>
<?
			}
		}
		
		public function mostrarFormulario(){
			$sqlModelo="select * from cat_modradio";
			$sqlLineas="select * from lineas";
			$resModelo=mysql_query($sqlModelo,$this->conectarBd());
			$resLineas=mysql_query($sqlLineas,$this->conectarBd());
?>
			<form name="frmBusqueda" id="frmBusqueda">
			<table border="0" cellpadding="1" cellspacing="1" width="500" style="margin:5px;">
				<tr>
					<td width="150" class="datosTablaBusqueda">Parametro</td>
					<td width="350" class="datosTablaBusqueda">Valor</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Fecha de Recibo</td>
					<td>
						<input type="text" name="f_recibo" id="f_recibo" readonly="readonly" style="width:100px;color:#FFF;font-weight:bold;"/>
						<input type="button" id="lanzador1" value="..." />
                
						<script type="text/javascript">					
						Calendar.setup({
							inputField     :    "f_recibo",      // id del campo de texto
							ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
							button         :    "lanzador1"   // el id del botón que lanzará el calendario
						});										
									
						</script>&nbsp;&nbsp;
						<input type="text" name="f_recibo2" id="f_recibo2" readonly="readonly" style="width:100px;color:#FFF;font-weight:bold;"/>
						<input type="button" id="lanzador2" value="..." />
                
						<script type="text/javascript">					
						Calendar.setup({
							inputField     :    "f_recibo2",      // id del campo de texto
							ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
							button         :    "lanzador2"   // el id del botón que lanzará el calendario
						});										
									
						</script>
					</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Modelo</td>
					<td>
						<select name="cboModelo" id="id_modelo" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
<?
				while($rowModelo=mysql_fetch_array($resModelo)){
?>
						<option value="<?=$rowModelo["id_modelo"];?>"><?=$rowModelo["modelo"];?></option>
<?
				}
?>												
						</select>
					</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Imei</td>
					<td><input type="text" name="imei" id="imei"></td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Serial</td>
					<td><input type="text" name="txtSerial" id="serial"></td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Sim</td>
					<td><input type="text" name="txtSim" id="sim"></td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Folio</td>
					<td><input type="text" name="txtFolio" id="lote"></td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Mfgdate</td>
					<td><input type="text" name="txtFechaM" id="mfgdate"></td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Numero de Movimiento</td>
					<td><input type="text" name="txtNumMov" id="num_movimiento"></td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Status</td>
					<td>
						<select name="cboStatus" id="status" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="WIP">WIP</option>
						<option value="ENVIADO">ENVIADO</option>						
						<option value="SCRAP">SCRAP</option>						
						</select>
					</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Status Proceso</td>
					<td>
						<select name="txtProceso" id="statusProceso" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="Recibo">RECIBO</option>
						<option value="Desensamble">DESENSAMBLE</option>
						<option value="Diagnostico">DIAGNOSTICO</option>
						<option value="Almacen">ALMACEN</option>
						<option value="Ingenieria">INGENIER&Iacute;A</option>
						<option value="Empaque">EMPAQUE</option>
						<option value="SCRAP">SCRAP</option>						
						</select>
					</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Status Desensamble</td>
					<td>
						<select name="txtDesensamble" id="statusDesensamble" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="OK">OK</option>                                
					    </select>
					</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Status Diagnostico</td>
					<td>
						<select name="txtDiagnostico" id="statusDiagnostico" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="OK">OK</option>
						<option value="SCRAP">SCRAP</option>
					    </select>
					</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Status Almacen</td>
					<td>
						<select name="txtAlmacen" id="statusAlmacen" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="Almacenado">ALMACENADO</option>
						<option value="Asignado">ASIGNADO</option>
					    </select>
					</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Status Ingenieria</td>
					<td>
						<select name="txtIngenieria" id="statusIngenieria" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="ING_OK">ING_OK</option>
						<option value="SCRAP">SCRAP</option>                                
					    </select>
					</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Status Empaque</td>
					<td>
						<select name="txtEmpaque" id="statusEmpaque" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="N/A">N/A</option>
						<option value="Empacado">EMPACADO</option>
						<option value="Validado">VALIDADO</option>
					    </select>
					</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Linea de Ensamble</td>
					<td>
						<select name="cboLineaEnsamble" id="lineaEnsamble" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
<?
				while($rowLineas=mysql_fetch_array($resLineas)){
?>
						<option value="<?=$rowLineas["id"];?>"><?=$rowLineas["nombre"];?></option>
<?
				}
?>												
						</select>						
					</td>
				</tr>				
				<tr>
					<td class="datosTablaBusqueda">Tipo de equipo</td>
					<td>
						<select name="tipoEquipo" id="tipoEquipo" style="width:130px;">
							<option value="Seleccione" selected="selected">Seleccione...</option>
							<option value="Nacional">Nacional</option>
							<option value="Frontera">Frontera</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="datosTablaBusqueda">Flex Nuevo</td>
					<td>
						<select name="txtFlexNuevo" id="flexNuevo" style="width:130px;">
						<option value="Seleccione" selected="selected">Seleccione...</option>
						<option value="1">S&iacute;</option>
						<option value="0">No</option>						
					    </select>
					</td>
				</tr>				
				<tr>
					<td colspan="2"><hr style="background:#000;"></td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<input type="reset" value="Borrar Valores">
						<input type="button" value="Buscar" onclick="busquedaAvanzada()" style="width:100px;">
					</td>					
				</tr>
			</table>
			</form>
			<!--<div id="parametrosConsulta"></div>-->
<?
		}	
	}//fin de la clase
?>