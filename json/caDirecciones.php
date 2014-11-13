<?php
    /************************************************************************
    * Autor: Mario Adrián Martínez Fernández
    * Fecha: 04-Agosto-2014
    * Tablas afectadas: caDirecciones
    * Descripción: Programa para afectar Direcciones
    *************************************************************************/
    session_start();
    $_SESSION['modulo'] = "caDirecciones";
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
    
    switch($_REQUEST['caDireccionesActionHdn']){
        case 'getDirecciones':
            getDirecciones();
            break;
        case 'addDirecciones':
            addDirecciones();
            break;
        case 'updDirecciones':
            updDirecciones();
            break;
        case 'dltDirecciones':
            dltDirecciones();
            break;
    }

    function getDirecciones(){
        $lsWhereStr = "WHERE cd.idcolonia = cc.idColonia ". 
                      "AND cc.idMunicipio = cm.idMunicipio ".
                      "AND cm.idEstado = ce.idEstado ".
                      "AND ce.idPais = cp.idPais ";
                    if($_REQUEST['caDireccionesGridHdn']!= ''){
                      $lsWhereStr = fn_concatena_condicion($lsWhereStr,"cd.Compania is null");
                      }

        if ($gb_error_filtro == 0){
              $lsCondicionStr = fn_construct($_REQUEST['caDireccionesIdDireccionHdn'], "cd.idDireccion", 0);
              $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caDireccionesCalleNumeroTxt'], "cd.CalleNumero", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caDireccionesIdColoniaHdn'], "cd.idColonia", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caDireccionesCompaniaTxt'], "cd.compania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caDireccionesTipoDireccionTxt'], "cd.tipoDireccion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caDireccionesIdMunicipioHdn'], "cc.idMunicipio", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caDireccionesIdEstadoHdn'], "cm.idEstado", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caDireccionesIdPaisHdn'], "ce.idPais", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetDireccionesStr = "SELECT cd.*,cc.descripcion as colonia,cc.idMunicipio,cm.descripcion as municipio, ". 
                                "cm.idEstado,ce.descripcion as estado, ce.idPais, cp.descripcion as pais, ".
                                "(select descripcion from cacompanias co2 ". 
                                "where co2.claveCompania = cd.compania) as nombreCompania, cc.codigoPostal ". 
                                "from cadirecciones cd, cacolonias cc, camunicipios cm, caestados ce, ".
                                "capaises cp ".$lsWhereStr.";";
            
        $rs = fn_ejecuta_query($sqlGetDireccionesStr);
        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['direccionCompleta'] = $rs['root'][$iInt]['calleNumero'].", ".
                                                      $rs['root'][$iInt]['colonia'].", ".
                                                      $rs['root'][$iInt]['municipio'].", ".
                                                      $rs['root'][$iInt]['estado'].", ".
                                                      $rs['root'][$iInt]['pais'].", ".
                                                      $rs['root'][$iInt]['codigoPostal'];
        }
        
        echo json_encode($rs);
    }
    function addDirecciones(){
        $a = array();
        $e = array();
        $errorArr= array();
        $a['success'] = true;

        if ($_REQUEST['caDireccionesIdColoniaHdn'] == "") {
            $e[] = array('id'=>'caDireccionesIdColoniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($_REQUEST['caDireccionesCalleNumeroTxt'] == "") {
            $e[] = array('id'=>'caDireccionesCalleNumeroTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $idColoniaArr = explode('|', substr($_REQUEST['caDireccionesIdColoniaHdn'], 0, -1));
            $calleNumeroArr = explode('|', substr($_REQUEST['caDireccionesCalleNumeroTxt'], 0, -1));
            $companiaArr = explode('|', substr($_REQUEST['caDireccionesCompaniaTxt'], 0, -1));
            $tipoDireccionArr = explode('|', substr($_REQUEST['caDireccionesTipoDireccionTxt'], 0, -1));

            for($nInt = 0; $nInt < sizeof($calleNumeroArr);$nInt++){
                $sqlAddDireccionesStr = "INSERT INTO caDirecciones (calleNumero, idColonia, compania, tipoDireccion) ".
                             "VALUES (".
                                replaceEmptyNull("'".$calleNumeroArr[$nInt]."'").", ".
                                replaceEmptyNull("'".$idColoniaArr[$nInt]."'").", ".
                                replaceEmptyNull("'".$companiaArr[$nInt]."'").", ".
                                replaceEmptyNull("'".$tipoDireccionArr[$nInt]."'").")";

                $rs = fn_ejecuta_query($sqlAddDireccionesStr);
                if ((!($_SESSION['error_sql'])) || (($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $calleNumeroArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                    $a['successMessage'] = getDireccionesSuccessMsg();
            } else {
                $a['errorMessage'] = getDireccionesFailedMsg();
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

    function updDirecciones(){
        $a = array();
        $e = array();
        $errorArr = array();
        $a['success'] = true;

        if ($_REQUEST['caDireccionesIdDireccionHdn'] == "") {
            $e[] = array('id'=>'caDireccionesIdDireccionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }



        if ($a['success'] == true) {
            $idDireccionArr = explode('|', substr($_REQUEST['caDireccionesIdDireccionHdn'], 0, -1));
            $idColoniaArr = explode('|', substr($_REQUEST['caDireccionesIdColoniaHdn'], 0, -1));
            $calleNumeroArr = explode('|', substr($_REQUEST['caDireccionesCalleNumeroTxt'], 0, -1));
            $companiaArr = explode('|', substr($_REQUEST['caDireccionesCompaniaTxt'], 0, -1));
            $tipoDireccionArr = explode('|', substr($_REQUEST['caDireccionesTipoDireccionTxt'], 0, -1));


            for($nInt = 0; $nInt < sizeof($idDireccionArr);$nInt++){
                $sqlUpdateDireccionesStr = "UPDATE caDirecciones SET ";
                    if (isset($_REQUEST['caDireccionesIdColoniaHdn']) && $_REQUEST['caDireccionesIdColoniaHdn'] != '') {
                        $sqlUpdateDireccionesStr .= " idColonia = '".replaceEmptyNull($idColoniaArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caDireccionesCalleNumeroTxt']) && $_REQUEST['caDireccionesCalleNumeroTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdateDireccionesStr .= ",";
                        }

                        $sqlUpdateDireccionesStr .= " calleNumero = '".replaceEmptyNull($calleNumeroArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caDireccionesCompaniaTxt']) && $_REQUEST['caDireccionesCompaniaTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdateDireccionesStr .= ",";
                        }

                        $sqlUpdateDireccionesStr .= " compania = '".replaceEmptyNull($companiaArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caDireccionesTipoDireccionTxt']) && $_REQUEST['caDireccionesTipoDireccionTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdateDireccionesStr .= ",";
                        }

                        $sqlUpdateDireccionesStr .= " tipoDireccion = '".replaceEmptyNull($tipoDireccionArr[$nInt])."'";
                        $updInt++;
                    }
                    if ($updInt > 0) {
                        $sqlUpdateDireccionesStr .= " WHERE idDireccion = '".replaceEmptyNull($idDireccionArr[$nInt])."'; ";
                    }
                $rs = fn_ejecuta_query($sqlUpdateDireccionesStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $idDireccionArr[$nInt]);
                }
            }

                if ($a['success'] == true) {
                    switch ($_REQUEST['caDireccionesNuevaCompaniaHdn']) {
                        case '':
                            $a['successMessage'] = getDireccionesUpdateMsg();
                            break;
                        case'add':
                            $a['successMessage'] = getCompaniasSuccessMsg();
                            break;
                        case 'update':
                            $a['successMessage'] = getCompaniasUpdMsg();
                            break;
                    }
                    /*if($_REQUEST['caDireccionesNuevaCompaniaHdn'] == ''){
                       $a['successMessage'] = getDireccionesUpdateMsg();
                    }else{
                        $a['successMessage'] = getCompaniasSuccessMsg();
                    }*/
                } else {
                $a['errorMessage'] = getDireccionesFailedMsg();
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

    function dltDirecciones(){

        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['caDireccionesIdDireccionHdn'] == "") {
            $e[] = array('id'=>'caDireccionesIdDireccionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }


        if ($a['success'] == true){
            $sqlDeleteDireccionesStr = "DELETE FROM caDirecciones WHERE idDireccion = ".$_REQUEST['caDireccionesIdDireccionHdn'].";";

            
            $rs = fn_ejecuta_query($sqlDeleteDireccionesStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlDeleteDireccionesStr;
                $a['successMessage'] = getGeneralesDeleteMsg();
                $a['id'] = $_REQUEST['caDireccionesIdDireccionHdn'];
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteDireccionesStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);       
}
?>