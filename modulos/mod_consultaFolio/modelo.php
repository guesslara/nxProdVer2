<?php
include("../../clases/conexion/conexion.php");
 class modeloNextel{
      
      
      public function mostrarInfoResumen($status,$folio,$div){	  
	  if($status != "ENVIADO" && $status != "SCRAP ENVIADO"){
	       if($status=="WIP"){
		    $sqlResumen="SELECT COUNT(*) AS `Filas`, equipos.`id_modelo` ,modelo FROM `equipos` inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo WHERE lote='".$folio."' AND status in('".$status."','WIP2') GROUP BY `id_modelo` ORDER BY `id_modelo`";
	       }else{
		    $sqlResumen="SELECT COUNT(*) AS `Filas`, equipos.`id_modelo` ,modelo FROM `equipos` inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo WHERE lote='".$folio."' AND status='".$status."' GROUP BY `id_modelo` ORDER BY `id_modelo`";
	       }
	  }else{
	       if($status=="ENVIADO"){
		    $sqlResumen="SELECT COUNT( * ) AS `Filas` , equipos_enviados.id_modelo, modelo, envioIq , destino FROM equipos_enviados INNER JOIN cat_modradio ON equipos_enviados.id_modelo = cat_modradio.id_modelo WHERE lote = '".$folio."' AND status='ENVIADO' GROUP BY envioIq ORDER BY envioIq";		    
	       }else if($status=="SCRAP ENVIADO"){
		    $sqlResumen="SELECT COUNT( * ) AS `Filas` , equipos_enviados.id_modelo, modelo, envioIq , destino FROM equipos_enviados INNER JOIN cat_modradio ON equipos_enviados.id_modelo = cat_modradio.id_modelo WHERE lote = '".$folio."' AND status='SCRAP ENVIADO' GROUP BY envioIq ORDER BY envioIq";
	       }	       
	  }
	  //echo $sqlResumen;
	  $resResumen=mysql_query($sqlResumen,$this->conexionBd());
	  if(mysql_num_rows($resResumen) == 0){
	       echo "Sin resultados";
	  }else{
?>
	       <span style="font-weight: bold;font-size: 12px;">Equipos Clasificados por: <?=$status;?></span><br>
	       <div onclick="cerrarDivReporteResumen('<?=$div?>')" style="float: right; margin-right: 10px;height: 18px;padding: 5px;width: 150px; background: red;border: 1px solid #FFF;text-align: center;color:#FFF;font-weight: bold;">Cerrar Informaci&oacute;n</div>
	       <table border="0" cellpadding="1" cellspacing="1" width="200" style="border: 1px solid #000;background: #FFF;">
<?
	       if($status != "ENVIADO" && $status != "SCRAP ENVIADO"){
?>
		    <tr>
			 <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;">Modelo</td>
			 <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;">Cantidad</td>
		    </tr>
<?
	       }else{
?>
		    <tr>
			 <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;">Modelo</td>
			 <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;">Cantidad</td>
			 <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;">Entrega</td>
			 <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;">Destino</td>
		    </tr>
<?
	       }
?>	       
		    
<?
	       $total=0;
	       while($rowResumen=mysql_fetch_array($resResumen)){
		    $total+=$rowResumen["Filas"];
		    if($status != "ENVIADO" && $status != "SCRAP ENVIADO"){
?>
			 <tr>
			      <td style="height: 15px; padding: 5px;text-align: center;border-bottom: 1px solid #CCC;"><?=$rowResumen["modelo"];?></td>
			      <td style="height: 15px; padding: 5px;text-align: center;border-bottom: 1px solid #CCC;"><?=$rowResumen["Filas"];?></td>
			 </tr>
<?
		    }else{
?>
			 <tr>
			      <td style="height: 15px; padding: 5px;text-align: center;border-bottom: 1px solid #CCC;"><?=$rowResumen["modelo"];?></td>
			      <td style="height: 15px; padding: 5px;text-align: center;border-bottom: 1px solid #CCC;"><?=$rowResumen["Filas"];?></td>
			      <td style="height: 15px; padding: 5px;text-align: center;border-bottom: 1px solid #CCC;"><?=$rowResumen["envioIq"];?></td>
			      <td style="height: 15px; padding: 5px;text-align: center;border-bottom: 1px solid #CCC;"><?=$rowResumen["destino"];?></td>
			 </tr>
<?
		    }
?>
		    
<?
	       }
		    if($status != "ENVIADO" && $status != "SCRAP ENVIADO"){
?>
			 <tr>
			      <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;">Total</td>
			      <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;"><?=$total;?></td>
			 </tr>
<?
		    }else{
?>
			 <tr>
			      <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;">Total</td>
			      <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;"><?=$total;?></td>
			      <td style="height: 15px; padding: 5px;text-align: center;background: #000;color: #FFF;" colspan="2">&nbsp;</td>
			 </tr>
<?
		    }
?>

	       </table><br>
<?	       
	  }
      }
      
      public function verFoliosResumen(){
	    $sqlResFolio="SELECT * FROM folios_nextel ORDER BY folio_salida";
	    $resFolio=mysql_query($sqlResFolio,$this->conexionBd());
	    if(mysql_num_rows($resFolio)==0){
		  echo "( 0 ) registros.";
	    }else{
?>
		  <table border="0" cellpadding="1" cellspacing="1" width="1000" style="font-size: 10px;">
			<tr>
			      <td colspan="7" class="titulosTablaInfo1">Info. NEXTEL</td>			      
			      <td colspan="11" class="titulosTablaInfo2">Proceso IQ</td>			      
			</tr>
			<tr>
			      <td class="titulosTablaResumen">&nbsp;</td>
			      <td class="titulosTablaResumen">Folio</td>
			      <td class="titulosTablaResumen">No Eq</td>
			      <td class="titulosTablaResumen">Nacional / Frontera</td>
			      <td class="titulosTablaResumen">Pedimento</td>
			      <td class="titulosTablaResumen">Fecha</td>
			      <td class="titulosTablaResumen">Factura</td>
			      <td class="titulosTablaResumen">Eq. Bd Enviada</td>
			      <td class="titulosTablaResumen">Eq. WIP</td>
			      <td class="titulosTablaResumen">Eq. WIP2</td>
			      <td class="titulosTablaResumen">Eq. WIP3</td>
			      <td class="titulosTablaResumen">Eq. SCRAP</td>
			      <td class="titulosTablaResumen">Eq. ENVIADOS</td>
			      <td class="titulosTablaResumen">Scrap. ENVIADO</td>
			      <td class="titulosTablaResumen">RETENCION</td>
			      <td class="titulosTablaResumen">RETENCION 2</td>
			      <td class="titulosTablaResumen">VALIDANDO</td>
			      <td class="titulosTablaResumen">TOTAL PROCESO</td>
			      <td class="titulosTablaResumen"> + / - </td>
			      <td class="titulosTablaResumen">D&iacute;as en IQ</td>
			      <td class="titulosTablaResumen">Acciones</td>
			</tr>
<?
		  $claseCss="listadoFoliosGrid1";	$i=0;
		  $totalReporte=0; $totalSubido=0; $totalIqBd=0; $totalWip=0; $totalScrap=0; $totalScrapEnviado=0; $totalValidando=0; $totalEnviado=0; $totalmasmenos=0;
		  while($rowFolio=mysql_fetch_array($resFolio)){
			 $sqlEq="select count(*) as totalBd from archivo_Cliente where lote='".$rowFolio["folio_salida"]."'";
			 $resEq=mysql_query($sqlEq,$this->conexionBd());
			 $rowEq=mysql_fetch_array($resEq);
			 $restantes=$rowFolio["cantidad"]-$rowEq["totalBd"];
			 if($restantes==0){
			      $msg="<span style='color:green;font-weight:bold;'>Ok</span>";
			 }else{
			      $msg="<span style='color:red;font-weight:bold;'>Verificar</span>";
			 }
			 //consultas a realizar
			 $sqlWip="select count(*) as totalWip from equipos where lote = '".$rowFolio["folio_salida"]."' AND status = 'WIP'";
			 $resWip=mysql_query($sqlWip,$this->conexionBd());
			 $rowWip=mysql_fetch_array($resWip);
			 
			 $sqlWip2="select count(*) as totalWip2 from equipos where lote = '".$rowFolio["folio_salida"]."' AND status = 'WIP2'";
			 $resWip2=mysql_query($sqlWip2,$this->conexionBd());
			 $rowWip2=mysql_fetch_array($resWip2);
			 
			 $sqlWip3="select count(*) as totalWip3 from equipos where lote = '".$rowFolio["folio_salida"]."' AND status = 'WIP3'";
			 $resWip3=mysql_query($sqlWip3,$this->conexionBd());
			 $rowWip3=mysql_fetch_array($resWip3);
			 
			 $sqlScrap="select count(*) as totalScrap from equipos where lote = '".$rowFolio["folio_salida"]."' AND status IN ('SCRAP','SCRAP POR ENVIAR')";
			 $resScrap=mysql_query($sqlScrap,$this->conexionBd());
			 $rowScrap=mysql_fetch_array($resScrap);
			 
			 $sqlEnviado="select count(*) as totalEnviado from equipos_enviados where lote = '".$rowFolio["folio_salida"]."' AND status='ENVIADO'";
			 $resEnviado=mysql_query($sqlEnviado,$this->conexionBd());
			 $rowEnviado=mysql_fetch_array($resEnviado);
			 
			 $sqlScrapEnviado="select count(*) as totalScrapEnviado from equipos_enviados where lote = '".$rowFolio["folio_salida"]."' AND status='SCRAP ENVIADO'";
			 $resScrapEnviado=mysql_query($sqlScrapEnviado,$this->conexionBd());
			 $rowScrapEnviado=mysql_fetch_array($resScrapEnviado);
			 
			 $sqlRetencion="select count(*) as totalRetencion from equipos where lote = '".$rowFolio["folio_salida"]."' AND status='Retencion'";
			 $resRetencion=mysql_query($sqlRetencion,$this->conexionBd());
			 $rowRetencion=mysql_fetch_array($resRetencion);
			 
			 $sqlRetencion2="select count(*) as totalRetencion2 from equipos where lote = '".$rowFolio["folio_salida"]."' AND status='Retencion2'";
			 $resRetencion2=mysql_query($sqlRetencion2,$this->conexionBd());
			 $rowRetencion2=mysql_fetch_array($resRetencion2);
			 
			 $sqlValidando="select count(*) as totalValidando from equipos where lote = '".$rowFolio["folio_salida"]."' AND status='Validando'";
			 $resValidando=mysql_query($sqlValidando,$this->conexionBd());
			 $rowValidando=mysql_fetch_array($resValidando);
			 
			 $totalProceso=0;	$masmenos=0;
			 $totalProceso=$rowWip["totalWip"]+$rowWip2["totalWip2"]+$rowWip3["totalWip3"]+$rowScrap["totalScrap"]+$rowEnviado["totalEnviado"]+$rowScrapEnviado["totalScrapEnviado"]+$rowRetencion["totalRetencion"]+$rowRetencion2["totalRetencion2"]+$rowValidando["totalValidando"];
			 $masmenos=$rowFolio["cantidad"]-$totalProceso;
			 //totales
			 $totalReporte+=$rowFolio["cantidad"];
			 $totalSubido+=$rowEq["totalBd"];
			 $totalIqBd+=$totalProceso;
			 $totalWip+=$rowWip["totalWip"];
			 $totalWip2+=$rowWip2["totalWip2"];
			 $totalWip3+=$rowWip3["totalWip3"];
			 $totalScrap+=$rowScrap["totalScrap"];
			 $totalScrapEnviado+=$rowScrapEnviado["totalScrapEnviado"];
			 $totalEnviado+=$rowEnviado["totalEnviado"];
			 $totalRetencion+=$rowRetencion["totalRetencion"];
			 $totalRetencion2+=$rowRetencion2["totalRetencion2"];
			 $totalValidando+=$rowValidando["totalValidando"];
			 $totalmasmenos+=$masmenos;
			 if($masmenos==0){
			      $fondo="green"; $fuente="#FFF";
			 }else{
			      $fondo="red"; $fuente="#FFF";
			 }
			 //consulta para los dias en IQ
			 $sqlDias="SELECT DATEDIFF('".date("Y-m-d")."','".$rowFolio["fecha"]."') AS diasT";
			 $resDias=mysql_query($sqlDias,$this->conexionBd());
			 $rowDias=mysql_fetch_array($resDias);
			 if($rowDias["diasT"] >= 0 && $rowDias["diasT"] <=30 ){
			      $fondoDias="green"; $fuenteDias="#FFF";
			 }else if($rowDias["diasT"] >= 31 && $rowDias["diasT"] <=45 ){
			      $fondoDias="yellow"; $fuenteDias="#000";
			 }else if($rowDias["diasT"] >= 46 && $rowDias["diasT"] >=60 ){
			      $fondoDias="red"; $fuenteDias="#FFF";
			 }
			 $divFolio="divFolio".$i;
?>
			<tr class="<?=$claseCss;?>">
			      <td ><a href="#" style="color:blue;">+</a></td>
			      <td ><?=$rowFolio["folio_salida"];?></td>
			      <td ><?=$rowFolio["cantidad"];?></td>
			      <td ><?=$rowFolio["tipo"];?></td>
			      <td ><?=$rowFolio["pedimento"];?></td>
			      <td ><?=$rowFolio["fecha"];?></td>
			      <td ><?=$rowFolio["factura"];?></td>
			      <td ><?=$rowEq["totalBd"]." ".$msg;?></td>
			      <td ><a href="#" onclick="mostrarResumenListadoReporte('<?=$divFolio;?>','WIP','<?=$rowFolio["folio_salida"];?>')" title="Ver Equipos WIP" style="color:blue;"><?=$rowWip["totalWip"];?></a></td>
			      <td ><a href="#" onclick="mostrarResumenListadoReporte('<?=$divFolio;?>','WIP2','<?=$rowFolio["folio_salida"];?>')" title="Ver Equipos WIP2" style="color:blue;"><?=$rowWip2["totalWip2"];?></a></td>
			      <td ><a href="#" onclick="mostrarResumenListadoReporte('<?=$divFolio;?>','WIP3','<?=$rowFolio["folio_salida"];?>')" title="Ver Equipos WIP3" style="color:blue;"><?=$rowWip3["totalWip3"];?></a></td>
			      <td ><a href="#" onclick="mostrarResumenListadoReporte('<?=$divFolio;?>','SCRAP','<?=$rowFolio["folio_salida"];?>')" title="Ver Equipos Scrap" style="color:blue;"><?=$rowScrap["totalScrap"];?></a></td>
			      <td ><a href="#" onclick="mostrarResumenListadoReporte('<?=$divFolio;?>','ENVIADO','<?=$rowFolio["folio_salida"];?>')" title="Ver Equipos Scrap" style="color:blue;"><?=$rowEnviado["totalEnviado"];?></a></td>
			      <td ><a href="#" onclick="mostrarResumenListadoReporte('<?=$divFolio;?>','SCRAP ENVIADO','<?=$rowFolio["folio_salida"];?>')" title="Ver Equipos Scrap" style="color:blue;"><?=$rowScrapEnviado["totalScrapEnviado"];?></a></td>
			      <td ><a href="#" onclick="mostrarResumenListadoReporte('<?=$divFolio;?>','RETENCION','<?=$rowFolio["folio_salida"];?>')" title="Ver Equipos Retencion" style="color:blue;"><?=$rowRetencion["totalRetencion"];?></a></td>
			      <td ><a href="#" onclick="mostrarResumenListadoReporte('<?=$divFolio;?>','RETENCION2','<?=$rowFolio["folio_salida"];?>')" title="Ver Equipos Retencion 2" style="color:blue;"><?=$rowRetencion2["totalRetencion2"];?></a></td>
			      <td ><?=$rowValidando["totalValidando"];?></td>
			      <td style="background: <?=$color;?>;border-bottom: 1px solid #CCC;border-right: 1px solid #CCC;height: 15px;padding: 5px;font-weight:bold;text-align:center;"><?=$totalProceso;?></td>
			      <td style="background: <?=$fondo;?>;color:<?=$fuente;?>;border-bottom: 1px solid #CCC;border-right: 1px solid #CCC;height: 15px;padding: 5px;text-align:center;"><?=$masmenos;?></td>
			      <td style="background: <?=$fondoDias;?>;color:<?=$fuenteDias;?>;border-bottom: 1px solid #CCC;border-right: 1px solid #CCC;height: 15px;padding: 5px;font-weight:bold;text-align:center;"><?=$rowDias["diasT"];?></td>
			      <td><a href="exportaFolio.php?folio=<?=$rowFolio["folio_salida"];?>" target="_blank" title="Exportar Folio <?=$rowFolio["folio_salida"];?>" style="color:blue;">Exportar</a></td>
			</tr>
			<tr>
			      <td colspan="8"></td>
			      <td colspan="12"><div id="<?=$divFolio;?>" style="display: none;background: #f0f0f0;"></div></td>
			</tr>
<?
		    ($claseCss=="listadoFoliosGrid1") ? $claseCss="listadoFoliosGrid2" : $claseCss="listadoFoliosGrid1";
		    $i+=1;
		  }
?>
			 <tr>
			      <td colspan="20"><hr style="background: #CCC;"></td>
			 </tr>
			 <tr>
			      <td colspan="2" style="font-weight: bold;font-size: 14px;text-align: center;">Totales</td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalReporte;?></td>
			      <td colspan="4" style="font-weight: bold;font-size: 12px;text-align: center;">&nbsp;</td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalSubido;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalWip;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalWip2;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalWip3;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalScrap;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalEnviado;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalScrapEnviado;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalRetencion;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalRetencion2;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalValidando;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalIqBd;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;"><?=$totalmasmenos;?></td>
			      <td style="font-weight: bold;font-size: 12px;text-align: center;">&nbsp;</td>
			 </tr>
		  </table>
<?
	    }
      }
       public function mostrarResumenEnviadosFolio($folio,$modelo,$filtro){
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
			$sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where lote='".$folio."' AND status='ENVIADO' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			$sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where lote='".$folio."' AND status='ENVIADO'";
		}else{
			$sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where (lote='".$folio."' and equipos.id_modelo='".$modelo."') AND status='ENVIADO' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			$sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where (lote='".$folio."' and equipos.id_modelo='".$modelo."') AND status='ENVIADO'";
		}
		//echo $sqlEquipos;
		
		$rs=mysql_query($sqlEquipos,$this->conexionBd());
		$rs1=mysql_query($sqlEquipos1,$this->conexionBd());
		
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
			<table border="0" cellpadding="1" cellspacing="1" width="98%" style="margin:5px; margin-left:10px; border:1px solid #CCC;">
				<tr>
					<td colspan="7" style="font-size:12px; font-weight:bold;">Mostrando equipos filtrados por el Folio: <?=$folio;?></td>
				</tr>
				<tr>
					<td colspan="7">Resultados (<?=$NroRegistros;?>).</td>
				</tr>
                <tr>
                	<td colspan="7">			
            <div style="text-align:center; height:10px; padding:5px;">
<?                    
			//desplazamiento
?>
				   <a href="javascript:PaginaResumenEnviadosFolio('1','<?=$folio;?>','<?=$modelo;?>','<?=$filtro;?>')" title="Primero" style="cursor:pointer; text-decoration:none;">|&lt;</a>&nbsp;
<?
			 if($PagAct>1){ 
?>
					 <a href="javascript:PaginaResumenEnviadosFolio('<?=$PagAnt;?>','<?=$folio;?>','<?=$modelo;?>','<?=$filtro;?>')" title="Anterior" style="cursor:pointer; text-decoration:none;">&lt;&lt;</a>&nbsp;
<?
			  }
			 echo "<strong>".$PagAct."/".$PagUlt."</strong>";
			 if($PagAct<$PagUlt){
?>
				  <a href="javascript:PaginaResumenEnviadosFolio('<?=$PagSig;?>','<?=$folio;?>','<?=$modelo;?>','<?=$filtro;?>')" title="Siguiente" style="cursor:pointer; text-decoration:none;">&gt;&gt;</a>&nbsp;
<?
			 }
?>     
				  <a href="javascript:PaginaResumenEnviadosFolio('<?=$PagUlt;?>','<?=$folio;?>','<?=$modelo;?>','<?=$filtro;?>')" title="Ultimo" style="cursor:pointer; text-decoration:none;">&gt;|</a>&nbsp;        
             </div>                           
                    </td>
                </tr>
				<tr>
					<td width="11%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Modelo</td>
					<td width="22%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Imei</td>
					<td width="19%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Serial</td>
					<td width="19%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Sim</td>
					<td width="14%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Folio</td>
					<td width="34%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Status</td>
					<td width="34%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Status Proceso</td>
					<td width="34%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">MFGDATE</td>
				</tr>
<?
			$color="#E1E1E1";
			while($rowEquipos=mysql_fetch_array($rs)){
?>
				<tr>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['modelo'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['imei'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['serial'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['sim'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['lote'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['status'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['statusProceso'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; text-align:center;"><?=$rowEquipos['mfgdate'];?></td>
				</tr>
<?			
			    ($color=="#E1E1E1") ? $color="#FFF" : $color="#E1E1E1";
			}
?>				
			</table>
<?		
		}
	}
       
       
       public function verModeloEnviadosFolio($folio,$div){
	      $sqlEnviadosFolio="SELECT COUNT(*) AS Filas,modelo,equipos.id_modelo as idModeloRadio 
			    FROM equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo
			    WHERE lote='".$folio."' AND status='ENVIADO' 
			    GROUP BY equipos.id_modelo 
			    ORDER BY Filas DESC ";
	      $resEnviadosFolio=mysql_query($sqlEnviadosFolio,$this->conexionBd());
	      if(mysql_num_rows($resEnviadosFolio)==0){
		     echo "Sin Resultados.";
	      }else{
?>
	      <br />
	      <table border="0" cellpadding="1" cellspacing="1" width="95%" style="margin:5px; background:#FFF;">
		     <tr>
			    <td colspan="3" style="text-align:right;"><a href="#" onclick="cerrarDivModeloEnviadosFolio('<?=$div;?>')">Cerrar Info</a></td>
		     </tr>
		     <tr>
                	<td colspan="3" style="font-size:12px; height:25px; padding:5px;">Resumen por Modelo:</td>
                     </tr>
		     <tr>
			    <td width="30%" style="height:25px; padding:5px;background:#000; color:#FFF;">Modelo</td>
		            <td width="21%" style="background:#000; color:#FFF;"># Registros</td>
			    <td width="49%" style="background:#000; color:#FFF;">&nbsp;</td>
		     </tr>
<?
		     while($rowEnviadosFolio=mysql_fetch_array($resEnviadosFolio)){
?>
		     <tr>
			    <td style="height:25px; padding:5px; border-bottom:1px solid #999; text-align:center;">&nbsp;<?=$rowEnviadosFolio['modelo'];?></td>
			    <td style="height:25px; padding:5px; border-bottom:1px solid #999; text-align:right;"><?=$rowEnviadosFolio['Filas'];?>&nbsp;</td>
			    <td style="height:25px; padding:5px; border-bottom:1px solid #999;">&nbsp;<a href="#" style="text-decoration:none;color:blue;" onclick="verResumenEnviadosFolioDetalle('<?=$folio;?>','<?=$rowEnviadosFolio['idModeloRadio'];?>','modelo')">Ver M&aacute;s</a></td>
	             </tr>
<?
		     }
?>
	      </table><br>
<?
	      }
       }
       
       public function verResumenEnviadoFolio(){
	      $sqlEnviadosFolio="SELECT COUNT(*) AS Filas,lote 
				   FROM equipos
				   WHERE status='ENVIADO'
				   GROUP BY lote
				   ORDER BY equipos.lote  DESC";
	      $resEnviadosFolio=mysql_query($sqlEnviadosFolio,$this->conexionBd());
	      if(mysql_num_rows($resEnviadosFolio)==0){
		     echo "<br>Sin Registros.";
	      }else{
?>
	      <table border="0" width="96%" cellpadding="1" cellspacing="1" style="margin:4px;">
		     <tr>
			    <td colspan="5">&nbsp;</td>
		     </tr>
		     <tr>
			    <td colspan="5">Resumen Enviados por Folio:</td>
		     </tr>
<?
		     $totalEnviados=0; $i=0;
		     while($rowEnviadosFolio=mysql_fetch_array($resEnviadosFolio)){
			    $idDiv="divDetalleEnviados".$i;
			    $totalEnviados+=$rowEnviadosFolio["Filas"];
?>
		     <tr>
			    <td width="29%" style="height:25px;border:1px solid #999; background:#ccc; text-align:left;"><?=$rowEnviadosFolio['lote'];?>: </td>
			    <td width="9%" style="height:25px;border:1px solid #999; background:#ccc; text-align:center;"><a href="#" style="text-decoration:none;color:blue;" title="Detalle por modelo" onclick="verResumenEnviadosModelos('<?=$rowEnviadosFolio['lote'];?>','<?=$idDiv;?>')">[ + ]</a></td>
			    <td width="42%" style="height:25px; border:1px solid #CCC; text-align:right;"><a href="#" style="text-decoration:none;color:blue;" onclick="verResumenEnviadosFolioDetalle('<?=$rowEnviadosFolio['lote'];?>','S/M','status')"><?=$rowEnviadosFolio['Filas'];?></a>&nbsp;</td>                
		     </tr>
		     <tr>
			    <td colspan="5"><div id="<?=$idDiv;?>" style="background:#f0f0f0;"></div></td>
		     </tr>
<?
			    $i+=1;
		     }
?>
		     <tr>
			    <td colspan="2" style="height:25px;border:1px solid #999; background:#ccc; text-align:left; font-weight:bold;font-size:14px;">Total Enviados:</td>
			    <td style="height:25px; border:1px solid #CCC; text-align:right; font-weight:bold;font-size:14px;"><?=$totalEnviados;?></td>
		     </tr>
	      </table>
<?
	      }
       }
	
	public function mostrarResumenModeloStatusProceso($status,$div){
	      $sqlModelo="SELECT COUNT( * ) AS `Filas` , modelo,equipos.id_modelo as idModeloRadio
					FROM equipos INNER JOIN cat_modradio ON equipos.id_modelo = cat_modradio.id_modelo
					WHERE statusProceso = '".$status."'
					GROUP BY equipos.id_modelo
					ORDER BY `Filas` ASC";
	      $resModelo=mysql_query($sqlModelo,$this->conexionBd());
	      if(mysql_num_rows($resModelo)==0){
		     echo "<br>Sin Registros.";
	      }else{
?>
	      <br />
	      <table border="0" cellpadding="1" cellspacing="1" width="95%" style="margin:5px; background:#FFF;">
		     <tr>
			    <td colspan="3" style="text-align:right;"><a href="#" onclick="cerrarDiv('<?=$div;?>')">Cerrar Info</a></td>
		     </tr>
		     <tr>
                	<td colspan="3" style="font-size:12px; height:25px; padding:5px;">Resumen por Modelo para el status <?=$status;?></td>
                     </tr>
		     <tr>
			    <td width="30%" style="height:25px; padding:5px;background:#000; color:#FFF;">Modelo</td>
		            <td width="21%" style="background:#000; color:#FFF;"># Registros</td>
			    <td width="49%" style="background:#000; color:#FFF;">&nbsp;</td>
		     </tr>
<?
		     while($rowModelo=mysql_fetch_array($resModelo)){
?>
		     <tr>
			    <td style="height:25px; padding:5px; border-bottom:1px solid #999; text-align:center;">&nbsp;<?=$rowModelo['modelo'];?></td>
			    <td style="height:25px; padding:5px; border-bottom:1px solid #999; text-align:right;"><?=$rowModelo['Filas'];?>&nbsp;</td>
			    <td style="height:25px; padding:5px; border-bottom:1px solid #999;">&nbsp;<a href="#" style="text-decoration:none;color:blue;" onclick="verResumen('<?=$status;?>','<?=$rowModelo['idModeloRadio'];?>','proceso')">Ver M&aacute;s</a></td>
	             </tr>
<?			
		     }
?>
	      </table><br />
<?		
	      }
       }
	
	public function resumenStatus($mes,$anio,$diaActual){
	      include("../../includes/conectarbase.php");
		$totalDias=$this->UltimoDia($anio,$mes);
		//$totalDias=UltimoDia($anio,$mes);
		$fecha1=$anio."-".$mes."-01";
		$fecha2=$anio."-".$mes."-".$totalDias;
	
		$sqlTotalEquipos="SELECT COUNT( * ) AS `Filas` , `statusProceso`
		FROM `equipos`
		GROUP BY `statusProceso`
		ORDER BY `statusProceso` ";
		$resTotalEquipos=mysql_query($sqlTotalEquipos,$this->conexionBd());
//		$rowTotalEquipos=mysql_fetch_array($resTotalEquipos);
?>
		<table border="0" width="98%" cellpadding="1" cellspacing="1" style="margin:4px;">
        	<tr>
            	<td colspan="5">&nbsp;</td>
	      </tr>
			<tr>
            	<td colspan="5">Resumen en el Sistema:</td>
            </tr>
<?
		$i=0;
		$cuentaTotalResumenProceso=0;
		while($rowTotalEquipos=mysql_fetch_array($resTotalEquipos)){
			$idDiv="divDetalle".$i;
			$cuentaTotalResumenProceso+=$rowTotalEquipos["Filas"];
?>
			<tr>
		         	<td width="49%" style="height:25px;border:1px solid #999; background:#ccc; text-align:left;"><?=$rowTotalEquipos['statusProceso'];?>: </td>
			        <td width="9%" style="height:25px;border:1px solid #999; background:#ccc; text-align:center;"><a href="#" style="text-decoration:none;color:blue;" title="Detalle por modelo" onclick="verResumenStatusProceso('<?=$rowTotalEquipos['statusProceso'];?>','<?=$idDiv;?>')">[ + ]</a></td>
				<td width="42%" style="height:25px; border:1px solid #CCC; text-align:right;"><a href="#" style="text-decoration:none;color:blue;" onclick="verResumen('<?=$rowTotalEquipos['statusProceso'];?>','S/M','proceso')"><?=$rowTotalEquipos['Filas'];?></a>&nbsp;</td>                
		        </tr>
			<tr>
				<td colspan="5"><div id="<?=$idDiv;?>" style="background:#f0f0f0;"></div></td>
			</tr>
<?		
			$i+=1;
		}
?>			
		     <tr>
			    <td colspan="2" style="height:25px;border:1px solid #999; background:#ccc; text-align:left; font-weight:bold;font-size:14px;">Total</td>
			    <td style="height:25px; border:1px solid #CCC; text-align:right; font-weight:bold;font-size:14px;"><?=$cuentaTotalResumenProceso;?></td>
		     </tr>
		</table>
<?		
	}
	
	public function verResumenLoteModelo($lote,$modelo){
		$sqlResumen="SELECT * FROM equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where lote='".$lote."' AND equipos.id_modelo='".$modelo."'";
		$resResumen=mysql_query($sqlResumen,$this->conexionBd());
		if(mysql_num_rows($resResumen)==0){
			echo "Sin resultados";
		}else{
?>
		<table border="0" cellpadding="1" cellspacing="1" width="90%" style="margin:5px; margin-left:10px; border:1px solid #CCC;">
        	<tr>
                <td colspan="8">&nbsp;</td>
            </tr>
            <tr>
                <td width="5%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">&nbsp;</td>
                <td width="7%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Modelo</td>
                <td width="17%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Imei</td>
                <td width="18%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Serial</td>
                <td width="24%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">MFGDATE</td>
                <td width="9%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Lote</td>
                <td width="12%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">F_Recibo</td>
                <td width="8%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Mov.</td>					
            </tr>
<?
			$color="#E1E1E1";
			while($rowEquipos=mysql_fetch_array($resResumen)){
?>
			<tr>
                <td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;">&nbsp;</td>
                <td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['modelo'];?></td>
                <td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['imei'];?></td>
                <td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['serial'];?></td>					
                <td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['mfgdate'];?></td>
                <td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['lote'];?></td>
                <td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['f_recibo'];?></td>
                <td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['num_movimiento'];?></td>
            </tr>
<?			
			    ($color=="#E1E1E1") ? $color="#FFF" : $color="#E1E1E1";
			}
?>
        </table>
<?		
		}
	}
	
	public function mostrarLotesDetalle($lote){
		$sqlLote="SELECT COUNT(*) AS `Filas`, modelo,equipos.id_modelo as idModelo FROM equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo WHERE lote='".$lote."' GROUP BY equipos.id_modelo ORDER BY equipos.id_modelo";
		$resLote=mysql_query($sqlLote,$this->conexionBd());
		if(mysql_num_rows($resLote)==0){
			echo "Sin resultados.";
		}else{
			$sqlTotal="SELECT count( * ) as total FROM equipos WHERE lote = '".$lote."' ";
			$resTotal=mysql_query($sqlTotal,$this->conexionBd());
			$rowTotal=mysql_fetch_array($resTotal);
?>
			<table width="241" border="0" cellpadding="0" cellspacing="0" style="margin-left:30px; font-size:10px; background:#FFF;">
            	<tr>
                	<td width="85" style="height:25px; padding:5px;background:#000; color:#FFF; text-align:center;">Modelo</td>
                    <td width="81" style="height:25px; padding:5px;background:#000; color:#FFF; text-align:center;">Filas</td>
                    <td width="75" style="height:25px; padding:5px;background:#000; color:#FFF; text-align:center;">&nbsp;</td>
                </tr>
<?
			while($rowLote=mysql_fetch_array($resLote)){
?>
				<tr>
                	<td style="height:25px; padding:5px; border-bottom:1px solid #999; text-align:center;"><?=$rowLote['modelo'];?></td>
                    <td style="height:25px; padding:5px; border-bottom:1px solid #999; text-align:center;"><?=$rowLote['Filas'];?></td>
                    <td style="height:25px; padding:5px; border-bottom:1px solid #999; text-align:center;"><a href="#" style="text-decoration:none;color:blue;" onclick="verResumenLoteModelo('<?=$lote;?>','<?=$rowLote['idModelo'];?>')" title="Ver Resumen" style="color:#06F;">Resumen</a></td>
                </tr>
<?			
			}
?>
            	<tr>
					<td width="85" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center; height:25px; font-weight:bold;">Total</td>
					<td width="81" style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center; height:25px; font-weight:bold; font-size:14px;"><?=$rowTotal['total'];?></td>
                    <td style="background:#CCCCCC; color:#000000; border-bottom:1px solid #000000; text-align:center; height:25px; font-weight:bold; font-size:14px;">&nbsp;</td>
				</tr>
            </table>
<?			
		}
	}

	public function mostrarLotes(){
		$sqlLote="SELECT COUNT( * ) AS `Filas` , `lote` FROM `equipos` GROUP BY `lote` ORDER BY `lote` DESC";
		$resLote=mysql_query($sqlLote,$this->conexionBd());
		if(mysql_num_rows($resLote)==0){
			echo "<br>Sin Resultados.";
		}else{
?>
			<div style=" margin:10px;background:#f0f0f0; border:1px solid #CCC;">
            	<div style="margin:10px; font-weight:bold; font-size:12px;">Lotes en el sistema:</div>
<?
				$i=0;
				while($rowLote=mysql_fetch_array($resLote)){
					$nombreDiv="div".$i;
?>
            		<p style="margin:10px; font-size:10px; font-weight:bold;">&raquo;&raquo;<a href="#" style="text-decoration:none;color:blue;" title="Ver Resumen del Lote" onclick="verDetalleLote('<?=$rowLote['lote']?>','<?=$nombreDiv;?>')" style="color:#06F;"> <?=$rowLote['lote']?> </a></p>
                    <div id="<?=$nombreDiv;?>" style="display:none;"></div>
<?
					$i+=1;
				}
?>
            </div>
<?			
		}
	}
	
	public function mostrarResumenModeloStatus($status,$div){
	    if($status != "WIP" && $status != 'Total'){
		  $sqlModelo="SELECT COUNT( * ) AS `Filas` , modelo,equipos.id_modelo as idModeloRadio
					FROM equipos INNER JOIN cat_modradio ON equipos.id_modelo = cat_modradio.id_modelo
					WHERE STATUS = '".$status."'
					GROUP BY equipos.id_modelo
					ORDER BY `Filas` ASC";
	    }else if($status=="WIP"){
		  $sqlModelo="SELECT COUNT( * ) AS `Filas` , modelo,equipos.id_modelo as idModeloRadio
					FROM equipos INNER JOIN cat_modradio ON equipos.id_modelo = cat_modradio.id_modelo
					WHERE STATUS in ('WIP','Validando','Retencion')
					GROUP BY equipos.id_modelo
					ORDER BY `Filas` ASC";
	    }else if($status=="Total"){
		  $sqlModelo="SELECT COUNT( * ) AS `Filas` , modelo,equipos.id_modelo as idModeloRadio
					FROM equipos INNER JOIN cat_modradio ON equipos.id_modelo = cat_modradio.id_modelo
					GROUP BY equipos.id_modelo
					ORDER BY `Filas` ASC";
	    }
		//echo $sqlModelo;
		$resModelo=mysql_query($sqlModelo,$this->conexionBd());
		if(mysql_num_rows($resModelo)==0){
			echo "<br>Sin Registros.";
		}else{
?>
			<br />
            <table border="0" cellpadding="1" cellspacing="1" width="95%" style="margin:5px; background:#FFF;">
            	<tr>
					<td colspan="3" style="text-align:right;"><a href="#" onclick="cerrarDiv('<?=$div;?>')">Cerrar Info</a></td>
				</tr>
				<tr>
                	<td colspan="3" style="font-size:12px; height:25px; padding:5px;">Resumen por Modelo para el status <?=$status;?></td>
                </tr>
                <tr>
                	<td width="30%" style="height:25px; padding:5px;background:#000; color:#FFF;">Modelo</td>
                    <td width="21%" style="background:#000; color:#FFF;"># Registros</td>
					<td width="49%" style="background:#000; color:#FFF;">&nbsp;</td>
                </tr>
<?
			while($rowModelo=mysql_fetch_array($resModelo)){
?>
				<tr>
                	<td style="height:25px; padding:5px; border-bottom:1px solid #999; text-align:center;">&nbsp;<?=$rowModelo['modelo'];?></td>
                    <td style="height:25px; padding:5px; border-bottom:1px solid #999; text-align:right;"><?=$rowModelo['Filas'];?>&nbsp;</td>
					<td style="height:25px; padding:5px; border-bottom:1px solid #999;">&nbsp;<a href="#" style="text-decoration:none;color:blue;" onclick="verResumen('<?=$status;?>','<?=$rowModelo['idModeloRadio'];?>','status')">Ver M&aacute;s</a></td>
                </tr>
<?			
			}
?>
            </table><br />
<?		
		}
	}
	
	public function mostrarResumen($status,$modelo,$tipo){
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
		if($tipo=="status"){
		     $campoStatus="status";
		}else if($tipo=="proceso"){
		     $campoStatus="statusProceso";
		}
		if($status != "WIP"  && $status != 'Total'){
		  if($modelo=="S/M"){
			  $sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where ".$campoStatus."='".$status."' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			  $sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where ".$campoStatus."='".$status."'";
		  }else{
			  $sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where ".$campoStatus."='".$status."' and equipos.id_modelo='".$modelo."' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			  $sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where ".$campoStatus."='".$status."' and equipos.id_modelo='".$modelo."'";
		  }
		}else if($status=="WIP"){
		  if($modelo=="S/M"){
			  $sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where status in ('WIP','Validando','Retencion') LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			  $sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where status in ('WIP','Validando','Retencion')";
		  }else{
			  $sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where status in ('WIP','Validando','Retencion') and equipos.id_modelo='".$modelo."' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			  $sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where status in ('WIP','Validando','Retencion') and equipos.id_modelo='".$modelo."'";
		  }
		}else if($status=="Total"){
		  if($modelo=="S/M"){
			  $sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			  $sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo";
		  }else{
			  $sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where equipos.id_modelo='".$modelo."' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			  $sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where equipos.id_modelo='".$modelo."'";
		  }
		}
		//echo $sqlEquipos;
		
		$rs=mysql_query($sqlEquipos,$this->conexionBd());
		$rs1=mysql_query($sqlEquipos1,$this->conexionBd());
		
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
			<table border="0" cellpadding="1" cellspacing="1" width="98%" style="margin:5px; margin-left:10px; border:1px solid #CCC;">
				<tr>
					<td colspan="7" style="font-size:12px; font-weight:bold;">Mostrando equipos filtrados por: <?=$status;?></td>
				</tr>
				<tr>
					<td colspan="7">Total de Resultados <?=$NroRegistros;?></td>
				</tr>
                <tr>
                	<td colspan="7">			
            <div style="text-align:center; height:10px; padding:5px;">
<?                    
			//desplazamiento
?>
				   <a href="javascript:Pagina('1','<?=$status;?>','<?=$modelo;?>','<?=$tipo;?>')" title="Primero" style="cursor:pointer; text-decoration:none;">|&lt;</a>&nbsp;
<?
			 if($PagAct>1){ 
?>
					 <a href="javascript:Pagina('<?=$PagAnt;?>','<?=$status;?>','<?=$modelo;?>','<?=$tipo;?>')" title="Anterior" style="cursor:pointer; text-decoration:none;">&lt;&lt;</a>&nbsp;
<?
			  }
			 echo "<strong>".$PagAct."/".$PagUlt."</strong>";
			 if($PagAct<$PagUlt){
?>
				  <a href="javascript:Pagina('<?=$PagSig;?>','<?=$status;?>','<?=$modelo;?>','<?=$tipo;?>')" title="Siguiente" style="cursor:pointer; text-decoration:none;">&gt;&gt;</a>&nbsp;
<?
			 }
?>     
				  <a href="javascript:Pagina('<?=$PagUlt;?>','<?=$status;?>','<?=$modelo;?>','<?=$tipo;?>')" title="Ultimo" style="cursor:pointer; text-decoration:none;">&gt;|</a>&nbsp;        
             </div>                           
                    </td>
                </tr>
				<tr>
					<td width="5%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Folio</td>
					<td width="5%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Modelo</td>
					<td width="17%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Imei</td>
					<td width="15%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Serial</td>
					<td width="10%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Programa</td>					
					<td width="15%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Status</td>
					<td width="15%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">Status Proceso</td>
					<td width="18%" style="background:#000; color:#FFF; height:30px; padding:4px; text-align:center;">MFGDATE</td>
				</tr>
<?
			$color="#E1E1E1";
			while($rowEquipos=mysql_fetch_array($rs)){
?>
				<tr>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['lote'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['modelo'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['imei'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=strtoupper($rowEquipos['serial']);?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['facturar'];?></td>					
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['status'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; border-right:1px solid #CCC; text-align:center;"><?=$rowEquipos['statusProceso'];?></td>
					<td style="background:<?=$color;?>;height:25px; padding:4px; border-bottom:1px solid #CCC; text-align:center;"><?=$rowEquipos['mfgdate'];?></td>
				</tr>
<?			
			    ($color=="#E1E1E1") ? $color="#FFF" : $color="#E1E1E1";
			}
?>				
			</table>
<?		
		}
	}
	
	public function resumen($mes,$anio,$diaActual){
	      include("../../includes/conectarbase.php");
	      $totalDias=$this->UltimoDia($anio,$mes);
	      //$totalDias=UltimoDia($anio,$mes);
	      $fecha1=$anio."-".$mes."-01";
	      $fecha2=$anio."-".$mes."-".$totalDias;
	
	      $sqlTotalEquipos="SELECT COUNT( * ) AS `Filas` , `status`
	      FROM `equipos`
	      GROUP BY `status`
	      ORDER BY `status` ";
	      /////////////////////////
	      $sqlTotal="SELECT COUNT(*) as total FROM equipos";
	      $restotal=mysql_query($sqlTotal,$this->conexionBd());
	      $rowTotal=mysql_fetch_array($restotal);
	      /*equipos enviados*/
	      $sqlEnviados="SELECT COUNT(*) AS enviados FROM equipos WHERE status='ENVIADO'";
	      $resEnviados=mysql_query($sqlEnviados,$this->conexionBd());
	      $rowEnviados=mysql_fetch_array($resEnviados);
	      /*equipos en proceso*/
	      $sqlProceso="SELECT COUNT(*) AS proceso FROM equipos WHERE status in ('WIP','Validando','Retencion')";
	      $resProceso=mysql_query($sqlProceso,$this->conexionBd());
	      $rowProceso=mysql_fetch_array($resProceso);
	      /*equipos scrap*/
	      $sqlScrap="SELECT COUNT(*) AS scrap FROM equipos WHERE status ='SCRAP'";
	      $resScrap=mysql_query($sqlScrap,$this->conexionBd());
	      $rowScrap=mysql_fetch_array($resScrap);
	      //scrap enviado
	      $sqlScrapE="SELECT COUNT(*) AS scrapEnviado FROM equipos WHERE status ='SCRAP ENVIADO'";
	      $resScrapE=mysql_query($sqlScrapE,$this->conexionBd());
	      $rowScrapE=mysql_fetch_array($resScrapE);
?>
	    <table border="0" width="90%" cellpadding="1" cellspacing="1" style="margin:4px;">
		  <tr>
			<td colspan="5">&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan="5">
			      <input type="button" value="Imprimir" style="border:1px solid #CCC;background:#f0f0f0;">&nbsp;&nbsp;<input type="button" value="Exportar" style="border:1px solid #CCC;background:#f0f0f0;">
			</td>
		  </tr>
		  <tr>
			<td colspan="5">&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan="5" style="font-size:12px;font-weight:bold;">Reporte ejecutivo Nextel:</td>
		  </tr>
		  <tr>
			<td colspan="5"><hr style="background:#CCC;"></td>
		  </tr>
		  <tr>
			<td width="45%" style="height:25px;border:1px solid #999; background:#ccc; text-align:left;">Inventario total: </td>
			<td width="20%" style="height:25px;border:1px solid #999; background:#ccc; text-align:center;"><a href="#" style="text-decoration:none;color:blue;" title="Detalle por modelo" onclick="verResumenstatus('Total','divInventarioTotal')">[ + ]</a></td>
			<td width="30%" style="height:25px; border:1px solid #CCC; text-align:right;"><a href="#" style="text-decoration:none;color:blue;" onclick="verResumen('Total','S/M','status')"><?=$rowTotal['total'];?></a>&nbsp;</td>                
		  </tr>
		  <tr>
			<td colspan="5"><div id="divInventarioTotal" style="background:#f0f0f0;"></div></td>
		  </tr>
		  <tr>
			<td width="45%" style="height:25px;border:1px solid #999; background:#ccc; text-align:left;">Entregas total: </td>
			<td width="20%" style="height:25px;border:1px solid #999; background:#ccc; text-align:center;"><a href="#" style="text-decoration:none;color:blue;" title="Detalle por modelo" onclick="verResumenstatus('ENVIADO','entregasTotal')">[ + ]</a></td>
			<td width="30%" style="height:25px; border:1px solid #CCC; text-align:right;"><a href="#" style="text-decoration:none;color:blue;" onclick="verResumen('ENVIADO','S/M','status')"><?=$rowEnviados['enviados'];?></a>&nbsp;</td>                
		  </tr>
		  <tr>
			<td colspan="5"><div id="entregasTotal" style="background:#f0f0f0;"></div></td>
		  </tr>
		  <tr>
			<td width="45%" style="height:25px;border:1px solid #999; background:#ccc; text-align:left;">En Proceso: </td>
			<td width="20%" style="height:25px;border:1px solid #999; background:#ccc; text-align:center;"><a href="#" style="text-decoration:none;color:blue;" title="Detalle por modelo" onclick="verResumenstatus('WIP','procesoTotal')">[ + ]</a></td>
			<td width="30%" style="height:25px; border:1px solid #CCC; text-align:right;"><a href="#" style="text-decoration:none;color:blue;" onclick="verResumen('WIP','S/M','status')"><?=$rowProceso['proceso'];?></a>&nbsp;</td>                
		  </tr>
		  <tr>
			<td colspan="5"><div id="procesoTotal" style="background:#f0f0f0;"></div></td>
		  </tr>
		  <tr>
			<td width="45%" style="height:25px;border:1px solid #999; background:#ccc; text-align:left;">Scrap Total: </td>
			<td width="20%" style="height:25px;border:1px solid #999; background:#ccc; text-align:center;"><a href="#" style="text-decoration:none;color:blue;" title="Detalle por modelo" onclick="verResumenstatus('SCRAP','scrapTotal')">[ + ]</a></td>
			<td width="30%" style="height:25px; border:1px solid #CCC; text-align:right;"><a href="#" style="text-decoration:none;color:blue;" onclick="verResumen('SCRAP','S/M','status')"><?=$rowScrap['scrap'];?></a>&nbsp;</td>                
		  </tr>
		  <tr>
			<td width="45%" style="height:25px;border:1px solid #999; background:#ccc; text-align:left;">Scrap Enviado: </td>
			<td width="20%" style="height:25px;border:1px solid #999; background:#ccc; text-align:center;"><a href="#" style="text-decoration:none;color:blue;" title="Detalle por modelo" onclick="verResumenstatus('SCRAP ENVIADO','scrapTotal')">[ + ]</a></td>
			<td width="30%" style="height:25px; border:1px solid #CCC; text-align:right;"><a href="#" style="text-decoration:none;color:blue;" onclick="verResumen('SCRAP ENVIADO','S/M','status')"><?=$rowScrapE['scrapEnviado'];?></a>&nbsp;</td>                
		  </tr>
		  <tr>
			<td colspan="5"><div id="scrapTotal" style="background:#f0f0f0;"></div></td>
		  </tr>
	    </table>
<?
	      
	      
	      
	}
	
	
	public function calendarizacion($mes,$anio,$diaActual){		
		$mes=$mes;//date("m");
		//año de la fecha
		$anio=$anio;
		//total de dias en el mes
		$totalDias=$this->UltimoDia($anio,$mes);
		//$totalDias=UltimoDia($anio,$mes);
		$numeroDia=date("w", mktime (0,0,0,$mes,1,$anio));//mes dia año
		$diaFecha=date("j", mktime (0,0,0,$mes,1,$anio));//mes dia año
		
		$dia=1;
		/*for($i=0;$i<6;$i++){
			for($j=0;$j<7;$j++){
				if($numeroDia==$j){
					echo date("j", mktime (0,0,0,$mes,$dia,$anio));
					$numeroDia+=1;
					$dia+=1;
				}else{
					echo "x";
				}
				
			}
			$numeroDia=0;
			echo "<br>";
		}
		$dia=1;*/
		$meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
?>
		<table width="99%" border="0" cellspacing="0" cellpadding="1" style="font-size:12px; margin-left:5px; margin-top:5px; margin-right:5px;">
			<tr>
            	<td colspan="7" style="font-weight:bold;">&nbsp;</td>
            </tr>
            <tr>
            	<td colspan="7" style="font-size:16px; text-align:center;"><?=$meses[date("n")-1];?></td>
            </tr>
            <tr>
	  			<td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Domingo</td>
				<td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Lunes</td>
                <td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Martes</td>
                <td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Miercoles</td>
                <td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Jueves</td>
                <td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Viernes</td>
                <td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">S&aacute;bado</td>
	  		</tr>
<?
			//se hace el recorrido por las semanas
			for($i=0;$i<6;$i++){
?>
			<tr>
<?				
				//se hace el recorrido por los dias de la semana
				for($j=0;$j<7;$j++){
					if($numeroDia==$j){
						$diaMes=date("j", mktime (0,0,0,$mes,$dia,$anio));
						($diaMes==$diaActual) ? $clase="diaCalendarioActual" : $clase="diaCalendario";
						
?>						
						<td valign="middle" style="height:60px; text-align:center;">
                        	<a href="vistaPagos.php?fecha=<?=$anio."-".$mes."-".$diaMes;?>&idEmp=<?=$emp;?>" target="_blank" style="text-decoration:none;"><div class="<?=$clase;?>"><?=$diaMes;?></div></a>
                        </td>
<?
						$numeroDia+=1;
						$dia+=1;
					}else{
?>
					<td><div class="diaCalendario">&nbsp;</div></td>
<?						
					}
					//se detiene el proceso en caso que sea igual al numero de dias
					if($diaMes==$totalDias)
						break;
				}
				$numeroDia=0;				
?>
  </tr>
<?
             //se detiene el proceso en caso que sea igual al numero de dias
						if($diaMes==$totalDias)
							break;
			}
			$dia=1;		
			
?>
        	</tr>
</table>    
<?
	}
	
	public function UltimoDia($anho,$mes){ 
	   if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
		   $dias_febrero = 29; 
	   } else { 
		   $dias_febrero = 28; 
	   } 
	   if(($mes==1) || ($mes==3) || ($mes==5) || ($mes==7) || ($mes==8) || ($mes==10) || ($mes==12)){
		   $dias_mes="31";
	   }else if(($mes==4) ||($mes==6) ||($mes==9) ||($mes==11)){
		   $dias_mes="30";
	   }else if($mes==2){
		   $dias_mes=$dias_febrero;
	   }
	   return $dias_mes;
	}
	
	private function conexionBd(){
		include("../../includes/config.inc.php");
		$conn = new Conexion();
		$conexion = $conn->getConexion($host,$usuario,$pass,$db);
		return $conexion;
	}
}//cierra clase nextel
?>