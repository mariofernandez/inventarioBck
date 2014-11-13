<?php
    /************************************************************************
    * Autor: Mario Adrián Martínez Fernández
    * Fecha: 07-Julio-2014
    * Tablas afectadas: caEstados
    * Descripción: Programa para afectar estados
    *************************************************************************/

    session_start();
    $_SESSION['modulo'] = "caEstados";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");

    //$_REQUEST = trasformUppercase($_REQUEST);
    
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
    
    switch($_REQUEST['caEstadosActionHdn']){
        case 'getEstados':
            getEstados();
            break;
        case 'addEstado':
            addEstado();
            break;
        case 'updEstado':
            updEstado();
            break;
        case 'dltEstado':
            dltEstado();
            break;
    }

    function getEstados(){
        $lsWhereStr = "WHERE ce.idPais = cp.idPais";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caEstadosIdPaisHdn'], "ce.idPais", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caEstadosIdEstadoHdn'], "ce.idEstado", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caEstadosDescripcionTxt'], "ce.descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetEstadoStr = "SELECT ce.*, cp.descripcion as descPais FROM caEstados ce, caPaises cp " . $lsWhereStr." order by ce.descripcion;";
            
        $rs = fn_ejecuta_query($sqlGetEstadoStr);
        
        echo json_encode($rs);
    }

    function addEstado(){

        $a = array();
        $e = array();
        $errorArr = array();
        $a['success'] = true;

        if ($_REQUEST['caEstadosIdPaisHdn'] == "") {
            $e[] = array('id'=>'caEstadosIdPaisHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caEstadosDescripcionTxt'] == "") {
            $e[] = array('id'=>'caEstadosDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            //$idPaisArr = explode('|', substr($_REQUEST['caEstadosIdPaisHdn'], 0, -1));
            $descripcionArr = explode('|', substr($_REQUEST['caEstadosDescripcionTxt'], 0, -1));

            for($nInt = 0; $nInt < sizeof($descripcionArr);$nInt++){
                $sqlAddEstadoStr = "INSERT INTO caEstados (idPais, descripcion) ".
                             "VALUES (".
                                $_REQUEST['caEstadosIdPaisHdn'].",".
                                 replaceEmptyNull("'".$descripcionArr[$nInt]."'").")";

                $rs = fn_ejecuta_query($sqlAddEstadoStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $descripcionArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getEstadoUpdateMsg();
            } else {
                $a['errorMessage'] = getEstadoFailedMsg();
                for ($nInt=0; $nInt < sizeof($errorArr); $nInt++) {                     
                $a['errorMessage'] .= $errorArr[$nInt]." ".$_SESSION['error_sql']."<br>";
                }
                $a['errorMessage']= substr($a['errorMessage'], 0, -4);
            }   
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);

    }

    function updEstado(){

        $a = array();
        $e = array();
        $errorArr = array();
        $a['success'] = true;

        if($_REQUEST['caEstadosIdEstadoHdn'] == ""){
            $e[] = array('id'=>'caEstadosIdEstadoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }


        if ($a['success'] == true) {
            //$idPaisArr = explode('|', substr($_REQUEST['caEstadosIdPaisHdn'], 0, -1));
            $idEstadoArr = explode('|', substr($_REQUEST['caEstadosIdEstadoHdn'], 0, -1));
            $idPaisNuevoArr = explode('|', substr($_REQUEST['caEstadosIdPaisNuevoHdn'], 0, -1));
            $descripcionArr = explode('|', substr($_REQUEST['caEstadosDescripcionTxt'], 0, -1));

            for($nInt = 0; $nInt < sizeof($idEstadoArr);$nInt++){
                $sqlUpdateEstadoStr = "UPDATE caEstados SET ";
                    if (isset($_REQUEST['caEstadosIdPaisNuevoHdn']) && $_REQUEST['caEstadosIdPaisNuevoHdn'] != '') {
                        $sqlUpdateEstadoStr .= " idPais = ".replaceEmptyNull($idPaisNuevoArr[$nInt]);
                        $updInt++;
                    }
                    if (isset($_REQUEST['caEstadosDescripcionTxt']) && $_REQUEST['caEstadosDescripcionTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdateEstadoStr .= ",";
                        }

                        $sqlUpdateEstadoStr .= " descripcion = '".replaceEmptyNull($descripcionArr[$nInt])."'";
                        $updInt++;
                    }
                    if ($updInt > 0) {
                        $sqlUpdateEstadoStr .= " WHERE idEstado = ".replaceEmptyNull($idEstadoArr[$nInt]).";";
                    }

                $rs = fn_ejecuta_query($sqlUpdateEstadoStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $idEstadoArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getEstadoUpdateMsg();
            } else {
                $a['errorMessage'] = getEstadoFailedMsg();
                for ($nInt=0; $nInt < sizeof($errorArr); $nInt++) {                     
                $a['errorMessage'] .= $errorArr[$nInt]." ".$_SESSION['error_sql']."<br>";
                }
                $a['errorMessage']= substr($a['errorMessage'], 0, -4);
            }   
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);

    }

    function dltEstado(){

        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['caEstadosIdEstadoHdn'] == ""){
            $e[] = array('id'=>'caEstadosIdEstadoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){
            $sqlDeleteEstadoStr = "DELETE FROM caEstados WHERE idEstado = ".$_REQUEST['caEstadosIdEstadoHdn'].";";
            
            $rs = fn_ejecuta_query($sqlDeleteEstadoStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlDeleteEstadoStr;
                $a['successMessage'] = getEstadoDeleteMsg();
                $a['id'] = $_REQUEST['caEstadosIdPaisHdn'];
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteEstadoStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);       

    }
?>