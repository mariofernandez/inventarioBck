<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
	session_start();
	$_SESSION['modulo'] = "catColonias";
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
	
    switch($_REQUEST['catColoniasActionHdn'])
	{
        case 'getColonias':
            getColonias();
            break;
        case 'getColoniasPorNombre':
            getColoniasPorNombre();
            break;
        case 'addColonia':
        	addColonia();
        	break;
        case 'updColonia':
        	updColonia();
        	break;
        case 'dltColonia':
        	dltColonia();
        	break;
    }

    function getColonias(){
    	$lsWhereStr = "WHERE c.idMunicipio = m.idMunicipio";

    	if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catColoniasIdMunicipioHdn'], "c.idMunicipio", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catColoniasIdColoniaHdn'], "c.idColonia", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catColoniasColoniaTxt'], "c.colonia", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catColoniasCpTxt'], "c.cp", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }

		$sqlGetColoniasStr = "SELECT c.idMunicipio, c.idColonia, c.colonia, c.cp, m.municipio ".
                             "FROM cacoloniastbl c, camunicipiostbl m " . $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetColoniasStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
    }

    function getColoniasPorNombre(){

        $sqlGetColoniasStr = "SELECT c.idColonia, c.colonia FROM cacoloniastbl c, camunicipiostbl m ".
                            "WHERE c.idMunicipio=m.idMunicipio AND m.municipio='".$_REQUEST['catDistribuidoresMunicipioHdn']."'";

        $rs = fn_ejecuta_query($sqlGetColoniasStr);
            
        $i = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$i] = $line;
            $i++;
        }
            
        echo json_encode($response);
    }

    function addColonia(){

    	$a = array();
        $e = array();
        $a['success'] = true;

    	if($_REQUEST['catColoniasIdMunicipioHdn'] == ""){
            $e[] = array('id'=>'catColoniasIdMunicipioHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catColoniasCpTxt'] == ""){
            $e[] = array('id'=>'catColoniasCpTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catColoniasColoniaTxt'] == ""){
            $e[] = array('id'=>'catColoniasColoniaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

         //Revisa que no exista una colonia de igual nombre en el municipio
        $sqlCheckColoniaStr = "SELECT * FROM cacoloniastbl ".
        					 "WHERE idMunicipio=".$_REQUEST['catColoniasIdMunicipioHdn']." ".
        					 "AND colonia= '".$_REQUEST['catColoniasColoniaTxt']."'";
        
        $rs = fn_ejecuta_query($sqlCheckColoniaStr);

        if(count($rs)){
        	$a['errorMessage'] = getColoniaDuplicateMsg();
        	$a['success'] = false;
        }

         if($a['success'] == true){

            $sqlAddColoniaStr = "INSERT INTO cacoloniastbl ".
            					"(idMunicipio, colonia, cp) VALUES(".
            					$_REQUEST['catColoniasIdMunicipioHdn'].", ".
            					"'".$_REQUEST['catColoniasColoniaTxt']."', ".
            					"'".$_REQUEST['catColoniasCpTxt']."')";
            
            $rs = fn_ejecuta_query($sqlAddColoniaStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlAddColoniaStr;
                $a['successMessage'] = getColoniaSuccessMsg();
                $a['id'] = $_REQUEST['catColoniasColoniaTxt'];;
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddColoniaStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updColonia(){
    	$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catColoniasColoniaTxt'] == ""){
            $e[] = array('id'=>'catColoniasColoniaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catColoniasCpTxt'] == ""){
            $e[] = array('id'=>'catColoniasCpTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){

        	$sqlUpdColoniaStr = "UPDATE cacoloniastbl ".
        						"SET colonia= '".$_REQUEST['catColoniasColoniaTxt']."', ".
        						"cp= '".$_REQUEST['catColoniasCpTxt']."' ".
        						"WHERE idColonia=".$_REQUEST['catColoniasIdColoniaHdn'];

        	$rs = fn_ejecuta_query($sqlUpdColoniaStr);

        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlUpdColoniaStr;
                $a['successMessage'] = getColoniaUpdateMsg();
                $a['id'] = $_REQUEST['catColoniasIdColoniaHdn'];;
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdColoniaStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function dltColonia(){
    	$a = array();
        $e = array();
        $a['success'] = true;

        $sqlDeleteColoniaStr = "DELETE FROM cacoloniastbl WHERE idColonia=".$_REQUEST['catColoniasIdColoniaHdn'];
       
        $rs = fn_ejecuta_query($sqlDeleteColoniaStr);

        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
            $a['sql'] = $sqlDeleteColoniaStr;
            $a['successMessage'] = getColoniaDeleteMsg();
            $a['id'] = $_REQUEST['catColoniasIdColoniaHdn'];
        } else {
            $a['success'] = false;
            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteColoniaStr;
        }
        
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>