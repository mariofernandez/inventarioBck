<?php
	session_start();
	$_SESSION['modulo'] = "catCuentasBancarias";
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

    switch($_REQUEST['catCuentasBancariasActionHdn']){
        case 'getCuentasBancarias':
        	getCuentasBancarias();
        	break;
        case 'addCuentasBancarias':
            addCuentasBancarias();
            break;
        case 'updCuentasBancarias':
            updCuentasBancarias();
            break;
    }

    function getCuentasBancarias(){
        $lsWhereStr = "WHERE b.idBanco = cb.idBanco ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catCuentasBancariasIdCuentaHdn'], "cb.idCuentaBancaria", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catCuentasBancariasIdBancoHdn'], "b.idBanco", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catCuentasBancariasCuentaTxt'], "cb.cuenta", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catCuentasBancariasCuentaClabeTxt'], "cb.cuentaClabe", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catCuentasBancariasSwiftCodeTxt'], "cb.swiftCode", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catCuentasBancariasEstatusTxt'], "cb.estatus", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetCuentasBancariasStr = "SELECT cb.*, b.banco, ".
                                     "(SELECT cg.nombre FROM caGeneralesTbl cg ".
                                        "WHERE cg.tabla='caCuentasBancariasTbl' AND cg.columna='estatus'".
                                        "AND valor = cb.estatus) AS nombreEstatus ".
                                     "FROM caCuentasBancariasTbl cb, caBancosTbl b ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetCuentasBancariasStr);
            
        echo json_encode($rs);
    }

    function addCuentasBancarias(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catCuentasBancariasIdBancoHdn'] == ""){
            $e[] = array('id'=>'catCuentasBancariasIdBancoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catCuentasBancariasCuentaTxt'] == ""){
            $e[] = array('id'=>'catCuentasBancariasCuentaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catCuentasBancariasCuentaClabeTxt'] == ""){
            $e[] = array('id'=>'catCuentasBancariasCuentaClabeTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catCuentasBancariasSwiftCodeTxt'] == ""){
            $e[] = array('id'=>'catCuentasBancariasSwiftCodeTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catCuentasBancariasEstatusHdn'] == ""){
            $e[] = array('id'=>'catCuentasBancariasEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlAddCuentasBancariasStr = "INSERT INTO caCuentasBancariasTbl ".
                                         "(idBanco, cuenta, cuentaClabe, swiftCode, estatus)   ".
                                         "VALUES (".
                                         $_REQUEST['catCuentasBancariasIdBancoHdn'].",".
                                         "'".$_REQUEST['catCuentasBancariasCuentaTxt']."',".
                                         "'".$_REQUEST['catCuentasBancariasCuentaClabeTxt']."',".
                                         "'".$_REQUEST['catCuentasBancariasSwiftCodeTxt']."',".
                                         "'".$_REQUEST['catCuentasBancariasEstatusHdn']."')";
            
            $rs = fn_ejecuta_query($sqlAddCuentasBancariasStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlAddCuentasBancariasStr;
                $a['successMessage'] = getCuentasBancariasSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddCuentasBancariasStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updCuentasBancarias(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catCuentasBancariasIdCuentaHdn'] == ""){
            $e[] = array('id'=>'catCuentasBancariasIdCuentaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdCuentaBancariaStr = "UPDATE caCuentasBancariasTbl ".
                                       "SET cuenta='".$_REQUEST['catCuentasBancariasCuentaTxt']."',".
                                       "cuentaClabe='".$_REQUEST['catCuentasBancariasCuentaClabeTxt']."',".
                                       "swiftCode='".$_REQUEST['catCuentasBancariasSwiftCodeTxt']."',".
                                       "estatus='".$_REQUEST['catCuentasBancariasEstatusHdn']."' ".
                                       "WHERE idCuentaBancaria=".$_REQUEST['catCuentasBancariasIdCuentaHdn'];

            $rs = fn_ejecuta_query($sqlUpdCuentaBancariaStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlUpdCuentaBancariaStr;
                $a['successMessage'] = getCuentasBancariasUpdMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdCuentaBancariaStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

?>