<?php
		
	class modeloRecibo{
		
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
		
		public function mostrarResumenBusqueda($lote,$modelo){
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
			if($modelo=="S/M"){
				$sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where lote='".$lote."' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
				$sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where lote='".$lote."'";
			}else{
				$sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where lote='".$lote."' and equipos.id_modelo='".$modelo."' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
				$sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where lote='".$lote."' and equipos.id_modelo='".$modelo."'";
			}
			//echo $sqlEquipos;
			
			$rs=mysql_query($sqlEquipos,$this->conectarBd());
			$rs1=mysql_query($sqlEquipos1,$this->conectarBd());
			
			//******--------determinar las páginas---------******//
			$NroRegistros=@mysql_num_rows($rs1) or die("Verifique el filtro de Busqueda");
			$PagAnt=$PagAct-1;
			$PagSig=$PagAct+1;
			$PagUlt=$NroRegistros/$RegistrosAMostrar;
			
			//verificamos residuo para ver si llevará decimales
			$Res=$NroRegistros%$RegistrosAMostrar;
			// si hay residuo usamos funcion floor para que me devuelva la parte entera, SIN REDONDEAR, y le sumamos una unidad para obtener la ultima pagina
			if($Res>0) $PagUlt=floor($PagUlt)+1;
			
			if($NroRegistros==0){
				echo "<br>Sin registros.<br>";
			}else{
?>
			<table border="0" cellpadding="1" cellspacing="1" width="90%" style="margin:5px; margin-left:10px; border:1px solid #CCC;">
				<tr>
					<td colspan="5" style="font-size:12px; font-weight:bold;">Mostrando equipos filtrados por: <?=$status;?></td>
				</tr>
				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>
                <tr>
                	<td colspan="5">			
            <div style="text-align:center; height:10px; padding:5px;">
<?                    
			//desplazamiento
?>
				   <a href="javascript:PaginaBusquedaListado('1','<?=$lote;?>','<?=$modelo;?>')" title="Primero" style="cursor:pointer; text-decoration:none;">|&lt;</a>&nbsp;
<?
			 if($PagAct>1){ 
?>
					 <a href="javascript:PaginaBusquedaListado('<?=$PagAnt;?>','<?=$lote;?>','<?=$modelo;?>')" title="Anterior" style="cursor:pointer; text-decoration:none;">&lt;&lt;</a>&nbsp;
<?
			  }
			 echo "<strong>".$PagAct."/".$PagUlt."</strong>";
			 if($PagAct<$PagUlt){
?>
				  <a href="javascript:PaginaBusquedaListado('<?=$PagSig;?>','<?=$lote;?>','<?=$modelo;?>')" title="Siguiente" style="cursor:pointer; text-decoration:none;">&gt;&gt;</a>&nbsp;
<?
			 }
?>     
				  <a href="javascript:PaginaBusquedaListado('<?=$PagUlt;?>','<?=$lote;?>','<?=$modelo;?>')" title="Ultimo" style="cursor:pointer; text-decoration:none;">&gt;|</a>&nbsp;        
             </div>                           
                    </td>
                </tr>
				<tr>
					<td width="11%" style="background:#000; color:#FFF; height:30px; padding:4px;">Modelo</td>
					<td width="22%" style="background:#000; color:#FFF; height:30px; padding:4px;">Imei</td>
					<td width="19%" style="background:#000; color:#FFF; height:30px; padding:4px;">Serial</td>
					<td width="14%" style="background:#000; color:#FFF; height:30px; padding:4px;">Lote</td>
					<td width="34%" style="background:#000; color:#FFF; height:30px; padding:4px;">MFGDATE</td>
				</tr>
<?
			while($rowEquipos=mysql_fetch_array($rs)){
?>
				<tr>
					<td style="height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['modelo'];?></td>
					<td style="height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['imei'];?></td>
					<td style="height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['serial'];?></td>
					<td style="height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['lote'];?></td>
					<td style="height:25px; padding:4px; border-bottom:1px solid #CCC; text-align:center;"><?=$rowEquipos['mfgdate'];?></td>
				</tr>
<?			
			}
?>				
			</table>
<?		
		}
	}
		
		public function mostrarResumenBusquedaLote($lote){
			$sqlResumen="SELECT COUNT(*) AS Filas, modelo,equipos.id_modelo as idModelo
			FROM `equipos` INNER JOIN cat_modradio on equipos.id_modelo=cat_modradio.id_modelo
			WHERE lote='".$lote."'
			GROUP BY equipos.id_modelo 
			ORDER BY equipos.id_modelo";
			$resResumen=mysql_query($sqlResumen,$this->conectarBd());
			$sqlTotal="SELECT count( * ) as total FROM equipos WHERE lote = '".$lote."' ";
			$resTotal=mysql_query($sqlTotal,$this->conectarBd());
			$rowTotal=mysql_fetch_array($resTotal);
?>
			<table width="97%" border="0" cellpadding="1" cellspacing="1" style="border:1px solid #CCCCCC; margin-left:2px; margin-bottom:10px;">
				<tr>
					<td colspan="2" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:left; font-weight:bold; height:25px;">Resumen del lote <?=$lote;?>:</td>
				</tr>
				<tr>
					<td width="485" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center; height:25px;">Modelo</td>
					<td width="655" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center; height:25px;"># Registros</td>
				</tr>
<?
				while($rowResumen=mysql_fetch_array($resResumen)){
?>
				<tr>
					<td style="border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC; text-align:center; height:25px;">&nbsp;<?=$rowResumen['modelo'];?></td>
					<td style="border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC; text-align:center; height:25px;">&nbsp;<a href="#" onclick="verDetalleBusqueda('<?=$lote;?>','<?=$rowResumen['idModelo'];?>')" title="ver detalle" style="color:#03F; text-decoration:none;"><?=$rowResumen['Filas'];?> &raquo;</a></td>
				</tr>
<?				
				}
?>				
				<tr>
					<td width="485" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center; height:25px; font-weight:bold;">Total</td>
					<td width="655" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center; height:25px; font-weight:bold; font-size:14px;"><?=$rowTotal['total'];?></td>
				</tr>
            </table>
<?            
		}
		
		public function buscarEquipos($fecha1,$fecha2){
			include("../../includes/conectarbase.php");
			//echo $sqlBusca="SELECT * FROM equipos WHERE f_recibo BETWEEN '".$fecha1."' AND '".$fecha2."'";
			//echo
			$sqlBusca="SELECT modelo,bdcode,imei,serial ,f_recibo,h_recibo
			FROM equipos INNER JOIN cat_modradio on equipos.id_modelo=cat_modradio.id_modelo
			WHERE f_recibo BETWEEN '2011-01-01' AND '2013-01-31'";	
			$resModelos=mysql_query($sqlBusca,$this->conectarBd());
			//resumen de la busqueda
			$sqlResumen="SELECT COUNT(*) AS Filas, modelo 
			FROM `equipos` INNER JOIN cat_modradio on equipos.id_modelo=cat_modradio.id_modelo 
			GROUP BY equipos.id_modelo 
			ORDER BY equipos.id_modelo";
			$resResumen=mysql_query($sqlResumen,$this->conectarBd());
			
?>
			<table width="308" border="0" cellpadding="1" cellspacing="1" style="border:1px solid #CCCCCC; margin-left:5px; margin-bottom:10px;">
				<tr>
					<td colspan="2" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center;">Resumen:</td>
				</tr>
				<tr>
					<td width="127" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center;"># Registros</td>
					<td width="172" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center;">Modelo</td>
				</tr>
<?
				while($rowResumen=mysql_fetch_array($resResumen)){
?>
				<tr>
					<td style="border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC; text-align:center;">&nbsp;<?=$rowResumen['Filas'];?></td>
					<td style="border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC; text-align:center;">&nbsp;<?=$rowResumen['modelo'];?></td>
				</tr>
<?				
				}
?>				
			</table><br />
			<table width="98%" border="0" cellpadding="1" cellspacing="1" style="border:1px solid #CCCCCC;margin-left:5px;">
				<tr>
					<td width="5%" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center;">Modelo</td>
					<td width="20%" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center;">BdCode</td>
					<td width="18%" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center;">Imei</td>
					<td width="18%" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center;">Serial</td>
					<td width="19%" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center;">Fecha</td>
					<td width="20%" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center;">Hora</td>
				</tr>
<?
			while($rowModelo=mysql_fetch_array($resModelos)){
?>
				<tr>
					<td style="border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC; text-align:center;">&nbsp;<?=$rowModelo['modelo'];?></td>
					<td style="border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC; text-align:center;">&nbsp;<?=$rowModelo['bdcode'];?></td>
					<td style="border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC; text-align:center;">&nbsp;<?=$rowModelo['imei'];?></td>
					<td style="border-bottom:1px solid #CCCCCC; text-align:center;">&nbsp;<?=$rowModelo['serial'];?></td>
					<td style="border-bottom:1px solid #CCCCCC; text-align:center;">&nbsp;<?=$rowModelo['f_recibo'];?></td>
					<td style="border-bottom:1px solid #CCCCCC; text-align:center;">&nbsp;<?=$rowModelo['h_recibo'];?></td>
				</tr>
<?			
			}
?>				
			</table>
<?			
		}

		public function opcionesBusquedaRecibo(){
?>
			<form name="opcionesBusquedaRecibo" id="opcionesBusquedaRecibo">
            <table width="98%" border="1" cellspacing="1" cellpadding="1" style="margin:5px;">
            	<tr>
                	<td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                	<td width="15%">Modelo</td>
                    <td width="85%"><input type="text" name="txtModeloBusquedaRecibo" id="txtModeloBusquedaRecibo" /></td>
                </tr>
                <tr>
                	<td>Imei</td>
                    <td><input type="text" name="txtImeiBusquedaRecibo" id="txtImeiBusquedaRecibo" /></td>
                </tr>
                <tr>
                	<td>Serial</td>
                    <td><input type="text" name="txtSerialBusquedaRecibo" id="txtSerialBusquedaRecibo" /></td>
                </tr>
                <tr>
                	<td>BdCode</td>
                    <td><input type="text" name="txtBDCodeBusquedaRecibo" id="txtBDCodeBusquedaRecibo" /></td>
                </tr>
                <tr>
                	<td>Lote</td>
                    <td><input type="text" name="txtLoteBusquedaRecibo" id="txtLoteBusquedaRecibo" /></td>
                </tr>
                <tr>
                	<td colspan="2">&nbsp;</td>
                </tr>
            </table></form>
<?		
		}
		
		public function datosIniciales($usuarioCaptura,$modelo,$mov,$cantidad){
			include("../../includes/conectarbase.php");
			//echo
			$sqlModelos="SELECT * FROM cat_modradio ";
			$resModelos=mysql_query($sqlModelos,$this->conectarBd());
			
			//usuario captura
			$sqlUsuario="SELECT ID,nombre,apaterno FROM ".$tabla_usuarios." WHERE ID='".$usuarioCaptura."'";
			$resUsuario=mysql_query($sqlUsuario,$this->conectarBd());
			$rowUsuario=mysql_fetch_array($resUsuario);
?>
			<form name="frmAsistente1" id="frmAsistente1">
            <input type="hidden" name="hdncantidad" id="hdncantidad" value="<?=$cantidad?>" />
            <input type="hidden" name="hdnmovimiento" id="hdnmovimiento" value="<?=$mov?>" />
            
            <table border="0" width="318" cellpadding="1" cellspacing="1" style="margin:5px;">
            	<tr>
                	<td colspan="2" class="tituloTabla">Recepci&oacute;n de Equipos</td>
                </tr>
                <tr>
                	<td width="104" class="tituloTextoFormulario">Modelo</td>
                    <td width="207" class="resultadosTablaReporte"><!--<input type="hidden" name="cboModeloRadio" id="cboModeloRadio" value="<?$modelo?>" />--><!--se oculto porque no viene de un movimiento-->
<?
					
					if($modelo['id_modelo']==""){
?>
                                     
                    <select name="cboModeloRadio" id="cboModeloRadio">
                    	<option value="" selected="selected">Selecciona...</option>
<?
					while($rowModelo=mysql_fetch_array($resModelos)){
?>
						<option value="<?=$rowModelo['id_modelo'];?>"><?=$rowModelo['modelo'];?></option>
<?						
					}
?>
                    </select>
                    
<?
					 }else { 
		    $sqlModelo="SELECT modelo FROM cat_modradio WHERE id_modelo='".$modelo."'";
			$resModelo=mysql_query($sqlModelo,$this->conectarBd());
			$rowModelo=mysql_fetch_array($resModelo);	

			echo $rowModelo['modelo'];
			

					}
                  

?>
                    </td>
                </tr>
                <tr>
                	<td class="tituloTextoFormulario">Recibe</td>
                    <td class="resultadosTablaReporte">&nbsp;<input type="hidden" name="hdnUsuario" id="hdnUsuario" value="<?=$rowUsuario['ID'];?>" /><?=$rowUsuario['nombre']."-".$rowUsuario['apaterno'];?></td>
                </tr>
		<tr>
			<td class="tituloTextoFormulario">Proceso</td>
			<td class="resultadosTablaReporte"><input type="text" name="cboProceso" id="cboProceso" value="Refurbish" readonly="readonly" /></td>
                </tr>
		<tr>
                	<td class="tituloTextoFormulario">MFGDATE</td>
                    <td class="resultadosTablaReporte"><input type="text" name="txtClave" id="txtClave" value="" readonly="readonly" /></td>
                </tr>
                <tr>
                	<td class="tituloTextoFormulario">Folio</td>
                    <td class="resultadosTablaReporte"><input type="text" name="txtLote" id="txtLote" /></td>
                </tr>
		<tr>
                	<td class="tituloTextoFormulario">Clasificaci&oacute;n</td>
			<td class="resultadosTablaReporte">
				<select name="cboClasificacion" id="cboClasificacion">
					<option value="Selecciona">Selecciona...</option>
					<option value="Nacional">Nacional</option>
					<option value="Frontera">Frontera</option>
				</select>
			</td>
                </tr>
                <tr>
                	<td colspan="2"><hr style="background:#999;" /></td>
                </tr>
                <tr>
                	<td colspan="2" align="right"><input type="button" value="Siguiente >>" onclick="recuperaDatos()" /></td>
                </tr>
</table></form>
<?		
		}
		
		
	}//fin de la clase
?>