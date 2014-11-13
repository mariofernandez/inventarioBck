<?php
	session_start();
	$_SESSION['modulo'] = "alFlujoMovimientos";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");
	
    $_REQUEST = trasformUppercase($_REQUEST);
    
	switch($_SESSION['idioma']){
        case 'ES':
            include("../funciones/idiomas/mensajesES.php");
            break;
        case 'EN':
            include("../funciones/idiomas/mensajesEN.php");
            break;
        default:
            include("../funciones/idiomas/mensajesES.php");
    }
	
    switch($_REQUEST['alFlujoMovimientosActionHdn']){
        case 'getEstatus':
            getEstatus();
            break;
        default:
        	echo '';
    }

	function getEstatus(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alFlujoMovimientosCentroDistribucionHdn'], "f.centroDistribucion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alFlujoMovimientosMarcaHdn'], "f.marca", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alFlujoMovimientosEstatusHdn'], "f.claveMovimiento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alFlujoMovimientosOpcionalHdn'], "f.opcional", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alFlujoMovimientosTipoMovHdn'], "f.tipoMovimiento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alFlujoMovimientosSecuenciaHdn'], "f.secuenciaMovimiento", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alFlujoMovimientosMovSucesorHdn'], "f.movimientoSucesor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetEstatusStr = "SELECT f.movimientoSucesor, f.secuenciaMovimiento, ".
        					  "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.valor=f.movimientoSucesor ".
        					  "AND g.tabla='alflujomovimientostbl' AND columna='sucesorMovimiento') AS nombre ".
                              "FROM alFlujoMovimientosTbl f ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetEstatusStr);
        
        echo json_encode($rs);   
    }
?>