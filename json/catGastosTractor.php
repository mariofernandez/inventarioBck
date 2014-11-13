<?php
	session_start();
	$_SESSION['modulo'] = "catGastosTractor";
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
	
    switch($_REQUEST['catGastosTractorActionHdn']) {
        case 'getGastosTractor':
            getGastosTractor();
            break;
		case 'addGastosTractor':
			addGastosTractor();
            break;
		case 'updGastosTractor':
			updGastosTractor();
            break;
		case 'dltGastosTractor':
			dltGastosTractor();
        default:
            echo '';
    }

    function getGastosTractor(){
    	$lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catGastosTractorIdConceptoHdn'], "idConcepto", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catGastosTractorTipoConceptoTxt'], "tipoConcepto", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catGastosTractorImporteTxt'], "importe", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetGastosTractorStr = "SELECT * ".
                                  "FROM caGastosTractorTbl ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetGastosTractorStr);
        
        echo json_encode($rs);
    }

    function addGastosTractor(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catGastosTractorTipoConceptoTxt'] == ""){
            $e[] = array('id'=>'catGastosTractorTipoConceptoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catGastosTractorImporteTxt'] == ""){
            $e[] = array('id'=>'catTractoresTractorTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        
        if ($a['success'] == true){
            $sqlAddGastosTractorStr = "INSERT INTO caGastosTractorTbl (tipoConcepto, importe) VALUES(".
                                        $_REQUEST['catGastosTractorTipoConceptoTxt'].", ".
                                        $_REQUEST['catGastosTractorImporteTxt'].")";
            
            $rs = fn_ejecuta_query($sqlAddGastosTractorStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddGastosTractorStr;
                $a['successMessage'] = getGastosTractorSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddGastosTractorStr;
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updGastosTractor(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catGastosTractorTipoConceptoTxt'] == ""){
            $e[] = array('id'=>'catGastosTractorTipoConceptoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catGastosTractorImporteTxt'] == ""){
            $e[] = array('id'=>'catTractoresTractorTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        
        if ($a['success'] == true){
            $sqlUpdGastosTractorStr = "UPDATE caGastosTractorTbl ".
                                      "SET tipoConcepto='".$_REQUEST['catGastosTractorTipoConceptoTxt']."', ".
                                      "importe='".$_REQUEST['catGastosTractorImporteTxt']."' ".
                                      "WHERE idConcepto=".$_REQUEST['catGastosTractorIdConceptoHdn'];
            
            $rs = fn_ejecuta_query($sqlUpdGastosTractorStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdGastosTractorStr;
                $a['successMessage'] = getGastosTractorUpdateMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdGastosTractorStr;
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>