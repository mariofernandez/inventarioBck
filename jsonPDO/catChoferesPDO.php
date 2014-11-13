<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
    session_start();
	$_SESSION['modulo'] = "catChoferes";
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
	
    switch($_REQUEST['catChoferesActionHdn']){
        case 'getChofer':
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
    	$ls_where = "";

	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['idChofer'], "id_chofer", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }

		$sqlGetChoferesStr = "SELECT *, CONCAT(claveChofer, ' - ', nombre, ' ', apellidoPaterno, ' ', apellidoMaterno) AS nombreChofer " .
		                     "FROM cachoferestbl " . $ls_where;
		
		$rs = fn_ejecuta_query($sqlGetChoferesStr);
			
		$iInt = 0;
		$response->success = true;
		$response->records = $total;
		
		foreach($rs as $line){
			$response->root[$iInt] = $line;
			$iInt++;
		}
			
		echo json_encode($response);
	}

	function addChofer() {
		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catChoferesClaveChoferTxt'] == "")
        {
            $e[] = array('id'=>'catChoferesClaveChoferTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesApellidoPaternoTxt'] == "")
        {
            $e[] = array('id'=>'catChoferesApellidoPaternoTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesNombreTxt'] == "")
        {
            $e[] = array('id'=>'catChoferesNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesLicenciaTxt'] == "")
        {
            $e[] = array('id'=>'catChoferesLicenciaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesCuentaContableTxt'] == "")
        {
            $e[] = array('id'=>'catChoferesCuentaContableTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catChoferesEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesTipoChoferHdn'] == "")
        {
            $e[] = array('id'=>'catChoferesTipoChoferHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesVigenciaLicenciaHdn'] == "")
        {
            $e[] = array('id'=>'catChoferesVigenciaLicenciaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesCentroDistribucionHdn'] == "")
        {
            $e[] = array('id'=>'catChoferesCentroDistribucionHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
        	$sqlAddChoferStr = "INSERT INTO cachoferestbl ".
        					   "VALUES(".
        					   $_REQUEST['catChoferesClaveChoferTxt'].", ".
        					   "'".$_REQUEST['catChoferesApellidoPaternoTxt']."', ".
        					   "'".$_REQUEST['catChoferesApellidoMaternoTxt']."', ".
        					   "'".$_REQUEST['catChoferesNombreTxt']."', ".
        					   "'".$_REQUEST['catChoferesLicenciaTxt']."',".
        					   "'".$_REQUEST['catChoferesCuentaContableTxt']."',".
        					   "'".$_REQUEST['catChoferesEstatusHdn']."',".
        					   $_REQUEST['catChoferesTipoChoferHdn'].", ".
        					   "'".$_REQUEST['catChoferesVigenciaLicenciaHdn']."',".
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

		if($_REQUEST['catChoferesClaveChoferTxt'] == "")
        {
            $e[] = array('id'=>'catChoferesClaveChoferTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesApellidoPaternoTxt'] == "")
        {
            $e[] = array('id'=>'catChoferesApellidoPaternoTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesNombreTxt'] == "")
        {
            $e[] = array('id'=>'catChoferesNombreTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesLicenciaTxt'] == "")
        {
            $e[] = array('id'=>'catChoferesLicenciaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesCuentaContableTxt'] == "")
        {
            $e[] = array('id'=>'catChoferesCuentaContableTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catChoferesEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesTipoChoferHdn'] == "")
        {
            $e[] = array('id'=>'catChoferesTipoChoferHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesVigenciaLicenciaHdn'] == "")
        {
            $e[] = array('id'=>'catChoferesVigenciaLicenciaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catChoferesCentroDistribucionHdn'] == "")
        {
            $e[] = array('id'=>'catChoferesCentroDistribucionHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true) {
        	
        	$sqlUpdChoferStr = "UPDATE cachoferestbl ".
        				   		"SET apellidoPaterno= '".$_REQUEST['catChoferesApellidoPaternoTxt']."', ".
        				   		"apellidoMaterno= '".$_REQUEST['catChoferesApellidoMaternoTxt']."', ".
        				   		"nombre= '".$_REQUEST['catChoferesNombreTxt']."', ".
        				   		"licencia= '".$_REQUEST['catChoferesLicenciaTxt']."', ".
        				   		"cuentaContable= '".$_REQUEST['catChoferesCuentaContableTxt']."', ".
        				   		"estatus= '".$_REQUEST['catChoferesEstatusHdn']."', ".
        				   		"tipoChofer=".$_REQUEST['catChoferesTipoChoferHdn'].", ".
        				   		"vigenciaLicencia= '".$_REQUEST['catChoferesVigenciaLicenciaHdn']."', ".
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

        $sqlDeleteChoferStr = "DELETE FROM cachoferestbl WHERE claveChofer=".$_REQUEST['catChoferesClaveChoferTxt'];
        
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