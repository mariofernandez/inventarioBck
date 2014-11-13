<?php
	session_start();
	$_SESSION['modulo'] = "catConceptos";
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
	
    switch($_REQUEST['catConceptosActionHdn']){
        case 'getConceptos':
            getConceptos();
            break;
        case 'addConcepto':
        	addConcepto();
        	break;
        case 'updConcepto':
        	updConcepto();
        	break;
    }

    function getConceptos(){
    	$lsWhereStr = "";

	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosConceptoTxt'], "c.concepto", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosNombreTxt'], "c.nombre", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosTipoConceptoHdn'], "c.tipoConcepto", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosEstatusHdn'], "c.estatus", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }

	    $sqlGetConceptosStr = "SELECT c.*, ".
                              "(SELECT g1.nombre FROM caGeneralesTbl g1 WHERE tabla='caConceptosTbl' ".
                                "AND columna='tipoConcepto' AND g1.valor = c.tipoConcepto ) AS nombreTipoConcepto,".
                              "(SELECT g2.nombre FROM caGeneralesTbl g2 WHERE tabla='caConceptosTbl' ".
                                "AND columna='estatus' AND g2.valor = c.estatus ) AS nombreEstatus ".
                              "FROM caConceptosTbl c ".$lsWhereStr;

		$rs = fn_ejecuta_query($sqlGetConceptosStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descConcepto'] = $rs['root'][$iInt]['concepto']." - ".$rs['root'][$iInt]['nombre'];
        }
			
		echo json_encode($rs);
    }

    function addConcepto(){
    	$a = array();
        $e = array();
        $a['success'] = true;

    	if($_REQUEST['catConceptosConceptoTxt'] == ""){
            $e[] = array('id'=>'catConceptosConceptoTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosNombreTxt'] == ""){
            $e[] = array('id'=>'catConceptosNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosTipoConceptoHdn'] == ""){
            $e[] = array('id'=>'catConceptosTipoConceptoHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosEstatusHdn'] == ""){
            $e[] = array('id'=>'catConceptosEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
        	$sqlAddConceptoStr = "INSERT INTO caConceptosTbl ".
        						 "VALUES(".
        						 "'".$_REQUEST['catConceptosConceptoTxt']."', ".
        						 "'".$_REQUEST['catConceptosNombreTxt']."', ".
        						 "'".$_REQUEST['catConceptosTipoConceptoHdn']."', ".
        						 "'".$_REQUEST['catConceptosEstatusHdn']."')";
			
        	$rs = fn_ejecuta_query($sqlAddConceptoStr);

        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddConceptoStr;
	            $a['successMessage'] = getConceptosSuccessMsg();
			} else {	
            	$a['success'] = false;
	            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddConceptoStr;
       	 	}

        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
	    echo json_encode($a);
    }

    function updConcepto(){
    	$a = array();
        $e = array();
        $a['success'] = true;

    	if($_REQUEST['catConceptosConceptoTxt'] == ""){
            $e[] = array('id'=>'catConceptosConceptoTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosNombreTxt'] == ""){
            $e[] = array('id'=>'catConceptosNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosTipoConceptoHdn'] == ""){
            $e[] = array('id'=>'catConceptosTipoConceptoHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosEstatusHdn'] == ""){
            $e[] = array('id'=>'catConceptosEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
        	$sqlUpdConceptoStr = "UPDATE caConceptosTbl ".
        						 "SET nombre='".$_REQUEST['catConceptosNombreTxt']."', ".
        						 "tipoConcepto='".$_REQUEST['catConceptosTipoConceptoHdn']."', ".
        						 "estatus='".$_REQUEST['catConceptosEstatusHdn']."' ".
        						 "WHERE concepto='".$_REQUEST['catConceptosConceptoTxt']."'";
			
        	$rs = fn_ejecuta_query($sqlUpdConceptoStr);

        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlUpdConceptoStr;
	            $a['successMessage'] = getConceptosUpdateMsg();
			} else {	
            	$a['success'] = false;
	            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdConceptoStr;
       	 	}

        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
	    echo json_encode($a);
    }

?>