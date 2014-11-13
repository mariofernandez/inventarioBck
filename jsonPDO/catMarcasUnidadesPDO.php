<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
    session_start();
	$_SESSION['modulo'] = "catMarcasUnidades";
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
    

    switch($_REQUEST['catMarcasUnidadesActionHdn'])
	{
        case 'getMarcasUnidades':
            getMarcasUnidades();
            break;
        case 'addMarcasUnidades':
            addMarcasUnidades();
            break; 
        case 'updMarcasUnidades':
            updMarcasUnidades();
            break;
        case 'dltMarcasUnidades':
            dltMarcasUnidades();
            break;                                                          
        default:
            
    }
			
	function getMarcasUnidades(){
    	$ls_where = "WHERE a.tipoMarca = b.valor ".
                    "AND b.tabla = 'camarcasunidadestbl'"; 

	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['marca_01'], "a.marca", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }

        if ($gb_error_filtro == 0)
        {
            $ls_condicion = fn_construct($_REQUEST['catMarcasUnidadesTipoHdn'], "a.tipoMarca", 1);
            $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
        }

		$sqlGetMarcasUnidadesStr = "SELECT a.marca, a.descripcion, b.valor, b.nombre " .
		                           "FROM camarcasunidadestbl a, cageneralestbl b  " . $ls_where;     
		
		$rs = fn_ejecuta_query($sqlGetMarcasUnidadesStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
	}

    function addMarcasUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;
        
        if($_REQUEST['catMarcasUnidadesMarcaTxt'] == "")
        {
            $e[] = array('id'=>'catMarcasUnidadesMarcaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catMarcasUnidadesTipoHdn'] == "")
        {
            $e[] = array('id'=>'catMarcasUnidadesTipoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catMarcasUnidadesDescripcionTxt'] == "")
        {
            $e[] = array('id'=>'catMarcasUnidadesDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        
        if ($a['success']) 
        {
            $sqlAddMarcasUnidadesStr = "INSERT INTO camarcasunidadestbl (marca, descripcion, tipoMarca) " . 
                                       "VALUES (". 
                                       "'".$_REQUEST['catMarcasUnidadesMarcaTxt'] . "', ".
                                       "'".$_REQUEST['catMarcasUnidadesDescripcionTxt']."', ".                   
                                       "'".$_REQUEST['catMarcasUnidadesTipoHdn']."')";
            
            $rs = fn_ejecuta_query($sqlAddMarcasUnidadesStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == ""))
            {
                $a['sql'] = $sqlAddMarcasUnidadesStr;
                $a['successMessage'] = getMarcasUnidadesSuccessMsg();
                $a['id'] = $_REQUEST['catMarcasUnidadesMarcaTxt'];
            }
            else
            {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddMarcasUnidadesStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }    

    function updMarcasUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;
        
        if($_REQUEST['catMarcasUnidadesMarcaTxt'] == ""){
            $e[] = array('id'=>'catMarcasUnidadesMarcaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catMarcasUnidadesTipoHdn'] == ""){
            $e[] = array('id'=>'catMarcasUnidadesTipoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catMarcasUnidadesDescripcionTxt'] == ""){
            $e[] = array('id'=>'catMarcasUnidadesDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        
        if ($a['success']) {
            $sqlUpdateMarcasUnidadesStr = "UPDATE camarcasunidadestbl ".
                                          "SET descripcion= '".$_REQUEST['catMarcasUnidadesDescripcionTxt']."', ".
                                          "tipoMarca= '".$_REQUEST['catMarcasUnidadesTipoHdn']."' ".
                                          "WHERE marca= '".$_REQUEST['catMarcasUnidadesMarcaTxt']."'";           
            

            $rs = fn_ejecuta_query($sqlUpdateMarcasUnidadesStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlUpdateMarcasUnidadesStr;
                $a['successMessage'] = getMarcasUnidadesUpdateMsg();
                $a['id'] = $_REQUEST['catMarcasUnidadesMarcaTxt'];
            }
            else{
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdateMarcasUnidadesStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function dltMarcasUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;
        
        if($_REQUEST['catMarcasUnidadesMarcaTxt'] == ""){
            $e[] = array('id'=>'catMarcasUnidadesMarcaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
            
        if ($a['success']){
            $sqlDeleteMarcasUnidadesStr = "DELETE FROM camarcasunidadestbl " . 
                   "WHERE marca= '".$_REQUEST['catMarcasUnidadesMarcaTxt']."'"; 

            
            $rs = fn_ejecuta_query($sqlDeleteMarcasUnidadesStr);
            
            if($_SESSION['error_sql'] == "")
            {
                $a['sql'] = $sqlDeleteMarcasUnidadesStr;
                $a['successmessage'] = getMarcasUnidadesDeleteMsg();
                $a['id'] = $_REQUEST['catMarcasUnidadesMarcaTxt'];
            }
            else
            {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteMarcasUnidadesStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }    
?>