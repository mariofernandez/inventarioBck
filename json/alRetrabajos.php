<?php
    session_start();
    $_SESSION['modulo'] = "alRetrabajos";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");

    $_REQUEST = trasformUppercase($_REQUEST);
    
    switch($_SESSION['idioma']){
        case 'ES':
            include_once("../funciones/idiomas/mensajesES.php");
            break;
        case 'EN':
            include_once("../funciones/idiomas/mensajesEN.php");
            break;
        default:
            include_once("../funciones/idiomas/mensajesES.php");
    }

    switch($_REQUEST['alRetrabajosActionHdn']){
        case 'getRetrabajos':
            getRetrabajos();
            break;
        case 'getUnidadRetrabajos':
            getUnidadRetrabajos();
            break;
        case 'addRetrabajos':
            addRetrabajos();
            break;
        case 'updRetrabajos':
            updRetrabajos();
            break;
    }

    function getRetrabajos(){
        $lsWheresStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap778VinHdn'], "vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap778SimboloHdn'], "simboloUnidad", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap778FechaHdn'], "fechaEvento", 2);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap778EstatusHdn'], "estatusRegistro", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetRetrabajosStr = "SELECT rt.* ".
                               "FROM alRetrabajoUnidadesTbl rt ".$lsWheresStr;

        $rs = fn_ejecuta_query($sqlGetRetrabajosStr);

        echo json_encode($rs);
    }

    function getUnidadRetrabajos(){
        $vin = array();
        $simbolo = array();

        $sqlGetUnidadRetrabajoStr = "SELECT vin, simboloUnidad ".
                                    "FROM alRetrabajoUnidadesTbl ".
                                    "WHERE vin = '".$_REQUEST['trap778VinHdn']."' ".
                                    "OR simboloUnidad = '".$_REQUEST['trap778SimboloHdn']."'";

        $rs = fn_ejecuta_query($sqlGetUnidadRetrabajoStr);

        for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
            if ($rs['root'][$nInt]['vin'] != "") {
                unset($rs['root'][$nInt]['simboloUnidad']);
            }
        }
        
        for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
            if ($rs['root'][$nInt]['vin'] == $_REQUEST['trap778VinHdn']) {
                $vin = array('success'=>true, 'records'=>1,'root'=>$rs['root'][$nInt]);
            }

            if (($rs['root'][$nInt]['simboloUnidad'] == $_REQUEST['trap778SimboloHdn']) && sizeof($simbolo) == 0) {
                $simbolo = array('success'=>true, 'records'=>1, 'root'=>$rs['root'][$nInt]);
            }
        }
        if(sizeof($vin) > 0){
            echo json_encode($vin);
        } elseif (sizeof($simbolo) > 0) {
            echo json_encode($simbolo);
        } else {
            echo json_encode(array('success'=>false, 'records'=>0, 'root'=>null));
        }
    }

    function addRetrabajos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['trap778EstatusHdn'] == "") {
            $e[] = array('id'=>'trap778EstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $vinArr = explode('|', substr($_REQUEST['trap778VinHdn'], 0, -1));
            $simboloArr = explode('|', substr($_REQUEST['trap778SimboloHdn'], 0, -1));

            for($nInt = 0; $nInt < sizeof($vinArr);$nInt++){
                $sqlAddRetrabajoStr = "INSERT INTO alRetrabajoUnidadesTbl ".
                                      "(vin, simboloUnidad, fechaEvento, estatusRegistro)".
                                      "VALUES(".
                                      replaceEmptyNull("'".$vinArr[$nInt]."'").",".
                                      replaceEmptyNull("'".$simboloArr[$nInt]."'").",".
                                      "'".date("Y-m-d H:i:s", time()+$nInt*2)."',".
                                      "'".$_REQUEST['trap778EstatusHdn']."')";

                $rs = fn_ejecuta_query($sqlAddRetrabajoStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $vinArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getRetrabajosSuccessMsg();
            } else {
                $a['errorMessage'] = getRetrabajosFailedMsg();
                for ($nInt=0; $nInt < sizeof($errorArr); $nInt++) { 
                    $a['errorMessage'] .= $errorArr[$nInt].", ";
                }
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updRetrabajos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['trap778IdRetrabajoHdn'] == "") {
            $e[] = array('id'=>'trap778IdRetrabajoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['trap778EstatusHdn'] == "") {
            $e[] = array('id'=>'trap778EstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdRetrabajosStr = "UPDATE alRetrabajoUnidadesTbl SET ".
                                   "avanzada = ".replaceEmptyNull("'".$_REQUEST['trap778VinHdn']."'").",".
                                   "simboloUnidad = ".replaceEmptyNull("'".$_REQUEST['trap778SimboloHdn']."'").",".
                                   "estatusRegistro = '".$_REQUEST['trap778EstatusHdn']."' ".
                                   "WHERE idRetrabajo = ".$_REQUEST['trap778IdRetrabajoHdn'];

            $rs = fn_ejecuta_query($sqlUpdRetrabajosStr);

            if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['successMessage'] = getRetrabajosUpdMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql']." <br> ".$sqlUpdRetrabajosStr;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

?>