<?

$mov=$_GET['mov'];
$modelo=$_GET['modelo'];
$cantidad=$_GET['cantidad'];
$captura= new modeloAvanzado();
$captura->mostrarCaptura($modelo,$recibe,$proceso,$lote,$clave);



	class modeloAvanzado{
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
		
		 public function registrarDatos($modelo,$usuarioRecibe,$proceso,$lote,$clave,$bdCode,$serial,$imei){
			//se buscan las repeticiones en la base de datos
			include("../../includes/conectarbase.php");
			$captura=$this->buscarDatosRepetidos($bdCode,$serial,$imei);
			if($captura==true){
				$sqlInsertaRadio_1="INSERT INTO equipos (id_personal,id_modelo,imei,serial,bdcode,lote,mfgdate,status,f_recibo,h_recibo,observaciones)";
				$sqlInsertaRadio_2=" VALUES ('".$usuarioRecibe."','".$modelo."','".$imei."','".$serial."','".$bdCode."','".$lote."','".$clave."','Recibo','".date("Y-m-d")."','".date("H:i:s")."','--')";
				//echo "<br>".
				$sqlRadio=$sqlInsertaRadio_1.$sqlInsertaRadio_2;
				$resRadio=mysql_query($sqlRadio,$this->conectarBd());
				if($resRadio==true){
					echo "<br>Registro Guardado";
?>
					<script type="text/javascript"> armaGrid('<?=$bdCode;?>','<?=$imei;?>','<?=$serial;?>'); </script>
<?					
				}else{
					echo "<br>Error al actualizar la informaci&oacute;n del Radio.";
				}
			}else{
				echo "<div style='background:#FFC;'><br>Revisar los Datos de la Captura.<br><br></div>";
			}
		}
				
		public function mostrarCaptura($modelo,$recibe,$proceso,$lote,$clave){
			include("../../includes/conectarbase.php");
			echo $sqlModelo="SELECT modelo FROM cat_modradio WHERE id_modelo='".$modelo."'";
			$resModelo=mysql_query($sqlModelo,$this->conectarBd());
			$rowModelo=mysql_fetch_array($resModelo);
?>
			<input type="hidden" name="modeloCaptura" id="modeloCaptura" value="<?=$modelo;?>" />
            <input type="hidden" name="usuarioRecibe" id="usuarioRecibe" value="<?=$recibe;?>" />
            <input type="hidden" name="procesoRadio" id="procesoRadio" value="<?=$proceso;?>" />
            <input type="hidden" name="loteRadio" id="loteRadio" value="<?=$lote;?>" />
            <input type="hidden" name="claveRadio" id="claveRadio" value="<?=$clave;?>" />
            <table border="0" width="1000" cellpadding="1" cellspacing="1" style="margin:5px; height:99%;">
            	<tr>
                	<td width="625" valign="top">
                        <table border="0" width="623" cellpadding="1" cellspacing="1">                            
                            <tr>
                                <td colspan="4" class="tituloTabla">Modelo a Capturar: <?=$rowModelo['modelo'];?></td>
                            </tr>
                            <tr>
                                <td width="294" class="tituloTextoFormulario">BDCode</td>
                                <td width="120" class="tituloTextoFormulario">Imei</td>
                                <td width="120" class="tituloTextoFormulario">Serial</td>
                                <td width="68">&nbsp;</td>
                            </tr>
                            <tr>
                                <td><input type="text" name="bdCode" id="bdCode" size="49" value="NUF3722A00H63XAN6RR4AN000100000358380364ADN02YK" onKeyPress="verificaTeclaBDCode(event)" style="height:30px;" /></td>
                                <td><input type="text" name="txtImei" id="txtImei" onkeypress="verificaTeclaImei(event)" size="20" style="height:30px;" /></td>
                                <td><input type="text" name="txtSerial" id="txtSerial" onkeypress="verificaTeclaSerial(event)" size="20" style="height:30px;" /></td>
                                <td><input type="button" id="btnRegistrarDatos" value="Registrar" onclick="registrarDatos()" style="height:30px;"></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                	<div id="gridCaptura" style="border:1px solid #999; overflow:auto;"></div>
                                    <div id="infoCaptura" style="border:1px solid #999; overflow:auto; height:70px; margin-top:5px;"></div>
                                </td>
                            </tr>
                        </table>
	            	</td>
                    <td width="362" valign="top" style="border-left:1px solid #CCC; margin-left:5px;">
                    	<div id="msgReciboGrid" class="msgNRadios">&nbsp;</div><br />
                        <!--<div id="msgNRadios" class="msgNRadios">#</div>-->
                    	<div class="resumenRepeticion"><div class="barraRepeticion"><div id="bdCodeRepetido"></div></div></div>
                        <div class="resumenRepeticion"><div class="barraRepeticion"><div id="serialRepetido"></div></div></div>
                        <div class="resumenRepeticion"><div class="barraRepeticion"><div id="imeiRepetido"></div></div></div>
                    </td>
                </tr>
            </table>
            <script type="text/javascript">
				gridReciboDimensiones();				
			</script>
<?			
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
						if(confirm("Existen: "+<?=$rowBdCode['totalBdCode'];?>+ " Datos repetidos con el BdCode introducido.\n\nDesea ver los resultados en una ventana distinta.")){
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
						if(confirm("Existen: "+<?=$rowSerial['totalSerial'];?>+ " Datos repetidos con el Serial introducido.\n\nDesea ver los resultados en una ventana distinta.")){
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
						if(confirm("Existen: "+<?=$rowImei['totalImei'];?>+ " Datos repetidos con el Imei introducido.\n\nDesea ver los resultados en una ventana distinta.")){
							muuestraDatosRepetidos('imeiRepetido','<?=$imei;?>');
						}
					</script>
<?
				}
			}
			return $captura;
		}
	}//fin de la clase
?>