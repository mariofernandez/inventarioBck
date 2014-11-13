<?php
    session_start();
	$_SESSION['modulo'] = "catCompanias";
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
	
    switch($_REQUEST['catCompaniasActionHdn']){
        case 'getCompanias':
            getCompanias();
            break;
		case 'addCompanias':
			addCompania();
			break;
		case 'updCompanias':
			updCompania();
			break;
		case 'dltCompanias':
			dltCompania();
			break;
        default:
            echo '';
    }
	
	function getCompanias(){
    	$lsWhereStr = "";

	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catCompaniasCompaniaTxt'], "c.compania", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catCompaniasDescripcionTxt'], "c.descripcion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catCompaniasTipoHdn'], "c.tipoCompania", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catCompaniasEstatusHdn'], "c.estatus", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }

		$sqlGetCompaniaStr = "SELECT c.*, ".
							 "(SELECT g.nombre FROM cageneralestbl g WHERE g.valor=c.estatus AND g.tabla='caCompaniasTbl' AND g.columna='estatus') AS nombreEstatus, ".
							 "(SELECT g.nombre FROM cageneralestbl g WHERE g.valor=c.tipoCompania AND g.tabla='caCompaniasTbl' AND g.columna='tipoCompania') AS nombreTipo, ".
							 "CONCAT(compania, ' - ', descripcion) AS descCiaTractor " .
		       				 "FROM caCompaniasTbl c " . $lsWhereStr;
		
		$rs = fn_ejecuta_query($sqlGetCompaniaStr);
			
		echo json_encode($rs);
	}
	
	function addCompania(){
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catCompaniasCompaniaTxt'] == "")
        {
            $e[] = array('id'=>'catCompaniasCompaniaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasDescripcionTxt'] == ""){
            $e[] = array('id'=>'catCompaniasDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasTipoHdn'] == ""){
            $e[] = array('id'=>'catCompaniasTipoHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasEstatusHdn'] == ""){
            $e[] = array('id'=>'catCompaniasEstatusHdn','msg'=>getRequerido());
		    $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
			
        if ($a['success'] == true){
            $sqlAddCiaStr = "INSERT INTO caCompaniasTbl ". 
				   		    "VALUES (".
				   		    "'".$_REQUEST['catCompaniasCompaniaTxt']."', ".
				   		    "'".$_REQUEST['catCompaniasDescripcionTxt']."', ". 
				   			"'".$_REQUEST['catCompaniasTipoHdn']."', ".
				  			"'".$_REQUEST['catCompaniasEstatusHdn']."')";

			$rs = fn_ejecuta_query($sqlAddCiaStr);
			
			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == ""))
			{
			    $a['sql'] = $sqlAddCiaStr;
                $a['successMessage'] = getCiaSuccesMsg();
                $a['id'] = $_REQUEST['catCompaniasCompaniaTxt'];
			}
		    else
			{
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddCiaStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
	
	
	
	function updCompania(){
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catCompaniasCompaniaTxt'] == ""){
            $e[] = array('id'=>'catCompaniasCompaniaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasDescripcionTxt'] == ""){
            $e[] = array('id'=>'catCompaniasDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasTipoHdn'] == ""){
            $e[] = array('id'=>'catCompaniasTipoHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasEstatusHdn'] == ""){
            $e[] = array('id'=>'catCompaniasEstatusHdn','msg'=>getRequerido());
		    $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
			
        if ($a['success'] == true) {
            $sqlUpdateCiaStr = "UPDATE caCompaniasTbl c ".
			            	   "SET descripcion = '" . $_REQUEST['catCompaniasDescripcionTxt'] . "', " .
						       "tipoCompania = '" . $_REQUEST['catCompaniasTipoHdn'] . "', " .
							   "estatus = '" . $_REQUEST['catCompaniasEstatusHdn'] . "' " .
						       "WHERE compania = '" . $_REQUEST['catCompaniasCompaniaTxt'] . "';";
			
			$rs = fn_ejecuta_query($sqlUpdateCiaStr);
			
			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    $a['sql'] = $sqlUpdateCiaStr;
                $a['successMessage'] = getCiaUpdtMsg();
                $a['id'] = $_REQUEST['catCompaniasCompaniaTxt'];
			} else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdateCiaStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}

	function dltCompania(){
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catCompaniasCompaniaTxt'] == ""){
            $e[] = array('id'=>'catCompaniasCompaniaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
			
        if ($a['success'] == true) {
            $sqlDltCompaniaStr = "DELETE FROM caCompaniasTbl " . 
			       				 "WHERE compania = '" . $_REQUEST['catCompaniasCompaniaTxt'] . "';";
			
			$rs = fn_ejecuta_query($sqlDltCompaniaStr);
			
			if($_SESSION['error_sql'] == ""){
			    $a['sql'] = $sqlDltCompaniaStr;
                $a['successMessage'] = getCiaDelMsg();
                $a['id'] = $_REQUEST['catCompaniasCompaniaTxt'];
			} else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDltCompaniaStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
?>