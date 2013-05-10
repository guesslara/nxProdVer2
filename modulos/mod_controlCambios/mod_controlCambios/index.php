<?php
	if($_SERVER['HTTP_REFERER']==""){
		header("Location: ../../index.php");
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>...::Listado de Correcciones::...</title>
<style type="text/css">
<!--
.titulo1{margin-left:10px;font-weight:bold;font-size:14px;}
.titulo2{margin-left:20px;font-weight:bold;}
.lista{margin-left:30px;}	
-->
</style>
</head>

<body>
<div style="border:#CCCCCC solid thin; background-color:#F0F0F0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;">
<p class="titulo1">Listado de Correcciones Sisco Compras</p>

<p class="titulo2"><img src="../../img/alert.png" width="32" height="32" />Actualizaci&oacute;n Datos Requisiciones de Compra.</p>
<p class="lista">Ahora se podr&aacute; visualizar los datos de la fecha de Orden de Compra, el tiempo de entrega de la misma as&iacute; como una fecha estimada de arribo.</p>

<p class="titulo2"><img src="../../img/alert.png" width="32" height="32" />Actualizaci&oacute;n Modulo Autorizaciones</p>
<p class="lista">Se actualiz&oacute; el Modulo de Autorizaciones.</p>
<p class="lista">Ahora se podr&aacute;n autorizar solo aquellos Items que sean necesarios y la siguiente ocasi&oacute;n que sean autorizados se separaran y de esta forma no mostrar&aacute; los que ya han sido autorizados</p>


<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Actualizaci&oacute;n Modulo Hist&oacute;rico. Fecha 14-01-2010</p>
<p class="lista">Se ha liberado la primera parte del hist&oacute;rico la cual permite mostrar de una manera separada las Ordenes de Compra del 2009 y las del a&ntilde;o actual</p>
<p class="lista">Desde esta opcion se podr&aacute; imprimir ya sea un Resumen de la Orden de Compra o hacer la reimpresi&oacute;n de la Orden de Compra.</p>
<p class="lista">Tambi&eacute;n se podr&aacute; consultar las entregas que el almac&eacute;n ha realizado.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Actualizaci&oacute;n Panel Asignaci&oacute;n Requisiciones de Compra. Fecha 14-01-2010</p>
<p class="lista">Se reorganizo el panel de asignaci&oacute;n de Requisiciones de Compra para un detalle m&aacute;s optimo, asi como se corrigieron problemas en la asignaci&oacute;n.</p>
<p class="lista">Para poder ver el detalle de cada Requisici&oacute;n solo hay que dar click en el numero de la misma y se mostrar&aacute; el detalle en el panel derecho.</p>
<p class="lista">El proceso de asignacion es el mismo.</p>


<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Mantenimiento a Base de Datos. Fecha 11-01-2010</p>
<p class="lista">Se migro la informaci&oacute;n del 2009 al 2010 (Requisiciones / Ordenes de Compra).</p>
<p class="lista">Se reparo la Base de Datos.</p>
<p class="lista">Algunos numeros de Requisici&oacute;n se modificaron para esta migración, los Centros de Costo afectados, serán notificados, con los nuevos numeros.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Recepci&oacute;n de Documentos. Fecha 30-09-09</p>
<p class="lista">Captura del tipo de Moneda al momento de capturar los documentos de los proveedores. (Facturas)</p>
<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Requisiciones de Compra. Fecha 29-09-09</p>
<p class="lista">Aumento en la posibilidad de poder introducir hasta 20 productos por cada requisición nueva en el sistema.</p>
<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Recepci&oacute;n de Documentos.</p>

<p class="lista">Actualizaci&oacute;n para poder dar de alta la documentaci&oacute;n de los servicios existentes para la empresa con la cual se este trabajando.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Panel de Verificaci&oacute;n Requisiciones de Compra</p>

<p class="lista">Panel de verificaci&oacute;n de Requisiciones, esta ventana de Reportes permite buscar por Centro de Costo las requisiciones en lo que va del a&ntilde;o.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Panel de Verificaci&oacute;n Ordenes de Compra</p>

<p class="lista">Panel de B&uacute;squeda de Ordenes de Compra en el Almacen para su posterior analisis.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Panel Requisiciones de Compra</p>

<p class="lista">Corregido el problema por el cual se tenia que limpiar las Cookies y Archivos Temporales al entrar el sistema, el listado se mostrar&aacute; de forma correcta.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Cancelaci&oacute;n de Ordenes de Compra</p>

<p class="lista">Ahora al momento de crear la Orden de Compra el Almacen no tendr&aacute; acceso a ella si no hasta el momento que el comprador la ponga a disposici&oacute;n del mismo Almacen.<br />

Por medio de este proceso el comprador podr&aacute; Cancelar la Orden de Compra antes que el Almacen la pueda dar por terminada.<br />

La Orden de Compra nueva se visualizar&aacute; en el listado con un borde de color Rojo el cual es indicativo que esa Orden aun no esta a disposicion del Almacen, para ponerla a disposici&oacute;n solo tendr&aacute;

que seleccionarla y presionar el bot&oacute;n Enviar OC situado en la parte Superior, el registro se visualizar&aacute; en un color azul indicando que esta seleccionado ese registro.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Panel Ordenes de Compra</p>

<p class="lista">Opci&oacute;n de Cancelaci&oacute;n de las Ordenes de Compra, adem&aacute;s de poder disponer de los items de las ordenes de compra, es decir si se quieren

utilizar para una Orden de Compra diferente, se puede hacer, o se pueden cancelar los items correspondientes a la misma.</p>

<p class="lista">Este listado se visualiza en el panel de Ordenes de Compra, las ordenes de compra que se han cancelado aparecen con una linea la cual es indicativo

de la cancelaci&oacute;n, en este listado se pueden ver los detalles de la misma.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Proveedores</p>

<p class="lista">Cambio en el listado, ahora la lista de proveedores aparece de manera diferente y las opciones de Modificaci&oacute;n y eliminaci&oacute;n aparecen en una ventana emergente, con 

la cual tambi&eacute;n aparece la posibilidad de poder ver un listado con las Requisiciones que se han cotizado con ese proveedor, as&iacute; como un listado de las ordenes de compra

que se han realizado a ese proveedor.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Hist&oacute;rico</p>

<p class="lista">Correcci&oacute;n al listado, mostraba un error el paginador, tambien se añadio la posibilidad de mostrar el detalle de cada Orden de Compra con este Status.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Cotizaciones</p>

<p class="lista">Posibilidad de mostrar el Historial de Cotizaciones de los Productos, al momento de Cotizar los items de la Requisici&oacute;n.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Requisiciones</p>

<p class="lista">Posibilidad de recotizaci&oacute;n de los items de la Requisici&oacute;nes de Compra</p>

<p class="lista">Aumento de paginador en la visualizaci&oacute;n de los diferentes status de las Requisiciones de Compra (<strong>Panel Lista de Tareas</strong>)</p>

<p class="lista">En esta misma secci&oacute;n se sustituyo la ventana emergente del navegador, por una generada por el propio sistema sin necesidad de ventanas emergentes.</p>

<p class="lista">Tambi&eacute;n se puede cancelar el item de la Requisici&oacute;n de Compra</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Panel Ordenes de Compra</p>

<p class="lista">En la opcion del menu Principal se aumento la oopcion <strong>(Lista OC Almacen)</strong> en esta parte mostrar&aa un listado con las Ordenes de Compra que el Almacen reciba, este cambio se vera reflejado en el aumento de la opcion en el menu <strong>(Mensajes / Panel Requisiciones Nuevas)</strong>

	avisando que existen ordenes de compra que el Almacen ya recibio <strong>(Recibo Almacen)</strong> y ademas se pueden consultar el detalle del recibo de la mercancia esto es Entrega Unica o Entregas Parciales.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />B&uacute;squeda</p>

<p class="lista">Listado con todos los items de las Requisiciones de Compra para su consulta.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Hist&oacute;rico</p>

<p class="lista">En esta parte se podran listar las ordenes de Compra que se hayan enviado a Historico para su posterior consulta</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />General</p>

<p class="lista">Corrección a datos faltantes en las interfaces de Usuario.</p>

<p class="lista">Corrección en el campo observaciones de los compradores, al momento de cotizar.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Asignación de Requisiciones</p>

<p class="lista">Modificación de Asignacion de Requisiciones de Compra, error al momento de la selección del comprador en la lista mostrada.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Ordenes de Compra</p>

<p class="lista">Recalculo de totales al momento de hacer la Orden de Compra</p>

<p class="lista">Aumento del Tipo de Compra ( Nacional / Internacional )</p>

<p class="lista">Colocación del numero de días en el campo Tiempo de Entrega a la Orden de Compra.</p>

<p class="lista">Aumento del Numero de Requisición al listar los items en la realización dela Orden de Compra.</p>

<p class="lista">Separar los numeros de Requisiciones, al momento de imprimir la Orden de Compra.</p>

<p class="lista">Corrección de impresión en los datos de la Orden de Compra</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Autorizaciones</p>

<p class="lista">Corrección a datos faltantes en las interfaces de usuario.</p>

<p class="lista">Aumento del tipo de moneda del item cotizado.</p>

<p class="lista">Aumento en la posibilidad del cambio de NIP para los usuarios con nivel de autorizacion en el sistema.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Modulo General Compras Buscador</p>

<p class="lista">Colocaci&oacute;n de Buscador para las Requisiciones de Compra en el cual se puede observar la informacion correspondiente con la Requisicion conociendo su status en nivel de Autorizacion y status en el Almacen.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Autorizaciones</p>

<p class="lista">Correccion al momento de poder recotizar un producto, este fallo provocaba que se perdiera visibilidad de la requisicion en el sistema.</p>

<p class="lista">Al momento de Autorizar se aumento el status Revisar en el cual le avisara al modulo de Compras que existe una Requisicion por revisar.</p>

<p class="lista">En el modulo de Compras se podrá ver esta Requisicion en el tablero de mensajes el cual indicara que se tienen que revisar, de esta forma el modulo de Compras podra volver a cotizar el Item de la Requisicion y se enviara de nuevo a Autorizaciones para su revision.</p>

<p class="titulo2"><img src="../../img/clean.png" width="32" height="32" />Panel de Requisiciones Nuevas</p>

<p class="lista">Actualizacion para tener visibilidad de las Requisiciones que se tengan que revisar para su posterior analisis.</p>

</div>

</body>

</html>

