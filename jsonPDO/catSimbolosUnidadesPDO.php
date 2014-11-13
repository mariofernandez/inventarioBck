<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
    session_start();
	$_SESSION['modulo'] = "catSimboloUnidades";
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
	
    switch($_REQUEST['catSimbolosUnidadesActionHdn'])
	{
        case 'getClasificacionMarcaCmb':
            getClasificacionMarcaCmb();
            break;   
        case 'getSimbolosUnidades':
            getSimbolosUnidades();
            break;
        case 'addSimbolosUnidades':
            addSimbolosUnidades();
            break;
        case 'updSimbolosUnidades':
            updSimbolosUnidades();
            break;  
        case 'dltSimbolosUnidades':
            dltSimbolosUnidades();                                                   
        default:
            echo '';           
    }

    function getClasificacionMarcaCmb(){
    	$lsWhereStr = "";

	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catSimbolosUnidadesMarcaHdn'], "marca", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }

		$sql = "SELECT * FROM caclasificacionmarcatbl  " . $lsWhereStr;
		       	
		       	
		$rs = fn_ejecuta_query($sql);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
	}		

    function getSimbolosUnidades(){
    	
      	$lsWhereStr = "";

        if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catSimbolosUnidadesMarcaHdn'], "marca", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);

	    }

	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catSimbolosUnidadesSimboloTxt'], "simboloUnidad", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);

	    }

		$sqlGetSimbolosStr = "SELECT * FROM casimbolosunidadestbl " . $lsWhereStr;      		      

		$rs = fn_ejecuta_query($sqlGetSimbolosStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
    }

    function addSimbolosUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catSimbolosUnidadesSimboloTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesSimboloTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catSimbolosUnidadesDescripcionTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catSimbolosUnidadesTipoOrigenUnidadHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesTipoOrigenUnidadHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesClasificacionMarcaHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesClasificacionMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesImporteBonificacionTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesImporteBonificacionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesRepuveTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesRepuveTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesMarcaHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesTipoUnidadHdn'] == "")
        {
            $e[] = array('id'=>'catSimbolosUnidadesTipoUnidadHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }        
         if ($a['success']) 
        {
            $sqlAddSimbolosStr = "INSERT INTO casimbolosunidadestbl (simboloUnidad, descripcion, tipoOrigenUnidad, clasificacion, importeBonificacion, tieneRepuve, marca, tipoUnidad) " . 
                   "VALUES ('" . $_REQUEST['catSimbolosUnidadesSimboloTxt'] . "', '".$_REQUEST['catSimbolosUnidadesDescripcionTxt']."', " . 
                   "'" . $_REQUEST['catSimbolosUnidadesTipoOrigenUnidadHdn'] . "', '" . $_REQUEST['catSimbolosUnidadesClasificacionMarcaHdn'] . "', " .
                   "'" . $_REQUEST['catSimbolosUnidadesImporteBonificacionTxt'] . "', '" . $_REQUEST['catSimbolosUnidadesRepuveTxt'] . "', " .
                   "'" . $_REQUEST['catSimbolosUnidadesMarcaHdn'] . "', '" . $_REQUEST['catSimbolosUnidadesTipoUnidadHdn'] . "'
                   );";

            $rs = fn_ejecuta_query($sqlAddSimbolosStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == ""))
            {
                $a['sql'] = $sqlAddSimbolosStr;
                $a['successmessage'] = getSimbolosUnidadesSuccessMsg();
                $a['id'] = $_REQUEST['catSimbolosUnidadesSimboloTxt'];
            }
            else
            {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddSimbolosStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
	
    function updSimbolosUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catSimbolosUnidadesDescripcionTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catSimbolosUnidadesTipoOrigenUnidadHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesTipoOrigenUnidadHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesClasificacionMarcaHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesClasificacionMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesImporteBonificacionTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesImporteBonificacionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesRepuveTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesRepuveTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesMarcaHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesTipoUnidadHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesTipoUnidadHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		
        if ($a['success']) 
		{
                      $sql = "UPDATE casimbolosunidadestbl SET descripcion = '" . $_REQUEST['catSimbolosUnidadesDescripcionTxt'] . "' " .
                         ", tipoOrigenUnidad = '" . $_REQUEST['catSimbolosUnidadesTipoOrigenUnidadHdn'] . "' " .
                            ", clasificacion = '" . $_REQUEST['catSimbolosUnidadesClasificacionMarcaHdn'] . "' " .
                      ", importeBonificacion = '" . $_REQUEST['catSimbolosUnidadesImporteBonificacionTxt'] . "' " .
                              ", tieneRepuve = '" . $_REQUEST['catSimbolosUnidadesRepuveTxt'] . "' " .
                                    ", marca = '" . $_REQUEST['catSimbolosUnidadesMarcaHdn'] . "' " .
                               ", tipoUnidad = '" . $_REQUEST['catSimbolosUnidadesTipoUnidadHdn'] . "' " .
                             "WHERE simboloUnidad = '" . $_REQUEST['catSimbolosUnidadesSimboloTxt'] . "' ;";			
			

			$rs = fn_ejecuta_query($sql);
			
			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == ""))
			{
			    $a['sql'] = $sql;
                $a['successMessage'] = getSimbolosUnidadesUpdateMsg();
                $a['id'] = $_REQUEST['catSimbolosUnidadesSimboloTxt'];
			}
		    else
			{
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sql;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}

    function dltSimbolosUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catSimbolosUnidadesSimboloTxt'] == "")
        {
            $e[] = array('id'=>'catSimbolosUnidadesSimboloTxt','msg'=>'Falto Seleccionar el Simbolo');
			$a['errorMessage'] = 'Los campos marcados con "*" son Requeridos';
            $a['success'] = false;
        }
			
        if ($a['success']) 
		{
            $sql = "DELETE FROM casimbolosunidadestbl " . 
			       "WHERE simboloUnidad = '" . $_REQUEST['catSimbolosUnidadesSimboloTxt'] . "' ;"; 

			
			$rs = fn_ejecuta_query($sql);
			
			if($_SESSION['error_sql'] == "")
			{
			    $a['sql'] = $sql;
                $a['successmessage'] = getSimbolosUnidadesDeleteMsg();
                $a['id'] = $_REQUEST['catSimbolosUnidadesSimboloTxt'];
			}
		    else
			{
                $a['success'] = false;
                $a['errormessage'] = $_SESSION['error_sql'] . "<br>" . $sql;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}

?>