<?php
    /************************************************************************
    * Autor: Mario Adrián Martínez Fernández
    * Fecha: 07-Julio-2014
    * Tablas afectadas: cacolonias
    * Descripción: Programa para afectar colonias
    *************************************************************************/
    
    session_start();
    $_SESSION['modulo'] = "caColonias";
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
    
    switch($_REQUEST['caColoniasActionHdn']){
        case 'getColonias':
            getColonias();
            break;
        case 'addColonia':
            addColonia();
            break;
        case 'updColonia':
            updColonia();
            break;
        case 'dltColonia':
            dltColonia();
            break;
    }

    function getColonias(){
        $lsWhereStr = "WHERE cm.idEstado = ce.idEstado AND cc.idMunicipio = cm.idMunicipio".
        " AND ce.idpais = cp.idpais";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caColoniasIdEstadoHdn'], "cc.idEstado", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caColoniasIdMunicipioHdn'], "cc.idMunicipio", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caColoniasIdColoniaHdn'], "cc.idColonia", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caColoniasDescripcionTxt'], "cc.descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caColoniasCodigoPostalTxt'], "cc.codigoPostal", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        $sqlGetColoniaStr = "SELECT cc.*,ce.idEstado, ce.descripcion as descEstado, cm.descripcion as descMunicipio, ". 
                            "cp.idPais, cp.descripcion as descPais ".
                            "FROM cacolonias cc, camunicipios cm, caestados ce, capaises cp ".
                            $lsWhereStr." order by cc.descripcion LIMIT 10000;";
            
        $rs = fn_ejecuta_query($sqlGetColoniaStr);
        
        echo json_encode($rs);
    }

    function addColonia(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['caColoniasIdMunicipioHdn'] == ""){
            $e[] = array('id'=>'caColoniasIdMunicipioHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($_REQUEST['caColoniasDescripcionTxt'] == ""){
            $e[] = array('id'=>'caColoniasDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $descripcionArr = explode('|', substr($_REQUEST['caColoniasDescripcionTxt'], 0, -1));
            $codigoPostalArr = explode('|', substr($_REQUEST['caColoniasCodigoPostalTxt'], 0, -1));

            for($nInt = 0; $nInt < sizeof($descripcionArr);$nInt++){

            $sqlAddColoniaStr = "INSERT INTO caColonias (idMunicipio, descripcion, codigoPostal) ".
                             "VALUES (".$_REQUEST['caColoniasIdMunicipioHdn'].", ".
                                replaceEmptyNull("'".$descripcionArr[$nInt]."'").", ".
                                replaceEmptyNull("'".$codigoPostalArr[$nInt]."'").");";

                $rs = fn_ejecuta_query($sqlAddColoniaStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $descripcionArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getColoniaSuccessMsg();
            } else {
                $a['errorMessage'] = getColoniaFailedMsg();
                for ($nInt=0; $nInt < sizeof($errorArr); $nInt++) { 
                    $a['errorMessage'] .= $errorArr[$nInt].", ";
                }
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updColonia(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['caColoniasIdColoniaHdn'] == ""){
            $e[] = array('id'=>'caColoniasIdColoniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $idMunicipioArr = explode('|', substr($_REQUEST['caColoniasIdMunicipioHdn'], 0, -1));
            $idColoniaArr = explode('|', substr($_REQUEST['caColoniasIdColoniaHdn'], 0, -1));
            $descripcionArr = explode('|', substr($_REQUEST['caColoniasDescripcionTxt'], 0, -1));
            $codigoPostalArr = explode('|', substr($_REQUEST['caColoniasCodigoPostalTxt'], 0, -1));

            for($nInt = 0; $nInt < sizeof($descripcionArr);$nInt++){
            $sqlUpdateColoniaStr = "UPDATE caColonias SET ";
                                if (isset($_REQUEST['caColoniasIdMunicipioHdn']) && $_REQUEST['caColoniasIdMunicipioHdn'] != '') {
                                    $sqlUpdateColoniaStr .= " idMunicipio = ".replaceEmptyNull($idMunicipioArr[$nInt]);
                                    $updInt++;
                                }
                                if (isset($_REQUEST['caColoniasDescripcionTxt']) && $_REQUEST['caColoniasDescripcionTxt'] != '') {
                                    if ($updInt > 0) {
                                        $sqlUpdateColoniaStr .= ",";
                                    }

                                    $sqlUpdateColoniaStr .= " descripcion = ".replaceEmptyNull("'".$descripcionArr[$nInt]."'");
                                    $updInt++;
                                }
                                if (isset($_REQUEST['caColoniasCodigoPostalTxt']) && $_REQUEST['caColoniasCodigoPostalTxt'] != '') {
                                    if ($updInt > 0) {
                                        $sqlUpdateColoniaStr .= ",";
                                    }

                                    $sqlUpdateColoniaStr .= " codigoPostal = ".replaceEmptyNull("'".$codigoPostalArr[$nInt]."'");
                                    $updInt++;
                                }
                                if ($updInt > 0) {
                                    $sqlUpdateColoniaStr .= " WHERE idColonia = ".replaceEmptyNull($idColoniaArr[$nInt]).";";
                                }

                $rs = fn_ejecuta_query($sqlUpdateColoniaStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $descripcionArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getColoniaUpdateMsg();
            } else {
                $a['errorMessage'] = getColoniaFailedMsg();
                for ($nInt=0; $nInt < sizeof($errorArr); $nInt++) { 
                    $a['errorMessage'] .= $errorArr[$nInt].", ";
                }
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
        /*$a = array();
        $e = array();
        $a['success'] = true;


        if($_REQUEST['caColoniasIdColoniaHdn'] == ""){
            $e[] = array('id'=>'caColoniasIdColoniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){
            $sqlUpdateColoniaStr = "UPDATE caColonias SET ";
                                if (isset($_REQUEST['caColoniasIdMunicipioHdn']) && $_REQUEST['caColoniasIdMunicipioHdn'] != '') {
                                    $sqlUpdateColoniaStr .= " idMunicipio = '".$_REQUEST['caColoniasIdMunicipioHdn']."'";
                                    $updInt++;
                                }
                                if (isset($_REQUEST['caColoniasDescripcionTxt']) && $_REQUEST['caColoniasDescripcionTxt'] != '') {
                                    if ($updInt > 0) {
                                        $sqlUpdateColoniaStr .= ",";
                                    }

                                    $sqlUpdateColoniaStr .= " descripcion = '".$_REQUEST['caColoniasDescripcionTxt']."'";
                                    $updInt++;
                                }
                                if (isset($_REQUEST['caColoniasCodigoPostalTxt']) && $_REQUEST['caColoniasCodigoPostalTxt'] != '') {
	                                if ($updInt > 0) {
	                                    $sqlUpdateColoniaStr .= ",";
	                                }

	                                $sqlUpdateColoniaStr .= " codigoPostal = '".$_REQUEST['caColoniasCodigoPostalTxt']."'";
	                                $updInt++;
	                            }
                                if ($updInt > 0) {
                                    $sqlUpdateColoniaStr .= " WHERE idColonia = ".$_REQUEST['caColoniasIdColoniaHdn'].";";
                                }

            $rs = fn_ejecuta_query($sqlUpdateColoniaStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['successMessage'] = getColoniaUpdateMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdateColoniaStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
*/
    }

    function dltColonia(){

        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['caColoniasIdColoniaHdn'] == ""){
            $e[] = array('id'=>'caColoniasIdColoniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){
            $sqlDeleteColoniaStr = "DELETE FROM caColonias WHERE idColonia = ".
            $_REQUEST['caColoniasIdColoniaHdn'].";";
            
            $rs = fn_ejecuta_query($sqlDeleteColoniaStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlDeleteColoniaStr;
                $a['successMessage'] = getColoniaDeleteMsg();
                $a['id'] = $_REQUEST['caColoniasIdColoniaHdn'];
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteColoniaStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);       

    }
?>