<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
    session_start();
	$_SESSION['modulo'] = "catGenerales";
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
	
    switch($_REQUEST['catGeneralesActionHdn'])
	{
        case 'getGeneralesGroup':
            getGeneralesGroup();
            break;
        case 'getGenerales':
            getGenerales();
            break;
		case 'addGenerales':
            addGenerales();
            break;
		case 'updGenerales':
            updGenerales();
            break;    
        default:
            echo '';
    }
			
	function getGeneralesGroup() {
    	$ls_where = "";
		
		if($_SESSION['idioma'] && $_SESSION['idioma'] != "")
			$ls_where = "WHERE idioma = '" . $_SESSION['idioma'] . "' ";

	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catGeneralesTablaTxt'], "tabla", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }

		$sqlGeneralesGroupStr = "SELECT tabla, columna " .
		       "FROM cageneralestbl  " . $ls_where .
		       "GROUP BY tabla, columna";      
		
		$rs = fn_ejecuta_query($sqlGeneralesGroupStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
	}	

	function getGenerales() {
		$ls_where = "";
		
		if($_SESSION['idioma'] && $_SESSION['idioma'] != "")
			$ls_where = "WHERE idioma = '" . $_SESSION['idioma'] . "' ";

	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catGeneralesTablaTxt'], "tabla", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catGeneralesColumnaTxt'], "columna", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catGeneralesValorTxt'], "valor", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catGeneralesNombreTxt'], "nombre", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catGeneralesEstatusHdn'], "estatus", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catGeneralesIdiomaHdn'], "idioma", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }

		$sqlGetGeneralesStr = "SELECT tabla, columna, valor, nombre, estatus, idioma " .
		       "FROM cageneralestbl  " . $ls_where;     

		$rs = fn_ejecuta_query($sqlGetGeneralesStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
	}
	
	function addGenerales() {
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catGeneralesTablaTxt'] == "")
        {
            $e[] = array('id'=>'catGeneralesTablaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesColumnaTxt'] == "")
        {
            $e[] = array('id'=>'catGeneralesColumnaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesValorHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesValorHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesNombreHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesNombreHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesIdiomaHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesIdiomaHdn','msg'=>getRequerido());
		    $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

		//Revisar que ningun dato del grid esté vacio
        $valorArr = explode('|', substr($_REQUEST['catGeneralesValorHdn'], 0, -1));
        if(in_array('', $valorArr)){
        	$e[] = array('id'=>'catGeneralesValorHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

		$estatusArr =  explode('|', substr($_REQUEST['catGeneralesEstatusHdn'], 0, -1));
		if(in_array('', $estatusArr)){
        	$e[] = array('id'=>'catGeneralesEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

		$nombreArr = explode('|', substr($_REQUEST['catGeneralesNombreHdn'], 0, -1));
		if(in_array('', $nombreArr)){
        	$e[] = array('id'=>'catGeneralesNombreHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		$idiomaArr = explode('|', substr($_REQUEST['catGeneralesIdiomaHdn'], 0, -1));
		if(in_array('', $idiomaArr)){
        	$e[] = array('id'=>'catGeneralesIdiomaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){

			$sqlAddGeneralesStr = "INSERT INTO cageneralestbl VALUES";

            for($nInt=0;$nInt<count($valorArr);$nInt++){
            	$sqlAddGeneralesStr = $sqlAddGeneralesStr.
            						"('".$_REQUEST['catGeneralesTablaTxt']."', ".
            						"'".$_REQUEST['catGeneralesColumnaTxt']."', ".
            						"'".$valorArr[$nInt]."', ".
            						"'".$nombreArr[$nInt]."', ".
            						"'".$estatusArr[$nInt]."', ".
            						"'".$idiomaArr[$nInt]."')";


            	if($nInt+1<count($valorArr)){
            		$sqlAddGeneralesStr = $sqlAddGeneralesStr.",";
            	}
            }
			
            $rs = fn_ejecuta_query($sqlAddGeneralesStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == ""))
			{
			    $a['sql'] = $sqlAddGeneralesStr;
                $a['successMessage'] = getGeneralesSuccessMsg();
                $a['id'] = $_REQUEST['catGeneralesTablaTxt'];
			}
		    else
			{
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddGeneralesStr;
			}
		}
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
	
	function updGenerales() {
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catGeneralesNombreHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesNombreHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catGeneralesValorHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesValorHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesIdiomaHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesIdiomaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        //Revisar que ningun dato del grid esté vacio
        $valorArr = explode('|', substr($_REQUEST['catGeneralesValorHdn'], 0, -1));
        if(in_array('', $valorArr)){
        	$e[] = array('id'=>'catGeneralesValorHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

		$estatusArr =  explode('|', substr($_REQUEST['catGeneralesEstatusHdn'], 0, -1));
		if(in_array('', $estatusArr)){
        	$e[] = array('id'=>'catGeneralesEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

		$nombreArr = explode('|', substr($_REQUEST['catGeneralesNombreHdn'], 0, -1));
		if(in_array('', $nombreArr)){
        	$e[] = array('id'=>'catGeneralesNombreHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		$idiomaArr = explode('|', substr($_REQUEST['catGeneralesIdiomaHdn'], 0, -1));
		if(in_array('', $idiomaArr)){
        	$e[] = array('id'=>'catGeneralesIdiomaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
			
        if ($a['success'] == true) {
			$sqlAddGeneralesStr = "INSERT INTO cageneralestbl VALUES";
			$newInt = 0;

            for($nInt=0;$nInt<count($valorArr);$nInt++){
            	//Select para no existentes
            	$sqlCheckGenExistStr = "SELECT * FROM cageneralestbl ".
            					   	   "WHERE tabla= '".$_REQUEST['catGeneralesTablaTxt']."' ".
            					   	   "AND columna= '".$_REQUEST['catGeneralesColumnaTxt']."' ".
            					   	   "AND valor= '".$valorArr[$nInt]."' ".
            					   	   "AND idioma= '".$idiomaArr[$nInt]."'";

            	$rs = fn_ejecuta_query($sqlCheckGenExistStr);

            	//Si hay UPDATE
            	if(count($rs) > 0){
            		$sqlUpdGeneralesStr = "UPDATE cageneralestbl ".
            							  "SET nombre= '".$nombreArr[$nInt]."', ".
            							  "estatus= '".$estatusArr[$nInt]."' ".
            							  "WHERE tabla= '".$_REQUEST['catGeneralesTablaTxt']."' ".
            							  "AND columna= '".$_REQUEST['catGeneralesColumnaTxt']."' ".
            							  "AND valor= '".$valorArr[$nInt]."' ".
            						  	  "AND idioma= '".$idiomaArr[$nInt]."'";

            		$rs = fn_ejecuta_query($sqlUpdGeneralesStr);

            		if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    		$a['sql'] = $sqlUpdGeneralesStr;
                		$a['successMessage'] = getGeneralesUpdateMsg();
                		$a['id'] = $_REQUEST['catGeneralesTablaTxt'];
					} else {
               			$a['success'] = false;
                		$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdGeneralesStr;
					}

            	} else {//Si no agrega la linea
                    //Si es el primer insert no agrega coma antes
                    if($newInt != 0){
                        $sqlAddGeneralesStr = $sqlAddGeneralesStr.",";
                    }

            		$sqlAddGeneralesStr = $sqlAddGeneralesStr.
            						"('".$_REQUEST['catGeneralesTablaTxt']."', ".
            						"'".$_REQUEST['catGeneralesColumnaTxt']."', ".
            						"'".$valorArr[$nInt]."', ".
            						"'".$nombreArr[$nInt]."', ".
            						"'".$estatus[$nInt]."', ".
            						"'".$idiomaArr[$nInt]."')";

            		$newInt++;
            	}
			}

			if($newInt > 0){
				$rs = fn_ejecuta_query($sqlAddGeneralesStr);

				if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    	$a['sql'] = $sqlAddGeneralesStr;
                	$a['successMessage'] = getGeneralesSuccessMsg();
                	$a['id'] = $_REQUEST['catGeneralesTablaTxt'];
				} else {
               		$a['success'] = false;
                	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddGeneralesStr;
				}
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
?>