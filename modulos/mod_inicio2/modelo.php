<?php
      include("../../clases/conexion/conexion.php");
      
      class modeloNextel{

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
	    $sqlTotalEquipos="SELECT COUNT( * ) AS `Filas` , `statusProceso` FROM `equipos` GROUP BY `statusProceso` ORDER BY `statusProceso` ";
	    $resTotalEquipos=mysql_query($sqlTotalEquipos,$this->conexionBd());
?>
	    <script>
		  $(function(){
			$('table').visualize({type: 'pie', height: '250px', width: '250px',parseDirection:'x',appendTitle:false,appendKey:true,pieMargin:30,pieLabelPos:'inside',yLabelInterval:40,lineWeight:6,barMargin: 5,colors:['#be1e2d','#666699','#92d5ea','#ee8310','#8d10ee','#5a3b16','#26a4ed','#f45a90','#e9e744','#969DFE','#BCFE96','#FF6262','#9C8FA6']});
		  });  
	    </script>
	    <table border="0" width="98%" cellpadding="1" cellspacing="1" style="margin:4px;">
		  <caption>Resumen por Proceso</caption>		     
			<thead>
			      <tr>
				    <td></td>
				    <th scope="col">Total</th>				      
			      </tr>
			</thead>
		  <tbody>
<?
		$i=0;
		$cuentaTotalResumenProceso=0;
		while($rowTotalEquipos=mysql_fetch_array($resTotalEquipos)){
			$idDiv="divDetalle".$i;
			$cuentaTotalResumenProceso+=$rowTotalEquipos["Filas"];
?>
			<!--<tr>
		         	<td width="49%" style="height:25px;border:1px solid #999; background:#ccc; text-align:left;"><?=$rowTotalEquipos['statusProceso'];?>: </td>
			        <td width="9%" style="height:25px;border:1px solid #999; background:#ccc; text-align:center;"><a href="#" style="text-decoration:none;color:blue;" title="Detalle por modelo" onclick="verResumenStatusProceso('<?=$rowTotalEquipos['statusProceso'];?>','<?=$idDiv;?>')">[ + ]</a></td>
				<td width="42%" style="height:25px; border:1px solid #CCC; text-align:right;"><a href="#" style="text-decoration:none;color:blue;" onclick="verResumen('<?=$rowTotalEquipos['statusProceso'];?>','S/M','proceso')"><?=$rowTotalEquipos['Filas'];?></a>&nbsp;</td>                
		        </tr>
			<tr>
				<td colspan="5"><div id="<?=$idDiv;?>" style="background:#f0f0f0;"></div></td>
			</tr>-->
			<tr>
			      <th scope="row" style="text-align: left;height: 15px;padding: 4px;"><?=$rowTotalEquipos['statusProceso'];?> </th>			    
			      <td><?=$rowTotalEquipos['Filas'];?></td>
			</tr>
			<!--<tr>
			      <td colspan="5"><div id="<?=$idDiv;?>" style="background:#f0f0f0;"></div></td>
			</tr>-->
<?		
			$i+=1;
		}
?>			
		  <!--<tr>
			<td colspan="2" style="height:25px;border:1px solid #999; background:#ccc; text-align:left; font-weight:bold;font-size:14px;">Total</td>
			<td style="height:25px; border:1px solid #CCC; text-align:right; font-weight:bold;font-size:14px;"><?=$cuentaTotalResumenProceso;?></td>
		  </tr>-->
		  </tbody>
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
		$sqlModelo="SELECT COUNT( * ) AS `Filas` , modelo,equipos.id_modelo as idModeloRadio
					FROM equipos INNER JOIN cat_modradio ON equipos.id_modelo = cat_modradio.id_modelo
					WHERE STATUS = '".$status."'
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
		if($modelo=="S/M"){
			$sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where ".$campoStatus."='".$status."' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			$sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where ".$campoStatus."='".$status."'";
		}else{
			$sqlEquipos="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where ".$campoStatus."='".$status."' and equipos.id_modelo='".$modelo."' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			$sqlEquipos1="select * from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo where ".$campoStatus."='".$status."' and equipos.id_modelo='".$modelo."'";
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
	
      public function filtrarModeloStatus($status,$div){
	    //se filtran los status disponibles
	    $sqlStatusWip="SELECT status FROM equipos GROUP BY status";
	    $resStatusWip=mysql_query($sqlStatusWip,$this->conexionBd());
	    $rowStatusWip=mysql_fetch_array($resStatusWip);
	    //if($status=="SCRAP" || $status=="WIP" || $status=="RETENCION" || $status=="RETENCION2" || $status=="SCRAP POR ENVIAR"){
	    if(in_array($status,$rowStatusWip)){  
		$sqlModelo="SELECT COUNT( * ) AS `Filas` , modelo,equipos.id_modelo as idModeloRadio
			FROM equipos INNER JOIN cat_modradio ON equipos.id_modelo = cat_modradio.id_modelo
			WHERE STATUS = '".$status."'
			GROUP BY equipos.id_modelo
			ORDER BY `Filas` ASC";  
	    }else{		  
		  $sqlModelo="SELECT COUNT( * ) AS `Filas` , modelo,equipos_enviados.id_modelo as idModeloRadio
			FROM equipos_enviados INNER JOIN cat_modradio ON equipos_enviados.id_modelo = cat_modradio.id_modelo
			WHERE STATUS = '".$status."'
			GROUP BY equipos_enviados.id_modelo
			ORDER BY `Filas` ASC";
	    }
	    echo "<br>".$sqlModelo;
	    $resModelo=mysql_query($sqlModelo,$this->conexionBd());
	    if(mysql_num_rows($resModelo)==0){
		  echo "<br>Sin Registros.";
	    }else{
		  //echo "";
?>		  
		  <div style="height: 20px;padding: 5px;text-align: right;"><a href="#" style="color:blue;" onclick="cerrarDiv('<?=$div;?>')">Cerrar</a></div>
		  <div style="width: 210px;height: 25px;border: 0px solid #000;overflow: hidden;">
			<div style="float: left;border: 1px solid #FFF; width: 72px;padding: 3px;background:#000; color:#FFF;text-align: center;">Modelo</div>
			<div style="float: left;border: 1px solid #FFF; width: 72px;padding: 3px;background:#000; color:#FFF;text-align: center;">#Registros</div>
			<div style="float: left;border: 1px solid #FFF; width: 38px;padding: 3px;background:#000; color:#FFF;">&nbsp;</div>
		  </div><div style="margin-top: 3px;"></div>
<?
			while($rowModelo=mysql_fetch_array($resModelo)){
?>
		  <div style="width: 210px;height: 25px;border: 0px solid #f0f0f0;overflow: hidden;margin-bottom: 4px;">
			<div style="float: left;border-bottom: 1px solid #CCC; width: 72px;padding: 3px;background:#FFF; color:#000;text-align: center;"><?=$rowModelo['modelo'];?></div>
			<div style="float: left;border-bottom: 1px solid #CCC; width: 72px;padding: 3px;background:#FFF; color:#000;text-align: center;"><?=$rowModelo['Filas'];?></div>
			<div style="float: left;border-bottom: 1px solid #CCC; width: 38px;padding: 3px;background:#FFF; color:#000;"><a href="#" style="text-decoration:none;color:blue;" onclick="verResumen('<?=$status;?>','<?=$rowModelo['idModeloRadio'];?>','status')">Ver</a></div>
		  </div><div style="clear:both;"></div>			
<?			
			}
		}
      }
      
      public function resumen($mes,$anio,$diaActual){// SFR_002320
	    include("../../includes/conectarbase.php");
	    $totalDias=$this->UltimoDia($anio,$mes);	    
	    $fecha1=$anio."-".$mes."-01";
	    $fecha2=$anio."-".$mes."-".$totalDias;
	    $sqlTotalEquipos="SELECT COUNT( * ) AS `Filas` , `status` FROM `equipos` GROUP BY `status` ORDER BY `status` ";
	    $sqlTotalEquipos1="SELECT COUNT( * ) AS `Filas` , `status` FROM `equipos_enviados` GROUP BY `status` ORDER BY `status` ";
	    $resTotalEquipos=mysql_query($sqlTotalEquipos,$this->conexionBd());
	    $resTotalEquipos1=mysql_query($sqlTotalEquipos1,$this->conexionBd());	      
	    $i=0;
	    $cuentaTotalResumen=0; $nombresStatus="";
	    while($rowTotalEquipos=mysql_fetch_array($resTotalEquipos)){
		  $idDiv="divDetalle".$i;
		  $cuentaTotalResumen+=$rowTotalEquipos['Filas'];
		  $status=$rowTotalEquipos['status'];
		  $nombreDiv="div_".$i;
?>
		  <div class="btnOpcionesInicioSubBoton" onclick="verResumenStatus('<?=$status;?>','<?=$nombreDiv;?>')"><?=$status;?><div style='float:right;'><a href='#' style='color:blue;'><?=$rowTotalEquipos['Filas'];?></a></div></div>
		  <div id='<?=$nombreDiv;?>' class='divOpcionesResumen' style='display:none;'></div>
<?
		  $status+=$rowTotalEquipos['status'];
		  $i+=1;
	    }
	    while($rowTotalEquipos1=mysql_fetch_array($resTotalEquipos1)){
		  $idDiv="divDetalle".$i;
		  $cuentaTotalResumen1+=$rowTotalEquipos1['Filas'];
		  $status=$rowTotalEquipos1['status'];			
		  $nombreDiv="div_".$i;
?>
		  <div class="btnOpcionesInicioSubBoton" onclick="verResumenStatus('<?=$status;?>','<?=$nombreDiv;?>')"><?=$status;?><div style='float:right;'><a href='#' style='color:blue;'><?=$rowTotalEquipos1['Filas'];?></a></div></div><div id='<?=$nombreDiv;?>' class='divOpcionesResumen' style='display:none;'></div>			
<?		
		  $i+=1;
	    }
      }
      
      public function resumenPrueba($mes,$anio,$diaActual){
	    include("../../includes/conectarbase.php");
	    $totalDias=$this->UltimoDia($anio,$mes);
	    //$totalDias=UltimoDia($anio,$mes);
	    $fecha1=$anio."-".$mes."-01";
	    $fecha2=$anio."-".$mes."-".$totalDias;
	    $sqlTotalEquipos="SELECT COUNT( * ) AS `Filas` , `status` FROM `equipos` GROUP BY `status` ORDER BY `status` ";
	    $sqlTotalEquipos1="SELECT COUNT( * ) AS `Filas` , `status` FROM `equipos_enviados` GROUP BY `status` ORDER BY `status` ";
	    $resTotalEquipos=mysql_query($sqlTotalEquipos,$this->conexionBd());
	    $resTotalEquipos1=mysql_query($sqlTotalEquipos1,$this->conexionBd());
?>
	    <div style="height:60px;"></div>		  
<?
		$i=0;
		$cuentaTotalResumen=0; $nombresStatus="";
		while($rowTotalEquipos=mysql_fetch_array($resTotalEquipos)){
			$idDiv="divDetalle".$i;
			$cuentaTotalResumen+=$rowTotalEquipos['Filas'];
			$status=$rowTotalEquipos['status'];
			$nombreDiv="div_".$i;
?>
			<div class='btnOpcionesInicioSubBoton' onclick='verResumenStatus(\"<?=$status;?>\",\"<?=$nombreDiv;?>\")'><?=$status;?><div style='float:right;'><a href='#' style='color:blue;'><?=$rowTotalEquipos['Filas'];?></a></div></div><div id='<?=$nombreDiv;?>' class='divOpcionesResumen' style='display:none;'></div>
<?
			$status+=$rowTotalEquipos['status'];
			$i+=1;
		}
		while($rowTotalEquipos1=mysql_fetch_array($resTotalEquipos1)){
			$idDiv="divDetalle".$i;
			$cuentaTotalResumen1+=$rowTotalEquipos1['Filas'];
			$status=$rowTotalEquipos1['status'];			
			$nombreDiv="div_".$i;
?>
			<div class='btnOpcionesInicioSubBoton' onclick='verResumenStatus(\"<?=$status;?>\",\"<?=$nombreDiv;?>\")'><?=$status;?><div style='float:right;'><a href='#' style='color:blue;'><?=$rowTotalEquipos1['Filas'];?></a></div></div><div id='<?=$nombreDiv;?>' class='divOpcionesResumen' style='display:none;'></div>			
<?		
			$i+=1;
		}
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