<?php
	session_start();
	$_SESSION['modulo'] = "catColores";
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

        switch($_REQUEST['catColoresActionHdn']){
        case 'getColores':
        	getColores();
        	break;   
        case 'addColores':
        	addColores();
        	break;
        case 'updColores':
            updColores();
            break;
        default:
        	echo '';
            
    }

    function getColores(){
    	$lsWhereStr = "WHERE cu.marca = mu.marca ";

    	if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catColoresMarcaHdn'], "cu.marca", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catColoresColorTxt'], "cu.color", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catColoresDescripcionTxt'], "cu.descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

	    $sqlGetColoresStr = "SELECT cu.*, mu.descripcion AS descMarca ".
                            "FROM caColorUnidadesTbl cu, caMarcasUnidadesTbl mu ".$lsWhereStr;
		
		$rs = fn_ejecuta_query($sqlGetColoresStr);

		for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descColor'] = $rs['root'][$iInt]['color']." - ".$rs['root'][$iInt]['descripcion'];
        }
		
		echo json_encode($rs);
    }

    function addColores(){
    	$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catColoresMarcaHdn'] == ""){
            $e[] = array('id'=>'catColoresMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catColoresColorTxt'] == ""){
            $e[] = array('id'=>'catColoresColorTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catColoresDescripcionTxt'] == ""){
            $e[] = array('id'=>'catColoresDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
        	$sqlAddColorStr = "INSERT INTO caColorUnidadesTbl ".
        					  "VALUES(".
        					  "'".$_REQUEST['catColoresMarcaHdn']."', ".
        					  "'".$_REQUEST['catColoresColorTxt']."', ".
        					  "'".$_REQUEST['catColoresDescripcionTxt']."')";

			$rs = fn_ejecuta_query($sqlAddColorStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddColorStr;
            	$a['successMessage'] = getColoresSuccessMsg();
			} else {
            	$a['success'] = false;
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddColorStr;

            	$errorNoArr = explode(":", $_SESSION['error_sql']);
            	if($errorNoArr[0] == '1062'){
            		$e[] = array('id'=>'duplicate','msg'=>getColoresDuplicateMsg());	
            	}
        	}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updColores(){
    	$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catColoresMarcaHdn'] == ""){
            $e[] = array('id'=>'catColoresMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catColoresColorTxt'] == ""){
            $e[] = array('id'=>'catColoresColorTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catColoresDescripcionTxt'] == ""){
            $e[] = array('id'=>'catColoresDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
        	$sqlUpdColoresStr = "UPDATE caColorUnidadesTbl ".
        						"SET descripcion= '".$_REQUEST['catColoresDescripcionTxt']."' ".
        						"WHERE marca= '".$_REQUEST['catColoresMarcaHdn']."' ".
        						"AND color= '".$_REQUEST['catColoresColorTxt']."'";

        	$rs = fn_ejecuta_query($sqlUpdColoresStr);

        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlUpdColoresStr;
            	$a['successMessage'] = getColoresUpdateMsg();
			} else {
            	$a['success'] = false;
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdColoresStr;

            	$errorNoArr = explode(":", $_SESSION['error_sql']);
            	if($errorNoArr[0] == '1062'){
            		$e[] = array('id'=>'duplicate','msg'=>getColoresDuplicateMsg());	
            	}
        	}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>