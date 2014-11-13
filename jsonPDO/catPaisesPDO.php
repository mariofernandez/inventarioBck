<?php
    //***********
    //FOR PDO USE
    //***********
	session_start();
	$_SESSION['modulo'] = "catPaises";
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
	
    switch($_REQUEST['catPaisesActionHdn'])
	{
        case 'getPaises':
            getPaises();
            break;
        case 'addPais':
        	addPais();
        	break;
        case 'updPais':
        	updPais();
        	break;
        case 'dltPais':
        	dltPais();
        	break;
    }

    function getPaises(){
    	$lsWhereStr = "";

	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catPaisesIdHdn'], "idPais", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catPaisesPaisTxt'], "pais", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }

		$sqlGetPaisStr = "SELECT * FROM capaisestbl " . $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetPaisStr);
			
		$iInt = 0;
		$response->success = true;
		$response->records = $totalInt;

		foreach ($rs as $line) {
			$response->root[$iInt] = $line;
            $iInt++;
		}
			
		echo json_encode($response);
    }

    function addPais(){

    	$a = array();
        $e = array();
        $a['success'] = true;

    	if($_REQUEST['catPaisesPaisTxt'] == ""){
            $e[] = array('id'=>'catPaisesPaisTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){
            //Revisa que no exista un país igual ya hecho
            $sqlCheckPaisStr = "SELECT * FROM capaisestbl ".
                               "WHERE pais= '".$_REQUEST['catPaisesPaisTxt']."'";
            
            $rs = fn_ejecuta_query($sqlCheckPaisStr);

            if(count($rs)){
                $a['errorMessage'] = getPaisDuplicateMsg();
                $a['success'] = false;
            }
        }

        if ($a['success'] == true){
        	$sqlAddPaisStr = "INSERT INTO capaisestbl ".
                             "(pais) ".
        					 "VALUES ('".$_REQUEST['catPaisesPaisTxt']."')";

			$rs = fn_ejecuta_query($sqlAddPaisStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    $a['sql'] = $sqlAddPaisStr;
                $a['successMessage'] = getPaisSuccessMsg();
                $a['id'] = $_REQUEST['catPaisesIdHdn'];
			} else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddPaisStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updPais(){
    	$a = array();
        $e = array();
        $a['success'] = true;

    	if($_REQUEST['catPaisesPaisTxt'] == ""){
            $e[] = array('id'=>'catPaisesPaisTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){
            //Revisa que no exista un país igual ya hecho
            $sqlCheckPaisStr = "SELECT * FROM capaisestbl ".
                               "WHERE pais= '".$_REQUEST['catPaisesPaisTxt']."' ".
                               "AND idPais !=".$_REQUEST['catPaisesIdHdn'];
            
            $rs = fn_ejecuta_query($sqlCheckPaisStr);

            if(count($rs)){
                $a['errorMessage'] = getPaisDuplicateMsg();
                $a['success'] = false;
            }
        }

        if ($a['success'] == true){
        	$sqlUpdatePaisStr = "UPDATE capaisestbl ".
        						"SET pais= '".$_REQUEST['catPaisesPaisTxt']."' ".
        						"WHERE idPais=".$_REQUEST['catPaisesIdHdn'];

        	$rs = fn_ejecuta_query($sqlUpdatePaisStr);

        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    $a['sql'] = $sqlUpdatePaisStr;
                $a['successMessage'] = getPaisUpdateMsg();
                $a['id'] = $_REQUEST['catPaisesIdHdn'];
			} else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdatePaisStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);

    }

    function dltPais(){

    	$a = array();
        $e = array();
        $a['success'] = true;

    	$sqlDeletePaisStr = "DELETE FROM capaisestbl WHERE idPais=".$_REQUEST['catPaisesIdHdn'];
        
        $rs = fn_ejecuta_query($sqlDeletePaisStr);

        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
			$a['sql'] = $sqlDeletePaisStr;
            $a['successMessage'] = getPaisDeleteMsg();
            $a['id'] = $_REQUEST['catPaisesIdHdn'];
		} else {
	        $a['success'] = false;
            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeletePaisStr;
		}
		
		$a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);		

    }
?>