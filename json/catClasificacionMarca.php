<?php
	session_start();
	$_SESSION['modulo'] = "catClasificacionMarca";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");

    $_REQUEST = trasformUppercase($_REQUEST);
	
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
        case 'getClasificacionesGroup':
            getClasificacionesGroup();
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
	
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catClasificacionMarcaClasificacionTxt'], "cm.clasificacion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catClasificacionMarcaMarcaHdn'], "cm.marca", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0)
	   	{
    		$lsCondicionStr = fn_construct($_REQUEST['catClasificacionMarcaDescripcionTxt'], "cm.descripcion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0)
        {
            $lsCondicionStr = fn_construct($_REQUEST['catClasificacionMarcaSubMarcaHdn'], "cm.submarca", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

	    $sqlGetClasificacionMarcaStr = "SELECT cm.*, ".
                                       "(SELECT mu.descripcion FROM caMarcasUnidadesTbl mu WHERE mu.marca=cm.marca) AS nombreMarca, ".
                                       "(SELECT cg.nombre FROM caGeneralesTbl cg WHERE cg.valor = cm.subMarca) AS nombreSubMarca ".
                                       "FROM caClasificacionMarcaTbl cm " . $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetClasificacionMarcaStr);
			
		echo json_encode($rs);
    }

    function getClasificacionesGroup(){
        $lsWhereStr = "";
    
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catClasificacionMarcaClasificacionTxt'], "cm.clasificacion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catClasificacionMarcaMarcaHdn'], "cm.marca", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0)
        {
            $lsCondicionStr = fn_construct($_REQUEST['catClasificacionMarcaDescripcionTxt'], "cm.descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0)
        {
            $lsCondicionStr = fn_construct($_REQUEST['catClasificacionMarcaSubMarcaHdn'], "cm.submarca", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetClasificacionMarcaStr = "SELECT cm.clasificacion ".
                                       "FROM caClasificacionMarcaTbl cm " . $lsWhereStr.
                                       "GROUP BY cm.clasificacion ";     
        
        $rs = fn_ejecuta_query($sqlGetClasificacionMarcaStr);
            
        echo json_encode($rs);
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
        if($_REQUEST['catClasificacionMarcaSubMarcaHdn'] == "")
        {
            $e[] = array('id'=>'catClasificacionMarcaSubMarcaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlAddClasificacionMarcaStr = "INSERT INTO caClasificacionMarcaTbl ".
                                           "VALUES(".
                                           "'".$_REQUEST['catClasificacionMarcaClasificacionTxt']."', ".
                                           "'".$_REQUEST['catClasificacionMarcaMarcaHdn']."', ".
                                           "'".$_REQUEST['catClasificacionMarcaDescripcionTxt']."', ".
                                           "'".$_REQUEST['catClasificacionMarcaSubMarcaHdn']."')";

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

        if($_REQUEST['catClasificacionMarcaMarcaHdn'] == ""){
            $e[] = array('id'=>'catClasificacionMarcaMarcaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catClasificacionMarcaClasificacionTxt'] == ""){
            $e[] = array('id'=>'catClasificacionMarcaClasificacionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catClasificacionMarcaDescripcionTxt'] == ""){
            $e[] = array('id'=>'catClasificacionMarcaDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdClasificacionMarcaStr = "UPDATE caClasificacionMarcaTbl ".
                                           "SET descripcion='".$_REQUEST['catClasificacionMarcaDescripcionTxt']."', ".
                                           "subMarca = '".$_REQUEST['catClasificacionMarcaSubMarcaHdn']."' ".
                                           "WHERE marca='".$_REQUEST['catClasificacionMarcaMarcaHdn']."' ".
                                           "AND clasificacion ='".$_REQUEST['catClasificacionMarcaClasificacionTxt']."'";

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

        $sqlDeleteClasificacionMarcaStr = "DELETE FROM caClasificacionMarcaTbl ".
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