<?php
    class grid3{
        private $hostDb;
        private $usuarioDb;
        private $passDb;
        private $dbDb;
        private $linkBase;
        
        public function __construct($host,$usuario,$pass,$db){
            $this->hostDb=$host;
            $this->usuarioDb=$usuario;
            $this->passDb=$pass;
            $this->dbDb=$db;
            
            $this->linkBase=$this->getConexion($this->hostDb,$this->usuarioDb,$this->passDb,$this->dbDb);
            if(!$this->linkBase){
                echo "Error al Conectar con el Servidor";
            }
        }
        
        private function getConexion($host,$usuario,$pass,$db){
            try{
                $link=@mysql_connect($host,$usuario,$pass) or die("Error de Conexion");
                $base=@mysql_select_db($db,$link) or die("Ocurrio un problema con la Base de Datos");
                $db=$link;
            }catch(Exception $e){
                echo "Ocurrio un error en la Aplicacion";
                $db=false;
            }
            return $db;
        }
        
        private function estilosReporteTabla(){
            
        }
        
        public function mostrarListado($camposTitulo,$fecha1,$fecha2,$campos,$campoOrden,$tipoOrden,$condiciones,$regsxpagina,$from,$where,$titulosReporte,$pag,$url){
            //set_time_limit(0);                                    
            $RegistrosAMostrar=$regsxpagina;
            $i=0;
            if($pag!=0){
                $RegistrosAEmpezar=($pag-1)*$RegistrosAMostrar;
                $PagAct=$pag;
            }else{
                $RegistrosAEmpezar=0;
                $PagAct=1;
            }

            $camposB=$campos;            
            $campos=explode(",",$campos);  //se recorre la cadena para separarla en fragmentos            
            $totalCampos=count($campos);  //# de campos o columnas
            for($i=0;$i<count($campos);$i++){				
                ($i==$totalCampos-1) ?	$camposConsulta.=$campos[$i] : $camposConsulta.=$campos[$i].",";	
            }            
            $select="SELECT ".$camposConsulta." ";                      //se hace un esquema de la consulta
            $condiciones1=$condiciones;                                 //se arma clausula where para uno o 2 parametros
            $condiciones=stripslashes($condiciones);                    //se limpia la cadena
            $limit=" LIMIT ".$RegistrosAEmpezar.",".$RegistrosAMostrar; //se arma la clausula limit
            if($campoOrden!=""){
                $ordenConsulta=" ORDER BY ".$campoOrden." ".$tipoOrden;
            }
            //se arma la consulta
            $sqlNueva=$select.$from.$where.$ordenConsulta.$limit;
            $sqlNueva1=$select.$from.$where.$ordenConsulta;
            
            $resultadoNueva=mysql_query($sqlNueva,$this->linkBase);      //se ejecutan las consultas
            $resultadoNueva1=mysql_query($sqlNueva1,$this->linkBase);
            //******--------determinar las páginas---------******//
            $NroRegistros=mysql_num_rows($resultadoNueva1);
            $PagAnt=$PagAct-1;
            $PagSig=$PagAct+1;
            $PagUlt=$NroRegistros/$RegistrosAMostrar;			            
            $Res=$NroRegistros%$RegistrosAMostrar;//verificamos residuo para ver si llevará decimales
            // si hay residuo usamos funcion floor para que me devuelva la parte entera, SIN REDONDEAR, y le sumamos una unidad para obtener la ultima pagina
            if($Res>0) $PagUlt=floor($PagUlt)+1;			
?>
		<div style="border: 1px solid #CCC;background-color:#F0F0F0; height:20px; padding: 5px; width:auto; margin:4px;font-weight: bold;font-size: 14px;">
                    <strong><?=$titulosReporte;?></strong><br />                    
		</div>
                <div style="text-align:center; height:10px;font-size: 12px; padding:5px;">
<?                    
			//desplazamiento
?>
		    <a href="<?=$url;?>?pagina=1" title="Primero" style="cursor:pointer; text-decoration:none;">|&lt;</a>&nbsp;
<?
		if($PagAct>1){ 
?>
                    <a href="<?=$url;?>?pagina=<?=$PagAnt;?>"  title="Anterior" style="cursor:pointer; text-decoration:none;">&lt;&lt;</a>&nbsp;
<?
		}
		echo "<strong>".$PagAct."/".$PagUlt."</strong>";
		if($PagAct<$PagUlt){
?>
                    <a href="<?=$url;?>?pagina=<?=$PagSig;?>"  title="Siguiente" style="cursor:pointer; text-decoration:none;">&gt;&gt;</a>&nbsp;
<?
		}
?>     
                    <a href="<?=$url;?>?pagina=<?=$PagUlt;?>"  title="Ultimo" style="cursor:pointer; text-decoration:none;">&gt;|</a>&nbsp;        
                </div>                
		
        <div align="left" style="margin-left:4px;">
                    <form name="frm_consultas" id="frm_contenedor">
                        <table border="0" cellpadding="1" cellspacing="1" style="width:auto;font-size: 10px;" >							  
                            <tr>
<?
				//valores de las columnas
				for($i=0;$i<($totalCampos+1);$i++){
?>
                                <td align="center" style="height: 20px;padding: 5px;border:1px solid #CCC;background:#F0F0F0;"><strong><?=$camposTitulo[$i];?></strong></td>
<?
				}
?>
			    </tr>
			    <tr>
<?
                                    $color="#CCCCCC";//background-Color:<?=$color;>;
                                    $i=0;
                                    while($row=mysql_fetch_array($resultadoNueva)){					
?>
                            <tr style="background:#FFF;" onMouseOver="anterior=this.style.backgroundColor;this.style.backgroundColor='#D5EAFF'" onmouseout="this.style.backgroundColor=anterior">
<?
                                        for($j=0;$j<=$totalCampos;$j++){					  
                                            ($campoOrden==$campos[$j]) ? $borde1="border:1px solid #09F;" : $borde1="";					  
?>
				<td style="height:25px;padding: 5px;border-bottom:1px solid #CCC; width:auto;<?=$borde1;?>" style="font-size: 10px;text-align: center;">
<?
                                            echo $row[$j];
?>		
				&nbsp;</td>
<?	  
                                        }
?>                  
                            </tr>                	
<?
                                        ($color=="#F0F0F0") ? $color="#CCCCCC" : $color="#F0F0F0";
                                        $i=$i+1;
                                    }
?>				
			</table>
                    </form>
		</div><br />
<?            
	}
    }
?>