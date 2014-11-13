<?php
    /************************************************************************
    * Autor: Mario Adrián Martínez Fernández
    * Fecha: 31-Julio-2014
    * Tablas afectadas: caGenerales
    * Descripción: Programa para afectar Generales
    *************************************************************************/

    session_start();
    $_SESSION['modulo'] = "caGenerales";
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
    
    switch($_REQUEST['caGeneralesActionHdn']){
        case 'getGenerales':
            getGenerales();
            break;
        case 'addGenerales':
            addGenerales();
            break;
        case 'updGenerales':
            updGenerales();
            break;
        case 'dltGenerales':
            dltGenerales();
            break;
    }

    function getGenerales(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caGeneralesTablaTxt'], "tabla", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caGeneralesColumnaTxt'], "columna", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caGeneralesValorTxt'], "valor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caGeneralesNombreTxt'], "nombre", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caGeneralesEsatusHdn'], "estatus", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caGeneralesIdiomaTxt'], "idioma", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetGeneralesStr = "SELECT * FROM caGenerales ".$lsWhereStr.";";
           
        $rs = fn_ejecuta_query($sqlGetGeneralesStr);

           for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descGeneral'] = $rs['root'][$iInt]['valor']." - ".$rs['root'][$iInt]['nombre'];
        }
        
        echo json_encode($rs);
    }

    function addGenerales(){

        $a = array();
        $e = array();
        $errorArr = array();
        $a['success'] = true;

        if ($_REQUEST['caGeneralesTablaTxt'] == "") {
            $e[] = array('id'=>'caGeneralesTablaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesColumnaTxt'] == "") {
            $e[] = array('id'=>'caGeneralesColumnaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesValorTxt'] == "") {
            $e[] = array('id'=>'caGeneralesValorTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesNombreTxt'] == "") {
            $e[] = array('id'=>'caGeneralesNombreTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesEstatusHdn'] == "") {
            $e[] = array('id'=>'caGeneralesEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesIdiomaTxt'] == "") {
            $e[] = array('id'=>'caGeneralesIdiomaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $tablaArr = explode('|', substr($_REQUEST['caGeneralesTablaTxt'], 0, -1));
            $columnaArr = explode('|', substr($_REQUEST['caGeneralesColumnaTxt'], 0, -1));
            $valorArr = explode('|', substr($_REQUEST['caGeneralesValorTxt'], 0, -1));
            $nombreArr = explode('|', substr($_REQUEST['caGeneralesNombreTxt'], 0, -1));
            $estatusArr = explode('|', substr($_REQUEST['caGeneralesEstatusHdn'], 0, -1));
            $idiomaArr = explode('|', substr($_REQUEST['caGeneralesIdiomaTxt'], 0, -1));

            for($nInt = 0; $nInt < sizeof($nombreArr);$nInt++){
                $sqlAddGeneralesStr = "INSERT INTO caGenerales (tabla, columna, valor, nombre, estatus, idioma) ".
                             "VALUES (".
                                replaceEmptyNull("'".$tablaArr[$nInt]."'").", ".
                                replaceEmptyNull("'".$columnaArr[$nInt]."'").", ".
                                replaceEmptyNull("'".$valorArr[$nInt]."'").", ".
                                replaceEmptyNull("'".$nombreArr[$nInt]."'").", ".
                                replaceEmptyNull("'".$estatusArr[$nInt]."'").", ".
                                 replaceEmptyNull("'".$idiomaArr[$nInt]."'").")";

                $rs = fn_ejecuta_query($sqlAddGeneralesStr);
                if ((!($_SESSION['error_sql'])) || (($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $nombreArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getGeneralesSuccessMsg();
            } else {
                $a['errorMessage'] = getGeneralesFailedMsg();
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

    function updGenerales(){
        $a = array();
        $e = array();
        $errorArr = array();
        $a['success'] = true;

        if ($_REQUEST['caGeneralesTablaTxt'] == "") {
            $e[] = array('id'=>'caGeneralesTablaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesColumnaTxt'] == "") {
            $e[] = array('id'=>'caGeneralesColumnaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesValorTxt'] == "") {
            $e[] = array('id'=>'caGeneralesValorTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesNombreTxt'] == "") {
            $e[] = array('id'=>'caGeneralesNombreTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesEstatusHdn'] == "") {
            $e[] = array('id'=>'caGeneralesEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesIdiomaTxt'] == "") {
            $e[] = array('id'=>'caGeneralesIdiomaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }


        if ($a['success'] == true) {
            $tablaArr = explode('|', substr($_REQUEST['caGeneralesTablaTxt'], 0, -1));
            $columnaArr = explode('|', substr($_REQUEST['caGeneralesColumnaTxt'], 0, -1));
            $valorArr = explode('|', substr($_REQUEST['caGeneralesValorTxt'], 0, -1));
            $nombreArr = explode('|', substr($_REQUEST['caGeneralesNombreTxt'], 0, -1));
            $estatusArr = explode('|', substr($_REQUEST['caGeneralesEstatusHdn'], 0, -1));
            $idiomaArr = explode('|', substr($_REQUEST['caGeneralesIdiomaTxt'], 0, -1));
            //Nuevos
            $tablaNewArr = explode('|', substr($_REQUEST['caGeneralesTablaNuevoTxt'], 0, -1));
            $columnaNewArr = explode('|', substr($_REQUEST['caGeneralesColumnaNuevoTxt'], 0, -1));
            $valorNewArr = explode('|', substr($_REQUEST['caGeneralesValorNuevoTxt'], 0, -1));
            $nombreNewArr = explode('|', substr($_REQUEST['caGeneralesNombreNuevoTxt'], 0, -1));
            $estatusNewArr = explode('|', substr($_REQUEST['caGeneralesEstatusNuevoHdn'], 0, -1));
            $idiomaNewArr = explode('|', substr($_REQUEST['caGeneralesIdiomaNuevoTxt'], 0, -1));
            //Nuevos

            for($nInt = 0; $nInt < sizeof($nombreArr);$nInt++){
                $sqlUpdateGeneralesStr = "UPDATE caGenerales SET ";
                    if (isset($_REQUEST['caGeneralesTablaNuevoTxt']) && $_REQUEST['caGeneralesTablaNuevoTxt'] != '') {
                        $sqlUpdateGeneralesStr .= " tabla = '".replaceEmptyNull($tablaNewArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caGeneralesColumnaNuevoTxt']) && $_REQUEST['caGeneralesColumnaNuevoTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdateGeneralesStr .= ",";
                        }

                        $sqlUpdateGeneralesStr .= " columna = '".replaceEmptyNull($columnaNewArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caGeneralesValorNuevoTxt']) && $_REQUEST['caGeneralesValorNuevoTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdateGeneralesStr .= ",";
                        }

                        $sqlUpdateGeneralesStr .= " valor = '".replaceEmptyNull($valorNewArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caGeneralesNombreNuevoTxt']) && $_REQUEST['caGeneralesNombreNuevoTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdateGeneralesStr .= ",";
                        }

                        $sqlUpdateGeneralesStr .= " nombre = '".replaceEmptyNull($nombreNewArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caGeneralesEstatusNuevoHdn']) && $_REQUEST['caGeneralesEstatusNuevoHdn'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdateGeneralesStr .= ",";
                        }

                        $sqlUpdateGeneralesStr .= " estatus = '".replaceEmptyNull($estatusNewArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caGeneralesIdiomaNuevoTxt']) && $_REQUEST['caGeneralesIdiomaNuevoTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdateGeneralesStr .= ",";
                        }

                        $sqlUpdateGeneralesStr .= " idioma = '".replaceEmptyNull($idiomaNewArr[$nInt])."'";
                        $updInt++;
                    }
                    if ($updInt > 0) {
                        $sqlUpdateGeneralesStr .= " WHERE tabla = '".replaceEmptyNull($tablaArr[$nInt])."' ".
                        "AND columna = '".replaceEmptyNull($columnaArr[$nInt])."' ".
                        "AND valor = '".replaceEmptyNull($valorArr[$nInt])."' ".
                        "AND estatus = '".replaceEmptyNull($estatusArr[$nInt])."' ".
                        "AND idioma = '".replaceEmptyNull($idiomaArr[$nInt])."';";
                    }

                $rs = fn_ejecuta_query($sqlUpdateGeneralesStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $nombreArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getGeneralesSuccessMsg();
            } else {
                $a['errorMessage'] = getGeneralesFailedMsg();
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

    function dltGenerales(){

        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['caGeneralesTablaTxt'] == "") {
            $e[] = array('id'=>'caGeneralesTablaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesColumnaTxt'] == "") {
            $e[] = array('id'=>'caGeneralesColumnaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesValorTxt'] == "") {
            $e[] = array('id'=>'caGeneralesValorTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesNombreTxt'] == "") {
            $e[] = array('id'=>'caGeneralesNombreTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesEstatusHdn'] == "") {
            $e[] = array('id'=>'caGeneralesEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caGeneralesIdiomaTxt'] == "") {
            $e[] = array('id'=>'caGeneralesIdiomaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($a['success'] == true){
            $sqlDeleteGeneralesStr = "DELETE FROM caGenerales WHERE tabla = '".$_REQUEST['caGeneralesTablaTxt']."'".
            " AND columna = '".$_REQUEST['caGeneralesColumnaTxt']."'".
            " AND valor = '".$_REQUEST['caGeneralesValorTxt']."'".
            " AND nombre = '".$_REQUEST['caGeneralesNombreTxt']."'".
            " AND estatus = '".$_REQUEST['caGeneralesEstatusHdn']."'".
            " AND idioma = '".$_REQUEST['caGeneralesIdiomaTxt']."';";

            
            $rs = fn_ejecuta_query($sqlDeleteGeneralesStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlDeleteGeneralesStr;
                $a['successMessage'] = getGeneralesDeleteMsg();
                $a['id'] = $_REQUEST['caGeneralesNombreTxt'];
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteGeneralesStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);       
}
?>