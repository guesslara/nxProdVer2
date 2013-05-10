<?
	session_start();
	include("../../includes/cabecera.php");
	include("../../includes/txtApp.php");	
	if(!isset($_SESSION[$txtApp['session']['idUsuario']])){
		echo "<script type='text/javascript'> alert('Su sesion ha terminado por inactividad'); window.location.href='../mod_login/index.php'; </script>";
		exit;
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Multiconsulta de Equipos Nextel.</title>
<link rel="stylesheet" type="text/css" href="css/estilosEmpaque.css" />
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<style type="text/css">
body, document, html{ position:absolute; width:100%; height:100%; background-color:#fff; font-family: Arial, Helvetica, sans-serif; margin:0px 0px 0px 0px; color:#000; font-size:small; }
#div_main{ position:absolute; width:100%; height:100%; margin:0px 0px 0px 0px; }
	#div_titulo{ position:relative; height:20px; text-align:center; font-size:large; background-color:#efefef;  }
	#div_menu{ position:relative; height:20px; text-align:center; background-color:#efefef;  }
	#div_a{ position:relative; height:auto; width:200px; text-align:center; float:left; background-color:#efefef; overflow:auto; }
	#div_b{ position:relative; height:auto; text-align:justify; float:left; overflow:auto; }
	
#tbl_01{ display:none; }
table{ border-left:#ccc 1px solid; border-top:#ccc 1px solid; margin-top:5px; margin-bottom:5px; }
th{ /**/ border-right:#ccc 1px solid; border-bottom:#ccc 1px solid; background-color:#efefef; font-weight:bold; text-align:center; }
td{ /**/ border-right:#ccc 1px solid; border-bottom:#ccc 1px solid;text-align:center; }

.nds{ width:auto; text-align:left; }
.act{ width:50px; text-align:center; }
.iot{ width:50px; text-align:right; color:#0000FF; }
.sta{ width:100px; text-align:left; }
.stb{ width:100px; text-align:left; }
.env{ text-align:right; }

.tr_hover:hover{ background-color:#efefcc;}    
    
.estiloBuscar{float: left;width: 100px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:12px;text-align:center;}
.estiloBuscar:hover{cursor: pointer;background: #CCC;}
.estilosResultados{float:left;width:100px;height:20px;padding:5px;background:#F0F0F0;border:1px solid #CCC;font-size:11px;text-align:left;color: #333;}
.estilosResultados:hover{background: #CCC;cursor: pointer;}
</style>
<script type="text/javascript">
	$(document).ready(function(){
            redimensionar();
            $("#txt_archivo_excel").attr("value","");
            $("#txt_archivo_excel").focus();
	});
	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var anchoDiv=$("#contenedorEnsamble3").width();
		var altoCuerpo=altoDiv-52;
		$("#detalleEmpaque").css("height",altoCuerpo+"px");
		$("#ventanaEnsambleContenido2").css("height",altoCuerpo+"px");
		$("#detalleEmpaque").css("width",(anchoDiv-178)+"px");
		$("#ventanaEnsambleContenido2").css("width",(anchoDiv-200)+"px");
		$("#infoEnsamble3").css("height",altoCuerpo+"px");
	}
	
	window.onresize=redimensionar;

	document.onkeypress=function(elEvento){
		var evento=elEvento || window.event;
		var codigo=evento.charCode || evento.keyCode;
		var caracter=String.fromCharCode(codigo);
		if(codigo==27){
			cerrarVentanaValidacion();
		}
	}

function crear_tabla(){
	//alert("crear_tabla()");alert(mi_html);
        
        for(var i=1;i<=2;i++){
            var mi_html='<table align="center" id="tbl_0'+i+'" border="0" cellpadding="2" cellspacing="0">';
		mi_html+='<tr>';
			mi_html+='<th>#</th>';
			mi_html+='<th>id</th>';
			mi_html+='<th>Modelo</th>';
			mi_html+='<th>Imei</th>';
			mi_html+='<th>Serie</th>';
			mi_html+='<th>Sim</th>';
			mi_html+='<th>Lote</th>';
			mi_html+='<th>Status</th>';
			mi_html+='<th>Status Nextel</th>'
			mi_html+='<th>Status Proceso</th>'
			mi_html+='<th>Status Desensamble</th>'
			mi_html+='<th>Status Diagnostico</th>'
			mi_html+='<th>Status Almacen</th>'
			mi_html+='<th>Status Ingenieria</th>'
			mi_html+='<th>Status Empaque</th>'
			mi_html+='<th>Linea Ensamble</th>';
			mi_html+='<th>Tipo</th>';			
		mi_html+='</tr>';
            mi_html+='</table>';
            if(i==1){                
                $("#eqProceso").html(mi_html);        
            }else if(i==2){                
                $("#eqEnviado").html(mi_html);        
            }
        }
	//$("#div_tabla").html(mi_html);	
}        

function agregar_filaX(nroDiv,id_lote2,nds,act,lot,mod,iot,st0,st1,ien,ica,status1,status2,status3,status4,linea,tipoEquipo){
    if(nroDiv==1){
        var no_filas = document.getElementById("tbl_01").rows.length;
    }else if(nroDiv==2){
        var no_filas = document.getElementById("tbl_02").rows.length;
    } 
	//var no_filas = document.getElementById("tbl_01").rows.length;
	var id_fila_activa=no_filas-1;
	var mi_html='<tr class="tr_hover">';
		mi_html+='<td align="center"><b>'+no_filas+'</b></td>';
		
		mi_html+='<td id="txt_nds_'+id_fila_activa+'" class="iot" >'+id_lote2+'</td>';
		
		mi_html+='<td id="txt_nds_'+id_fila_activa+'" class="nds" >'+nds+'</td>';
		mi_html+='<td id="txt_act_'+id_fila_activa+'" class="act" >'+act+'</td>';
		mi_html+='<td id="txt_lot_'+id_fila_activa+'" class="act" >'+lot+'</td>';
		mi_html+='<td id="txt_mod_'+id_fila_activa+'" class="sta" >'+mod+'</td>';
		mi_html+='<td id="txt_iot_'+id_fila_activa+'" class="iot" >'+iot+'</td>';
		mi_html+='<td id="txt_sta_'+id_fila_activa+'" class="sta" >'+st0+'</td>';
		mi_html+='<td id="txt_stb_'+id_fila_activa+'" class="stb" >'+st1+'</td>';
		mi_html+='<td id="txt_stb_'+id_fila_activa+'" class="stb" >'+ien+'</td>';
		mi_html+='<td id="txt_stb_'+id_fila_activa+'" class="stb" >'+ica+'</td>';
		mi_html+='<td id="txt_stb_'+id_fila_activa+'" class="stb" >'+status1+'</td>';
		mi_html+='<td id="txt_stb_'+id_fila_activa+'" class="stb" >'+status2+'</td>';
		mi_html+='<td id="txt_stb_'+id_fila_activa+'" class="stb" >'+status3+'</td>';
		mi_html+='<td id="txt_stb_'+id_fila_activa+'" class="stb" >'+status4+'</td>';
		mi_html+='<td id="txt_stb_'+id_fila_activa+'" class="stb" >'+linea+'</td>';
		mi_html+='<td id="txt_stb_'+id_fila_activa+'" class="stb" >'+tipoEquipo+'</td>';
	mi_html+='</tr>';
        if(nroDiv==1){
            $("#tbl_01").append(mi_html);
        }else if(nroDiv==2){
            $("#tbl_02").append(mi_html);
        }
}    
        
function ajax(capa,datos,ocultar_capa){
    if (!(ocultar_capa==""||ocultar_capa==undefined||ocultar_capa==null)) { $("#"+ocultar_capa).hide(); }
    //var url="<?=$_SERVER['PHP_SELF']?>";
    var url="modeloconsulta3.php";
    $.ajax({
	async:true, type: "POST", dataType: "html", contentType: "application/x-www-form-urlencoded",
	url:url, data:datos, 
	beforeSend:function(){ 
	    $("#"+capa).show().html('<center>Procesando, espere un momento.</center>'); 
	},
	success:function(datos){ 
	    $("#"+capa).show().html(datos); 
	},
	timeout:90000000,
	error:function() { $("#"+capa).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
    });

}
function buscar_valores_x(){
    obtener_valores_textarea();
}
function obtener_valores_textarea(){
    var valores=$("#txt_archivo_excel").attr("value");
    if(valores==''||valores==' '||valores==undefined||valores==null) return;
    
    $("#tbl_01").show();
    var nuevos_valores='';
            nuevos_valores=valores.replace(/\n/gi,',');
            nuevos_valores=nuevos_valores.replace(' ','');
            nuevos_valores=nuevos_valores.replace(/\r/gi,',');			
    
    var datos="action=buscar_nds_xxx&data="+nuevos_valores;
    //alert(datos);
    //ajax("div_resultados",datos);
    ajax("eqProceso",datos);
}

function buscar_valores_grid(activo_inactivo){
    var no_filas = document.getElementById("tbl_01").rows.length;
    if(no_filas<=1) return;
    --no_filas;
    var id_fila_activa=no_filas-1;
    var m_indices=new Array();
    var m_nds=new Array();
    for(var i=0;i<no_filas;i++){	
            m_indices.push(i);
            m_nds.push($("#txt_nds_"+i).text());
    }
    //alert(m_nds);
    var datos="action=buscar_nds&activo="+activo_inactivo+"&m_indices="+m_indices+"&data="+m_nds;
    ajax("div_resultados",datos);
}
function mostrarTab(opcion){
    //alert(opcion);
    $("#eqProceso").hide();
    $("#eqEnviado").hide();
    $("#eqNoEncontrado").hide();
    $("#"+opcion).show();
}
</script>
</head>
<body>
<div id="contenedorEnsamble">
	<div id="contenedorEnsamble3">
		<div id="barraOpcionesEnsamble">                    
                    <div id="" style="float:left;width:230px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:16px;text-align:left;">Multi-Consulta de Equipos</div>
                    <div class="estiloBuscar" onclick="buscar_valores_x()">Buscar</div>
                    <div id="cargadorEmpaque" style="float:right;width:200px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:13px;text-align:right;"></div>
		</div>
		<div id="infoEnsamble3">			
		    <div id="listadoEmpaque" style="border:1px solid #e1e1e1;background:#fff; height:99%;width:97%;font-size:12px;margin:3px;overflow: auto;">
                        <!--Opciones para los imeis-->                        
                        Listado de Imei's<textarea id="txt_archivo_excel" cols="20" rows="30" style="height:95%;"></textarea>
                        <!--Fin de las Opciones-->
                    </div>
		</div>
		<div id="detalleEmpaque" class="ventanaEnsambleContenido" style="overflow: hidden;">
                    <div id="" class="estilosResultados" onclick="mostrarTab('eqProceso')">Eq. Proceso</div>
                    <div id="" class="estilosResultados" onclick="mostrarTab('eqEnviado')">Eq. Enviado</div>
                    <div id="" class="estilosResultados" onclick="mostrarTab('eqNoEncontrado')">No Encontrados</div>
                    <div id="eqProceso" style="border: 1px solid #CCC;width: 99.3%;height: 94.5%;margin: 33px 3px 3px 3px;overflow: auto;"></div>
                    <div id="eqEnviado" style="display: none;border: 1px solid #CCC;width: 99.3%;height: 94.5%;margin: 33px 3px 3px 3px;overflow: auto;"></div>
                    <div id="eqNoEncontrado" style="display: none;border: 1px solid #CCC;width: 99.3%;height: 94.5%;margin: 33px 3px 3px 3px;overflow: auto;"></div>
                </div>
		<div id="ventanaEnsambleContenido2" class="ventanaEnsambleContenido" style="display:none;"></div>
		<div style="clear:both;"></div>
		<!--<div id="barraInferiorEnsamble">			
			<div id="erroresCaptura"></div>
			<div id="opcionCancelar"><input type="button" onclick="cancelarCaptura()" value="Cancelar" style=" width:100px; height:30px;padding:5px;background:#FF0000;color:#FFF;border:1px solid #FF0000;font-weight:bold;" /></div>
		</div>-->
	</div>
</div>
</body>
</html>