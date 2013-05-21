<?php
    class modeloBounce{
        
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
        
        public function mostrarResumenImei($imei){
            $sql="SELECT * FROM equipos WHERE imei='".$imei."'";
            $res=mysql_query($sql,$this->conectarBd());
            if(mysql_num_rows($res)==0){
                echo "Imei No existe en Base de Datos, Verifique la Informacion";
            }else{
                $datoImei=mysql_fetch_array($res);
                echo "<strong>Informacion:</strong><br><br>";
                echo "Imei:".$datoImei["imei"]."<br>";
                echo "Serial:".$datoImei["serial"]."<br>";
                echo "Sim".$datoImei["sim"]."<br>";
                echo "Status:".$datoImei["status"]."<br><br>";
                echo "<hr style='background:#666;'>";
?>
                Imei Bounce
                <input type="text" name="" id="" style="width: 250px;font-size: 16px;">
<?
            }
                
        }
        
        public function mostrarFormularioCambio(){
?>
            <table border="1" cellpadding="1" cellspacing="1" width="99%">
                <tr>
                    <td colspan="2">Reemplazos de Equipos Bounce</td>
                </tr>
                <tr>
                    <td width="20%">Imei Refurbish</td>
                    <td width="79%" style="text-align: left;"><input type="text" name="txtImeiBounce" id="txtImeiBounce" style="width: 250px;font-size: 16px;" onkeyup="buscarImeiParaBounce(event)"></td>
                </tr>
                <tr>
                    <td colspan="2"><div id="divDetalleFormBounce" style="border: 1px solid #FF0000;"></div></td>                    
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
<?
        }
        
    }
?>