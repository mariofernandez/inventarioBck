<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
	session_start();
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
		case 'dltRegion':
			dltRegion();
			break;
		default:
			echo '';	
	}
	
	function getRegiones(){
    	$lsWhereStr = "";

	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catRegionesRegionTxt'], "region", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catRegionesNombreTxt'], "nombre", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catRegionesColorTxt'], "color", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }

	    $sqlGetRegionesStr = "SELECT * FROM caregionestbl".$ls_where;
		
		$rs = fn_ejecuta_query($sqlGetRegionesStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
    }

	function addRegion(){
		
		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catRegionesRegionTxt'] == "")
        {
            $e[] = array('id'=>'catRegionesRegionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catRegionesNombreTxt'] == "")
        {
            $e[] = array('id'=>'catRegionesNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catRegionesColorTxt'] == "")
        {
            $e[] = array('id'=>'catRegionesColorTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){

        	$sqlAddRegionStr = "INSERT INTO caregionestbl ".
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

        	$sqlUpdRegionStr = "UPDATE caregionestbl ".
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

    function dltRegion(){

    	$a = array();
        $e = array();
        $a['success'] = true;

        $sqlDeleteRegionStr = "DELETE FROM caregionestbl WHERE idRegion=".$_REQUEST['catRegionesIdHdn'];

        $rs = fn_ejecuta_query($sqlDeleteRegionStr);

        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
			$a['sql'] = $sqlDeleteRegionStr;
            $a['successMessage'] = getRegionDeleteMsg();
            $a['id'] = $_REQUEST['catRegionesIdHdn'];
		} else {
	        $a['success'] = false;
            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteRegionStr;
		}

		$a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>