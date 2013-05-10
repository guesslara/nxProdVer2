<?php
if($_POST["action"]=="buscar_nds_xxx"){
            print_r($_POST);
            $link=conectarBd();
	    $m_indices=explode(",",$_POST["m_indices"]);
	    $m_data=explode(",",$_POST["data"]);
?>
		<script language="javascript">
			crear_tabla(); 
			$("#tbl_01").show();			
		</script>
<?
            for($i=0;$i<count($m_data);$i++){
			//echo $m_data[$i]."<br>";
			$sql="select id_radio,modelo,imei,serial,sim,lote,status,status_nextel,statusProceso,statusDesensamble,statusDiagnostico,statusAlmacen,statusIngenieria,statusEmpaque,lineaEnsamble,facturar 
			from equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo 
			where imei='".$m_data[$i]."'";			
			$res=mysql_query($sql,$link);
			if(mysql_num_rows($res) != 0){
				//se encontro en la tabla de equipos
				while($reg=mysql_fetch_array($res)){
					$sqlNoEnviar="select * from equipos_no_enviar where imei='".$reg["imei"]."'";
					$resNoEnviar=mysql_query($sqlNoEnviar,$link);
					$rowNoEnviar=mysql_fetch_array($resNoEnviar);
					if($rowNoEnviar['imei']==""){
						$noEnviar="Verificado";
					}else{
						$noEnviar="No Enviar";
					}
?>
					<script language="javascript">
					agregar_filaX('1','<?=$reg["id_radio"]?>','<?=$reg["modelo"]?>','<?=$reg["imei"]?>','<?=strtoupper($reg["serial"])?>','<?=$reg["sim"]?>','<?=$reg["lote"]?>','<?=$reg["status"]?>','<?=$noEnviar?>','<?=$reg["statusProceso"]?>','<?=$reg["statusDesensamble"]?>','<?=$reg["statusDiagnostico"]?>','<?=$reg["statusAlmacen"]?>','<?=$reg["statusIngenieria"]?>','<?=$reg["statusEmpaque"]?>','<?=$reg["lineaEnsamble"]?>','<?=$reg["facturar"]?>');
					</script>
<?php
				}				
			}else{
				//buscar en ENVIADOS
				$sqlEnviados="select id_radio,modelo,imei,serial,sim,lote,status,status_nextel,statusProceso,statusDesensamble,statusDiagnostico,statusAlmacen,statusIngenieria,statusEmpaque,lineaEnsamble,facturar 
				from equipos_enviados inner join cat_modradio on equipos_enviados.id_modelo=cat_modradio.id_modelo 
				where imei='".$m_data[$i]."'";
				$resEnviados=mysql_query($sqlEnviados,$link);
				if(mysql_num_rows($resEnviados)==0){
					//echo "no encontrado";
?>
					<script language="javascript">
					//agregar_filaX('N/A','N/A','<?=$m_data[$i];?>','N/A','N/A','N/A','N/A','N/A','N/A','N/A','N/A','N/A','N/A','N/A','N/A');
					$("#eqNoEncontrado").append("<div style='margin:5px;'>"+<?=$m_data[$i];?>+"</div>");
					</script>
<?					
					//echo "<br> ".$m_data[$i]." NO EXISTE";	
				}else{
					while($rowEnviados=mysql_fetch_array($resEnviados)){
						$sqlNoEnviar="select * from equipos_no_enviar where imei='".$reg["imei"]."'";
						$resNoEnviar=mysql_query($sqlNoEnviar,$link);
						$rowNoEnviar=mysql_fetch_array($resNoEnviar);
						if($rowNoEnviar['imei']==""){
							$noEnviar="Verificado";
						}else{
							$noEnviar="No Enviar";
						}
?>
					<script language="javascript">
					agregar_filaX('2','<?=$rowEnviados["id_radio"]?>','<?=$rowEnviados["modelo"]?>','<?=$rowEnviados["imei"]?>','<?=strtoupper($rowEnviados["serial"])?>','<?=$rowEnviados["sim"]?>','<?=$rowEnviados["lote"]?>','<?=$rowEnviados["status"]?>','<?=$noEnviar?>','<?=$rowEnviados["statusProceso"]?>','<?=$rowEnviados["statusDesensamble"]?>','<?=$rowEnviados["statusDiagnostico"]?>','<?=$rowEnviados["statusAlmacen"]?>','<?=$rowEnviados["statusIngenieria"]?>','<?=$rowEnviados["statusEmpaque"]?>','<?=$rowEnviados["lineaEnsamble"]?>','<?=$reg["facturar"]?>');
					</script>
<?
					}
				}				
			}
			/*********************/
		}
        }
	
	
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
?>