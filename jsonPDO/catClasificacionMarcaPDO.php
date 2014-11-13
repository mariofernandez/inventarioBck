<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
	session_start();
	$_SESSION['modulo'] = "catClasificacionMarca";
    require("../funciones/generalesPDO.php");
    require("../funciones/construct.php");
    require("../funciones/utilidades.php");
	
    switch($_SESSION['idioma']) {
        case 'ES':
            include("../funciones/idiomas/mensajesES.php");
            break;
        case 'EN':
            include("../funciones/idiomas/mensajesEN.php");
            break;
        default:
            include("../funciones/idiomas/mensajesES.php");
    } 

    switch($_REQUEST['catClasificacionMarcaActionHdn']) {
        case 'getClasificacionMarca':
            getClasificacionMarca();
            break;
        case 'addClasificacionMarca':
        	addClasificacionMarca(); 
            break;
        case 'updClasificacionMarca':
            updClasificacionMarca();  
            break;                                                 
        case 'dltClasificacionMarca':
            dltClasificacionMarca();  
            break;                                                 
        default:
            echo '';
    }	

    function getClasificacionMarca(){
    	$lsWhereStr = "";
	
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catClasificacionMarcaClasificacionTxt'], "clasificacion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catClasificacionMarcaMarcaHdn'], "marca", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catClasificacionMarcaDescripcionTxt'], "descripcion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }

	    $sqlGetClasificacionMarcaStr = "SELECT * FROM caclasificacionmarcatbl " . $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetClasificacionMarcaStr);
			
		$iInt = 0;
		$response->success = true;
		$response->records = $totalInt;

		foreach($rs as $line){
			$response->root[$iInt] = $line;
			$iInt++;
		}
			
		echo json_encode($response);
    }

    function addClasificacionMarca(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catClasificacionMarcaClasificacionTxt'] == "")
        {
            $e[] = array('id'=>'catClasificacionMarcaClasificacionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catClasificacionMarcaMarcaHdn'] == "")
        {
            $e[] = array('id'=>'catClasificacionMarcaMarcaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catClasificacionMarcaDescripcionTxt'] == "")
        {
            $e[] = array('id'=>'catClasificacionMarcaDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {

            $sqlAddClasificacionMarcaStr = "INSERT INTO caclasificacionmarcatbl ".
                                           "VALUES(".
                                           "'".$_REQUEST['catClasificacionMarcaClasificacionTxt']."', ".
                                           "'".$_REQUEST['catClasificacionMarcaMarcaHdn']."', ".
                                           "'".$_REQUEST['catClasificacionMarcaDescripcionTxt']."')";

            $rs = fn_ejecuta_query($sqlAddClasificacionMarcaStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddClasificacionMarcaStr;
                $a['successMessage'] = getClasificacionMarcaSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddClasificacionMarcaStr;
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updClasificacionMarca(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catClasificacionMarcaDescripcionTxt'] == "")
        {
            $e[] = array('id'=>'catClasificacionMarcaDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdClasificacionMarcaStr = "UPDATE caclasificacionmarcatbl ".
                                           "SET descripcion='".$_REQUEST['catClasificacionMarcaDescripcionTxt']."', ".
                                           "marca='".$_REQUEST['catClasificacionMarcaMarcaHdn']."' ".
                                           "WHERE clasificacion ='".$_REQUEST['catClasificacionMarcaClasificacionTxt']."'";

            $rs = fn_ejecuta_query($sqlUpdClasificacionMarcaStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdClasificacionMarcaStr;
                $a['successMessage'] = getClasificacionMarcaUpdateMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdClasificacionMarcaStr;
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function dltClasificacionMarca(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $sqlDeleteClasificacionMarcaStr = "DELETE FROM caclasificacionmarcatbl ".
                                          "WHERE clasificacion='".$_REQUEST['catClasificacionMarcaClasificacionTxt']."' ".
                                          "AND marca='".$_REQUEST['catClasificacionMarcaMarcaHdn']."'";
        
        $rs = fn_ejecuta_query($sqlDeleteClasificacionMarcaStr);

        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
            $a['sql'] = $sqlDeleteClasificacionMarcaStr;
            $a['successMessage'] = getClasificacionMarcaDeleteMsg();
            $a['id'] = $_REQUEST['catClasificacionMarcaClasificacionTxt'];
        } else {
            $a['success'] = false;
            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteClasificacionMarcaStr;
        }
        
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a); 
    }
?>