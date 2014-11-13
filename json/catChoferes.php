<?php
    session_start();
	$_SESSION['modulo'] = "catChoferes";
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
	
    switch($_REQUEST['catChoferesActionHdn']){
        case 'getChoferes':
            getChofer();
            break;
        case 'addChofer':
        	addChofer();
        	break;
        case 'updChofer':
        	updChofer();
        	break;
        case 'dltChofer':
        	dltChofer();
        	break;
        default:
            echo '';
    }
			
	function getChofer() {
    	$lsWhereStr = "";

	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catChoferesClaveChoferTxt'], "claveChofer", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catChoferesApellidoPaternoTxt'], "apellidoPaterno", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catChoferesApellidoMaternoTxt'], "apellidoMaterno", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catChoferesNombreTxt'], "nombre", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catChoferesLicenciaTxt'], "licencia", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catChoferesVigenciaLicenciaTxt'], "vigenciaLicencia", 2);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catChoferesCuentaContableTxt'], "cuentaContable", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catChoferesTipoChoferHdn'], "tipoChofer", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }       
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catChoferesEstatusHdn'], "estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }    
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catChoferesCentroDistribucionHdn'], "centroDistribucionOrigen", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

		$sqlGetChoferesStr = "SELECT ch.*, ".
                             "(SELECT dc.descripcionCentro FROM cadistribuidorescentrostbl dc ".
                                "WHERE ch.centroDistribucionOrigen = dc.distribuidorCentro) AS nombreDistOrigen, " .
                             "(SELECT g.nombre FROM cageneralestbl g WHERE g.valor=ch.estatus ".
                                "AND g.tabla='caChoferesTbl' AND g.columna='estatus') AS nombreEstatus, ".
                             "(SELECT g.nombre FROM cageneralestbl g WHERE g.valor=ch.tipoChofer ".
                                "AND g.tabla='caChoferesTbl' AND g.columna='tipoChofer') AS nombreTipo ".
		                     "FROM caChoferesTbl ch " . $lsWhereStr. "ORDER BY ch.claveChofer ";
		
		$rs = fn_ejecuta_query($sqlGetChoferesStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['nombreChofer'] = $rs['root'][$iInt]['claveChofer']." - ".
                                                 $rs['root'][$iInt]['nombre']." ".
                                                 $rs['root'][$iInt]['apellidoPaterno']." ".
                                                 $rs['root'][$iInt]['apellidoMaterno'];
        }
			
		echo json_encode($rs);
	}

	function addChofer() {
		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catChoferesClaveChoferTxt'] == ""){
            $e[] = array('id'=>'catChoferesClaveChoferTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesApellidoPaternoTxt'] == ""){
            $e[] = array('id'=>'catChoferesApellidoPaternoTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesNombreTxt'] == ""){
            $e[] = array('id'=>'catChoferesNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesLicenciaTxt'] == ""){
            $e[] = array('id'=>'catChoferesLicenciaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesCuentaContableTxt'] == ""){
            $e[] = array('id'=>'catChoferesCuentaContableTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesEstatusHdn'] == ""){
            $e[] = array('id'=>'catChoferesEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesTipoChoferHdn'] == ""){
            $e[] = array('id'=>'catChoferesTipoChoferHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesVigenciaLicenciaTxt'] == ""){
            $e[] = array('id'=>'catChoferesVigenciaLicenciaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesCentroDistribucionHdn'] == ""){
            $e[] = array('id'=>'catChoferesCentroDistribucionHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
        	$sqlAddChoferStr = "INSERT INTO caChoferesTbl ".
        					   "VALUES(".
        					   $_REQUEST['catChoferesClaveChoferTxt'].", ".
        					   "'".$_REQUEST['catChoferesApellidoPaternoTxt']."', ".
        					   "'".$_REQUEST['catChoferesApellidoMaternoTxt']."', ".
        					   "'".$_REQUEST['catChoferesNombreTxt']."', ".
        					   "'".$_REQUEST['catChoferesLicenciaTxt']."',".
        					   "'".$_REQUEST['catChoferesCuentaContableTxt']."',".
        					   "'".$_REQUEST['catChoferesEstatusHdn']."',".
        					   "'".$_REQUEST['catChoferesTipoChoferHdn']."', ".
        					   "'".$_REQUEST['catChoferesVigenciaLicenciaTxt']."',".
        					   "'".$_REQUEST['catChoferesCentroDistribucionHdn']."')";

			$rs = fn_ejecuta_query($sqlAddChoferStr);
			
			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddChoferStr;
            	$a['successMessage'] = getChoferesSuccessMsg();
			} else {
            	$a['success'] = false;
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddChoferStr;

                $errorNoArr = explode(":", $_SESSION['error_sql']);
            	if($errorNoArr[0] == '1062'){
            		$e[] = array('id'=>'duplicate','msg'=>getChoferesDuplicateMsg());	
            	}
        	}	
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}

	function updChofer() {
		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catChoferesClaveChoferTxt'] == ""){
            $e[] = array('id'=>'catChoferesClaveChoferTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesApellidoPaternoTxt'] == ""){
            $e[] = array('id'=>'catChoferesApellidoPaternoTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesNombreTxt'] == ""){
            $e[] = array('id'=>'catChoferesNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesLicenciaTxt'] == ""){
            $e[] = array('id'=>'catChoferesLicenciaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesCuentaContableTxt'] == ""){
            $e[] = array('id'=>'catChoferesCuentaContableTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesEstatusHdn'] == ""){
            $e[] = array('id'=>'catChoferesEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesTipoChoferHdn'] == ""){
            $e[] = array('id'=>'catChoferesTipoChoferHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesVigenciaLicenciaTxt'] == ""){
            $e[] = array('id'=>'catChoferesVigenciaLicenciaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesCentroDistribucionHdn'] == ""){
            $e[] = array('id'=>'catChoferesCentroDistribucionHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true) {
        	
        	$sqlUpdChoferStr = "UPDATE caChoferesTbl ".
        				   		"SET apellidoPaterno= '".$_REQUEST['catChoferesApellidoPaternoTxt']."', ".
        				   		"apellidoMaterno= '".$_REQUEST['catChoferesApellidoMaternoTxt']."', ".
        				   		"nombre= '".$_REQUEST['catChoferesNombreTxt']."', ".
        				   		"licencia= '".$_REQUEST['catChoferesLicenciaTxt']."', ".
        				   		"cuentaContable= '".$_REQUEST['catChoferesCuentaContableTxt']."', ".
        				   		"estatus= '".$_REQUEST['catChoferesEstatusHdn']."', ".
        				   		"tipoChofer='".$_REQUEST['catChoferesTipoChoferHdn']."', ".
        				   		"vigenciaLicencia= '".$_REQUEST['catChoferesVigenciaLicenciaTxt']."', ".
        				   		"centroDistribucionOrigen= '".$_REQUEST['catChoferesCentroDistribucionHdn']."' ".
								"WHERE claveChofer=".$_REQUEST['catChoferesClaveChoferTxt'];
		
			$rs = fn_ejecuta_query($sqlUpdChoferStr);

        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                	$a['sql'] = $sqlUpdChoferStr;
                	$a['successMessage'] = getChoferesUpdateMsg();
                	$a['id'] = $_REQUEST['catChoferesClaveChoferTxt'];
            } else {
                $a['success'] = false;
               	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdChoferStr;

                $errorNoArr = explode(":", $_SESSION['error_sql']);
                if($errorNoArr[0] == '1062'){
	                $e[] = array('id'=>'catChoferesClaveChoferTxt','msg'=>getChoferesDuplicateMsg());  
            	}
        	}
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}

	function dltChofer(){
		$a = array();
        $e = array();
        $a['success'] = true;

        $sqlDeleteChoferStr = "DELETE FROM caChoferesTbl WHERE claveChofer=".$_REQUEST['catChoferesClaveChoferTxt'];
        
        $rs = fn_ejecuta_query($sqlDeleteChoferStr);

        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
            $a['sql'] = $sqlDeleteChoferStr;
            $a['successMessage'] = getChoferesDeleteMsg();
            $a['id'] = $_REQUEST['catChoferesClaveChoferTxt'];
        } else {
            $a['success'] = false;
            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteChoferStr;
        }
        
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
?>