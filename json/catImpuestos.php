<?php
    session_start();
	$_SESSION['modulo'] = "catImpuestos";
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
	
    switch($_REQUEST['catImpuestosActionHdn']){
        case 'getImpuestos':
            getImpuestos();
            break;
        case 'addImpuestos':
            addImpuestos();
            break;
        case 'updImpuestos':
            updImpuestos();
            break;
        default:            
    }

    function getImpuestos(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catImpuestosImpuestoTxt'], "i.impuesto", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catImpuestosDescripcionTxt'], "i.descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catImpuestosTipoHdn'], "i.tipoImpuesto", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catImpuestosImporteTxt'], "i.importe", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetImpuestosStr = "SELECT i.*, ".
                              "(SELECT g.nombre FROM caGeneralesTbl g WHERE tabla='caImpuestosTbl' ".
                                "AND columna='tipoImpuesto' AND g.valor=i.tipoImpuesto) as nombreTipoImpuesto ".
                              "FROM caImpuestosTbl i ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetImpuestosStr);
            
        echo json_encode($rs);
    }

    function addImpuestos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catImpuestosImpuestoTxt'] == ""){
            $e[] = array('id'=>'catImpuestosImpuestoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catImpuestosDescripcionTxt'] == ""){
            $e[] = array('id'=>'catImpuestosDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catImpuestosTipoHdn'] == ""){
            $e[] = array('id'=>'catImpuestosTipoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catImpuestosImporteTxt'] == ""){
            $e[] = array('id'=>'catImpuestosImporteTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlAddImpuestoStr = "INSERT INTO caImpuestosTbl VALUES(".
                                 "'".$_REQUEST['catImpuestosImpuestoTxt']."',".
                                 "'".$_REQUEST['catImpuestosDescripcionTxt']."',".
                                 "'".$_REQUEST['catImpuestosTipoHdn']."',".
                                 $_REQUEST['catImpuestosImporteTxt'].")";

            $rs = fn_ejecuta_query($sqlAddImpuestoStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddImpuestoStr;
                $a['successMessage'] = getImpuestosSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddImpuestoStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updImpuestos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catImpuestosImpuestoTxt'] == ""){
            $e[] = array('id'=>'catImpuestosImpuestoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catImpuestosDescripcionTxt'] == ""){
            $e[] = array('id'=>'catImpuestosDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catImpuestosTipoHdn'] == ""){
            $e[] = array('id'=>'catImpuestosTipoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catImpuestosImporteTxt'] == ""){
            $e[] = array('id'=>'catImpuestosImporteTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdImpuestosStr = "UPDATE caImpuestosTbl ".
                                  "SET descripcion = '".$_REQUEST['catImpuestosDescripcionTxt']."', ".
                                  "tipoImpuesto = '".$_REQUEST['catImpuestosTipoHdn']."', ".
                                  "importe = ".$_REQUEST['catImpuestosImporteTxt']." ".
                                  "WHERE impuesto = '".$_REQUEST['catImpuestosImpuestoTxt']."'";

            $rs = fn_ejecuta_query($sqlUpdImpuestosStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdImpuestosStr;
                $a['successMessage'] = getImpuestosUpdMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdImpuestosStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>