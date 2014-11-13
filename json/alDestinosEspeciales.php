<?php
    /************************************************************************
    * Autor: Alfonso César Martínez Fuertes
    * Fecha: 20-Febrero-2014
    * Tablas afectadas: alDestinosEspecialesTbl
    * Descripción: Matenimiento a los Destinos Especiales
    *************************************************************************/
    session_start();
    $_SESSION['modulo'] = "alDestinosEspeciales";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");
    require_once("alUnidades.php");

    $_REQUEST = trasformUppercase($_REQUEST);
    $_REQUEST['centroDistGlobal'] = 'CDTOL';
    
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
    
    switch($_REQUEST['alDestinosEspecialesActionHdn']){
        case 'getDestinosEspeciales':
            getDestinosEspeciales();
            break;
        case 'getUltimoDestinoEspecialUnidad':
            getUltimoDestinoEspecialUnidad();
            break;
        case 'addDestinoEspecial':
            addDestinoEspecial();
            break;
        case 'updDestinoEspecial':
            updDestinoEspecial();
            break;
        case 'dltDestinoEspecial':
            dltDestinoEspecial();
            break;
    }

    function getDestinosEspeciales(){
        $lsWhereStr = "WHERE de.vin = h.vin ".
                      "AND h.fechaEvento=(".
                      "SELECT MAX(h1.fechaEvento) ".
                      "FROM alHistoricoUnidadesTbl h1 ". 
                      "WHERE h1.vin = de.vin) ".
                      "AND h.vin = u.vin ".
                      "AND tf.idTarifa = de.idTarifa ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesIdDestinoHdn'], "de.idDestinoEspecial", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesVinTxt'], "de.vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesDistOrigenHdn'], "de.distribuidorOrigen", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesDirOrigenHdn'], "de.direccionOrigen", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesPlazaOrigenHdn'], "de.idPlazaOrigen", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesDistDestinoHdn'], "de.distribuidorDestino", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesDirDestinoHdn'], "de.direccionDestino", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesPlazaDestinoHdn'], "de.idPlazaDestino", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesImporteCobTxt'], "de.importeCob", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesSueldoTxt'], "de.sueldo", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesTarifaHdn'], "de.idTarifa", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesCentroDistHdn'], "h.centroDistribucion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesAvanzadaHdn'], "u.avanzada", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetDestinosEspStr = "SELECT de.*, h.centroDistribucion, u.avanzada, tf.tarifa, tf.descripcion AS nombreTarifa, ".
                                "(SELECT dc.descripcionCentro FROM caDistribuidoresCentrosTbl dc ".
                                    "WHERE dc.distribuidorCentro = de.distribuidorOrigen) AS nombreDistOrigen, ".
                                "(SELECT pl.plaza FROM caPlazasTbl pl WHERE pl.idPlaza = de.idPlazaOrigen) AS nombrePlazaOrigen, ".
                                "(SELECT dc1.descripcionCentro FROM caDistribuidoresCentrosTbl dc1 ".
                                    "WHERE dc1.distribuidorCentro = de.distribuidorDestino) AS nombreDistDestino, ".
                                "(SELECT pl.plaza FROM caPlazasTbl pl WHERE pl.idPlaza = de.idPlazaDestino) AS nombrePlazaDestino, ".
                                "(SELECT dc2.descripcionCentro FROM caDistribuidoresCentrosTbl dc2 ".
                                    "WHERE dc2.distribuidorCentro = h.centroDistribucion) AS nombreCentroDist ".
                                "FROM alDestinosEspecialesTbl de, alHistoricoUnidadesTbl h, alUnidadesTbl u, caTarifasTbl tf ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetDestinosEspStr);

        for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
            $rs['root'][$nInt]['descDistOrigen'] = $rs['root'][$nInt]['distribuidorOrigen']." - ".$rs['root'][$nInt]['nombreDistOrigen'];
            $rs['root'][$nInt]['descPlazaOrigen'] = $rs['root'][$nInt]['idPlazaOrigen']." - ".$rs['root'][$nInt]['nombrePlazaOrigen'];
            $rs['root'][$nInt]['descDistDestino'] = $rs['root'][$nInt]['distribuidorDestino']." - ".$rs['root'][$nInt]['nombreDistDestino'];
            $rs['root'][$nInt]['descPlazaDestino'] = $rs['root'][$nInt]['idPlazaDestino']." - ".$rs['root'][$nInt]['nombrePlazaDestino'];
            $rs['root'][$nInt]['descTarifa'] = $rs['root'][$nInt]['tarifa']." - ".$rs['root'][$nInt]['nombreTarifa'];
            $rs['root'][$nInt]['descCentroDist'] = $rs['root'][$nInt]['centroDistribucion']." - ".$rs['root'][$nInt]['nombreCentroDist'];
        }
            
        echo json_encode($rs);
    }

    function getUltimoDestinoEspecialUnidad(){
        $lsWhereStr = "WHERE de.idDestinoEspecial = (".
                      "SELECT MAX(de1.idDestinoEspecial) ".
                      "FROM alDestinosEspecialesTbl de1 ".
                      "WHERE de1.vin = de.vin) ".
                      "AND de.vin = h.vin ".
                      "AND h.fechaEvento=(".
                      "SELECT MAX(h1.fechaEvento) ".
                      "FROM alHistoricoUnidadesTbl h1 ". 
                      "WHERE h1.vin = de.vin) ".
                      "AND h.vin = u.vin ".
                      "AND tf.idTarifa = de.idTarifa ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesIdDestinoHdn'], "de.idDestinoEspecial", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesVinTxt'], "de.vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesDistOrigenHdn'], "de.distribuidorOrigen", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesDirOrigenHdn'], "de.direccionOrigen", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesPlazaOrigenHdn'], "de.idPlazaOrigen", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesDistDestinoHdn'], "de.distribuidorDestino", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesDirDestinoHdn'], "de.direccionDestino", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesPlazaDestinoHdn'], "de.idPlazaDestino", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesImporteCobTxt'], "de.importeCob", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesSueldoTxt'], "de.sueldo", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesTarifaHdn'], "de.idTarifa", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesCentroDistHdn'], "h.centroDistribucion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alDestinosEspecialesAvanzadaHdn'], "u.avanzada", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetDestinosEspStr = "SELECT de.*, h.centroDistribucion, u.avanzada, tf.tarifa, tf.descripcion AS nombreTarifa, ".
                                "(SELECT dc.descripcionCentro FROM caDistribuidoresCentrosTbl dc ".
                                    "WHERE dc.distribuidorCentro = de.distribuidorOrigen) AS nombreDistOrigen, ".
                                "(SELECT pl.plaza FROM caPlazasTbl pl WHERE pl.idPlaza = de.idPlazaOrigen) AS nombrePlazaOrigen, ".
                                "(SELECT dc1.descripcionCentro FROM caDistribuidoresCentrosTbl dc1 ".
                                    "WHERE dc1.distribuidorCentro = de.distribuidorDestino) AS nombreDistDestino, ".
                                "(SELECT pl.plaza FROM caPlazasTbl pl WHERE pl.idPlaza = de.idPlazaDestino) AS nombrePlazaDestino, ".
                                "(SELECT dc2.descripcionCentro FROM caDistribuidoresCentrosTbl dc2 ".
                                    "WHERE dc2.distribuidorCentro = h.centroDistribucion) AS nombreCentroDist ".
                                "FROM alDestinosEspecialesTbl de, alHistoricoUnidadesTbl h, alUnidadesTbl u, caTarifasTbl tf ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetDestinosEspStr);

        for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
            $rs['root'][$nInt]['descDistOrigen'] = $rs['root'][$nInt]['distribuidorOrigen']." - ".$rs['root'][$nInt]['nombreDistOrigen'];
            $rs['root'][$nInt]['descPlazaOrigen'] = $rs['root'][$nInt]['idPlazaOrigen']." - ".$rs['root'][$nInt]['nombrePlazaOrigen'];
            $rs['root'][$nInt]['descDistDestino'] = $rs['root'][$nInt]['distribuidorDestino']." - ".$rs['root'][$nInt]['nombreDistDestino'];
            $rs['root'][$nInt]['descPlazaDestino'] = $rs['root'][$nInt]['idPlazaDestino']." - ".$rs['root'][$nInt]['nombrePlazaDestino'];
            $rs['root'][$nInt]['descTarifa'] = $rs['root'][$nInt]['tarifa']." - ".$rs['root'][$nInt]['nombreTarifa'];
            $rs['root'][$nInt]['descCentroDist'] = $rs['root'][$nInt]['centroDistribucion']." - ".$rs['root'][$nInt]['nombreCentroDist'];
        }
            
        echo json_encode($rs);
    }

    function addDestinoEspecial(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $vinArr = explode('|', substr($_REQUEST['trap010VinTxt'], 0, -1));
        if(in_array('', $vinArr)){
            $e[] = array('id'=>'trap010VinTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $colorArr = explode('|', substr($_REQUEST['trap010ColorHdn'], 0, -1));
        if(in_array('', $colorArr)){
            $e[] = array('id'=>'trap010ColorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $simboloArr = explode('|', substr($_REQUEST['trap010SimboloHdn'], 0, -1));
        if(in_array('', $simboloArr)){
            $e[] = array('id'=>'trap010SimboloHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap010CentroDistHdn'] == ""){
            $e[] = array('id'=>'trap010CentroDistHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap010DetenidasHdn'] == ""){
            $e[] = array('id'=>'trap010DetenidasHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap010DistOrigenHdn'] == ""){
            $e[] = array('id'=>'trap010DistOrigenHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap010DistDestinoHdn'] == ""){
            $e[] = array('id'=>'trap010DistDestinoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap010DistribuidorHdn'] == ""){
            $e[] = array('id'=>'trap010DistribuidorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $errorArr = array();
            $success = false;

            for ($nInt=0; $nInt < sizeof($vinArr); $nInt++) { 
                $sqlGetTarifaStr = "SELECT tf.idTarifa ".
                               "FROM caTarifasTbl tf, caClasificacionTarifasTbl ct, caSimbolosUnidadesTbl su ".
                               "WHERE su.simboloUnidad = '".$simboloArr[$nInt]."' ".
                               "AND ct.clasificacion = su.clasificacion ".
                               "AND tf.idTarifa = ct.idTarifa ".
                               "AND tf.tipoTarifa = 'E'";

                $rs = fn_ejecuta_query($sqlGetTarifaStr);

                if (sizeof($rs['records']) > 0) {
                    $tarifa = $rs['root'][0]['idTarifa'];
                }

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    if($_REQUEST['trap010DetenidasHdn'] == 0){
                        //Si es nueva la unidad, ya con la tarifa se inserta en alUnidadesTbl
                        $data = addUnidad($vinArr[$nInt],$_REQUEST['trap010DistribuidorHdn'],$simboloArr[$nInt],$colorArr[$nInt],$_REQUEST['trap010CentroDistHdn'],'RC',$tarifa,$_REQUEST['trap010DistOrigenHdn'],'','','');
                    } else {
                        $data = addHistoricoUnidad($_REQUEST['trap010CentroDistHdn'],$vinArr[$nInt],'RC',$_REQUEST['trap010DistribuidorHdn'],$tarifa,$_REQUEST['trap010DistOrigenHdn'],'','',$_SESSION['usuarioGlobal']);
                    }
                    if ($data['success'] == true) {
                        //Se inserta el segundo estatus en alHistoricoUnidadesTbl con LA
                        $data = addHistoricoUnidad($_REQUEST['trap010CentroDistHdn'],$vinArr[$nInt],'LA',$_REQUEST['trap010DistribuidorHdn'],$tarifa,$_REQUEST['trap010DistOrigenHdn'],'','',$_SESSION['usuarioGlobal'], 2);
                        if ($data['success'] == true) {
                            //Se obtienen las direcciones y plazas del distribuidor Origen y Destino
                            $sqlGetDireccionesPlazasDistStr = "SELECT distribuidorCentro, idPlaza, direccionEntrega ".
                                                              "FROM caDistribuidoresCentrosTbl ".
                                                              "WHERE distribuidorCentro ='".$_REQUEST['trap010DistOrigenHdn']."' ".
                                                              "OR distribuidorCentro ='".$_REQUEST['trap010DistDestinoHdn']."'";

                            $rs = fn_ejecuta_query($sqlGetDireccionesPlazasDistStr);

                            for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
                                if ($rs['root'][$iInt]['distribuidorCentro'] == $_REQUEST['trap010DistOrigenHdn']) {
                                    $dirOrigen = $rs['root'][$iInt]['direccionEntrega'];
                                    $plazaOrigen = $rs['root'][$iInt]['idPlaza'];
                                }
                                if ($rs['root'][$iInt]['distribuidorCentro'] == $_REQUEST['trap010DistDestinoHdn']) {
                                    $dirDestino = $rs['root'][$iInt]['direccionEntrega'];
                                    $plazaDestino = $rs['root'][$iInt]['idPlaza'];
                                }
                            }
                            
                            $sqlAddDestinoEspecialStr = "INSERT INTO alDestinosEspecialesTbl ".
                                                        "(vin, distribuidorOrigen, direccionOrigen, idPlazaOrigen, distribuidorDestino,".
                                                        "direccionDestino, idPlazaDestino, importeCob, sueldo, idTarifa) VALUES('".
                                                        $vinArr[$nInt]."','".$_REQUEST['trap010DistOrigenHdn']."',".
                                                        $dirOrigen.",".$plazaOrigen.",'".
                                                        $_REQUEST['trap010DistDestinoHdn']."',".$dirDestino.",".
                                                        $plazaDestino.",0.00,0.00,".$tarifa.")";

                            $rs = fn_ejecuta_query($sqlAddDestinoEspecialStr);

                            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                                $success = true;
                                if ($_REQUEST['trap010DetenidasHdn'] == 1) {
                                    $sqlGetNumMovimiento = "SELECT MAX(numeroMovimiento) AS numeroMovimiento FROM alUnidadesDetenidasTbl ".
                                                           "WHERE vin = '".$vinArr[$nInt]."'";

                                    $rsLib = fn_ejecuta_query($sqlGetNumMovimiento);

                                    $sqlLiberarUnidadStr = "UPDATE alUnidadesDetenidasTbl SET ".
                                                           "claveMovimiento = 'UL', ".
                                                           "centroLibera = '".$_REQUEST['trap010CentroDistHdn']."', ".
                                                           "fechaFinal = '".date("Y-m-d H:i:s")."' ".
                                                           "WHERE vin = '".$vinArr[$nInt]."' ".
                                                           "AND numeroMovimiento = ".$rsLib['root'][0]['numeroMovimiento'];

                                    $rsLib = fn_ejecuta_query($sqlLiberarUnidadStr);
                                }
                            } else {
                                $a['success'] = false;
                                array_push($errorArr, $vinArr[$nInt]);
                            }            
                        } else {
                            $a['success'] = false;
                            array_push($errorArr, $vinArr[$nInt]);
                        }
                    } else {
                        $a['success'] = false;
                        array_push($errorArr, $vinArr[$nInt]);
                    }
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = array_push($errorArr, $vinArr[$nInt]);
                } 
            }

            //Concatenate Errors
            if (sizeof($errorArr) > 0) {
                if ($success == true) {
                    $a['errorMessage'] =  getDestinosEspecialesSuccessMsg()."<br>".getDestinosEspecialesFailedMsg();
                    foreach ($errorArr as $error) {
                        $a['errorMessage'] .= "<br>".$error;
                    }
                } else if ($success == false){
                    $a['errorMessage'] = getDestinosEspecialesFailedMsg();
                    foreach ($errorArr as $error) {
                        $a['errorMessage'] .= "<br>".$error;
                    }
                }   
            } else if($success == true){
                $a['successMessage'] = getDestinosEspecialesSuccessMsg();
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updDestinoEspecial(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trap045IdDestinoEspecialHdn'] == ""){
            $e[] = array('id'=>'trap045IdDestinoEspecialHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap045VinHdn'] == ""){
            $e[] = array('id'=>'trap045VinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap045DistOrigenHdn'] == ""){
            $e[] = array('id'=>'trap045DistOrigenHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap045DirOrigenHdn'] == ""){
            $e[] = array('id'=>'trap045DirOrigenHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap045PlazaOrigenHdn'] == ""){
            $e[] = array('id'=>'trap045PlazaOrigenHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap045DistDestinoHdn'] == ""){
            $e[] = array('id'=>'trap045DistDestinoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap045DirDestinoHdn'] == ""){
            $e[] = array('id'=>'trap045DirDestinoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap045PlazaDestinoHdn'] == ""){
            $e[] = array('id'=>'trap045PlazaDestinoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap045IdTarifaHdn'] == ""){
            $e[] = array('id'=>'trap045IdTarifaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdDestinoEspStr = "UPDATE alDestinosEspecialesTbl ".
                                   "SET vin = '".$_REQUEST['trap045VinHdn']."', ".
                                   "distribuidorOrigen = '".$_REQUEST['trap045DistOrigenHdn']."', ".
                                   "direccionOrigen = ".$_REQUEST['trap045DirOrigenHdn'].",".
                                   "idPlazaOrigen = ".$_REQUEST['trap045PlazaOrigenHdn'].",".
                                   "distribuidorDestino = '".$_REQUEST['trap045DistDestinoHdn']."',".
                                   "direccionDestino = ".$_REQUEST['trap045DirDestinoHdn'].",".
                                   "idPlazaDestino = ".$_REQUEST['trap045PlazaDestinoHdn'].",".
                                   "importeCob = ".replaceEmptyDec($_REQUEST['trap045ImporteCobHdn']).",".
                                   "sueldo = ".replaceEmptyDec($_REQUEST['trap045SueldoHdn']).",".
                                   "idTarifa = ".$_REQUEST['trap045IdTarifaHdn']." ".
                                   "WHERE idDestinoEspecial = ".$_REQUEST['trap045IdDestinoEspecialHdn'];

            $rs = fn_ejecuta_query($sqlUpdDestinoEspStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdDestinoEspStr;
                $a['successMessage'] = getDestinosEspecialesUpdMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdDestinoEspStr;
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function dltDestinoEspecial(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trap733VinHdn'] == ""){
            $e[] = array('id'=>'trap733VinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap733CentroDistHdn'] == ""){
            $e[] = array('id'=>'trap733CentroDistHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap733DistribuidorHdn'] == ""){
            $e[] = array('id'=>'trap733DistribuidorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap733TarifaHdn'] == ""){
            $e[] = array('id'=>'trap733TarifaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap733LocalizacionHdn'] == ""){
            $e[] = array('id'=>'trap733LocalizacionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
            $sqlGetUltimoDestinoEspecialStr = "SELECT MAX(idDestinoEspecial) AS idDestinoEspecial FROM alDestinosEspecialesTbl ".
                                              "WHERE vin = '".$_REQUEST['trap733VinHdn']."'";

            $rs = fn_ejecuta_query($sqlGetUltimoDestinoEspecialStr);

            $idDestino = $rs['root'][0]['idDestinoEspecial'];

            if($idDestino > 0){
                $sqlDltDestinoEspecialStr = "DELETE FROM alDestinosEspecialesTbl ".
                                            "WHERE vin = '".$_REQUEST['trap733VinHdn']."' ".
                                            "AND idDestinoEspecial = ".$idDestino;

                $rs = fn_ejecuta_query($sqlDltDestinoEspecialStr);

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {

                    $sqlCheckDetenidaNuevaStr = "SELECT vin, MAX(numeroMovimiento) AS numeroMovimiento FROM alUnidadesDetenidasTbl ".
                                                "WHERE vin = '".$_REQUEST['trap733VinHdn']."' ".
                                                "AND numeroMovimiento = (SELECT max(numeroMovimiento) ".
                                                    "FROM alunidadesdetenidastbl WHERE vin = '".$_REQUEST['trap733VinHdn']."') ".
                                                "AND centroLibera IS NOT null AND fechaFinal IS NOT NULL ";

                    $rs = fn_ejecuta_query($sqlCheckDetenidaNuevaStr);
                    $ultimaDetencion = $rs['root'][0]['numeroMovimiento'];

                    if ($rs['root'][0]['vin'] != "") {
                        $sqlLiberarUnidadStr = "UPDATE alUnidadesDetenidasTbl SET ".
                                               "claveMovimiento = 'UD', ".
                                               "centroLibera = NULL, ".
                                               "fechaFinal = NULL ".
                                               "WHERE vin = '".$_REQUEST['trap733VinHdn']."' ".
                                                "AND numeroMovimiento = ".$ultimaDetencion;

                        $rs = fn_ejecuta_query($sqlLiberarUnidadStr);

                        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                        } else {
                            $a['success'] = false;
                            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlLiberarUnidadStr;
                        }
                    }
                }
            }
            
            if($a['success'] == true){
                $data = addHistoricoUnidad($_REQUEST['trap733CentroDistHdn'],$_REQUEST['trap733VinHdn'],'MC',$_REQUEST['trap733DistribuidorHdn'],$_REQUEST['trap733TarifaHdn'],$_REQUEST['trap733LocalizacionHdn'],'','',$_SESSION['usuarioGlobal']);

                if ($data['success'] == true) {
                    $a['successMessage'] = getDestinosEspecialesDltMsg();
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $data['errorMessage'];
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDltDestinoEspecialStr;
            }   
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

?>