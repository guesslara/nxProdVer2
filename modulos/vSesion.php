<?php
    session_start();
    include("../includes/txtApp.php");
    if($_SESSION[$txtApp['session']['idUsuario']]){
        echo "<script type='text/javascript'> alert('Su sesion ha terminado por Inactividad'); window.location.href='cerrar_sesion.php'; </script>";
	exit;
    }
?>