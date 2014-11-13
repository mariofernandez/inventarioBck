<?php
	//***********
    //FOR PDO USE
    //***********
	//CHECKED
    //***********
    session_start();
	$_SESSION['modulo'] = "catCompanias";
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
	
    switch($_REQUEST['catCompaniasActionHdn']){
        case 'getCompanias':
            getCompanias();
            break;
		case 'addCompania':
			addCompania();
			break;
		case 'updCompania':
			updCompania();
			break;
		case 'dltCompania':
			dltCompania();
			break;
        default:
            echo '';
    }
	
	function getCompanias(){
    	$ls_where = "";

	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catCompaniasCompaniaTxt'], "compania", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catCompaniasDescripcionTxt'], "descripcion", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catCompaniasTipoCompaniaHdn'], "tipoCompania", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }

		$sqlGetCiaStr = "SELECT *, CONCAT(compania, ' - ', descripcion) AS descCiaTractor " .
		       "FROM cacompaniastbl  " . $ls_where;
		
		$rs = fn_ejecuta_query($sqlGetCiaStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
	}
	
	function addCompania(){
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catCompaniasCompaniaTxt'] == "")
        {
            $e[] = array('id'=>'catCompaniasCompaniaTxt','msg'=>getRequerido());
			$a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasDescripcionTxt'] == "")
        {
            $e[] = array('id'=>'catCompaniasDescripcionTxt','msg'=>getRequerido());
			$a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasTipoCompaniaHdn'] == "")
        {
            $e[] = array('id'=>'catCompaniasTipoCompaniaHdn','msg'=>getRequerido());
			$a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catCompaniasEstatusHdn','msg'=>getRequerido());
		    $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
			
        if ($a['success']) 
		{
            $sqlAddCiaStr = "INSERT INTO cacompaniastbl ". 
				   		    "VALUES (".
				   		    "'".$_REQUEST['catCompaniasCompaniaTxt']."', ".
				   		    "'".$_REQUEST['catCompaniasDescripcionTxt']."', ". 
				   			"'".$_REQUEST['catCompaniasTipoCompaniaHdn']."', ".
				  			"'".$_REQUEST['catCompaniasEstatusHdn']."')";
			
			$rs = fn_ejecuta_query($sqlAddCiaStr);
			
			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    $a['sql'] = $sqlAddCiaStr;
                $a['successmessage'] = getCiaSuccesMsg();
                $a['id'] = $_REQUEST['catCompaniasCompaniaTxt'];
			}else{
                $a['success'] = false;
                $a['errormessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddCiaStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}	
	
	function updCompania()
	{
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catCompaniasCompaniaTxt'] == "")
        {
            $e[] = array('id'=>'catCompaniasCompaniaTxt','msg'=>getRequerido());
			$a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasDescripcionTxt'] == "")
        {
            $e[] = array('id'=>'catCompaniasDescripcionTxt','msg'=>getRequerido());
			$a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasTipoCompaniaHdn'] == "")
        {
            $e[] = array('id'=>'catCompaniasTipoCompaniaHdn','msg'=>getRequerido());
			$a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catCompaniasEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catCompaniasEstatusHdn','msg'=>getRequerido());
		    $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
			
        if ($a['success']){
            $sqlUpdateCiaStr = "UPDATE cacompaniastbl ".
            				   "SET descripcion = '".$_REQUEST['catCompaniasDescripcionTxt']."', ".
			       			   "tipoCompania = '".$_REQUEST['catCompaniasTipoCompaniaHdn']."', ".
				   			   "estatus = '" . $_REQUEST['catCompaniasEstatusHdn']."' ".
			       			   "WHERE compania = '".$_REQUEST['catCompaniasCompaniaTxt']."'";
			
			$rs = fn_ejecuta_query($sqlUpdateCiaStr);
			
			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    $a['sql'] = $sqlUpdateCiaStr;
                $a['successmessage'] = getCiaUpdtMsg();
                $a['id'] = $_REQUEST['catCompaniasCompaniaTxt'];
			}else{
                $a['success'] = false;
                $a['errormessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdateCiaStr;
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
			$a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
			
        if ($a['success']) {
            $sql = "DELETE FROM cacompaniastbl " . 
			       "WHERE compania = '".$_REQUEST['catCompaniasCompaniaTxt']."'";
			
			$rs = fn_ejecuta_query($sql);
			
			if($_SESSION['error_sql'] == ""){
			    $a['sql'] = $sql;
                $a['successmessage'] = getCiaDelMsg();
                $a['id'] = $_REQUEST['catCompaniasCompaniaTxt'];
			}else{
                $a['success'] = false;
                $a['errormessage'] = $_SESSION['error_sql'] . "<br>" . $sql;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
?>