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
		
		public function validaStatusBaseDatos($imei){
			$sqlImei="SELECT * from equipos where imei='".$imei."'";
			$resImei=mysql_query($sqlImei,$this->conectarBd());
			$rowImei=mysql_fetch_array($resImei);
			if($rowImei['statusProceso']=="waitING" && $rowImei['statusDesensamble']=="OK" && $rowImei['statusIngenieria']==""){
				$validacion=true;
			}else{
				$validacion=false;
			}
			return $validacion;
		}
		
		
		public function actualizaDatosAlmacenDiagnostico($equipos,$proceso,$usuarioEnsamble,$txtProcesoAsig){			
			$objFunciones=new funcionesComunes();
			//se extrae el id del radio
			echo "<br>Procesando Datos...........................";
			$equipos=explode(",",$equipos);
			for($i=0;$i<count($equipos);$i++){
				//se verifica si el equipo proviene de desensamble
				$sqlC="select statusDesensamble from equipos where imei='".$equipos[$i]."'";
				$resC=mysql_query($sqlC,$this->conectarBd());
				$rowC=mysql_fetch_array($resC);
				//echo "Estatus Desensamble ".$rowC['statusDesensamble'];
				//if($rowC['statusDesensamble']!=""){		
					$sqlRadio="UPDATE equipos set statusAlmacen='Asignado',statusProceso='".$txtProcesoAsig."' WHERE imei='".$equipos[$i]."'";
					$resRadio=mysql_query($sqlRadio,$this->conectarBd());
					if($resRadio){
						echo "<br> -> Registro Actualizado";
					}else{
						echo "<br> -> Registro No Actualizado";
					}				
				/*}else{
					echo "<br>Verifique la informaci&oacute;n del equipo con imei <strong>(".$equipos[$i].")</strong>.<br>";
				}*/
				$id_Radio=$rowRadio['id_radio'];
				/*
				$sql_insert1="INSERT INTO detalle_ing (id_proc,id_personal,id_radio,status,id_falla,f_registro,h_registro,observaciones)";
				$sql_insert2=" VALUES('".$proceso."','".$usuarioEnsamble."','','Ok','0','".date("Y-m-d")."','".date("H:i:s")."','---')";
				echo "<br>".$sqlEnsamble=$sql_insert1.$sql_insert2;
				*/
				$objFunciones->guardaDetalleSistema($proceso,$usuarioEnsamble,$equipos[$i]);
			}
?>
			<script type="text/javascript"> resetForm(); </script>
<?			
		}
	}//fin de la clase
?>