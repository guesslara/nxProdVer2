<script language="javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<style type="text/css">
    .xl65{mso-style-parent:style0;mso-number-format:"\@";}
</style>
<?
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
    
    function creaLoteConversion(){
        //se extrae la informacion de los modelos para hacer la separacion de los diferentes folios de conversion
        $sqlCliente="SELECT * FROM `archivo_Cliente` WHERE modelo IN ('U.i576S', 'U.i560Y', 'U.i880', 'U.i465')";
        $resCliente=mysql_query($sqlCliente,conectarBd());
        if(mysql_num_rows($resCliente)==0){
            echo "( 0 ) resultados.";
        }else{
            while($rowCliente=mysql_fetch_array($resCliente)){
                //se hace la consulta hacia la tabla equipos para actualizar el folio
                $sqlIq="SELECT * from equipos where imei='".$rowCliente["imei"]."'";
                $resIq=mysql_query($sqlIq,conectarBd());
                if(mysql_num_rows($resIq)==0){
?>
                    <script type="text/javascript"> $("#datosIncorrectos").append("<div>"+<?=$rowCliente["imei"];?>+"</div>"); </script>
<?                    
                }else{
                    $rowIq=mysql_fetch_array($resIq);
                    //se hace la consulta para actualizar la informacion del folio
                    $folioConversion=$rowCliente["lote"]."-C";
                    $sqlAct="UPDATE equipos set lote='".$folioConversion."' WHERE imei='".$rowIq["imei"]."'";
                    $resAct=mysql_query($sqlAct,conectarBd());
                    if(mysql_affected_rows() >= 1){
?>
                    <script type="text/javascript"> $("#datosCorrectos").append("<div>"+<? echo $rowIq["imei"];?>+"</div>"); </script>
<?                        
                    }else{
?>
                    <script type="text/javascript"> $("#datosNoActualizados").append("<div>"+<? echo $rowIq["imei"];?>+"</div>"); </script>
<?                         
                    }
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
<? creaLoteConversion(); ?>