<?php
	session_start();
	$_SESSION['modulo'] = "catBancos";
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

    switch($_REQUEST['catBancosActionHdn']) {
        case 'getBancos':
            getBancos();
            break;
        case 'addBancos':
        	addBancos(); 
            break;
        case 'updBancos':
            updBancos();  
            break;                                                                                                  
        default:           
    }

    function getBancos(){
    	$lsWhereStr = "";
	
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catBancosBancoTxt'], "banco", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    
	    $sqlGetConceptosCentrosStr = "SELECT * FROM caBancosTbl " . $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetConceptosCentrosStr);
			
		echo json_encode($rs);
    }

    function addBancos(){
    	$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catBancosActionHdn'] == ""){
            $e[] = array('id'=>'catBancosActionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        
        if($a['success'] == true){
        	$sqlAddBancos = "INSERT INTO caBancosTbl (banco) ".
							"VALUES(".
                            "'".$_REQUEST['catBancosBancoTxt']."')";

			$rs = fn_ejecuta_query($sqlAddBancos);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddBancos;
                $a['successMessage'] = getBancosSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddBancos;

                $errorNoArr = explode(":", $_SESSION['error_sql']);
            	if($errorNoArr[0] == '1062'){
            		$e[] = array('id'=>'duplicate','msg'=>getBancosDuplicateMsg());	
            	}
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updBancos(){
    	$a = array();
        $e = array();
        $a['success'] = true;

 
        if($_REQUEST['catBancosIdBancoHdn'] == ""){
            $e[] = array('id'=>'catBancosIdBancoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catBancosBancoTxt'] == ""){
            $e[] = array('id'=>'catBancosBancoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
        	$sqlUpdateBancos =  "UPDATE caBancosTbl ".
						   		"SET banco = '".$_REQUEST['catBancosBancoTxt']."' ".
    							"WHERE idBanco = '".$_REQUEST['catBancosIdBancoHdn']."'";


        	$rs = fn_ejecuta_query($sqlUpdateBancos);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdateBancos;
                $a['successMessage'] = getBancosUpdateMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdateBancos;
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>