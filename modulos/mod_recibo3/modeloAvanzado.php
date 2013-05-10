<?
	class modeloAvanzado{
		
		public $noCapturados=1;
		
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
		
		public function registrarDatos2($modelo,$usuarioRecibe,$proceso,$lote,$clave,$mov,$cantidad,$elementos,$clasificacion){
			$elementos=explode(",,,",$elementos);//se separa la cadena para formar un array de los valores
			$pos=0;
			$insertados=0;
			$noInsertados=0;
			for($i=0;$i<count($elementos);$i++){
				if($clasificacion=="Nacional"){
					$tipoF="2F";
				}else if($clasificacion=="Frontera"){
					$tipoF="2FF";
				}
				$sqlInsertaRadio_1="INSERT INTO equipos (id_personal,id_modelo,imei,serial,lote,mfgdate,status,statusProceso,f_recibo,h_recibo,observaciones,num_movimiento,facturar,tipoEquipo)";
				$sqlInsertaRadio_2=" VALUES ('".$usuarioRecibe."','".$modelo."','".$elementos[$pos]."','".$elementos[$pos+1]."','".$lote."','".$clave."','WIP','Recibo','".date("Y-m-d")."','".date("H:i:s")."','--','".$mov."','".$tipoF."','".$clasificacion."')";
				$sql=$sqlInsertaRadio_1.$sqlInsertaRadio_2;
				$res=mysql_query($sql,$this->conectarBd());
				if($res){
					$insertados+=1;	
				}else{
					$noInsertados+=1;
				}
				$pos=$pos+2;
				if(count($elementos)==$pos){
					break;
				}
			}
?>
			<div id="desv">
				<div id="msgManttoProg">
					<p style="text-align:center;">Informaci&oacute;n</p>
					<p style="text-align:left;margin-left:5px;">Equipos insertados en la Base de Datos: <?=$insertados;?></p>
					<p style="text-align:left;margin-left:5px;">Ocurrieron <?=$noInsertados;?> errores al guardar la informaci&oacute;n.</p>
					<p style="text-align:center;"><input type="button" value="Aceptar" onclick="cerrarMensajeGuardado()" /></p>
				</div>
			</div>
<?
		}
		
		public function validarObsoletos($series){
			//include("../../includes/conf/conf_validacion_serial.php");
			echo "Validando datos...";
			//se extrae la informacion de la tabla de configuracion
			$sqlObsoleto="SELECT nombreConf,valor FROM configuracionglobal WHERE nombreConf='obsoletos'";
			$resObsoleto=mysql_query($sqlObsoleto,$this->conectarBd());
			$rowObsoleto=mysql_fetch_array($resObsoleto);
			$filaObsoleto=$rowObsoleto['valor'];
			$filaObsoleto=explode(",",$filaObsoleto);
			
			$series=explode(",",$series);
			for($i=0;$i<count($series);$i++){				
				$series[$i]=$series[$i];
				$div="#obsoleto".$series[$i];//jquery
				$anio=strtoupper(substr($series[$i],4,1));				
				if(in_array($anio,$filaObsoleto)){
					//echo "encontrado";
?>
					<script>
						$("<?=$div;?>").removeClass("validacion1");
						$("<?=$div;?>").addClass("validacion1Activa");
						$("<?=$div;?>").attr("value","Obsoleto");						
					</script>
<?					
				}else{
?>
					<script>
						$("<?=$div;?>").attr("value","Validado");
						$("<?=$div;?>").removeClass("validacion1");
						$("<?=$div;?>").addClass("validacion1ActivaValidado");
					</script>
<?				
					//echo "no encontrado";
				}
			}
?>
			<script>
				$("#msgReciboGrid").html("Validacion terminada");
			</script>
<?				
		}

		public function validarNoEnviar($imeis){
			echo "Validando datos...";
			$imeis=explode(",",$imeis);
			for($i=0;$i<count($imeis);$i++){
				$sqlImei="select * from equipos_no_enviar where imei='".$imeis[$i]."'";
				$resImei=mysql_query($sqlImei,$this->conectarBd());
				if(mysql_num_rows($resImei)!=0){
					//marcar el div
					$div="#noEnviar".$imeis[$i];//jquery
					$chk="#chkRecibo".$imeis[$i];
?>
					<script>
						$("<?=$div;?>").removeClass("validacion2");
						$("<?=$div;?>").addClass("validacion2Activa");
						$("<?=$div;?>").attr("value","No enviar");
						$("<?=$chk;?>").attr("checked",false);
					</script>
<?					
				}else{
					$div="#noEnviar".$imeis[$i];//jquery
?>
					<script>
						$("<?=$div;?>").attr("value","Validado");
						$("<?=$div;?>").removeClass("validacion2");
						$("<?=$div;?>").addClass("validacion2ActivaValidado");
					</script>
<?					
					
				}
			}
?>
			<script>
				$("#msgReciboGrid").html("Validacion terminada");
			</script>
<?			
		}
	
		public function registrarDatos($modelo,$usuarioRecibe,$proceso,$lote,$clave,$bdCode,$serial,$imei,$mov,$cantidad){
			//se buscan las repeticiones en la base de datos
			include("../../includes/conectarbase.php");
			$captura=$this->buscarDatosRepetidos($bdCode,$serial,$imei);
			if($captura==true){
				/*modificacion para especificar el guardado de equipos l metodo actual*/
				//se quita el insert y se va a realizar un insert masivo de datos
				
				//
				//$sqlInsertaRadio_1="INSERT INTO equipos (id_personal,id_modelo,imei,serial,bdcode,lote,mfgdate,status,statusProceso,f_recibo,h_recibo,observaciones,num_movimiento)";
				//$sqlInsertaRadio_2=" VALUES ('".$usuarioRecibe."','".$modelo."','".$imei."','".$serial."','".$bdCode."','".$lote."','".$clave."','En Proceso','Recibo','".date("Y-m-d")."','".date("H:i:s")."','--','".$mov."')";
				
				//$sqlRadio=$sqlInsertaRadio_1.$sqlInsertaRadio_2;
				//$resRadio=mysql_query($sqlRadio,$this->conectarBd());
				//if($resRadio==true){
					
					//echo 
					//$sqlContador="SELECT COUNT(*) AS Total FROM equipos WHERE num_movimiento='".$mov."' AND id_modelo='".$modelo."'";
					//$resContador=mysql_query($sqlContador,$this->conectarBd());
					//$rowContador=mysql_fetch_array($resContador);
					//echo"<br>".
					//$contador=$rowContador["Total"];
					
					//if($contador==$cantidad){
						
?>

					<script type="text/javascript"> //validacion();</script>
<?
						//echo "aqui se detiene";
							
						
					//}
					
					//echo "<br>Registro Guardado";
?>
					<script type="text/javascript"> 
						armaGrid2('<?=$bdCode;?>','<?=$imei;?>','<?=$serial;?>'); 
						
						//contador('<?$mov?>','<?$modelo?>');//noCapturados=noCapturados+1;
					</script>
<?		
               
				//}else{
					//echo "<br>Error al actualizar la informaci&oacute;n del Radio.";
?>
			<script type="text/javascript"> //noCapturados=noCapturados-1; </script>

<?
					
				//}
			}else{
				echo "<div style='background:#FFC;'><br>Revisar los Datos de la Captura.<br><br></div>";
				
?>
			<!--<script type="text/javascript"> noCapturados=noCapturados-1; 
            //$("#infoCaptura").html("<div>Equipos Capturados</div><div>"+noCapturados+"</div>");
            </script>-->

<?			
	
			}
		}
			
	// public $contador=1;		
		public function mostrarCaptura($modelo,$recibe,$proceso,$lote,$clave,$mov,$cantidad,$clasificacion){
			include("../../includes/conectarbase.php");
			$sqlModelo="SELECT modelo FROM cat_modradio WHERE id_modelo='".$modelo."'";
			$resModelo=mysql_query($sqlModelo,$this->conectarBd());
			$rowModelo=mysql_fetch_array($resModelo);
			/**************************/
			$sqlMov="SELECT num_movimiento FROM equipos WHERE num_movimiento='".$mov."'"; 
			$resMov=mysql_query($sqlMov,$this->conectarBd());
			$rowMov=mysql_fetch_array($resMov);
			//if( $cerrar=="cerrar"){
			//modificacion se crea el movimiento para tener el control de la exportacion de los registros
			$sqlMov="Insert into reg_movimientos (fecha,hora,tipo) values ('".date("Y-m-d")."','".date("H:i:s")."','ENTRADA')";
			$resMov=mysql_query($sqlMov,$this->conectarBd());
			if($resMov==false){
				echo "Error al crear el movimiento en el sistema";
				exit;
			}else{
				$sql_id = "SELECT LAST_INSERT_ID() as id FROM reg_movimientos";
				$res_id=mysql_query($sql_id,$this->conectarBd());
				$row_id=mysql_fetch_array($res_id);
				$mov=$row_id['id'];
?>
			<script type="text/javascript"> document.getElementById("txtImei").focus(); </script>
			<form name="reciboEquipos" id="reciboEquipos">
			<input type="hidden" name="hdncantidad" id="hdncantidad" value="<?=$cantidad?>" />
			<input type="hidden" name="hdnmovimiento" id="hdnmovimiento" value="<?=$mov?>" />
			<input type="hidden" name="modeloCaptura" id="modeloCaptura" value="<?=$modelo;?>" />
			<input type="hidden" name="usuarioRecibe" id="usuarioRecibe" value="<?=$recibe;?>" />
			<input type="hidden" name="procesoRadio" id="procesoRadio" value="<?=$proceso;?>" />
			<input type="hidden" name="loteRadio" id="loteRadio" value="<?=$lote;?>" />
			<input type="hidden" name="claveRadio" id="claveRadio" value="<?=$clave;?>" />
			<input type="hidden" name="clasificacion" id="clasificacion" value="<?=$clasificacion;?>" />
			<table border="0" width="1000" cellpadding="1" cellspacing="1" style="margin:5px; border:1px solid #333;">
			    <tr>
			      <td colspan="2" style="border:1px solid #999; background:#CCC; height:30px; font-size:12px;"><strong>Numero de Movimiento: <?=$mov?></strong></td>
			      <td align="right">
				    <div id="infoCaptura" style="border:1px solid #999; background:#CCC; color:#000; overflow:auto; height:40px; width:100px; margin-top:5px; float:left;"></div>
				<div id="msgReciboGrid" class="msgNRadios">&nbsp;</div>
			      </td>
			    </tr>
			    <tr>
				    <td height="27" colspan="3" class="tituloTabla">Modelo a Capturar: <?=$rowModelo['modelo'];?></td>
			    </tr>
			    <tr>
				    <td width="240" class="tituloTextoFormulario">BDCode</td>
				<td width="125" class="tituloTextoFormulario">Imei</td>
				<td width="625" class="tituloTextoFormulario">Serial</td>
			    </tr>
			    <tr>
				    <td><input type="text" name="bdCode" id="bdCode" size="29" value="" onKeyPress="verificaTeclaBDCode(event)" style="height:30px;" disabled="disabled" /></td>
				<td><input type="text" name="txtImei" id="txtImei" onkeypress="verificaTeclaImei(event)" size="15" style="height:30px;" /></td>
				<td>
				    <div style="float:left;"><input type="text" name="txtSerial" id="txtSerial" onkeypress="verificaTeclaSerial(event)"  onkeyup="this.value = this.value.replace (/[^A-Za-z0-9]/, '');" size="10" style="height:30px;" /></div>
				    <div style="float:right;">
					    
				    </div>
				</td>
			    </tr>
			    <tr>
				    <td colspan="3">
				<div class='estiloContador' style="background:#000; color:#FFF; border:1px solid #CCC;">#</div>
				<div class='estiloCheck' style="width:15px;background:#000; color:#FFF; border:1px solid #CCC;">&nbsp;</div>
				<div class='estiloImei' style="background:#000; color:#FFF; border:1px solid #CCC;">Imei</div>
				<div class='estiloSerial' style="background:#000; color:#FFF; border:1px solid #CCC;">Serial</div>
				<div class='estiloBdCode' style="background:#000; color:#FFF; border:1px solid #CCC;">BdCode</div>
				<div class='validacion1' style="background:#000; color:#FFF; border:1px solid #CCC;">Obsoleto</div>
				<div class='validacion2' style="background:#000; color:#FFF; border:1px solid #CCC;">No Enviar</div>
				<div class='estiloContador' style="background:#000; color:#FFF; border:1px solid #CCC;">#</div><div style='clear:both;'></div>
				</td>
			    </tr>
			    <tr>
				<td colspan="3">			
					<div id="gridCaptura" style="border:1px solid #999; overflow:auto; height:400px; float:left; width:845px;"></div>
					<div id="botonera" style="float:right; border:1px solid #999; height:400px; width:140px;  text-align:center;">
						<input type="button" id="btnValidarObsoletos" value="Validar Obsoletos  " onclick="validarObsoletos()" style=" width:100px;height:30px; font-size:10px;"><br />
						<input type="button" id="btnValidarNoEnviar" value="Validar No Enviar" onclick="validarNoEnviar()" style=" width:100px;height:30px; font-size:10px;"><br />
						<input type="button" id="btnRegistrarDatos" value="Registrar" onclick="guardarGrid()" style=" width:100px;height:30px; font-size:10px;"><br><br><br>
						<input type="button" id="btnRegistrarDatos" value="CANCELAR" onclick="cancelarCaptura()" style=" width:100px;height:30px; font-size:10px; background:#FF0000; color:#FFF; font-weight:bold;">
					</div>					
				</td>
			    </tr>
			    <tr>
				<td colspan="3">
					<div class="resumenRepeticion"><div class="barraRepeticion">&nbsp;BDcode</div><div id="bdCodeRepetido" class="divsRepeticiones"></div></div>
					<div class="resumenRepeticion"><div class="barraRepeticion">&nbsp;Serial</div><div id="serialRepetido" class="divsRepeticiones"></div></div>
					<div class="resumenRepeticion"><div class="barraRepeticion">&nbsp;Imei</div><div id="imeiRepetido" class="divsRepeticiones"></div></div>
				</td>
			    </tr>
			    <tr>
				<td colspan="3"><div id="transparenciaGeneral" style="display:none;">
					<div id="msgGuardado">
					<br /><br/>
					<br /><br />
					<p style="margin-left:10px; font-size:12px; font-weight:bold;">Se ha concluido la captura del Item del movimiento <?=$mov?>.</p><br/>
					<p style="margin-left:10px; font-size:10px;" > Para Cerrar esta ventana presione el boton Cerrar situado en la parte inferior.</p><br/><br/>
					<p align="center"> <input type="button" id="btncerrar" value="Cerrar" onclick="cerrar()" style="height:50px; width:90px;"></p>
				    </div>
				    </div></td>
				</tr>
			    </table>
			</form>
            <br /><br />
            <!--<table border="0" width="1000" cellpadding="1" cellspacing="1" style="margin:5px; height:99%;">
            	<tr >
                	<td width="625" valign="top">
                        <table border="0" width="623" cellpadding="1" cellspacing="1">                            
                            <tr>
                                <td colspan="4" style="border:1px solid #999; background:#CCC; height:30px; font-size:12px;"><strong>Numero de Movimiento: <?=$mov?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="tituloTabla">Modelo a Capturar: <?=$rowModelo['modelo'];?></td>
                            </tr>
                            <tr>
                                <td width="294" class="tituloTextoFormulario">BDCode</td>
                                <td width="120" class="tituloTextoFormulario">Imei</td>
                                <td width="120" class="tituloTextoFormulario">Serial</td>
                                <td width="68">&nbsp;</td>
                            </tr>
                            <tr id="nipUsuarioSurtido">
                                <td><input type="text" name="bdCode" id="bdCode" size="39" value="" onKeyPress="verificaTeclaBDCode(event)" style="height:30px;" disabled="disabled" /></td>
                                <td><input type="text" name="txtImei" id="txtImei" onkeypress="verificaTeclaImei(event)" size="20" style="height:30px;" /></td>
                                <td><input type="text" name="txtSerial" id="txtSerial" onkeypress="verificaTeclaSerial(event)"  onkeyup="this.value = this.value.replace (/[^A-Za-z0-9]/, '');" size="20" style="height:30px;" /></td>
                                
                                
                                
                                <td><input type="button" id="btnRegistrarDatos" value="Registrar" onclick="registrarDatos()" style="height:30px;"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                	<div id="gridCaptura" style="border:1px solid #999; overflow:auto;"></div>
                                    <div id="infoCaptura" style="border:1px solid #999; background:#CCC; color:#000; overflow:auto; height:40px; margin-top:5px;"></div>
                                </td>
								<td valign="top">
									<input type="button" id="btnRegistrarDatos" value="Validar Equipos  " onclick="registrarDatos()" style="height:40px;">
									<input type="button" id="btnRegistrarDatos" value="Validar No Enviar" onclick="registrarDatos()" style="height:40px;">
								</td>
                            </tr>
                        </table>
	            	</td>
                    
                    <td width="362" valign="top" style="border-left:1px solid #CCC; margin-left:5px;">
                    
                    <div id="transparenciaGeneral" style="display:none;">
                		<div id="msgGuardado">
                			<br /><br/>
                			<br /><br />
                			<p style="margin-left:10px; font-size:12px; font-weight:bold;">Se ha concluido la captura del Item del movimiento <?=$mov?>.</p><br/>
                			<p style="margin-left:10px; font-size:10px;" > Para Cerrar esta ventana presione el boton Cerrar situado en la parte inferior.</p><br/><br/>
                   			<p align="center"> <input type="button" id="btncerrar" value="Cerrar" onclick="cerrar()" style="height:50px; width:90px;"></p>
	                    </div>
                    </div>
                    
                    	<div id="msgReciboGrid" class="msgNRadios">&nbsp;</div><br />
                   
                        
                    	<div class="resumenRepeticion"><div class="barraRepeticion">&nbsp;BDcode</div><div id="bdCodeRepetido" class="divsRepeticiones"></div></div>
                        <div class="resumenRepeticion"><div class="barraRepeticion">&nbsp;Serial</div><div id="serialRepetido" class="divsRepeticiones"></div></div>
                        <div class="resumenRepeticion"><div class="barraRepeticion">&nbsp;Imei</div><div id="imeiRepetido" class="divsRepeticiones"></div></div>
                        
                    </td>
                </tr>
            </table>--><!--<div id="msgNRadios" class="msgNRadios">#</div>-->
            
<?			
			/*}else if ($cerrar=="boton"){
?>
				<script type="text/javascript"> document.getElementById("btncerrar").focus(); $("#nipUsuarioSurtido").hide(); $("#txtSerial").hide(); </script>
                <br /><br />
                <div id="transparenciaGeneral">
                <div id="msgGuardado">
                <br /><br/>
               
                <br /><br /><br /><br />
                <input type="submit" id="btncerrar" value="Cerrar" onclick="cerrar()" style="height:50px; width:90px; ">
                <!--<input type="submit" id="botonGuardar" class="boton" value="Guardar Informaci&oacute;n" onclick="guardarSurtido()" style="height:40px;"  />-->
                </div>
                </div>
                <br /><br />
<?               
				
			}else{*/
?>
            <script type="text/javascript">
				//gridReciboDimensiones();				
			</script>
<?			
		//}
			}
		}
		
		private function buscarDatosRepetidos($bdCode,$serial,$imei){
			include("../../includes/conectarbase.php");
			$captura=true;
			//se busca el bdCode
			if($bdCode != ""){
				//echo "<br>".
				$sqlBdCode="SELECT COUNT(*) AS totalBdCode FROM equipos WHERE bdcode='".$bdCode."'";
				$resBdCode=mysql_query($sqlBdCode,$this->conectarBd());
				$rowBdCode=mysql_fetch_array($resBdCode);
				if($rowBdCode['totalBdCode'] != 0){				
					$captura=false;
?>				
					<script type="text/javascript">
						if(confirm("Existen: "+<?=$rowBdCode['totalBdCode'];?>+ " dato(s) repetido(s) con el BdCode introducido en la Base de Datos.\n\nDesea ver los resultados en una ventana distinta.")){
							muuestraDatosRepetidos('bdCodeRepetido','<?=$bdCode;?>');
						}
					</script>
<?                
				}
			}
			//se busca el serial
			if($serial != ""){
				//echo "<br>".
				$sqlSerial="SELECT COUNT(*) AS totalSerial FROM equipos WHERE serial='".$serial."'";
				$resSerial=mysql_query($sqlSerial,$this->conectarBd());
				$rowSerial=mysql_fetch_array($resSerial);
				if($rowSerial['totalSerial'] != 0){
					$captura=false;
?>				
					<script type="text/javascript"> 
						if(confirm("Existen: "+<?=$rowSerial['totalSerial'];?>+ " dato(s) repetido(s) con el Serial introducido en la Base de Datos.\n\nDesea ver los resultados en una ventana distinta.")){
							muuestraDatosRepetidos('serialRepetido','<?=$serial;?>');
						}
					</script>
<?
				}
			}
			//se busca el imei
			if($imei != ""){
				//echo "<br>".
				$sqlImei="SELECT COUNT(*) AS totalImei FROM equipos WHERE imei='".$imei."'";
				$resImei=mysql_query($sqlImei,$this->conectarBd());
				$rowImei=mysql_fetch_array($resImei);
				if($rowImei['totalImei'] != 0){
					$captura=false;
?>
					<script type="text/javascript">
						if(confirm("Existen: "+<?=$rowImei['totalImei'];?>+ " dato(s) repetido(s) con el Imei introducido en la Base de Datos.\n\nDesea ver los resultados en una ventana distinta.")){
							muuestraDatosRepetidos('imeiRepetido','<?=$imei;?>');
						}
					</script>
<?
				}
			}
			return $captura;
		}
		
		
		public function contador($modelo,$mov){
			include("../../includes/conectarbase.php");
			 $sqlContador="SELECT COUNT(*) AS Total FROM equipos WHERE num_movimiento='".$mov."' AND id_modelo='".$modelo."'";
					$resContador=mysql_query($sqlContador,$this->conectarBd());
					$rowContador=mysql_fetch_array($resContador);
					//echo"<br>".
					 $contador=$rowContador["Total"];
		
					echo "<div><strong>Equipos capturados: $contador<strong></div>";
       
		}		
		
	}//fin de la clase
	
	//$obj=new modeloAvanzado();
	//$obj->mostrarCaptura(1,1,"Recibo",'Lote-01',"CLAVE","","5");
?>