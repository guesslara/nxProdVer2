<script language="javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<style type="text/css">
    .xl65{mso-style-parent:style0;mso-number-format:"\@";}
</style>
<?php
    
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
    
    function mover_equipos(){
	set_time_limit(0);
        //se extrae equipo por equipo
        $sql="select imei from equipos where status in ('ENVIADO','SCRAP ENVIADO')";
        $res=mysql_query($sql,conectarBd());
        if(mysql_num_rows($res)==0){
            echo "( 0 ) Sin Registros.";
        }else{
            while($row=mysql_fetch_array($res)){
                //se busca el imei en la tabla equipos_enviados
                $sqlCliente="select imei from equipos_enviados where imei='".$row["imei"]."'";
                $resCliente=mysql_query($sqlCliente,conectarBd());
                if(mysql_num_rows($resCliente)==0){                    
?>
                    <script type="text/javascript"> $("#datosIncorrectos").append("<div>"+<?=$row["imei"];?>+"</div>"); </script>
<?
                }else{
                    $rowCliente=mysql_fetch_array($resCliente);
                    //si se encontro se muestra en el div                    
?>
                    <script type="text/javascript"> $("#datosCorrectos").append("<div>"+<? echo $row["imei"];?>+"</div>"); </script>
<?
                }
            }
        }
    }
?>
<br>
<div style="float:left;margin-left:50px;">Imei's Actualizados</div><div style="float:left;margin-left:90px;">Imei's NO ENCONTRADOS</div><div style="float:left;margin-left:70px;">Imei's NO ACTUALIZADOS</div><div style="clear:both;">&nbsp;</div>
<div id="datosCorrectos" class="xl65" style="border:1px solid #FF0000;height:400px;width:250px;float:left;overflow:auto;"></div>
<div id="datosIncorrectos" style="border:1px solid #FF0000;height:400px;width:250px; margin-left:10px;float:left;overflow:auto;"></div>
<div id="datosNoActualizados" style="border:1px solid #FF0000;height:400px;width:250px; margin-left:10px;float:left;overflow:auto;"></div>
<? mover_equipos(); ?>