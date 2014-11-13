<?php
	/************************************************************************
    * Autor: Alfonso César Martínez Fuertes
    * Fecha: 15-Enero-2014
    * Tablas afectadas: 
    * Descripción: Programa para dar mantenimiento a gastos de foraneos
    *************************************************************************/

    session_start();
	$_SESSION['modulo'] = "trGastosViajeTractor";
    //SESION DE PRUEBA
    $_SESSION['usuCto'] = "CDTOL";
    $_SESSION['usuario'] = 1;
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
	
    switch($_REQUEST['trGastosViajeTractorActionHdn']){
    	case 'getGastosViajeTractor':
    		getGastosViajeTractor();
    		break;
        case 'getGastosViajePatio':
            getGastosViajePatio();
            break;
        case 'getCalculoGastos':
            getCalculoGastos();
            break;
        case 'getCalculoGastosViajeVacio':
            getCalculoGastosViajeVacio();
            break;
        case 'addGastosViajeTractor':
            echo addGastosViajeTractor($_REQUEST['trGastosViajeTractorConceptoHdn'], 
                                        $_REQUEST['trGastosViajeTractorFolioTxt'],
                                        $_REQUEST['trGastosViajeTractorCtaContableTxt'],
                                        $_REQUEST['trGastosViajeTractorMesAfectacionTxt'],
                                        $_REQUEST['trGastosViajeTractorImporteTxt'],
                                        $_REQUEST['trGastosViajeTractorObservacionesTxa'],
                                        $_REQUEST['trGastosViajeTractorClaveMovimientoHdn']);
            break;
        case 'cancelarGastos':
            cancelarGastos();
            break;
    }

    function getGastosViajeTractor(){
        $lsWhereStr = "WHERE gv.idViajeTractor = vt.idViajeTractor ".
                      "AND vt.idTractor = tr.idTractor ".
                      "AND ch.claveChofer = vt.claveChofer ".
                      "AND co.concepto = gv.concepto ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorIdViajeTractorHdn'], "gv.idViajeTractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorConceptoHdn'], "gv.concepto", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorCentroDistHdn'], "gv.centroDistribucion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorFolioTxt'], "gv.folio", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorFechaTxt'], "gv.fechaEvento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorCtaContableTxt'], "gv.cuentaContable", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorMesAfectacionTxt'], "gv.mesAfectacion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorImporteTxt'], "gv.importe", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorObservacionesTxa'], "gv.observaciones", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorClaveMovimientoHdn'], "gv.claveMovimiento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetGastosViajesTractoresStr = "SELECT gv.*, gv.claveMovimiento AS claveMovGasto, vt.*, tr.tractor, co.nombre AS nombreConcepto, ".
                                          "tr.compania AS ciaTractor, ch.claveChofer, ch.nombre, ch.apellidoPaterno, ch.apellidoMaterno, ".
                                          "(SELECT co2.descripcion FROM caCompaniasTbl co2 WHERE co2.compania = tr.compania) AS descCiaTractor, ".
                                          "(SELECT pl.plaza FROM caPlazasTbl pl WHERE pl.idPlaza = vt.idPlazaOrigen) AS nombrePlazaOrigen, ".
                                          "(SELECT pl2.plaza FROM caPlazasTbl pl2 WHERE pl2.idPlaza = vt.idPlazaDestino) AS nombrePlazaDestino ".
                                          "FROM trGastosViajeTractorTbl gv, trViajesTractoresTbl vt, ".
                                          "caTractoresTbl tr, caChoferesTbl ch, caConceptosTbl co ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetGastosViajesTractoresStr);

        for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
            $rs['root'][$nInt]['descDistribuidor'] = $rs['root'][$nInt]['distribuidor']." - ".$rs['root'][$nInt]['descripcionCentro'];
            $rs['root'][$nInt]['descCompania'] = $rs['root'][$nInt]['companiaRemitente']." - ".$rs['root'][$nInt]['descripcionCompania'];
            $rs['root'][$nInt]['descTractorCia'] = $rs['root'][$nInt]['ciaTractor']." - ".$rs['root'][$nInt]['descCiaTractor'];
            $rs['root'][$nInt]['nombreChofer'] = $rs['root'][$nInt]['claveChofer']." - ".
                                                 $rs['root'][$nInt]['nombre']." ".
                                                 $rs['root'][$nInt]['apellidoPaterno']." ".
                                                 $rs['root'][$nInt]['apellidoMaterno'];
            $rs['root'][$nInt]['fechaEvento'] = date_format(date_create($rs['root'][$nInt]['fechaEvento']), "Y-m-d");

            $rs['root'][$nInt]['descConcepto'] = $rs['root'][$nInt]['concepto']." - ".$rs['root'][$nInt]['nombreConcepto'];
        }

        echo json_encode($rs);	
    }

    function getGastosViajePatio(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trGastosViajeTractorIdViajeTractorHdn'], "gv.idViajeTractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetGastosViajePatioStr = "SELECT gv.*, ".
                                     "(SELECT dc.descripcionCentro FROM caDistribuidoresCentrosTbl dc ".
                                        "WHERE dc.distribuidorCentro = gv.centroDistribucion) AS nombreCd ".
                                     "FROM trGastosViajeTractorTbl gv ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetGastosViajePatioStr);
        
        for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
            if (!isset($patios[$rs['root'][$nInt]['centroDistribucion']])) {
                $patios[$rs['root'][$nInt]['centroDistribucion']] = 0;
            }

            $patios[$rs['root'][$nInt]['centroDistribucion']] += $rs['root'][$nInt]['importe'];
            $nombres[$rs['root'][$nInt]['centroDistribucion']] = $rs['root'][$nInt]['nombreCd'];
        }

        $data = array();
        $data['success'] = true;
        $data['records'] = sizeof($patios);

        for ($nInt=0; $nInt < sizeof($patios); $nInt++) { 
            $data['root'][$nInt] = array('patio' => key($patios), 'nombre' => $nombres[key($patios)], 'importe'=>$patios[key($patios)]);   
        }

        echo json_encode($data);
    }

    function getCalculoGastos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap484IdViajeHdn'], "vt.idViajeTractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $getDatosViajeStr = "SELECT vt.kilometrosTabulados,vt.numeroUnidades,vt.numeroRepartos, tr.rendimiento ".
                            "FROM trViajesTractoresTbl vt, caTractoresTbl tr ".
                            "WHERE tr.idTractor = vt.idTractor ".$lsWhereStr;

        $rs = fn_ejecuta_query($getDatosViajeStr);

        //Si cambian el dato de kilometraje a mano
        if (isset($_REQUEST['trap484KilometrosTxt']) && $_REQUEST['trap484KilometrosTxt'] >= 0) {
            $kmTabulados = $_REQUEST['trap484KilometrosTxt'];
        } else {
            $kmTabulados = $rs['root'][0]['kilometrosTabulados'];
        }

        $rendimiento = $rs['root'][0]['rendimiento'];
        $numUnidades = $rs['root'][0]['numeroUnidades'];
        $repartos = $rs['root'][0]['numeroRepartos'];
        $macheteros = 0;

        $sqlGetConceptosStr = "SELECT concepto, importe ".
                              "FROM caConceptosCentrosTbl ".
                              "WHERE (concepto = 2315 ".
                              "OR concepto = 6002 ".
                              "OR concepto = 6010 ".
                              "OR concepto = 2342) ".
                              "AND centroDistribucion = '".$_SESSION['usuCto']."'";

        $rs = fn_ejecuta_query($sqlGetConceptosStr);        
        $conceptosArr = array();

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $conceptosArr[$rs['root'][$iInt]['concepto']] = $rs['root'][$iInt]['importe'];
        }

        //Se revisa si los conceptos existen
        //Si no, los cálculos de gastos no se realizan
        if (!isset($conceptosArr['2315'])) {
            $a['id'] = '2315';
            $a['success'] = false;
            $a['errorMessage'] = getConceptosNoExist();
        }
         if (!isset($conceptosArr['6002'])) {
            $a['id'] = '6002';
            $a['success'] = false;
            $a['errorMessage'] = getConceptosNoExist();
        }
         if (!isset($conceptosArr['6010'])) {
            $a['id'] = '6010';
            $a['success'] = false;
            $a['errorMessage'] = getConceptosNoExist();
        }
         if (!isset($conceptosArr['2342'])) {
            $a['id'] = '2342';
            $a['success'] = false;
            $a['errorMessage'] = getConceptosNoExist();
        }

        if ($a['success'] == true) {
            //CALCULO DE LITROS
            $litros = ($kmTabulados * 2) / $rendimiento;
            $litros = number_format($litros,2, ".", "");

            // CALCULO DE COMBUSTIBLE 
            $combustible = $litros *  $conceptosArr['2315'];
            
            //CALCULO MACHETEROS
            if ($repartos == 1) {
                $macheteros  = $conceptosArr['6002'] * $numUnidades;
            }
            else
            {
                $macheteros1 = $repartos - 1;
                $macheteros2 = $conceptosArr['6002']  * $macheteros1;
                $macheteros3 = $conceptosArr['6002'] * $numUnidades; 
                $macheteros = $macheteros2 + $macheteros3;
              
            }

            //CALCULO DE TAXIS 
            $taxi = $conceptosArr['6010'] * $numUnidades;

            //CALCULO DE ALIMENTOS
            $alimentos = $conceptosArr['2342'] * $kmTabulados;
            $totalAlimentos = $alimentos * 2;

            //TOTAL
            $total = ceil($combustible + $macheteros + $taxi + $totalAlimentos);
            $cent = (($total / 100) - floor($total/100))*100;
            
            if ($cent > 1) {
                $total += 100 - $cent;
            }
            $combustible = number_format($combustible, 2, ".", "");
            $macheteros = number_format($macheteros, 2, ".", "");
            $taxi = number_format($taxi, 2, ".", "");
            $totalAlimentos = number_format($totalAlimentos, 2, ".", "");

            $a['root'] = array(array('concepto'=>'Litros','cantidad' => $litros),array('id'=>'2315','concepto'=>'COMBUSTIBLE','cantidad' => $combustible),array('id'=>'6010','concepto'=>'TAXIS','cantidad' => $taxi),array('id'=>'2342','concepto'=>'ALIMENTOS','cantidad' => $totalAlimentos), array('id'=>'2333','concepto'=>'PEAJES','cantidad' => 0.00), array('id'=>'6002','concepto'=>'MACHETEROS','cantidad' => $macheteros), array('id'=>'OTROS','concepto'=>'OTROS', 'cantidad'=>0.00),array('concepto'=>'TOTAL','cantidad' => $total));
        }            
        
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function getCalculoGastosViajeVacio(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trap484KmTabuladosTxt'] == ""){
            $e[] = array('id'=>'trap484KmTabuladosTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['trap484RendimientoTxt'] == ""){
            $e[] = array('id'=>'trap484RendimientoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        
        if($a['success'] == true){
            $kmTabulados = $_REQUEST['trap484KmTabuladosTxt'];
            $rendimiento = $_REQUEST['trap484RendimientoTxt'];

            $sqlGetConceptosStr = "SELECT concepto, importe ".
                                  "FROM caConceptosCentrosTbl ".
                                  "WHERE (concepto = 2315 ".
                                  "OR concepto = 2342) ".
                                  "AND centroDistribucion = '".$_SESSION['usuCto']."'";

            $rs = fn_ejecuta_query($sqlGetConceptosStr);        
            $conceptosArr = array();

            for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
                $conceptosArr[$rs['root'][$iInt]['concepto']] = $rs['root'][$iInt]['importe'];
            }

            //Se revisa si los conceptos existen
            //Si no, los cálculos de gastos no se realizan
            if (!isset($conceptosArr['2315'])) {
                $a['id'] = '2315';
                $a['success'] = false;
                $a['errorMessage'] = getConceptosNoExist();
            }
             if (!isset($conceptosArr['2342'])) {
                $a['id'] = '2342';
                $a['success'] = false;
                $a['errorMessage'] = getConceptosNoExist();
            }

            if ($a['success'] == true) {
                //CALCULO DE LITROS
                $litros = ($kmTabulados * 2) / $rendimiento;
                $litros = number_format($litros,2, ".", "");

                // CALCULO DE COMBUSTIBLE 
                $combustible = $litros *  $conceptosArr['2315'];

                //CALCULO DE ALIMENTOS
                $alimentos = $conceptosArr['2342'] * $kmTabulados;
                $totalAlimentos = $alimentos * 2;

                //TOTAL
                $total = ceil($combustible + $totalAlimentos);
                $cent = (($total / 100) - floor($total/100))*100;
                
                if ($cent > 1) {
                    $total += 100 - $cent;
                }
                $combustible = number_format($combustible, 2, ".", "");
                $totalAlimentos = number_format($totalAlimentos, 2, ".", "");

                $a['root'] = array(array('concepto'=>'Litros','cantidad' => $litros),array('id'=>'2315','concepto'=>'COMBUSTIBLE','cantidad' => $combustible),array('id'=>'2342','concepto'=>'ALIMENTOS','cantidad' => $totalAlimentos), array('id'=>'OTROS','concepto'=>'OTROS', 'cantidad'=>0.00),array('concepto'=>'TOTAL','cantidad' => $total));
            }            
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function addGastosViajeTractor($idViaje, $compania, $concepto, $importe, $observaciones, $claveMov, $tipoDocumento){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($idViaje == ""){
            $e[] = array('id'=>'%idViajeTractorHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($compania == ""){
            $e[] = array('id'=>'%CompaniaHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($tipoDocumento == ""){
            $e[] = array('id'=>'%TipoDoctoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($claveMov == ""){
            $e[] = array('id'=>'%ClaveMovimientoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }

        $conceptoArr = explode('|', substr($concepto, 0, -1));
        if(in_array('', $valorArr)){
            $e[] = array('id'=>'%ConceptoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $importeArr = explode('|', substr($importe, 0, -1));
        if(in_array('', $valorArr)){
            $e[] = array('id'=>'%ImporteTxt','msg'=>getRequerido());
            $a['success'] = false;
        }
        $observacionesArr = explode('|', substr($observaciones, 0, -1));
        if(in_array('', $valorArr)){
            $e[] = array('id'=>'%ObservacionesTxa','msg'=>getRequerido());
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            //Obtener el folio
            $sqlGetFolioStr = "SELECT folio FROM trFoliosTbl ".
                              "WHERE tipoDocumento='".$tipoDocumento."' ".
                              "AND centroDistribucion='".$_SESSION['usuCto']."' ".
                              "AND compania = '".$compania."'";

            $rs = fn_ejecuta_query($sqlGetFolioStr);
            $folio = $rs['root'][0]['folio'];

            if ((integer) $folio < 9) {
                $folio = '0'.(string)((integer)$folio+1);
            } else {
                $folio = (string)((integer)$folio+1);
            }

            for ($iInt=0; $iInt < sizeof($conceptoArr); $iInt++) { 
                if ($conceptoArr[$iInt] != "OTROS") {

                    $sqlAddGastosViajeTractorStr = "INSERT INTO trGastosViajeTractorTbl ".
                                                   "(idViajeTractor, concepto,centroDistribucion,folio,fechaEvento,cuentaContable,".
                                                    "mesAfectacion,importe,observaciones,claveMovimiento,usuario,ip) ".
                                                   "VALUES (".
                                                    $idViaje.",".
                                                    "'".$conceptoArr[$iInt]."',".
                                                    "'".$_SESSION['usuCto']."',".
                                                    "'".$folio."',".
                                                    "'".date("Y-m-d H:i:s")."',".
                                                    "(SELECT cuentaContable FROM caConceptosCentrosTbl ".
                                                        "WHERE concepto = '".$conceptoArr[$iInt]."' ".
                                                        "AND centroDistribucion = '".$_SESSION['usuCto']."'),".
                                                    "0,".
                                                    replaceEmptyDec($importeArr[$iInt]).",".
                                                    replaceEmptyNull($observacionesArr[$iInt]).",".
                                                    "'".$claveMov."',".
                                                    $_SESSION['usuario'].",".
                                                    "'".$_SERVER['REMOTE_ADDR']."'".
                                                    ")";

                    $rs = fn_ejecuta_query($sqlAddGastosViajeTractorStr);

                    if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                        $a['successMessage'] = getGastosViajeTractorSuccessMsg();
                        $a['sql'] = $sqlAddGastosViajeTractorStr;
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddGastosViajeTractorStr;
                        break;
                    }
                }
            }

            $sqlUpdFolioStr = "UPDATE trFoliosTbl ".
                              "SET folio = '".$folio."' ".
                              "WHERE centroDistribucion = '".$_SESSION['usuCto']."' ".
                              "AND compania = '".$compania."' ".
                              "AND tipoDocumento = '".$tipoDocumento."'";

            $rs = fn_ejecuta_query($sqlUpdFolioStr);
        } else {
            $a['errorMessage'] = getErrorRequeridos();
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        return $a;
    }

    function cancelarGastos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trap486FolioTxt'] == ""){
            $e[] = array('id'=>'trap486FolioTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($a['success'] == true){
            $sqlCancelarGastosStr = "UPDATE trGastosViajeTractorTbl SET claveMovimiento = 'GX' ".
                                    "WHERE folio = '".$_REQUEST['trap486FolioTxt']."'";

            $rs = fn_ejecuta_query($sqlCancelarGastosStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlCancelarGastosStr;
                $a['successMessage'] = getGastosCanceladosSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCancelarGastosStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>