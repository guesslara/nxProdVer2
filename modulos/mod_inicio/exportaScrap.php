<?php
	$status=$_GET["status"];
	$modelo=$_GET["modelo"];
	//listar_items_tarimas($id_tarimaR);

	exportarScrap($status,$modelo);
	
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
	
	
	function exportarScrap($status,$modelo){
		$nombreArchivo="scrap_modelo".$modelo."_".date("Y-m-d")."_".date("H:i:s");
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
		$sql="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where status='SCRAP' and modelo='".$modelo."'";
		
		if($res=mysql_query($sql,$link)){
			$ndr=mysql_num_rows($res);
			if($ndr==0){
				echo "Sin Resultados";
			}else{
?>
			<table cellspacing="0" cellpadding="2" width="95%" align="center" class="tabla_bordeada">
				<tr>
					<th>Item</th>
					<th>Imei</th>
					<th>Serie</th>
					<th>Folio</th>
					<th>Tipo</th>
					
				</tr>
<?
			while($reg=mysql_fetch_array($res)){
				
				
?>			
				<tr>
					<td><?=$reg['modelo'];?></td>
					<td class="xl65"><?=(string)$reg['imei'];?></td>
					<td><?=$reg['serial'];?></td>
					<td><?=$reg['lote'];?></td>
					<td><?=$reg['tipoEquipo'];?></td>					
				</tr>
<?				
				}
			}
?>				
			</table>
<?			
			}
		
	}//fin de la funcion
					
	
?>