<?php
	session_start();
	$_SESSION['modulo'] = "catClasificacionTarifa";
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

    switch($_REQUEST['catClasificacionTarifaActionHdn']){
	    case 'getClasificacionTarifa':
	    	getClasificacionTarifa();
	    	break;
	    case 'addClasificacionTarifa':
	    	addClasificacionTarifa();
	    	break;
    }

    function getClasificacionTarifa(){
    	$lsWhereStr = "";

    	if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catClasificacionTarifaClasificacionHdn'], "clasificacion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catClasificacionTarifaTarifaHdn'], "idTarifa", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }

	    $sqlGetColoresStr = "SELECT ct.*, ".
	    					"(SELECT tr.descripcion FROM catarifastbl tr WHERE tr.idTarifa=ct.idTarifa) AS nombreTarifa ".
	    					"FROM caClasificacionTarifasTbl ct ".$lsWhereStr;
		
		$rs = fn_ejecuta_query($sqlGetColoresStr);
		
		echo json_encode($rs);
    }

    function addClasificacionTarifa(){
    	$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catClasificacionTarifaClasificacionHdn'] == ""){
            $e[] = array('id'=>'catClasificacionTarifaClasificacionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
         if($_REQUEST['catClasificacionTarifaTarifaHdn'] == ""){
            $e[] = array('id'=>'catClasificacionTarifaTarifaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
        	$sqlAddClasificacionMarcaStr = "INSERT INTO caClasificacionTarifasTbl ".
        								   "VALUES('".$_REQUEST['catClasificacionTarifaClasificacionHdn']."'".
        								   ",".$_REQUEST['catClasificacionTarifaTarifaHdn'].")";
			
			$rs = fn_ejecuta_query($sqlAddClasificacionMarcaStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddClasificacionMarcaStr;
                $a['successMessage'] = getClasificacionTarifaSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddClasificacionMarcaStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>