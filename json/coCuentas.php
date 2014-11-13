<?php
	session_start();
    $_SESSION['modulo'] = "coCuentas";
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
	
	switch($_REQUEST['coCuentasActionHdn']){
		case 'getCuentas':
			getCuentas();
			break;
		case 'addCuentas':
			addCuentas();
			break;
		case 'updCuentas':
			updCuentas();
			break;
		default:
			echo '';	
	}

	function getCuentas(){
    	$lsWhereStr = "";
        
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasIdCuentaHdn'], "idCuenta", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasIdCompaniaHdn'], "idCompania", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasCuentaTxt'], "cuenta", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasNombreTxt'], "nombre", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasAfectableTxt'], "afectable", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasNaturalezaTxt'], "naturaleza", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasSeccionTxt'], "seccion", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasReportarTxt'], "reportar", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasFechaTxt'], "fecha", 2);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasFechaActualTxt'], "fechaActual", 2);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasUsuarioHdn'], "usuario", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasIpHdn'], "ip", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['coCuentasEstatusHdn'], "estatus", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }

	    $sqlGetCuentasTbl = "SELECT cc.*, ".
	    					"(SELECT g.nombre FROM caGeneralesTbl g WHERE g.tabla='coCuentasTbl' ".
	    						"AND g.columna='estatus' AND g.valor = cc.estatus) as nombreEstatus ".
                            "FROM coCuentasTbl cc ".$lsWhereStr;
		
		$rs = fn_ejecuta_query($sqlGetCuentasTbl);
			
		echo json_encode($rs);
    }

    function addCuentas(){
    	$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['coCuentasIdCompaniaHdn'] == ""){
            $e[] = array('id'=>'coCuentasIdCompaniaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasCuentaTxt'] == ""){
            $e[] = array('id'=>'coCuentasCuentaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasNombreTxt'] == ""){
            $e[] = array('id'=>'coCuentasNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasAfectableTxt'] == ""){
            $e[] = array('id'=>'coCuentasAfectableTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasNaturalezaTxt'] == ""){
            $e[] = array('id'=>'coCuentasNaturalezaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasSeccionTxt'] == ""){
            $e[] = array('id'=>'coCuentasSeccionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasReportarTxt'] == ""){
            $e[] = array('id'=>'coCuentasReportarTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }


        if($a['success'] == true){
        	$sqlAddCuentaStr = "INSERT INTO coCuentasTbl (idCompania,cuenta,nombre,afectable,".
        							"naturaleza,seccion,reportar,fecha,fechaActual,usuario,ip,estatus)".
        					   "VALUES(".
        					   $_REQUEST['coCuentasIdCompaniaHdn'].",".
        					   "'".$_REQUEST['coCuentasCuentaTxt']."',".
        					   "'".$_REQUEST['coCuentasNombreTxt']."',".
        					   $_REQUEST['coCuentasAfectableTxt'].",".
        					   $_REQUEST['coCuentasNaturalezaTxt'].",".
        					   $_REQUEST['coCuentasSeccionTxt'].",".
        					   $_REQUEST['coCuentasReportarTxt'].",".
        					   "'".date("Y-m-d")."',".
        					   "NULL,".
        					   "'".$_SESSION['usuario']."',".
        					   "'".$_SERVER['REMOTE_ADDR']."',".
        					   "'".$_REQUEST['coCuentasEstatusHdn']."')";

			
			$rs = fn_ejecuta_query($sqlAddCuentaStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddCuentaStr;
	            $a['successMessage'] = getCuentasSuccessMsg();
			} else {	
            	$a['success'] = false;
	            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddCuentaStr;
       	 	}		   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updCuentas(){
    	$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['coCuentasIdCuentaHdn'] == ""){
            $e[] = array('id'=>'coCuentasIdCuentaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['coCuentasIdCompaniaHdn'] == ""){
            $e[] = array('id'=>'coCuentasIdCompaniaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasCuentaTxt'] == ""){
            $e[] = array('id'=>'coCuentasCuentaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasNombreTxt'] == ""){
            $e[] = array('id'=>'coCuentasNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasAfectableTxt'] == ""){
            $e[] = array('id'=>'coCuentasAfectableTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasNaturalezaTxt'] == ""){
            $e[] = array('id'=>'coCuentasNaturalezaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasSeccionTxt'] == ""){
            $e[] = array('id'=>'coCuentasSeccionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasReportarTxt'] == ""){
            $e[] = array('id'=>'coCuentasReportarTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['coCuentasFechaTxt'] == ""){
            $e[] = array('id'=>'coCuentasFechaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }


        if($a['success'] == true){
        	$sqlUpdCuentasStr = "UPDATE coCuentasTbl SET ".
        						"idCompania = ".$_REQUEST['coCuentasIdCompaniaHdn'].",".
        						"cuenta = '".$_REQUEST['coCuentasCuentaTxt']."',".
        						"nombre = '".$_REQUEST['coCuentasNombreTxt']."',".
        						"afectable =".$_REQUEST['coCuentasAfectableTxt'].",".
        						"naturaleza =".$_REQUEST['coCuentasNaturalezaTxt'].",".
        						"seccion = ".$_REQUEST['coCuentasSeccionTxt'].",".
        						"reportar = ".$_REQUEST['coCuentasReportarTxt'].",".
        						"fecha = '".$_REQUEST['coCuentasFechaTxt']."',".
        						"fechaActual = '".date("Y-m-d H:m:s")."',".
        						"usuario = '".$_SESSION['usuario']."',".
        						"ip = '".$_SERVER['REMOTE_ADDR']."',".
        						"estatus = '".$_REQUEST['coCuentasEstatusHdn']."' ".
        						"WHERE idCuenta =".$_REQUEST['coCuentasIdCuentaHdn'];
        	
        	$rs = fn_ejecuta_query($sqlUpdCuentasStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlUpdCuentasStr;
	            $a['successMessage'] = getCuentasUpdMsg();
			} else {	
            	$a['success'] = false;
	            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdCuentasStr;
       	 	}		   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>