<?php
    session_start();
	$_SESSION['modulo'] = "catMarcasUnidades";
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
    

    switch($_REQUEST['catMarcasUnidadesActionHdn']){
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
    	$lsWhereStr = ""; 

	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catMarcasUnidadesMarcaTxt'], "a.marca", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catMarcasUnidadesDescripcionTxt'], "a.descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catMarcasUnidadesTipoHdn'], "a.tipoMarca", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

		$sqlGetMarcasUnidadesStr = "SELECT a.*, ".
                                   "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.valor=a.tipoMarca AND g.tabla='caMarcasUnidadesTbl' AND g.columna='tipoMarca') AS nombreTipoMarca ".
		                           "FROM caMarcasUnidadesTbl a " . $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetMarcasUnidadesStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['marcaDesc'] = $rs['root'][$iInt]['marca']." - ".$rs['root'][$iInt]['descripcion'];
        }
			
		echo json_encode($rs);
	}

    function addMarcasUnidades(){
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
            $sqlAddMarcasUnidadesStr = "INSERT INTO caMarcasUnidadesTbl (marca, descripcion, tipoMarca) " . 
                                       "VALUES (". 
                                       "'".$_REQUEST['catMarcasUnidadesMarcaTxt'] . "', ".
                                       "'".$_REQUEST['catMarcasUnidadesDescripcionTxt']."', ".                   
                                       "'".$_REQUEST['catMarcasUnidadesTipoHdn']."')";
            
            $rs = fn_ejecuta_query($sqlAddMarcasUnidadesStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlAddMarcasUnidadesStr;
                $a['successMessage'] = getMarcasUnidadesSuccessMsg();
                $a['id'] = $_REQUEST['catMarcasUnidadesMarcaTxt'];
            } else {
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
            $sqlUpdateMarcasUnidadesStr = "UPDATE caMarcasUnidadesTbl ".
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
            $sqlDeleteMarcasUnidadesStr = "DELETE FROM caMarcasUnidadesTbl " . 
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