<?php
	$folio=$_GET['folio'];

	//listar_items_tarimas($id_tarimaR);

	exportarFolio($folio);
	
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
	
	
	function exportarFolio($folio){
		$nombreArchivo="equipos_folio_".$folio."_".date("Y-m-d");
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
		$sql="SELECT DISTINCT modelo, equipos.imei, equipos.serial, equipos.sim as sim, equipos.lote, equipos.STATUS as status, equipos.facturar, equipos.tipoEquipo, folios_nextel.folio_salida, folios_nextel.cantidad, folios_nextel.tipo, folios_nextel.pedimento, folios_nextel.factura
			FROM (equipos INNER JOIN cat_modradio ON equipos.id_modelo = cat_modradio.id_modelo)
			LEFT JOIN folios_nextel ON equipos.lote = folios_nextel.folio_salida
			WHERE equipos.lote = '".$folio."' AND equipos.status IN ('WIP','WIP2', 'SCRAP')";
		$sql1="SELECT DISTINCT modelo, equipos_enviados.imei, equipos_enviados.serial, equipos_enviados.sim as sim, equipos_enviados.lote, equipos_enviados.STATUS as status, equipos_enviados.facturar, equipos_enviados.tipoEquipo, folios_nextel.folio_salida, folios_nextel.cantidad, folios_nextel.tipo, folios_nextel.pedimento, folios_nextel.factura
			FROM (equipos_enviados INNER JOIN cat_modradio ON equipos_enviados.id_modelo = cat_modradio.id_modelo)
			LEFT JOIN folios_nextel ON equipos_enviados.lote = folios_nextel.folio_salida
			WHERE equipos_enviados.lote = '".$folio."' AND equipos_enviados.status IN ('ENVIADO', 'SCRAP ENVIADO')";
		if($res=mysql_query($sql,$link)){
			$ndr=mysql_num_rows($res);			
			if($ndr==0){
				echo "Sin Resultados";
			}else{				
				$res1=mysql_query($sql1,$link);
				$ndr1=mysql_num_rows($res1);
				//echo $ndr+mysql_num_rows($res1)."<br>";
?>
			<table cellspacing="0" cellpadding="2" width="95%" align="center" class="tabla_bordeada">
				<tr>
					<th>Modelo</th>
					<th>Imei</th>
					<th>Serie</th>
					<th>Sim</th>
					<th>Folio</th>
					<th>Status</th>
					<th>Tipo</th>
					<th>Folio</th>
					<th>Cantidad</th>
					<th>Nacional/Frontera</th>
					<th>Pedimento</th>
					<th>Factura</th>
				</tr>
<?
			while($reg=mysql_fetch_array($res)){				
?>			
				<tr>
					<td><?=$reg['modelo'];?></td>
					<td class="xl65"><?=(string)$reg['imei'];?></td>
					<td><?=$reg['serial'];?></td>
					<td><?=$reg['sim'];?></td>
					<td><?=$reg['lote'];?></td>
					<td><?=$reg['status'];?></td>
					<td><?=$reg['facturar'];?></td>
					<td><?=$reg['tipoEquipo'];?></td>
					<td><?=$reg['folio_salida'];?></td>
					<td><?=$reg['cantidad'];?></td>
					<td><?=$reg['tipo'];?></td>
					<td><?=$reg['pedimento'];?></td>
					<td><?=$reg['factura'];?></td>
				</tr>
<?				
				}
			}
			while($reg1=mysql_fetch_array($res1)){
?>
				<tr>
					<td><?=$reg1['modelo'];?></td>
					<td class="xl65"><?=(string)$reg1['imei'];?></td>
					<td><?=$reg1['serial'];?></td>
					<td><?=$reg1['sim'];?></td>
					<td><?=$reg1['lote'];?></td>
					<td><?=$reg1['status'];?></td>
					<td><?=$reg1['facturar'];?></td>
					<td><?=$reg1['tipoEquipo'];?></td>
					<td><?=$reg1['folio_salida'];?></td>
					<td><?=$reg1['cantidad'];?></td>
					<td><?=$reg1['tipo'];?></td>
					<td><?=$reg1['pedimento'];?></td>
					<td><?=$reg1['factura'];?></td>
				</tr>
<?
			}
?>				
			</table>
<?			
			}
		
	}//fin de la funcion
					
	
?>