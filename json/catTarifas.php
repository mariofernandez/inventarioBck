<?php
    session_start();
	$_SESSION['modulo'] = "catTarifas";
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
	
    switch($_REQUEST['catTarifasActionHdn']){
        case 'getTarifas':
            getTarifas();
            break;
        case 'addTarifa':
        	addTarifa();
        	break;
        case 'updTarifa':
        	updTarifa();
        	break;
        case 'dltTarifa':
        	dltTarifa();
        	break;
        default:
            echo '';
    }

    function getTarifas(){

    	if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catTarifasIdTarifaHdn'], "idTarifa", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catTarifasTarifaTxt'], "tarifa", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catTarifasTipoTarifaHdn'], "tipoTarifa", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catTarifasDescripcionTxt'], "descripcion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }

	    $sqlGetTarifasStr = "SELECT tf.*, ".
                             "(SELECT cg.nombre FROM caGeneralesTbl cg ".
                                "WHERE cg.valor = tf.tipoTarifa AND tabla = 'caTarifasTbl' ".
                                "AND columna = 'tipoTarifa') AS nombreTipoTarifa ".
                            "FROM caTarifasTbl tf " . $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetTarifasStr);
                for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descTarifa'] = $rs['root'][$iInt]['tarifa']." - ".$rs['root'][$iInt]['descripcion'];
        }
			
		echo json_encode($rs);
    }

    function addTarifa(){
    	$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catTarifasTarifaTxt'] == ""){
            $e[] = array('id'=>'catTarifasTarifaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTarifasTipoTarifaHdn'] == ""){
            $e[] = array('id'=>'catTarifasTipoTarifaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTarifasDescripcionTxt'] == ""){
            $e[] = array('id'=>'catTarifasDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
            $sqlAddTarifaStr = "INSERT INTO caTarifasTbl (tarifa, tipoTarifa, descripcion) ".
                               "VALUES (".
                               "'".$_REQUEST['catTarifasTarifaTxt']."', ".
                               "'".$_REQUEST['catTarifasTipoTarifaHdn']."', ".
                               "'".$_REQUEST['catTarifasDescripcionTxt']."')";
            
            $rs = fn_ejecuta_query($sqlAddTarifaStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddTarifaStr;
                $a['successMessage'] = getTarifasSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddTarifaStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getTarifasSuccessMsg();
        echo json_encode($a);
    }

    function updTarifa(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catTarifasTarifaTxt'] == ""){
            $e[] = array('id'=>'catTarifasTarifaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTarifasTipoTarifaHdn'] == ""){
            $e[] = array('id'=>'catTarifasTipoTarifaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTarifasDescripcionTxt'] == ""){
            $e[] = array('id'=>'catTarifasDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
            $sqlUpdTarifaStr = "UPDATE caTarifasTbl ".
                               "SET tarifa= '".$_REQUEST['catTarifasTarifaTxt']."', ".
                               "tipoTarifa= '".$_REQUEST['catTarifasTipoTarifaHdn']."', ".
                               "descripcion= '".$_REQUEST['catTarifasDescripcionTxt']."' ".
                               "WHERE idTarifa=".$_REQUEST['catTarifasIdTarifaHdn'];

            $rs = fn_ejecuta_query($sqlUpdTarifaStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdTarifaStr;
                $a['successMessage'] = getTarifasUpdateMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdTarifaStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getTarifasUpdateMsg();
        echo json_encode($a);
    }

    function dltTarifa(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $sqlDeleteTarifaStr = "DELETE FROM caTarifasTbl WHERE idTarifa=".$_REQUEST['catTarifasIdTarifaHdn'];
        
        $rs = fn_ejecuta_query($sqlDeleteTarifaStr);

        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
            $a['sql'] = $sqlDeleteTarifaStr;
            $a['successMessage'] = getTarifasDeleteMsg();
            $a['id'] = $_REQUEST['catTarifasIdTarifaHdn'];
        } else {
            $a['success'] = false;
            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteTarifaStr;
        }
        
        $a['errors'] = $e;
        $a['successTitle'] = getTarifasDeleteMsg();
        echo json_encode($a);
    }
?>