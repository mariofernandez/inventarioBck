<?php
	session_start();
    $_SESSION['modulo'] = "catRegiones";
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
	
	switch($_REQUEST['catRegionesActionHdn']){
		case 'getRegiones':
			getRegiones();
			break;
		case 'addRegion':
			addRegion();
			break;
		case 'updRegion':
			updRegion();
			break;
		default:
			echo '';	
	}
	
	function getRegiones(){
    	$lsWhereStr = "";
        
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catRegionesRegionTxt'], "region", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catRegionesNombreTxt'], "nombre", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catRegionesColorTxt'], "color", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }


	    $sqlGetRegionesStr = "SELECT * ".
                             "FROM caRegionesTbl ".$lsWhereStr;
		
		$rs = fn_ejecuta_query($sqlGetRegionesStr);
			
		echo json_encode($rs);
    }

	function addRegion(){
		
		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catRegionesRegionTxt'] == ""){
            $e[] = array('id'=>'catRegionesRegionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catRegionesNombreTxt'] == ""){
            $e[] = array('id'=>'catRegionesNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catRegionesColorTxt'] == ""){
            $e[] = array('id'=>'catRegionesColorTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
        	$sqlAddRegionStr = "INSERT INTO caRegionesTbl ".
        					   "(region, nombre, color) VALUES(".
        					   	"'".$_REQUEST['catRegionesRegionTxt']."', ".
        					   	"'".$_REQUEST['catRegionesNombreTxt']."', ".
        					   	"'".$_REQUEST['catRegionesColorTxt']."')";
			
			$rs = fn_ejecuta_query($sqlAddRegionStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddRegionStr;
	            $a['successMessage'] = getRegionSuccessMsg();
			} else {	
            	$a['success'] = false;
	            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddRegionStr;
       	 	}		   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updRegion(){

    	$a = array();
        $e = array();
        $a['success'] = true;

    	if($_REQUEST['catRegionesRegionTxt'] == ""){
            $e[] = array('id'=>'catRegionesRegionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catRegionesNombreTxt'] == ""){
            $e[] = array('id'=>'catRegionesNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catRegionesColorTxt'] == ""){
            $e[] = array('id'=>'catRegionesColorTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
        	$sqlUpdRegionStr = "UPDATE caRegionesTbl ".
        					   "SET region= '".$_REQUEST['catRegionesRegionTxt']."', ".
        					   "nombre= '".$_REQUEST['catRegionesNombreTxt']."', ".
        					   "color= '".$_REQUEST['catRegionesColorTxt']."' ".
        					   "WHERE idRegion= '".$_REQUEST['catRegionesIdHdn']."'";
        	
        	$rs = fn_ejecuta_query($sqlUpdRegionStr);

        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlUpdRegionStr;
	            $a['successMessage'] = getRegionUpdateMsg();
			} else {	
            	$a['success'] = false;
	            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdRegionStr;
       	 	}

        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
	    echo json_encode($a);
    }
?>