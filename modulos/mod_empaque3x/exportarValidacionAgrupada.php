<?
	$empaque=$_GET['id_validacion'];
	
	exportarValidacion($empaque);
	
	
	function conectarBd(){
		require("../../includes/config.inc.php");
		$link=mysql_connect($host,$usuario,$pass);
		if($link==false){
			echo "Error en la conexion a la base de datos";
		}else{
			mysql_select_db($db);
			return $link;
		}				
	}
	
	function exportarValidacion($empaque){
		$nombreArchivo="equiposValidacion_".date("Y-m-d");
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=$nombreArchivo.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		
?>
		<style type="text/css">
		.xl65{
			mso-style-parent:style0;
 			mso-number-format:"\@";
		}
		</style>	
<?		
		$link=conectarBd();		
		$sql="select * from empaque_validaciones where id='".$empaque."'";
		
		if($res=mysql_query($sql,$link)){
			$ndr=mysql_num_rows($res);
			if($ndr==0){
				echo "Sin Resultados";
			}else{
				$row=mysql_fetch_array($res);
				$ids_entregas=$row["id_entregas"];
				$sqlValidacion="select * from (empaque inner join empaque_items on empaque.id=empaque_items.id_empaque) inner join cat_modradio on empaque.modelo=cat_modradio.id_modelo where empaque.id in (".$ids_entregas.")";
				$res=mysql_query($sqlValidacion,$link);
?>
                <table cellspacing="0" cellpadding="2" width="95%" align="center" class="tabla_bordeada">
                    <tr>
                    	<td colspan="7">Archivo de Validacion</td>
                    </tr>
                    <tr>
                        <th>Modelo</th>
                        <th>Imei</th>
                        <th>Sim</th>
                        <th>Serie</th>
                        <th>Lote</th>
                        <th>Mfgdate</th>
                        <th>Caja</th>
                    </tr>
<?
				while($reg=mysql_fetch_array($res)){
					//se extraen los equipos de las cajas
					//echo "<br>".$sqlItems="SELECT * FROM empaque_items WHERE id_caja='".$reg['caja']."'";
					$sqlItems="SELECT modelo,equipos.imei as imeiRadio,empaque_items.sim as simEquipo,equipos.serial as serial,lote,empaque_items.mfgdate as mfgdate 
						FROM (empaque_items left join equipos on empaque_items.imei=equipos.imei) left join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo
						WHERE equipos.imei='".$reg['imei']."'";
					$resItems=mysql_query($sqlItems,$link);
					$rowItems=mysql_fetch_array($resItems)
?>			
                        <tr>
                            <td><?=$rowItems['modelo'];?></td>
                            <td style="mso-number-format:'@';"><?=(string)$rowItems['imeiRadio'];?></td><!-- class="xl65"-->
                            <td class="xl65"><?=(string)$rowItems['simEquipo'];?></td>
                            <td class="xl65"><?=strtoupper($rowItems['serial']);?></td>
                            <td><?=$rowItems['lote'];?></td>
                            <td><?=strtoupper($rowItems['mfgdate']);?></td>
                            <td><?=$reg['id_caja'];?></td>
                        </tr>
<?				
					
				}
?>				
			</table>
<?			
			}
		}
		
	}//fin de la funcion
?>