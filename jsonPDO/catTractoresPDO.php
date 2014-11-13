<?php
    //***********
    //FOR PDO USE
    //***********
    session_start();
	$_SESSION['modulo'] = "catTractores";
    require("../funciones/generalesPDO.php");
    require("../funciones/construct.php");

    switch($_SESSION['idioma'])
	{
        case 'ES':
            include("../funciones/idiomas/mensajesES.php");
            break;
        case 'EN':
            include("../funciones/idiomas/mensajesEN.php");
            break;
        default:
            include("../funciones/idiomas/mensajesES.php");
    }
	
    switch($_REQUEST['catTractoresActionHdn']) {
        case 'getTractores':
            getTractores();
            break;
		case 'addTractor':
			addTractor();
            break;
		case 'updTractor':
			updTractor();
            break;
		case 'dltTractor':
			dltTractor();
        default:
            echo '';
    }

    function getTractores(){
    	$lsWhereStr = "";
	
		if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresIdTractorHdn'], "idTractor", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresCiaHdn'], "compania", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresTractorTxt'], "tractor", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresMarcaHdn'], "marca", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresModeloTxt'], "modelo", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresSerieTxt'], "serie", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresPlacaTxt'], "placas", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresRendimientoTxt'], "rendimiento", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresEjesTxt'], "ejes", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresObservacionesTxt'], "observaciones", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresEstatusHdn'], "estatus", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catTractoresIaveTxt'], "tarjetaIave", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }

	    $sqlGetTractoresStr = "SELECT * FROM catractorestbl ".$lsWhereStr;
		
		$rs = fn_ejecuta_query($sqlGetTractoresStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
    }
			
	function addTractor(){

		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catTractoresCiaHdn'] == ""){
            $e[] = array('id'=>'catTractoresCiaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresTractorTxt'] == ""){
            $e[] = array('id'=>'catTractoresTractorTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresMarcaHdn'] == ""){
            $e[] = array('id'=>'catTractoresMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresModeloTxt'] == ""){
            $e[] = array('id'=>'catTractoresModeloTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresSerieTxt'] == ""){
            $e[] = array('id'=>'catTractoresSerieTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresPlacaTxt'] == ""){
            $e[] = array('id'=>'catTractoresPlacaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresRendimientoTxt'] == ""){
            $e[] = array('id'=>'catTractoresRendimientoTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresEjesTxt'] == ""){
            $e[] = array('id'=>'catTractoresEjesTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresEstatusHdn'] == ""){
            $e[] = array('id'=>'catTractoresEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresIaveTxt'] == ""){
            $e[] = array('id'=>'catTractoresIaveTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){
        	$sqlAddTractorStr = "INSERT INTO catractorestbl ".
        						"(compania, tractor, marca, modelo, serie, placas, rendimiento, ejes, observaciones, estatus, tarjetaIave) ".
								"VALUES (".
								"'".$_REQUEST['catTractoresCiaHdn']."', ".
								"'".$_REQUEST['catTractoresTractorTxt']."', ".
								"'".$_REQUEST['catTractoresMarcaHdn']."', ".
								"'".$_REQUEST['catTractoresModeloTxt']."', ".
								"'".$_REQUEST['catTractoresSerieTxt']."', ".
								"'".$_REQUEST['catTractoresPlacaTxt']."', ".
								"'".$_REQUEST['catTractoresRendimientoTxt']."', ".
								"'".$_REQUEST['catTractoresEjesTxt']."', ".
								"'".$_REQUEST['catTractoresObservacionesTxt']."', ".
								"'".$_REQUEST['catTractoresEstatusHdn']."', ".
								"'".$_REQUEST['catTractoresIaveTxt']."')";
			
			$rs = fn_ejecuta_query($sqlAddTractorStr);
			
			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddTractorStr;
            	$a['successMessage'] = getTractoresSuccessMsg();
			} else {
            	$a['success'] = false;
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddTractorStr;

                $errorNoArr = explode(":", $_SESSION['error_sql']);
            	if($errorNoArr[0] == '1062'){
            		$e[] = array('id'=>'duplicate','msg'=>getTractoresDuplicateMsg());	
            	}
        	}	
		}
		$a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}

	function updTractor(){
		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catTractoresCiaHdn'] == ""){
            $e[] = array('id'=>'catTractoresCiaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresTractorTxt'] == ""){
            $e[] = array('id'=>'catTractoresTractorTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresMarcaHdn'] == ""){
            $e[] = array('id'=>'catTractoresMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresModeloTxt'] == ""){
            $e[] = array('id'=>'catTractoresModeloTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresSerieTxt'] == ""){
            $e[] = array('id'=>'catTractoresSerieTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresPlacaTxt'] == ""){
            $e[] = array('id'=>'catTractoresPlacaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresRendimientoTxt'] == ""){
            $e[] = array('id'=>'catTractoresRendimientoTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresEjesTxt'] == ""){
            $e[] = array('id'=>'catTractoresEjesTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresEstatusHdn'] == ""){
            $e[] = array('id'=>'catTractoresEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catTractoresIaveTxt'] == ""){
            $e[] = array('id'=>'catTractoresIaveTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
           $sqlUpdTractoresStr = "UPDATE catractorestbl ".
                                 "SET compania= '".$_REQUEST['catTractoresCiaHdn']."', ".
                                 "tractor=".$_REQUEST['catTractoresTractorTxt'].", ".
                                 "marca= '".$_REQUEST['catTractoresMarcaHdn']."', ".
                                 "modelo= '".$_REQUEST['catTractoresModeloTxt']."', ".
                                 "serie= '".$_REQUEST['catTractoresSerieTxt']."', ".
                                 "placas= '".$_REQUEST['catTractoresPlacaTxt']."', ".
                                 "rendimiento=".$_REQUEST['catTractoresRendimientoTxt'].", ".
                                 "ejes=".$_REQUEST['catTractoresEjesTxt'].", ".
                                 "observaciones= '".$_REQUEST['catTractoresObservacionesTxt']."', ".
                                 "estatus= '".$_REQUEST['catTractoresEstatusHdn']."', ".
                                 "tarjetaIave= '".$_REQUEST['catTractoresIaveTxt']."' ".
                                 "WHERE idTractor=".$_REQUEST['catTractoresIdTractorHdn'];
            echo $sqlUpdTractoresStr;
            $rs = fn_ejecuta_query($sqlUpdTractoresStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdTractoresStr;
                $a['successMessage'] = getTractoresUpdateMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdTractoresStr;

                $errorNoArr = explode(":", $_SESSION['error_sql']);
                if($errorNoArr[0] == '1062'){
                    $e[] = array('id'=>'duplicate','msg'=>getTractoresDuplicateMsg());  
                }
            }
        }             
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
    
    function dltTractor(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $sqlDeleteTractorStr = "DELETE FROM catractorestbl WHERE idTractor=".$_REQUEST['catTractoresIdTractorHdn'];
        
        $rs = fn_ejecuta_query($sqlDeleteTractorStr);

        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
            $a['sql'] = $sqlDeleteTractorStr;
            $a['successMessage'] = getTractoresDeleteMsg();
            $a['id'] = $_REQUEST['catTractorIdTractorHdn'];
        } else {
            $a['success'] = false;
            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteTractorStr;
        }
        
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);       

    }
?>