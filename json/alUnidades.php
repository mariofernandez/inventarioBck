<?php
	session_start();
	$_SESSION['modulo'] = "alUnidades";
    //PRUEBA
    $_SESSION['centroDistGlobal'] = 'CDTOL';
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");

    date_default_timezone_set('America/Mexico_City');

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
	
    switch($_REQUEST['alUnidadesActionHdn']){
        case 'getUnidades':
            getUnidades();
            break;
        case 'getBloqueadas':
            getBloqueadas();
            break;
        case 'getDisponibles':
            getDisponibles();
            break;
        case 'getUnidades660':
            getUnidades660();
            break;
        case 'getHistoriaUnidad':
            getHistoriaUnidad();
            break;
        case 'addUnidadMasivo':
            addUnidadMasivo();
            break;
        case 'addUnidad':
            echo json_encode(addUnidad($_REQUEST['alUnidadesVinHdn'],$_REQUEST['alUnidadesDistribuidorTxt'],
                                $_REQUEST['alUnidadesSimboloUnidadHdn'], $_REQUEST['alUnidadesColorHdn'],
                                $_REQUEST['alUnidadesCentroDistHdn'],$_REQUEST['alUnidadesClaveMovimientoHdn'],
                                $_REQUEST['alUnidadesTarifaTxt'],$_REQUEST['alUnidadesLocalizacionUnidadHdn'],
                                $_REQUEST['alUnidadesFolioRepuveTxt'],$_REQUEST['alUnidadesChoferHdn'],
                                $_REQUEST['alUnidadesObservacionesTxa']));
            break;
        case 'updUnidad':
            updUnidad();
            break;
        case 'addHistoricoUnidad':
            echo json_encode(addHistoricoUnidad($_REQUEST['alUnidadesCentroDistHdn'],$_REQUEST['alUnidadesVinHdn'],
                                                $_REQUEST['alUnidadesClaveMovimientoHdn'],$_REQUEST['alUnidadesDistribuidorTxt'],
                                                $_REQUEST['alUnidadesTarifaTxt'],$_REQUEST['alUnidadesLocalizacionUnidadHdn'],
                                                $_REQUEST['alUnidadesChoferHdn'],$_REQUEST['alUnidadesObservacionesTxa'],
                                                $_REQUEST['alUnidadesUsuarioHdn']));
            break;
        case 'liberarUnidad':
            liberarUnidad();
            break;
        case 'bloquearUnidad':
            bloquearUnidad();
            break;
        case 'getDetenidas':
            getDetenidas();
            break;
        case 'getNoDetenidas':
            getNoDetenidas();
            break;
        case 'detencionUnidades':
            detencionUnidades();
            break;
        case 'liberarDetencionUnidades':
            liberarDetencionUnidades();
            break;
        case 'getHoldeadas':
            getHoldeadas();
            break;
        case 'holdUnidades':
            echo json_encode(holdUnidades($_REQUEST['alUnidadesVinHdn'], $_REQUEST['alUnidadesClaveMovimientoHdn']));
            break;
        case 'quitarHoldUnidades':
            quitarHoldUnidades();
            break;
        case 'insertarEntradaPatio':
            insertarEntradaPatio();
            break;
		case 'getHistoricoUnidades':
            getHistoricoUnidades();
            break;
        case 'updateObservacionesHistorico':
            updateObservacionesHistorico();
            break;
        case 'getRepuve':
            getRepuve();
            break;
        default:
        	echo '';
    }

    function getUnidades(){
    	$lsWhereStr = "WHERE u.vin = h.vin ".
                      "AND h.idTarifa = t.idTarifa ".
					  "AND h.fechaEvento=(".
    				  "SELECT MAX(h1.fechaEvento) ".
    				  "FROM alHistoricoUnidadesTbl h1 ". 
    				  "WHERE h1.vin = u.vin) ".
                      "AND sm.simboloUnidad = u.simboloUnidad ".
                      "AND u.distribuidor = d2.distribuidorCentro ";

	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['alUnidadesAvanzadaHdn'], "u.avanzada", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesVinHdn'], "u.vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesDistribuidorHdn'], "h.distribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesSimboloUnidadHdn'], "u.simboloUnidad", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesColorHdn'], "u.color", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            if ($_REQUEST['alUnidadesRepuveHdn'] == 'NOTNULL') {
                $lsWhereStr .= "AND u.folioRepuve IS NOT NULL ";
            } else if ($_REQUEST['alUnidadesRepuveHdn'] == 'NULL'){
                $lsWhereStr .= "AND u.folioRepuve IS NULL ";
            } else {
                $lsCondicionStr = fn_construct($_REQUEST['alUnidadesRepuveHdn'], "u.folioRepuve", 0);
                $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
            }
        }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['alUnidadesCentroDistHdn'], "h.centroDistribucion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesDistribuidorTxt'], "h.distribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['alUnidadesClaveMovimientoHdn'], "h.claveMovimiento", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesMarcaHdn'], "sm.marca", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesIdTarifaHdn'], "t.idTarifa", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }


		$sqlGetUnidadesStr = "SELECT u.vin, u.avanzada, u.distribuidor AS distribuidorInicial, ".
                             "u.simboloUnidad, u.color, u.folioRepuve, h.*, t.descripcion AS descTarifa, ".
                             "d2.estatus AS estatusDistribuidor, sm.marca, sm.descripcion AS nombreSimbolo, ".
                             "(SELECT ud.claveMovimiento FROM alUnidadesDetenidasTbl ud WHERE ud.vin = u.vin ".
                                "AND ud.numeroMovimiento = (SELECT MAX(ud2.numeroMovimiento) FROM alUnidadesDetenidasTbl ud2 ".
                                    "WHERE ud2.vin=ud.vin)) AS claveMovDetenidas, ".
                             "(SELECT g2.nombre FROM caGeneralesTbl g2 WHERE g2.tabla = 'alUnidadesDetenidasTbl' ".
                                "AND g2.columna = 'claveMovimiento' AND g2.valor = ".
                                    "(SELECT ud.claveMovimiento FROM alUnidadesDetenidasTbl ud WHERE ud.vin = u.vin ".
                                    "AND ud.numeroMovimiento = (SELECT MAX(ud2.numeroMovimiento) FROM alUnidadesDetenidasTbl ud2 ".
                                        "WHERE ud2.vin=ud.vin))".
                                ") AS nombreClaveMovDetenidas, ".
                             "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.valor=h.claveMovimiento ".
                             "AND g.tabla='alHistoricoUnidadesTbl' AND g.columna='claveMovimiento') AS nombreClaveMov, ".
                             "(SELECT d.descripcionCentro FROM caDistribuidoresCentrosTbl d ".
                             "WHERE d.distribuidorCentro=h.distribuidor) AS nombreDistribuidor, ".
                             "(SELECT d.descripcionCentro FROM caDistribuidoresCentrosTbl d WHERE ".
                             "d.distribuidorCentro=h.centroDistribucion) AS descCentroDistribucion, ".
                             "(SELECT d.descripcionCentro FROM caDistribuidoresCentrosTbl d WHERE ".
                             "d.distribuidorCentro=h.localizacionUnidad) AS descLocalizacion,".
                             "(SELECT mu.descripcion FROM caMarcasUnidadesTbl mu WHERE mu.marca = sm.marca) as descMarca, ".
                             "(SELECT co.descripcion FROM caColorUnidadesTbl co WHERE co.marca = sm.marca AND co.color = u.color) AS nombreColor ".
						 	 "FROM alUnidadesTbl u, alHistoricoUnidadesTbl h, caTarifasTbl t, caSimbolosUnidadesTbl sm, ".
						 	 "caDistribuidoresCentrosTbl d2 ".$lsWhereStr;
        
		$rs = fn_ejecuta_query($sqlGetUnidadesStr);
        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descDist'] = $rs['root'][$iInt]['distribuidor']." - ".$rs['root'][$iInt]['nombreDistribuidor'];
            $rs['root'][$iInt]['descColor'] = $rs['root'][$iInt]['color']." - ".$rs['root'][$iInt]['nombreColor'];
            $rs['root'][$iInt]['descTarifa'] = $rs['root'][$iInt]['idTarifa']." - ".$rs['root'][$iInt]['descTarifa'];
            $rs['root'][$iInt]['descSimbolo'] = $rs['root'][$iInt]['simboloUnidad']." - ".$rs['root'][$iInt]['nombreSimbolo'];
            $rs['root'][$iInt]['marcaDesc'] = $rs['root'][$iInt]['marca']." - ".$rs['root'][$iInt]['descMarca'];
        }
			
		echo json_encode($rs);
   	}

    function getBloqueadas(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesAvanzadaHdn'], "avanzada", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesVinHdn'], "vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetBloqueadasStr = "SELECT * FROM alUnidadesTmp ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetBloqueadasStr);
            
        echo json_encode($rs);
    }

    function getDisponibles(){
        $lsWhereStr = "WHERE vin NOT IN  ".
                      "(SELECT vin from alUnidadesTmp)";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesAvanzadaHdn'], "avanzada", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesVinHdn'], "vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetDisponiblesStr = "SELECT * FROM alUnidadesTbl ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetDisponiblesStr);
            
        echo json_encode($rs);
    }

    function getUnidades660(){
        $lsWhereStr = "WHERE su.simboloUnidad = ss.model ".
                      "AND ct.clasificacion = su.clasificacion ".
                      "AND tf.idTarifa = ct.idTarifa ".
                      "AND tf.tipoTarifa = 'N' ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesVinHdn'], "ss.vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesAvanzadaHdn'], "ss.numAvanzada", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesTipoTarifaHdn'], "tf.tipoTarifa", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGet660Str = "SELECT ss.*, su.descripcion AS descSimbolo, tf.idTarifa, tf.descripcion AS nombreTarifa,".
                        "(SELECT co.descripcion FROM cacolorunidadestbl co WHERE co.color = ss.colorcode ".
                            "AND co.marca = su.marca) AS nombreColor,".
                        "(SELECT dc.descripcionCentro FROM cadistribuidorescentrostbl dc ".
                            "WHERE dc.distribuidorCentro = ss.dealerid) AS descripcionCentro ". 
                        "FROM al660tbl ss, caSimbolosUnidadesTbl su, caclasificaciontarifastbl ct, catarifastbl tf ".$lsWhereStr;   
        
        $rs = fn_ejecuta_query($sqlGet660Str);
        
        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descTarifa'] = $rs['root'][$iInt]['idTarifa']." - ".$rs['root'][$iInt]['nombreTarifa'];
        }

        echo json_encode($rs);
    }

    function getHistoriaUnidad(){

        $sqlGetHistoricoUnidadStr = "SELECT hu.*,".
                                    "(SELECT lp.fila FROM alLocalizacionPatiosTbl lp WHERE lp.vin = hu.vin) AS fila,".
                                    "(SELECT lp2.lugar FROM alLocalizacionPatiosTbl lp2 WHERE lp2.vin = hu.vin) AS lugar,".
                                    "(SELECT tv.folio ".
                                        "FROM trunidadesdetallestalonestbl ut, trtalonesviajestbl tv ".
                                        "WHERE ut.vin = hu.vin ".
                                        "AND tv.idTalon = ut.idTalon ".
                                        "AND date_format(tv.fechaEvento, '%H:%i') = date_format(hu.fechaEvento, '%H:%i') ".
                                        "HAVING hu.claveMovimiento = 'AM' ".
                                    ") AS folio, ".
                                    "(SELECT tr.tractor ".
                                        "FROM trunidadesdetallestalonestbl ut2, ".
                                            "trtalonesviajestbl tv2, trviajestractorestbl vt, catractorestbl tr ".
                                        "WHERE ut2.vin = hu.vin ".
                                        "AND tv2.idTalon = ut2.idTalon ".
                                        "AND vt.idViajeTractor = tv2.idViajeTractor ".
                                        "AND tr.idTractor = vt.idTractor ".
                                        "AND date_format(tv2.fechaEvento, '%H:%i') = date_format(hu.fechaEvento, '%H:%i') ".
                                        "HAVING hu.claveMovimiento = 'AM' ".
                                    ") AS tractor, ".
                                    "(SELECT tf.descripcion FROM caTarifasTbl tf WHERE idTarifa = hu.idTarifa) AS descTarifa, ".
                                    "(SELECT cg.nombre FROM caGeneralesTbl cg WHERE cg.tabla='alHistoricoUnidadesTbl' ".
                                        "AND cg.columna='claveMovimiento' AND cg.valor=hu.claveMovimiento) AS nombreClaveMov ".
                                    "FROM alhistoricounidadestbl hu ".
                                    "WHERE hu.vin = '".$_REQUEST['trap276VinHdn']."'";

        $rs = fn_ejecuta_query($sqlGetHistoricoUnidadStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descClaveMov'] = $rs['root'][$iInt]['claveMovimiento']." - ".$rs['root'][$iInt]['nombreClaveMov'];
            $rs['root'][$iInt]['localizacionCompleta'] = $rs['root'][$iInt]['localizacionUnidad'].", ".$rs['root'][$iInt]['fila']." - ".$rs['root'][$iInt]['lugar'];
        }
            
        $rs['root'] = array_slice($rs['root'], $_REQUEST['start'], $_REQUEST['start']+$_REQUEST['limit']);

        echo json_encode($rs);
    }

    function addUnidadMasivo(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $vinArr = explode('|', substr($_REQUEST['alUnidadesVinHdn'], 0, -1));
        if(in_array('', $vinArr)){
            $e[] = array('id'=>'alUnidadesVinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $distArr = explode('|', substr($_REQUEST['alUnidadesDistribuidorHdn'], 0, -1));
        if(in_array('', $distArr)){
            $e[] = array('id'=>'alUnidadesDistribuidorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $simboloArr = explode('|', substr($_REQUEST['alUnidadesSimboloUnidadHdn'], 0, -1));
        if(in_array('', $simboloArr)){
            $e[] = array('id'=>'alUnidadesSimboloUnidadHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $colorArr = explode('|', substr($_REQUEST['alUnidadesColorHdn'], 0, -1));
        if(in_array('', $colorArr)){
            $e[] = array('id'=>'alUnidadesColorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $centroDistArr = explode('|', substr($_REQUEST['alUnidadesCentroDistHdn'], 0, -1));
        if(in_array('', $centroDistArr)){
            $e[] = array('id'=>'alUnidadesCentroDistHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $tarifaArr = explode('|', substr($_REQUEST['alUnidadesTarifaHdn'], 0, -1));
        if(in_array('', $tarifaArr)){
            $e[] = array('id'=>'alUnidadesTarifaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $localizacionArr = explode('|', substr($_REQUEST['alUnidadesLocalizacionUnidadHdn'], 0, -1));
        if(in_array('', $localizacionArr)){
            $e[] = array('id'=>'alUnidadesLocalizacionUnidadHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $arrTemp = explode('|', substr($_REQUEST['alUnidadesHoldHdn'], 0,-1));
            $holdArr = array();
            for ($nInt=0; $nInt < sizeof($arrTemp); $nInt++) {
                $arrTempVin = explode('-', $arrTemp[$nInt]);
                $holdArr[$arrTempVin[0]] = $arrTempVin[1];
            }

            $vinError = array();
            $repuveArr = explode('|', substr($_REQUEST['alUnidadesFolioRepuveTxt'], 0, -1));
            $choferArr = explode('|', substr($_REQUEST['alUnidadesChoferHdn'], 0, -1));
            $observacionesArr = explode('|', substr($_REQUEST['alUnidadesObservacionesTxa'], 0, -1));

            for ($nInt=0; $nInt < sizeof($vinArr); $nInt++) {

                $sqlCheckVinStr = "SELECT vin ".
                              "FROM alHistoricoUnidadesTbl ".
                              "WHERE vin = '".$vinArr[$nInt]."' ".
                              "AND claveMovimiento != 'PR' ";

                $rs = fn_ejecuta_query($sqlCheckVinStr);

                if(sizeof($rs['root']) > 0 ) {
                    $data = addHistoricoUnidad($centroDistArr[$nInt],$vinArr[$nInt],'PR',$distArr[$nInt],$tarifaArr[$nInt],
                                            $localizacionArr[$nInt],$choferArr[$nInt],$observacionesArr[$nInt], $_SESSION['idUsuario']);
                } else {
                    $data = addUnidad($vinArr[$nInt],$distArr[$nInt],$simboloArr[$nInt],$colorArr[$nInt],$centroDistArr[$nInt],'PR',
                                        $tarifaArr[$nInt],$localizacionArr[$nInt],$repuve[$nInt],$choferArr[$nInt],$observacionesArr[$nInt]);
                }
                if ($data['success']) {
                    if (isset($holdArr[$vinArr[$nInt]])) {
                        $arrTemp = explode(',', $holdArr[$vinArr[$nInt]]);
                        for ($mInt=0; $mInt < sizeof($arrTemp); $mInt++) {                            
                            $data = addHistoricoUnidad($centroDistArr[$nInt],$vinArr[$nInt],$arrTemp[$mInt], $distArr[$nInt],$tarifaArr[$nInt],$localizacionArr[$nInt],'','',$_SESSION['usuarioGlobal'],6);
                        }
                        $data = quitarHoldUnitario($vinArr[$nInt]);
                    }
                }
                if ($data['success'] == false) {
                    array_push($vinError, $vinArr[$nInt]);
                } else {
                    $success = true;
                }
            }

            if (sizeof($vinError) > 0) {
                if ($success == true) {
                    $a['successMessage'] = "Unidades Insertadas Correctamente. <br>Error al insertar los siguientes VIN: ";
                    foreach ($vinError as $vin) {
                        $a['successMessage'] .= "<br>".$vin.",";
                    }
                } else if ($success == false){
                    $a['errorMessage'] = "Error al insertar los siguientes VIN: ";
                    foreach ($vinError as $vin) {
                        $a['errorMessage'] .= "<br>".$vin.",";
                    }
                }   
            } else {
                $a['successMessage'] = getUnidadesMasivoSuccessMsg();
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function addUnidad($vin,$distribuidor,$simbolo,$color,$centroDist,$claveMovimiento,$tarifa,$localizacion,$repuve,$chofer,$observaciones){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($vin == ""){
            $e[] = array('id'=>'%VinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($distribuidor == ""){
            $e[] = array('id'=>'%DistribuidorTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($simbolo == ""){
            $e[] = array('id'=>'%SimboloUnidadHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($color == ""){
            $e[] = array('id'=>'%ColorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($centroDist == ""){
            $e[] = array('id'=>'%CentroDistHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($claveMovimiento == ""){
            $e[] = array('id'=>'%ClaveMovimientoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($tarifa == ""){
            $e[] = array('id'=>'%TarifaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }       

        if($a['success'] == true){
            $sqlAddUnidadStr = "INSERT INTO alUnidadesTbl ".
                               "VALUES(".
                               "'".$vin."',".
                               "'".substr($vin, -8)."',".
                               "'".$distribuidor."',".
                               "'".$simbolo."',".
                               "'".$color."',".
                               replaceEmptyNull("'".$repuve."'").")";

            $rs = fn_ejecuta_query($sqlAddUnidadStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddUnidadStr;
                $a['successMessage'] = getUnidadesSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddUnidadStr;
                //echo $a['errorMessage'];
            }   
        }

        if ($a['success'] == true) {
            $sqlAddHistoricoUnidadStr = "INSERT INTO alHistoricoUnidadesTbl ".
                                        "(centroDistribucion, vin, fechaEvento, claveMovimiento, distribuidor, idTarifa, ".
                                        "localizacionUnidad, claveChofer, observaciones, usuario, ip) ".
                                        "VALUES(".
                                        "'".$centroDist."',".
                                        "'".$vin."',".
                                        "'".date("Y-m-d h:m:s")."',".
                                        "'".$claveMovimiento."',".
                                        "'".$distribuidor."',".
                                        "'".$tarifa."',".
                                        replaceEmptyNull("'".$localizacion."'").",".
                                        replaceEmptyNull("'".$chofer."'").",".
                                        "'".$observaciones."',".
                                        "'".$_SESSION['usuario']."',".
                                        "'".$_SERVER['REMOTE_ADDR']."')";
            
            $rs = fn_ejecuta_query($sqlAddHistoricoUnidadStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddUnidadStr." ".$sqlAddHistoricoUnidadStr;
                $a['successMessage'] = getUnidadesSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddHistoricoUnidadStr;
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        return $a;
    }

    function updUnidad(){
        $a = array();
        $e = array();
        $a['success'] = true;
        $updInt = 0;

        if($_REQUEST['alUnidadesVinHdn'] == ""){
            $e[] = array('id'=>'alUnidadesVinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){

            $sqlUpdUnidadStr = "UPDATE alUnidadesTbl SET";

            if (isset($_REQUEST['alUnidadesDistribuidorTxt']) && $_REQUEST['alUnidadesDistribuidorTxt'] != '') {
                $sqlUpdUnidadStr .= " distribuidor ='".$_REQUEST['alUnidadesDistribuidorTxt']."'";
                $updInt++;
            }
            if (isset($_REQUEST['alUnidadesSimboloUnidadHdn']) && $_REQUEST['alUnidadesSimboloUnidadHdn'] != '') {
                if ($updInt > 0) {
                    $sqlUpdUnidadStr .= ",";
                }

                $sqlUpdUnidadStr .= " simboloUnidad ='".$_REQUEST['alUnidadesSimboloUnidadHdn']."'";
                $updInt++;
            }
            if (isset($_REQUEST['alUnidadesColorHdn']) && $_REQUEST['alUnidadesColorHdn'] != '') {
                if ($updInt > 0) {
                    $sqlUpdUnidadStr .= ",";
                }

                $sqlUpdUnidadStr .= " color ='".$_REQUEST['alUnidadesColorHdn']."'";
                $updInt++;
            }
            if (isset($_REQUEST['alUnidadesFolioRepuveTxt']) && $_REQUEST['alUnidadesFolioRepuveTxt'] != '') {
                if ($updInt > 0) {
                    $sqlUpdUnidadStr .= ",";
                }

                $sqlUpdUnidadStr .= " folioRepuve ='".$_REQUEST['alUnidadesFolioRepuveTxt']."'";
                $updInt++;
            }

            if ($updInt > 0) {
                $sqlUpdUnidadStr .= " WHERE vin='".$_REQUEST['alUnidadesVinHdn']."'";

                $rs = fn_ejecuta_query($sqlUpdUnidadStr);

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                    $a['sql'] = $sqlUpdUnidadStr;
                    $a['successMessage'] = getUnidadesUpdMsg();
                    $a['id'] = $_REQUEST['alUnidadesVinHdn'];
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdUnidadStr;
                }

            } else {
                $sqlUpdUnidadStr = "";
                $a['successMessage'] = getUnidadesNotUpdMsg();
                $a['id'] = $_REQUEST['alUnidadesVinHdn'];
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        json_encode($a);
    }

    function addHistoricoUnidad($RQcentroDist,$RQvin,$RQclaveMovimiento,$RQdist,$RQtarifa,$RQlocalizacion,$RQchofer,$RQobservaciones,$RQusuario,$RQtime=0){
    	$a = array();
        $e = array();
        $a['success'] = true;

        if($RQcentroDist == "") {
                $e[] = array('id'=>'CentroDistHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        if($RQvin == "") {
                $e[] = array('id'=>'VinHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        if($RQclaveMovimiento == "") {
                $e[] = array('id'=>'ClaveMovimientoHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        if($RQtarifa == "") {
                $e[] = array('id'=>'TarifaTxt','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        if($RQdist == "") {
                $e[] = array('id'=>'DistribuidorTxt','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        if($RQlocalizacion == "") {
                $e[] = array('id'=>'LocalizacionUnidadHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }

        if ($a['success'] == true) {
        	$sqlAddCambioHistoricoStr = "INSERT INTO alHistoricoUnidadesTbl ".
                						"(centroDistribucion, vin, fechaEvento, claveMovimiento, distribuidor, idTarifa, localizacionUnidad, ".
                						"claveChofer, observaciones, usuario, ip) ".
                						"VALUES(".
                						"'".$RQcentroDist."',".
                						"'".$RQvin."',".
                						"'".date("Y-m-d H:i:s", time()+$RQtime)."',".
                						"'".$RQclaveMovimiento."',".
                						"'".$RQdist."',".
                						"'".$RQtarifa."',".
                						"'".$RQlocalizacion."',".
                						replaceEmptyNull("'".$RQchofer."'").",".
                						"'".$RQobservaciones."',".
                						"'".$_SESSION['usuario']."',".
                						"'".$_SERVER['REMOTE_ADDR']."')";

			$rs = fn_ejecuta_query($sqlAddCambioHistoricoStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddCambioHistoricoStr;
                $a['successMessage'] = getHistoricoUnidad();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddCambioHistoricoStr;
            }
        }

        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        return $a;
    }

    function bloquearUnidad(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['alUnidadesVinHdn'] == ""){
            $e[] = array('id'=>'alUnidadesVinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
         if($_REQUEST['alUnidadesAvanzadaHdn'] == ""){
            $e[] = array('id'=>'alUnidadesAvanzadaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlBloquearUnidadStr = "INSERT INTO alUnidadesTmp ".
                                   "VALUES(".
                                   "'".$_REQUEST['alUnidadesVinHdn']."',".
                                   "'".$_REQUEST['alUnidadesAvanzadaHdn']."',".
                                   "'".$_SESSION['modulo']."',".
                                   "'".$_SESSION['usuario']."',".
                                   "'".$_SERVER['REMOTE_ADDR']."',".
                                   "(SELECT CURRENT_TIMESTAMP))";

            $rs = fn_ejecuta_query($sqlBloquearUnidadStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlBloquearUnidadStr;
                $a['successMessage'] = getUnidadesBloquearMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlBloquearUnidadStr;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function liberarUnidad(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['alUnidadesVinHdn'] == ""){
            $e[] = array('id'=>'alUnidadesVinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = true;
        }
        if ($a['success'] == true) {
            $sqlLiberarUnidadStr = "DELETE FROM alUnidadesTmp ".
                                   "WHERE vin='".$_REQUEST['alUnidadesVinHdn']."' ";

            $rs = fn_ejecuta_query($sqlLiberarUnidadStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlLiberarUnidadStr;
                $a['successMessage'] = getUnidadesLiberarMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlLiberarUnidadStr;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function getDetenidas(){
        $lsWhereStr = "WHERE u.vin = h.vin ".
                      "AND h.fechaEvento=(".
                      "SELECT MAX(h1.fechaEvento) ".
                      "FROM alHistoricoUnidadesTbl h1 ". 
                      "WHERE h1.vin = u.vin) ".
                      "AND ud.vin = u.vin ".
                      "AND ud.numeroMovimiento = (SELECT MAX(ud2.numeroMovimiento) FROM alUnidadesDetenidasTbl ud2 ".
                        "WHERE ud2.vin = u.vin) ".
                      "AND ud.claveMovimiento = 'UD' ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesDistribuidorHdn'], "h.distribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesSimboloUnidadHdn'], "u.simboloUnidad", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesVinHdn'], "u.vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesAvanzadaHdn'], "u.avanzada", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetDetenidasStr = "SELECT u.*, h.*, ud.claveMovimiento AS claveDetenida,".
                              "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.valor=h.claveMovimiento ".
                              "AND g.tabla='alHistoricoUnidadesTbl' AND g.columna='claveMovimiento') AS nombreEstatus, ".
                              "(SELECT d.descripcionCentro FROM caDistribuidoresCentrosTbl d ".
                              "WHERE d.distribuidorCentro=u.distribuidor) AS nombreDistribuidor, ".
                              "(SELECT c.descripcion FROM cacolorunidadestbl c, caSimbolosUnidadesTbl su WHERE c.color=u.color ".
                                "AND c.marca=su.marca AND su.simboloUnidad=u.simboloUnidad) AS nombreColor ".
                              "FROM alUnidadesTbl u, alHistoricoUnidadesTbl h, alUnidadesDetenidasTbl ud ".
                              $lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetDetenidasStr);
            
        echo json_encode($rs);
    }

    function getNoDetenidas(){
        $lsWhereStr = "WHERE u.vin = h.vin ".
                      "AND h.fechaEvento=(".
                      "SELECT MAX(h1.fechaEvento) ".
                      "FROM alHistoricoUnidadesTbl h1 ". 
                      "WHERE h1.vin = u.vin) ".
                      "AND ((SELECT ud.claveMovimiento FROM alUnidadesDetenidasTbl ud ".
                        "WHERE ud.numeroMovimiento = (SELECT MAX(ud2.numeroMovimiento) FROM alUnidadesDetenidasTbl ud2 WHERE ud2.vin = u.vin) AND ud.vin = u.vin) != 'UD' ".
                        "OR (SELECT ud.claveMovimiento FROM alUnidadesDetenidasTbl ud ".
                        "WHERE ud.numeroMovimiento = (SELECT MAX(ud2.numeroMovimiento) FROM alUnidadesDetenidasTbl ud2 WHERE ud2.vin = u.vin) AND ud.vin = u.vin) IS NULL) ".
                      "AND h.claveMovimiento != 'UD'";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesDistribuidorHdn'], "h.distribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesSimboloUnidadHdn'], "u.simboloUnidad", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesVinHdn'], "u.vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alUnidadesAvanzadaHdn'], "u.avanzada", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetNoDetenidasStr = "SELECT u.*, h.*,".
                              "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.valor=h.claveMovimiento ".
                              "AND g.tabla='alHistoricoUnidadesTbl' AND g.columna='claveMovimiento') AS nombreEstatus, ".
                              "(SELECT d.descripcionCentro FROM caDistribuidoresCentrosTbl d ".
                              "WHERE d.distribuidorCentro=u.distribuidor) AS nombreDistribuidor, ".
                              "(SELECT c.descripcion FROM cacolorunidadestbl c, caSimbolosUnidadesTbl su WHERE c.color=u.color ".
                                "AND c.marca=su.marca AND su.simboloUnidad=u.simboloUnidad) AS nombreColor ".
                              "FROM alUnidadesTbl u, alHistoricoUnidadesTbl h ".
                              $lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetNoDetenidasStr);
            
        echo json_encode($rs);
    }

    //Se usaba antes en el proceso de Detencion
    function detencionUnidadesANTERIOR(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['alUnidadesDistribuidorHdn'] == "" && $_REQUEST['alUnidadesSimboloUnidadHdn'] == "" && $_REQUEST['alUnidadesVinHdn'] == ""){
            $e[] = array('id'=>'alUnidadesDistribuidorHdn || alUnidadesSimboloUnidadHdn || alUnidadesVinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        $lsWhereStr = "WHERE u.vin = h.vin ".
                      "AND h.fechaEvento=(".
                      "SELECT MAX(h1.fechaEvento) ".
                      "FROM alHistoricoUnidadesTbl h1 ". 
                      "WHERE h1.vin = u.vin) ".
                      "AND ((SELECT ud2.claveMovimiento FROM alUnidadesDetenidasTbl ud2 WHERE ud2.vin = u.vin ".
                        "AND ud2.numeroMovimiento = (SELECT MAX(numeroMovimiento) FROM alUnidadesDetenidasTbl WHERE vin = u.vin)) = 'UL' ".
                        "OR (SELECT ud2.claveMovimiento FROM alUnidadesDetenidasTbl ud2 WHERE ud2.vin = u.vin ) IS NULL) ";

        if($_REQUEST['alUnidadesDistribuidorHdn'] != ""){
            $lsWhereStr .= "AND h.distribuidor ='".$_REQUEST['alUnidadesDistribuidorHdn']."'";
        } elseif ($_REQUEST['alUnidadesSimboloUnidadHdn'] != "") {
            $lsWhereStr .= "AND u.simboloUnidad ='".$_REQUEST['alUnidadesSimboloUnidadHdn']."'";
        } elseif ($_REQUEST['alUnidadesVinHdn'] != "") {
            $lsWhereStr .= "AND u.vin ='".$_REQUEST['alUnidadesVinHdn']."'";
        }

        if ($a['success'] == true) {
            $sqlGetUnidadesDistStr = "SELECT u.*, h.*, ".
                                     "(SELECT MAX(ud.numeroMovimiento) FROM alUnidadesDetenidasTbl ud WHERE ud.vin = u.vin) AS numeroMovimiento, ".
                                     "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.valor=h.claveMovimiento ".
                                     "AND g.tabla='alHistoricoUnidadesTbl' AND g.columna='claveMovimiento') AS nombreEstatus, ".
                                     "(SELECT d.descripcionCentro FROM caDistribuidoresCentrosTbl d ".
                                     "WHERE d.distribuidorCentro=u.distribuidor) AS nombreDistribuidor ".
                                     "FROM alUnidadesTbl u, alHistoricoUnidadesTbl h ".
                                     $lsWhereStr;

            $rs = fn_ejecuta_query($sqlGetUnidadesDistStr);

            for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
                $unidades[$nInt] = $rs['root'][$nInt];
            }

            for ($nInt=0; $nInt < sizeof($unidades); $nInt++) {
                if ($unidades[$nInt]['numeroMovimiento'] != "" && $unidades[$nInt]['numeroMovimiento'] > 0) {
                    $unidades[$nInt]['numeroMovimiento'] += 1;
                } else {
                    $unidades[$nInt]['numeroMovimiento'] = 1;
                }
                
                $sqlDetenerUnidadStr = "INSERT INTO alUnidadesDetenidasTbl (numeroMovimiento, vin, claveMovimiento, centroDetiene, fechaInicial) ".
                                       "VALUES (".
                                            $unidades[$nInt]['numeroMovimiento'].", ".
                                            "'".$unidades[$nInt]['vin']."',".
                                            "'UD',".
                                            "'".$_SESSION['centroDistGlobal']."',".
                                            "'".date("Y-m-d H:i:s", time()+$nInt*2)."')";

                $rs = fn_ejecuta_query($sqlDetenerUnidadStr);

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $a['sql'] = $sqlDetenerUnidadStr;
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] .   "<br>" . $sqlDetenerUnidadStr;
                }
            }
        }

        if ($a['success'] == true && $_REQUEST['alUnidadesDistribuidorHdn'] != "" && $_REQUEST['alUnidadesSimboloUnidadHdn'] == "") {
            $sqlUpdEstatusDistStr = "UPDATE caDistribuidoresCentrosTbl ".
                                    "SET estatus = '2' ".
                                    "WHERE distribuidorCentro = '".$_REQUEST['alUnidadesDistribuidorHdn']."'";

            $rs = fn_ejecuta_query($sqlUpdEstatusDistStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdEstatusDistStr;
            } else {
                $a['success'] = false;
                $a['sql'] = $sqlUpdEstatusDistStr;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdEstatusDistStr;
            }
        }

        if ($a['success'] == true) {
            if($_REQUEST['alUnidadesDistribuidorHdn'] != "" && $_REQUEST['alUnidadesSimboloUnidadHdn'] == ""){
                $a['successMessage'] = getUnidadesDetencionDistribuidor($_REQUEST['alUnidadesDistribuidorHdn']);
            } elseif ($_REQUEST['alUnidadesDistribuidorHdn'] != "" && $_REQUEST['alUnidadesSimboloUnidadHdn'] != "") {
                $a['successMessage'] = getUnidadesDetencionSimbolo($_REQUEST['alUnidadesSimboloUnidadHdn']);
            } elseif ($_REQUEST['alUnidadesVinHdn'] != "") {
                $a['successMessage'] = getUnidadesDetencionVin($_REQUEST['alUnidadesVinHdn']);
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function detencionUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $distArr = explode('|', substr($_REQUEST['alUnidadesDistribuidorHdn'], 0, -1));
        if(in_array('', $distArr)) {
            $e[] = array('id'=>'alUnidadesDistribuidorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $vinArr = explode('|', substr($_REQUEST['alUnidadesVinHdn'], 0, -1));
        if(in_array('', $vinArr)) {
            $e[] = array('id'=>'alUnidadesVinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $simboloArr = explode('|', substr($_REQUEST['alUnidadesSimboloUnidadHdn'], 0, -1));
        if(in_array('', $centroDistArr)) {
            $e[] = array('id'=>'alUnidadesSimboloUnidadHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $centroDistArr = explode('|', substr($_REQUEST['alUnidadesCentroDistHdn'], 0, -1));
        if(in_array('', $centroDistArr)) {
            $e[] = array('id'=>'alUnidadesCentroDistHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $tarifaArr = explode('|', substr($_REQUEST['alUnidadesTarifaHdn'], 0, -1));
        if(in_array('', $tarifaArr)) {
            $e[] = array('id'=>'alUnidadesTarifaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $localizacionArr = explode('|', substr($_REQUEST['alUnidadesLocalizacionUnidadHdn'], 0, -1));
        if(in_array('', $localizacionArr)) {
            $e[] = array('id'=>'alUnidadesLocalizacionUnidadHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {

            for ($nInt=0; $nInt < sizeof($vinArr); $nInt++) { 
                //Revisa si el distribuidor y el simbolo de la unidad es de Mercedes
                $sqlCheckDistMercedesStr = "SELECT marca AS marcaSimbolo, ".
                                           "(SELECT marca FROM caMarcasDistribuidoresCentrosTbl ".
                                                "WHERE distribuidor = '".$distArr[$nInt]."' AND marca = 'MB') AS marcaDist ".
                                           "FROM caSimbolosUnidadesTbl ".
                                           "WHERE simboloUnidad = '".$simboloArr[$nInt]."'";

                $rs = fn_ejecuta_query($sqlCheckDistMercedesStr);
                
                //Si es de Mercedes se inserta en el historial
                if ($rs['root'][0]['marcaSimbolo'] == 'MB' && $rs['root'][0]['marcaDist'] == 'MB') {
                    //echo "OK<br>";
                    $data = addHistoricoUnidad($centroDistArr[$nInt],$vinArr[$nInt],'UD', $distArr[$nInt],$tarifaArr[$nInt],$localizacionArr[$nInt],'','',$_SESSION['usuarioGlobal']);
                    if ($data['success'] == false) {
                        $a['success'] = false;
                        $a['errorMessage'] = $data['errorMessage'];
                    }
                } else {
                    //Si no lo es se inserta su movimiento de detencion en alUnidadesDetenidasTbl
                    $sqlDetenerUnidadStr = "INSERT INTO alUnidadesDetenidasTbl (vin, claveMovimiento, centroDetiene, fechaInicial, numeroMovimiento) ".
                                           "SELECT '".$vinArr[$nInt]."','UD', '".$centroDistArr[$nInt]."','".date("Y-m-d H:i:s")."',MAX(numeroMovimiento)+1 ".
                                           "FROM alUnidadesDetenidasTbl WHERE vin='".$vinArr[$nInt]."' AND claveMovimiento='UL' ";
                    
                    //echo $rs['root'][0]['marcaSimbolo']." - ".$rs['root'][0]['marcaDist']."<br>";
                    $rs = fn_ejecuta_query($sqlDetenerUnidadStr);

                    if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                        
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlLiberarDetencionUnidadStr;
                    }
                }
            }
        }
        if ($a['success'] == true) {
            $a['successMessage'] = getUnidadesDetenidasSuccessMsg();
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function liberarDetencionUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['alUnidadesDistribuidorHdn'] == "" && $_REQUEST['alUnidadesSimboloUnidadHdn'] == "" && $_REQUEST['alUnidadesVinHdn'] == ""){
            $e[] = array('id'=>'alUnidadesDistribuidorHdn || alUnidadesSimboloUnidadHdn || alUnidadesVinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        $lsWhereStr = "WHERE u.vin = h.vin ".
                      "AND h.fechaEvento=(".
                      "SELECT MAX(h1.fechaEvento) ".
                      "FROM alHistoricoUnidadesTbl h1 ". 
                      "WHERE h1.vin = u.vin) ".
                      "AND (SELECT ud2.claveMovimiento FROM alUnidadesDetenidasTbl ud2 WHERE ud2.vin = u.vin ".
                        "AND ud2.numeroMovimiento = (SELECT MAX(numeroMovimiento) FROM alUnidadesDetenidasTbl WHERE vin = u.vin)) = 'UD' ";

        if($_REQUEST['alUnidadesDistribuidorHdn'] != ""){
            $lsWhereStr .= "AND h.distribuidor ='".$_REQUEST['alUnidadesDistribuidorHdn']."'";
        } elseif ($_REQUEST['alUnidadesSimboloUnidadHdn'] != "") {
            $lsWhereStr .= "AND u.simboloUnidad ='".$_REQUEST['alUnidadesSimboloUnidadHdn']."'";
        } elseif ($_REQUEST['alUnidadesVinHdn'] != "") {
            $lsWhereStr .= "AND u.vin ='".$_REQUEST['alUnidadesVinHdn']."'";
        }

        if ($a['success'] == true) {
            $sqlGetUnidadesDistStr = "SELECT u.*, h.*, ".
                                     "(SELECT MAX(ud.numeroMovimiento) FROM alUnidadesDetenidasTbl ud WHERE ud.vin = u.vin) AS numeroMovimiento, ".
                                     "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.valor=h.claveMovimiento ".
                                     "AND g.tabla='alHistoricoUnidadesTbl' AND g.columna='claveMovimiento') AS nombreEstatus, ".
                                     "(SELECT d.descripcionCentro FROM caDistribuidoresCentrosTbl d ".
                                     "WHERE d.distribuidorCentro=u.distribuidor) AS nombreDistribuidor ".
                                     "FROM alUnidadesTbl u, alHistoricoUnidadesTbl h ".
                                     $lsWhereStr;

            $rs = fn_ejecuta_query($sqlGetUnidadesDistStr);
            
            for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
                $unidades[$nInt] = $rs['root'][$nInt];
            }
            
            for ($nInt=0; $nInt < sizeof($unidades); $nInt++) {
                $sqlLiberarDetencionUnidadStr = "UPDATE alUnidadesDetenidasTbl ".
                                                "SET centroLibera ='".$_SESSION['centroDistGlobal']."',".
                                                "claveMovimiento = 'UL',".
                                                "fechaFinal = '".date("Y-m-d H:i:s", time()+$nInt*2)."' ".
                                                "WHERE vin='".$unidades[$nInt]['vin']."' ".
                                                "AND numeroMovimiento = ".$unidades[$nInt]['numeroMovimiento'];

                $rs = fn_ejecuta_query($sqlLiberarDetencionUnidadStr);

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $a['sql'] = $sqlLiberarDetencionUnidadStr;
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlLiberarDetencionUnidadStr;
                }
            }
        }

        if ($a['success'] == true && $_REQUEST['alUnidadesDistribuidorHdn'] != "" && $_REQUEST['alUnidadesSimboloUnidadHdn'] == "") {
            $sqlUpdEstatusDistStr = "UPDATE caDistribuidoresCentrosTbl ".
                                    "SET estatus = '1' ".
                                    "WHERE distribuidorCentro = '".$_REQUEST['alUnidadesDistribuidorHdn']."'";

            $rs = fn_ejecuta_query($sqlUpdEstatusDistStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdEstatusDistStr;
            } else {
                $a['success'] = false;
                $a['sql'] = $sqlUpdEstatusDistStr;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdEstatusDistStr;
            }
        }

        if ($a['success'] == true) {
            if($_REQUEST['alUnidadesDistribuidorHdn'] != "" && $_REQUEST['alUnidadesSimboloUnidadHdn'] == ""){
                $a['successMessage'] = getUnidadesLiberarDetencionDistribuidor($_REQUEST['alUnidadesDistribuidorHdn']);
            } elseif ($_REQUEST['alUnidadesSimboloUnidadHdn'] != "" && $_REQUEST['alUnidadesSimboloUnidadHdn'] != "") {
                $a['successMessage'] = getUnidadesLiberarDetencionSimbolo($_REQUEST['alUnidadesSimboloUnidadHdn']);
            } elseif ($_REQUEST['alUnidadesVinHdn'] != "") {
                $a['successMessage'] = getUnidadesLiberarDetencionVin($_REQUEST['alUnidadesVinHdn']);
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function getHoldeadas(){
        $sqlGetHoldeadasStr = "SELECT * FROM alHoldsUnidadesTbl ";

        $rs = fn_ejecuta_query($sqlGetHoldeadasStr);
            
        echo json_encode($rs);
    }

    function holdUnidades($listaVin, $listaClavesMovimiento){
        $a['success'] = true;
        $a['ok'] = 0;
        $a['fail'] = 0;

        $vinArr = explode("|", substr($listaVin, 0, -1));
        if (in_array('', $vinArr)) {
           $a['success'] = false;
           $e[] = array('id'=>'Vin','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
        }
        $claveArr = explode("|", substr($listaClavesMovimiento, 0, -1));
        if (in_array('', $claveArr)) {
           $a['success'] = false;
        }


        for ($i=0; $i < sizeof($vinArr); $i++) { 
            if ($i != 0) {
                $sqlHoldUnidadesStr .= ",";
            }

            $sqlHoldUnidadesStr = "INSERT INTO alHoldsUnidadesTbl VALUES('".$vinArr[$i]."','".$claveArr[$i]."')";

            $rs = fn_ejecuta_query($sqlHoldUnidadesStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $a['ok'] += 1;
            } else {
                   $a['fail'] += 1;
            }
        }

        if ($a['fail'] > 0) {
            $a['success'] = false;
            $a['errorMessage'] = getHoldUnidadesMessage($a['ok'], $a['fail']);
        } else {
             $a['successMessage'] = getHoldUnidadesMessage($a['ok'], $a['fail']);
        }

        $a['successTitle'] = getMsgTitulo();
        return $a;
    }

    function quitarHoldUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $vinArr = explode("|", substr($_REQUEST['alUnidadesVinHdn'], 0, -1));
        if (in_array('', $vinArr)) {
            $e[] = array('id'=>'alUnidadesVinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        $sqlquitarHoldUnidades = "DELETE FROM alHoldsUnidadesTbl ";

        if ($a['success'] == true) {
            for ($i=0; $i < sizeof($vinArr); $i++) { 
                if ($i == 0) {
                    $sqlquitarHoldUnidades .= "WHERE vin = '".$vinArr[$i]."' ";
                } else {
                    $sqlquitarHoldUnidades .= "OR vin = '".$vinArr[$i]."' ";
                }
            }

            $rs = fn_ejecuta_query($sqlquitarHoldUnidades);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlquitarHoldUnidades;
                $a['successMessage'] = getUnidadesQuitarHold(sizeof($vinArr));
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlquitarHoldUnidades;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function quitarHoldUnitario($vin){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($vin == "") {
            $e[] = array('id'=>'alUnidadesVinHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlquitarHoldUnidades = "DELETE FROM alHoldsUnidadesTbl WHERE vin = '".$vin."' ".
                                     "AND claveHold != 'CE'";

            $rs = fn_ejecuta_query($sqlquitarHoldUnidades);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlquitarHoldUnidades;
                $a['successMessage'] = getUnidadesQuitarHold(sizeof($vinArr));
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlquitarHoldUnidades;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        return $a;
    }

    function insertarEntradaPatio(){
        $a = array();
        $e = array();
        $a['success'] = true;

       if($_REQUEST['alUnidadesCentroDistHdn'] == ''){
                $e[] = array('id'=>'alUnidadesCentroDistHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        if($_REQUEST['alUnidadesFlotillaHdn'] == ''){
                $e[] = array('id'=>'alUnidadesFlotillaHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        $vinArr = explode('|', substr($_REQUEST['alUnidadesVinHdn'], 0,-1));
        if(in_array('', $vinArr)){
                $e[] = array('id'=>'alUnidadesVinHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        $tarifaArr = explode('|', substr($_REQUEST['alUnidadesTarifaTxt'], 0,-1));
        if(in_array('', $tarifaArr)){
                $e[] = array('id'=>'alUnidadesTarifaTxt','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        $distArr = explode('|', substr($_REQUEST['alUnidadesDistribuidorTxt'], 0,-1));
        if(in_array('', $distArr)){
                $e[] = array('id'=>'alUnidadesDistribuidorTxt','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        $localizacionArr = explode('|', substr($_REQUEST['alUnidadesLocalizacionUnidadHdn'], 0,-1));
        if(in_array('', $localizacionArr)){
                $e[] = array('id'=>'alUnidadesLocalizacionUnidadHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        $simboloArr = explode('|', substr($_REQUEST['alUnidadesSimboloUnidadHdn'], 0,-1));
        if(in_array('', $simboloArr)){
                $e[] = array('id'=>'alUnidadesSimboloUnidadHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
        }
        
        if ($a['success'] == true) {
            $arrTemp = explode('|', substr($_REQUEST['alUnidadesHoldHdn'], 0,-1));
            $holdArr = array();
            for ($nInt=0; $nInt < sizeof($arrTemp); $nInt++) {
                $arrTempVin = explode('-', $arrTemp[$nInt]);
                $holdArr[$arrTempVin[0]] = $arrTempVin[1];
            }

            for ($nInt=0; $nInt < sizeof($vinArr); $nInt++) {   
                $data = addHistoricoUnidad($_REQUEST['alUnidadesCentroDistHdn'],$vinArr[$nInt],'EP', $distArr[$nInt],$tarifaArr[$nInt],$localizacionArr[$nInt],'','',$_SESSION['usuarioGlobal']);

                if ($data['success'] == true) {
                    $data = addHistoricoUnidad($_REQUEST['alUnidadesCentroDistHdn'],$vinArr[$nInt],'FA', $distArr[$nInt],$tarifaArr[$nInt],$localizacionArr[$nInt],'','',$_SESSION['usuarioGlobal'],2);

                    if($data['success'] == true){
                        if ($_REQUEST['alUnidadesFlotillaHdn'] == 1) {
                            $data = addHistoricoUnidad($_REQUEST['alUnidadesCentroDistHdn'],$vinArr[$nInt],'FL', $distArr[$nInt],$tarifaArr[$nInt],$localizacionArr[$nInt],'','',$_SESSION['usuarioGlobal'],2);                        
                        }

                        if ($data['success'] == true) {
                            $sqlEstaHomologado = "SELECT homologacion AS homologacionSimbolo, ".
                                                 "(SELECT homologacion FROM cadistribuidorescentrostbl ".
                                                    "WHERE distribuidorCentro = '".$_REQUEST['alUnidadesCentroDistHdn']."')  AS homologacionCentro ".
                                                 "FROM caSimbolosUnidadesTbl ".
                                                 "WHERE simboloUnidad = '".$simboloArr[$nInt]."'";

                            $rs = fn_ejecuta_query($sqlEstaHomologado);
                            
                            if($rs['root'][0]['homologacionSimbolo'] == '1' && $rs['root'][0]['homologacionCentro'] == '1'){
                                $data = addHistoricoUnidad($_REQUEST['alUnidadesCentroDistHdn'],$vinArr[$nInt],'HO', $distArr[$nInt],$tarifaArr[$nInt],$localizacionArr[$nInt],'','',$_SESSION['usuarioGlobal'],4);            
                            }

                            if ($data['success'] == true) {
                                if (isset($holdArr[$vinArr[$nInt]])) {
                                    $arrTemp = explode(',', $holdArr[$vinArr[$nInt]]);
                                    for ($mInt=0; $mInt < sizeof($arrTemp); $mInt++) {
                                        $data = addHistoricoUnidad($_REQUEST['alUnidadesCentroDistHdn'],$vinArr[$nInt],$arrTemp[$mInt], $distArr[$nInt],$tarifaArr[$nInt],$localizacionArr[$nInt],'','',$_SESSION['usuarioGlobal'],6);
                                    }
                                    $data = quitarHoldUnitario($vinArr[$nInt]);
                                } else {
                                    $sqlCheckHoldeoStr = "SELECT hi.idHistorico, hi.vin, hi.fechaEvento, hi.claveMovimiento ".
                                                         "FROM alhistoricounidadestbl hi ".
                                                         "WHERE hi.vin ='".$vinArr[$nInt]."' ".
                                                         "AND hi.claveMovimiento IN ( ".
                                                            "SELECT cg.valor FROM cageneralestbl cg ".
                                                            "WHERE cg.tabla='alHoldsUnidadesTbl' AND cg.columna='CDTOL' ) ".
                                                         "AND hi.claveMovimiento IN ( ".
                                                            "SELECT valor FROM cageneralestbl ".
                                                            "WHERE tabla = 'alHoldsUnidadesTbl' ".
                                                            "AND columna = 'CDTOL' AND valor = hi.claveMovimiento ".
                                                            "AND estatus NOT IN (SELECT hi2.claveMovimiento FROM alhistoricounidadestbl hi2 ".
                                                                "WHERE hi2.vin = '".$vinArr[$nInt]."') )";
                                    
                                    $rs = fn_ejecuta_query($sqlCheckHoldeoStr);

                                    if(sizeof($rs['root']) == 0){
                                        $data = addHistoricoUnidad($_REQUEST['alUnidadesCentroDistHdn'],$vinArr[$nInt],'LA', $distArr[$nInt],$tarifaArr[$nInt],$localizacionArr[$nInt],'','',$_SESSION['usuarioGlobal'],8);
                                    }
                                }
                                if ($data['success']) {
                                    $a['success'] = true;
                                    $a['successMessage'] = getInsertarEntradaPatio();
                                } else {
                                    $a['success'] = false;
                                    $a['errorMessage'] = $data['errorMessage'];
                                }
                            } else {
                                $a['success'] = false;
                                $a['errorMessage'] = $data['errorMessage'];
                            }
                        } else {
                            $a['success'] = false;
                            $a['errorMessage'] = $data['errorMessage'];
                        }
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $data['errorMessage'];
                    }
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $data['errorMessage'];
                }
            }
        }
        
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        
        echo json_encode($a);
    }
	
	function getHistoricoUnidades(){
    	$lsWhereStr = "WHERE u.vin = h.vin ".
                      "AND h.idTarifa = t.idTarifa ".
                      "AND sm.simboloUnidad = u.simboloUnidad ".
                      "AND u.distribuidor = dc.distribuidorCentro";

	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['alHistoricoUnidadesAvanzadaHdn'], "u.avanzada", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alHistoricoUnidadesVinHdn'], "u.vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['alHistoricoUnidadesCentroDistHdn'], "h.centroDistribucion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['alHistoricoUnidadesEstatusHdn'], "h.claveMovimiento", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alHistoricoUnidadesMarcaHdn'], "sm.marca", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

		$sqlGetUnidadesStr = "SELECT u.*, h.*, t.descripcion AS nombreTarifa, dc.descripcionCentro AS nombreDistribuidor, ".
                             "dc.estatus AS estatusDistribuidor, dc.tipoDistribuidor, sm.descripcion AS nombreSimbolo, ".
                             "(SELECT lp.fila FROM alLocalizacionPatiosTbl lp WHERE lp.vin = u.vin) AS fila,".
                             "(SELECT lp2.lugar FROM alLocalizacionPatiosTbl lp2 WHERE lp2.vin = u.vin) AS lugar,".
                             "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.valor=h.claveMovimiento ".
                                "AND g.tabla='alHistoricoUnidadesTbl' AND g.columna='claveMovimiento') AS nombreEstatus, ".
                             "(SELECT c.descripcion FROM caColorUnidadesTbl c WHERE u.color = c.color ".
                                "AND sm.marca = c.marca) AS nombreColor, ".
                             "(SELECT g2.nombre FROM caGeneralesTbl g2 WHERE g2.tabla = 'caDistribuidoresCentrosTbl' ".
                                "AND g2.columna='tipoDistribuidor' AND g2.valor = dc.tipoDistribuidor) AS nombreTipoDistribuidor ".
						 	 "FROM alUnidadesTbl u, alHistoricoUnidadesTbl h, caTarifasTbl t, caSimbolosUnidadesTbl sm, ".
						 	 "caDistribuidoresCentrosTbl dc ".$lsWhereStr;
        
		$rs = fn_ejecuta_query($sqlGetUnidadesStr);
        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descDist'] = $rs['root'][$fechaInicialt]['distribuidor']." - ".$rs['root'][$iInt]['nombreDistribuidor'];
            $rs['root'][$iInt]['descTarifa'] = $rs['root'][$iInt]['idTarifa']." - ".$rs['root'][$iInt]['nombreTarifa'];
            $rs['root'][$iInt]['descSimbolo'] = $rs['root'][$iInt]['simboloUnidad']." - ".$rs['root'][$iInt]['nombreSimbolo'];
            $rs['root'][$iInt]['descColor'] = $rs['root'][$iInt]['color']." - ".$rs['root'][$iInt]['nombreColor'];
            $rs['root'][$iInt]['localizacionCompleta'] = $rs['root'][$iInt]['localizacionUnidad'].", ".$rs['root'][$iInt]['fila']." - ".$rs['root'][$iInt]['lugar'];
        }
			
		echo json_encode($rs);
   	}

    function updateObservacionesHistorico(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $idHistoricoArr = explode('|', substr($_REQUEST['alUnidadesIdHistoricoHdn'], 0, -1));
        if(in_array('', $idHistoricoArr)){
           $e[] = array('id'=>'alUnidadesIdHistoricoHdn','msg'=>getRequerido());
           $a['errorMessage'] = getErrorRequeridos();
           $a['success'] = false;
        }

        if ($a['success'] == true) {
            $observacionesArr = explode('|', substr($_REQUEST['alUnidadesObservacionesTxa'], 0, -1));

            for ($i=0; $i < sizeof($idHistoricoArr); $i++) { 
                $sqlUpdObservacionesHistStr = "UPDATE alHistoricoUnidadesTbl ".
                                              "SET observaciones='".$observacionesArr[$i]."' ".
                                              "WHERE idHistorico=".$idHistoricoArr[$i];

                $rs = fn_ejecuta_query($sqlUpdObservacionesHistStr);

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $a['successMessage'] = getUnidadesUpdObservaciones();
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdObservacionesHistStr;
                }
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function getRepuve(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap135FolioRepuveHdn'], "folioRepuve", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap135MarcaHdn'], "marca", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap135CentroDistHdn'], "centroDistribucion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap135TidHdn'], "tid", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap135FechaTxt'], "fechaEvento", 2);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap135EstatusHdn'], "estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetRepuveStr = "SELECT * FROM alRepuveTbl ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetRepuveStr);
            
        echo json_encode($rs);
    }

?>