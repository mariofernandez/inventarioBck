<?php
	session_start();
    $_SESSION['modulo'] = "catSeries";
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
	
	switch($_REQUEST['catSeriesActionHdn']){
		case 'getSeries':
			getSeries();
			break;
		case 'addSeries':
			addSeries();
			break;
		case 'updSeries':
			updSeries();
			break;
		default:
			echo '';	
	}
	
	function getSeries(){
    	$lsWhereStr = "";
        
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catSeriesSerieTxt'], "serie", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catSeriesImporteTxt'], "importe", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }

	    $sqlGetSeriesStr = "SELECT * ".
                           "FROM caSeriesTbl ".$lsWhereStr;
		
		$rs = fn_ejecuta_query($sqlGetSeriesStr);
			
		echo json_encode($rs);
    }

	function addSeries(){	
		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catSeriesSerieTxt'] == ""){
            $e[] = array('id'=>'catSeriesSerieTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSeriesImporteTxt'] == ""){
            $e[] = array('id'=>'catSeriesImporteTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
        	$sqlAddSeriesStr = "INSERT INTO caSeriesTbl ".
        					   "(serie, importe) VALUES(".
        					   	"'".$_REQUEST['catSeriesSerieTxt']."', ".
        					   	$_REQUEST['catSeriesImporteTxt'].")";
			
			$rs = fn_ejecuta_query($sqlAddSeriesStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddSeriesStr;
	            $a['successMessage'] = getSeriesSuccessMsg();
			} else {	
            	$a['success'] = false;
	            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddSeriesStr;
       	 	}		   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updSeries(){
    	$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catSeriesSerieTxt'] == ""){
            $e[] = array('id'=>'catSeriesSerieTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSeriesImporteTxt'] == ""){
            $e[] = array('id'=>'catSeriesImporteTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
        	$sqlUpdSeriesStr = "UPDATE caSeriesTbl ".
        					   "SET importe = ".$_REQUEST['catSeriesImporteTxt']." ".
        					   "WHERE serie = '".$_REQUEST['catSeriesSerieTxt']."'";
        	
        	$rs = fn_ejecuta_query($sqlUpdSeriesStr);

        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlUpdSeriesStr;
	            $a['successMessage'] = getSeriesUpdateMsg();
			} else {	
            	$a['success'] = false;
	            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdSeriesStr;
       	 	}

        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
	    echo json_encode($a);
    }
?>