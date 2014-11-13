<?php
    /************************************************************************
    * Autor: Mario Adrián Martínez Fernández
    * Fecha: 07-Julio-2014
    * Tablas afectadas: caMunicipios
    * Descripción: Programa para afectar municipios
    *************************************************************************/

    session_start();
    $_SESSION['modulo'] = "caMunicipios";
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
    
    switch($_REQUEST['caMunicipiosActionHdn']){
        case 'getMunicipios':
            getMunicipios();
            break;
        case 'addMunicipio':
            addMunicipio();
            break;
        case 'updMunicipio':
            updMunicipio();
            break;
        case 'dltMunicipio':
            dltMunicipio();
            break;
    }

    function getMunicipios(){
        $lsWhereStr = "WHERE ce.idPais = cp.idPais".
        " AND cm.idEstado = ce.idEstado";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caMunicipiosIdEstadoHdn'], "cm.idEstado", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caMunicipiosIdMunicipioHdn'], "cm.idMunicipio", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caMunicipiosDescripcionTxt'], "cm.descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        $sqlGetMunicipioStr = "SELECT cm.*, cp.descripcion as descPais, ce.descripcion as descEstado".
         " FROM caMunicipios cm, caPaises cp, caEstados ce ".$lsWhereStr." order by cm.descripcion;";
            
        $rs = fn_ejecuta_query($sqlGetMunicipioStr);
        
        echo json_encode($rs);
    }

    function addMunicipio(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['caMunicipiosIdEstadoHdn'] == ""){
            $e[] = array('id'=>'caMunicipiosIdEstadoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($_REQUEST['caMunicipiosDescripcionTxt'] == ""){
            $e[] = array('id'=>'caMunicipiosDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $descripcionArr = explode('|', substr($_REQUEST['caMunicipiosDescripcionTxt'], 0, -1));

            for($nInt = 0; $nInt < sizeof($descripcionArr);$nInt++){
            $sqlAddMunicipioStr = "INSERT INTO caMunicipios (idEstado, descripcion) ".
                             "VALUES (".$_REQUEST['caMunicipiosIdEstadoHdn'].", ".
                                replaceEmptyNull("'".$descripcionArr[$nInt]."'").")";

                $rs = fn_ejecuta_query($sqlAddMunicipioStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $descripcionArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getMunicipioSuccessMsg();
            } else {
                $a['errorMessage'] = getMunicipioFailedMsg();
                for ($nInt=0; $nInt < sizeof($errorArr); $nInt++) { 
                    $a['errorMessage'] .= $errorArr[$nInt].", ";
                }
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updMunicipio(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['caMunicipiosIdMunicipioHdn'] == ""){
            $e[] = array('id'=>'caMunicipiosIdMunicipioHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $idMunicipioArr = explode('|', substr($_REQUEST['caMunicipiosIdMunicipioHdn'], 0, -1));
            $idEstadoArr = explode('|', substr($_REQUEST['caMunicipiosIdEstadoHdn'], 0, -1));
            $descripcionArr = explode('|', substr($_REQUEST['caMunicipiosDescripcionTxt'], 0, -1));

            for($nInt = 0; $nInt < sizeof($descripcionArr);$nInt++){
            $updInt = 0;   
            $sqlUpdateMunicipioStr = "UPDATE caMunicipios SET ";
                                if (isset($_REQUEST['caMunicipiosIdEstadoHdn']) && $_REQUEST['caMunicipiosIdEstadoHdn'] != '') {
                                    $sqlUpdateMunicipioStr .= " idEstado = ".replaceEmptyNull($idEstadoArr[$nInt]);
                                    $updInt++;
                                }
                                if (isset($_REQUEST['caMunicipiosDescripcionTxt']) && $_REQUEST['caMunicipiosDescripcionTxt'] != '') {
                                    if ($updInt > 0) {
                                        $sqlUpdateMunicipioStr .= ",";
                                    }

                                    $sqlUpdateMunicipioStr .= " descripcion = ".replaceEmptyNull("'".$descripcionArr[$nInt]."'");
                                    $updInt++;
                                }
                                if ($updInt > 0) {
                                    $sqlUpdateMunicipioStr .= "WHERE idMunicipio = ".replaceEmptyNull($idMunicipioArr[$nInt]).";";
                                }


                $rs = fn_ejecuta_query($sqlUpdateMunicipioStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $descripcionArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getMunicipioUpdateMsg();
            } else {
                $a['errorMessage'] = getMunicipioFailedMsg();
                for ($nInt=0; $nInt < sizeof($errorArr); $nInt++) { 
                    $a['errorMessage'] .= $errorArr[$nInt].", ";
                }
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);

    }

    function dltMunicipio(){

        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['caMunicipiosIdMunicipioHdn'] == ""){
            $e[] = array('id'=>'caMunicipiosIdMunicipioHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){
            $sqlDeleteMunicipioStr = "DELETE FROM caMunicipios WHERE ".
            "idMunicipio = ".$_REQUEST['caMunicipiosIdMunicipioHdn'].";";
            
            $rs = fn_ejecuta_query($sqlDeleteMunicipioStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlDeleteMunicipioStr;
                $a['successMessage'] = getMunicipioDeleteMsg();
                $a['id'] = $_REQUEST['caMunicipiosIdPaisHdn'];
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteMunicipioStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);       

    }
?>