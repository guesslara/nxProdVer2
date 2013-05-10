<?php
    include("../../recursos/graficas2/class/pData.class.php");
    include("../../recursos/graficas2/class/pDraw.class.php");
    include("../../recursos/graficas2/class/pPie.class.php");
    include("../../recursos/graficas2/class/pImage.class.php"); 
    /*CONSULTAS A LAS BASES DE DATOS*/
    $sqlTotalEquipos="SELECT COUNT( * ) AS `Filas` , `status` FROM `equipos` GROUP BY `status` ORDER BY `status` ";
    $sqlTotalEquipos1="SELECT COUNT( * ) AS `Filas` , `status` FROM `equipos_enviados` GROUP BY `status` ORDER BY `status` ";
    $resTotalEquipos=mysql_query($sqlTotalEquipos,conexionBd());
    $resTotalEquipos1=mysql_query($sqlTotalEquipos1,conexionBd());
    $valores="";    $nombres="";
    while($rowEquipos=mysql_fetch_array($resTotalEquipos)){
        if($valores==""){
            $valores=$valores.$rowEquipos["Filas"];
        }else{
            $valores=$valores.",".$rowEquipos["Filas"];
        }
        if($nombres==""){
            $nombres=$nombres.$rowEquipos["status"];
        }else{
            $nombres=$nombres.",".$rowEquipos["status"];
        }
    }
    while($rowEquipos1=mysql_fetch_array($resTotalEquipos1)){
        if($valores==""){
            $valores=$valores.$rowEquipos1["Filas"];
        }else{
            $valores=$valores.",".$rowEquipos1["Filas"];
        }
        if($nombres==""){
            $nombres=$nombres.$rowEquipos1["status"];
        }else{
            $nombres=$nombres.",".$rowEquipos1["status"];
        }
    }
    /*se convierten los valores en array*/
    $valores=explode(",",$valores);
    $nombres=explode(",",$nombres);    
    /*FIN DE LAS CONSULTAS*/    
    
    
    /* Create and populate the pData object */
    $MyData = new pData();   
    //$MyData->addPoints(array($valores),"ScoreA");
    $MyData->addPoints($valores,"ScoreA"); //40,30,10,10 
    $MyData->setSerieDescription("ScoreA","Application A");
   
    /* Define the absissa serie */
    //$MyData->addPoints(array($nombres),"Labels");
    $MyData->addPoints($nombres,"Labels");
    $MyData->setAbscissa("Labels");
   
    /* Create the pChart object */
    $myPicture = new pImage(500,430,$MyData,TRUE);
    
    /* Draw a solid background */
    $Settings = array("R"=>255, "G"=>255, "B"=>255, "Dash"=>255, "DashR"=>255, "DashG"=>255, "DashB"=>255);
    $myPicture->drawFilledRectangle(0,0,500,430,$Settings);
   
    /* Draw a gradient overlay */ /*aqui se define el color de fondo o degradado de la grafica*/
    $Settings = array("StartR"=>0, "StartG"=>0, "StartB"=>0, "EndR"=>255, "EndG"=>255, "EndB"=>255, "Alpha"=>50);
    $myPicture->drawGradientArea(0,0,500,430,DIRECTION_VERTICAL,$Settings);
    $myPicture->drawGradientArea(0,0,500,30,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));
   
    /* Add a border to the picture */
    //$myPicture->drawRectangle(0,0,399,229,array("R"=>0,"G"=>0,"B"=>0));
    $myPicture->drawRectangle(0,0,499,429,array("R"=>0,"G"=>0,"B"=>0));
   
    /* Write the picture title */ /*titulo del grafico*/
    $myPicture->setFontProperties(array("FontName"=>"../../recursos/graficas2/fonts/verdana.ttf","FontSize"=>9));
    $myPicture->drawText(10,20,"Resumen Nextel",array("R"=>255,"G"=>255,"B"=>255));//10,13
   
    /* Set the default font properties */ /*estas son propiedades para los numeros en la grafica*/
    $myPicture->setFontProperties(array("FontName"=>"../../recursos/graficas2/fonts/Forgotte.ttf","FontSize"=>12,"R"=>255,"G"=>255,"B"=>255));
   
    /* Create the pPie object */ 
    $PieChart = new pPie($myPicture,$MyData);
   
    /* Define the slice color */
    $PieChart->setSliceColor(0,array("R"=>143,"G"=>197,"B"=>0));
    $PieChart->setSliceColor(1,array("R"=>97,"G"=>77,"B"=>63));
    $PieChart->setSliceColor(2,array("R"=>97,"G"=>113,"B"=>63));
   
    /* Draw a simple pie chart */ 
    //$PieChart->draw3DPie(120,125,array("SecondPass"=>FALSE));
   
    /* Draw an AA pie chart */ 
    //$PieChart->draw3DPie(340,125,array("DrawLabels"=>TRUE,"Border"=>TRUE));
   
    /* Enable shadow computing */ 
    $myPicture->setShadow(TRUE,array("X"=>3,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
   
    /* Draw a splitted pie chart */ 
    $PieChart->draw3DPie(250,190,array("WriteValues"=>TRUE,"DataGapAngle"=>20,"DataGapRadius"=>10,"Border"=>TRUE));
    /* Write the legend */
    $myPicture->setFontProperties(array("FontName"=>"../../recursos/graficas2/fonts/verdana.ttf","FontSize"=>6));
    $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));
    
    //$myPicture->drawText(120,200,"Single AA pass",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));-
    //$myPicture->drawText(200,200,"Resumen General",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));
   
    /* Write the legend box */ /*porpiedades de la leyenda en la grafica*/
    $myPicture->setFontProperties(array("FontName"=>"../../recursos/graficas2/fonts/verdana.ttf","FontSize"=>9,"R"=>0,"G"=>0,"B"=>0));
    $PieChart->drawPieLegend(30,290,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL));
   
    /* Render the picture (choose the best way) */
    $myPicture->autoOutput("pictures/example.draw3DPie.png"); 
    
/**********************************************************/

/**********************************************************/
    
    
    function conexionBd(){
	include("../../includes/config.inc.php");
        $link=mysql_connect($host,$usuario,$pass);
        if(!$link){
            echo "Error al conectar con el Servidor.";
            exit;
        }else{
            mysql_select_db($db);    
        }
	return $link;
    }
?>