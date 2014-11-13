<?php
    /************************************************************************
    * Autor: Alfonso César Martínez Fuertes
    * Fecha: 09-Enero-2014
    * Tablas afectadas: trViajesTractoresTbl, trTalonesViajesTbl, trFoliosTbl
    * Descripción: Programa para dar mantenimiento a foraneos
    *************************************************************************/
	session_start();
	$_SESSION['modulo'] = "trViajesTractores";
  //SESION DE PRUEBA
  $_SESSION['usuCto'] = "CDTOL";
  $_SESSION['usuCompania'] = "TOLUCA";
  $_SESSION['idUsuario'] = 1;
  require_once("../funciones/generales.php");
  require_once("../funciones/construct.php");
  require_once("../funciones/utilidades.php");
  require_once("trGastosViajeTractor.php");
  require_once("alUnidades.php");

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
	
    switch($_REQUEST['trViajesTractoresActionHdn']){
        case 'getTractoresDisponibles':
            getTractoresDisponibles();
            break;
        case 'getTractoresDisponiblesViaje':
            getTractoresDisponiblesViaje();
            break;
        case 'getTractoresCancelacion':
            getTractoresCancelacion();
            break;
        case 'getChoferesDisponibles':
            getChoferesDisponibles();
            break;
        case 'getChoferesDisponiblesGastos':
            getChoferesDisponiblesGastos();
            break;
        case 'getTalonesViaje':
            getTalonesViaje();
            break;
        case 'getUnidadesTalon':
            getUnidadesTalon();
            break;
        case 'getPreviaje':
            getPreviaje();
            break;
        case 'getViajes':
            getViajes();
            break;
        case 'getHistoricoViajes':
            getHistoricoViajes();
            break;
        case 'addViajes':
            addViajes();
            break;
        case 'addViajeVacio':
            addViajeVacio();
            break;
        case 'updTalones':
            updTalones();
            break;
        case 'entregarTalon':
            entregarTalon();
            break;
        case 'addUnidadTalon':
            addUnidadTalon();
            break;
        case 'updUnidadTalon':
            updUnidadTalon();
            break;
        case 'getDetalleTalon':
            getDetalleTalon();
            break;
        case 'cancelarViaje':
            cancelarViaje();
            break;
        case 'cancelarTalon':
            cancelarTalon();
            break;
        case 'cancelarUnidadTalon':
            echo json_encode(cancelarUnidadTalon($_REQUEST['trap446IdTalonHdn'],$_REQUEST['trap446VinHdn']));
            break;
        case 'comprobacionViaje':
            comprobacionViaje();
            break;
    }

    function getTractoresDisponiblesViaje(){
        $lsWhereStr = "WHERE vt.viaje = (SELECT MAX(vt2.viaje) FROM trViajesTractoresTbl vt2 ".
                      "WHERE vt2.idTractor = tr.idTractor) ".
                      "AND tr.idTractor NOT IN  ".
                      "(SELECT tmp.idTractor from trViajesTractoresTmp tmp) ";
                      "AND tr.estatus = 1 ";
        
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct(substr($_REQUEST['trViajesTractoresMovDispHdn'], 0,-1), "vt.claveMovimiento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresCompaniaHdn'], "tr.compania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        
        $sqlGetTractoresDisponibles = "SELECT vt.idViajeTractor,tr.idTractor, tr.tractor, vt.claveMovimiento, ".
                                      "tr.tipoTractor, vt.centroDistribucion ".
                                      "FROM caTractoresTbl tr ".
                                      "LEFT JOIN trViajesTractoresTbl vt ON vt.idTractor = tr.idTractor ".
                                      $lsWhereStr;


        $rs = fn_ejecuta_query($sqlGetTractoresDisponibles);
        for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) {
          $rs['root'][$nInt]['centroDistSesion'] = $_SESSION['usuCto'];
        }
            
        echo json_encode($rs);
    }

    function getTractoresDisponibles(){
        $lsWhereStr = "WHERE (vt.viaje = (SELECT MAX(vt2.viaje) FROM trViajesTractoresTbl vt2 ".
                      "WHERE vt2.idTractor = tr.idTractor) ";
        
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct(substr($_REQUEST['trViajesTractoresMovDispHdn'], 0,-1), "vt.claveMovimiento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
            $lsWhereStr .= ")";
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresCompaniaHdn'], "tr.compania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $lsWhereStr .=  "AND tr.idTractor NOT IN  ".
                        "(SELECT tmp.idTractor from trViajesTractoresTmp tmp) ".
                        "AND tr.estatus = 1 ";
        
        $sqlGetTractoresDisponibles = "SELECT vt.idViajeTractor,tr.idTractor, tr.tractor, vt.claveMovimiento, tr.tipoTractor ".
                                      "FROM caTractoresTbl tr ".
                                      "LEFT JOIN trViajesTractoresTbl vt ON vt.idTractor = tr.idTractor ".
                                      $lsWhereStr;


        $rs = fn_ejecuta_query($sqlGetTractoresDisponibles);
            
        echo json_encode($rs);
    }

    function getTractoresCancelacion(){
        $lsWhereStr = "WHERE tr.idTractor = vt.idTractor ".
                      "AND vt.viaje = (SELECT MAX(vt2.viaje) FROM trViajesTractoresTbl vt2 ".
                      "WHERE vt2.idTractor = tr.idTractor) ";
        
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct(substr($_REQUEST['trap481MovDispHdn'], 0,-1), "vt.claveMovimiento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap481CompaniaHdn'], "tr.compania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $lsWhereStr .=  "AND tr.idTractor NOT IN  ".
                        "(SELECT tmp.idTractor from trViajesTractoresTmp tmp) ".
                        "AND tr.estatus = 1 ";
        
        $sqlGetTractoresCancelacionStr = "SELECT vt.idViajeTractor,tr.idTractor, tr.tractor, vt.claveMovimiento, ".
                                         "tr.tipoTractor, vt.viaje ".
                                         "FROM caTractoresTbl tr, trViajesTractoresTbl vt ".
                                         $lsWhereStr;


        $rs = fn_ejecuta_query($sqlGetTractoresCancelacionStr);
            
        echo json_encode($rs);
    }

    function getChoferesDisponibles(){
        $lsWhereStr = "WHERE (vt.viaje = (SELECT MAX(vt2.viaje) FROM trViajesTractoresTbl vt2 ".
                      "WHERE vt2.claveChofer = ch.claveChofer) ".
                      "AND ch.claveChofer NOT IN  ".
                      "(SELECT tmp.claveChofer from trViajesTractoresTmp tmp) ";
                      "AND tr.estatus = 1 ";
    
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct(substr($_REQUEST['trViajesTractoresMovDispHdn'], 0,-1), "vt.claveMovimiento", 1);
            $lsWhereStr .= $lsCondicionStr." OR vt.claveMovimiento IS NULL) ";
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresCompaniaHdn'], "tr.compania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
    
        $sqlGetTractoresDisponibles = "SELECT ch.*, vt.claveMovimiento as claveMovimientoViaje ".
                                      "FROM caChoferesTbl ch ".
                                      "LEFT JOIN trViajesTractoresTbl vt ON vt.claveChofer = ch.claveChofer ".
                                      $lsWhereStr.
                                      "ORDER BY ch.claveChofer ";

        $rs = fn_ejecuta_query($sqlGetTractoresDisponibles);
        
        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['nombreChofer'] = $rs['root'][$iInt]['claveChofer']." - ".
                                                 $rs['root'][$iInt]['nombre']." ".
                                                 $rs['root'][$iInt]['apellidoPaterno']." ".
                                                 $rs['root'][$iInt]['apellidoMaterno'];
        }
            
        echo json_encode($rs);
    }

    function getChoferesDisponiblesGastos(){
        $lsWhereStr = "WHERE (vt.viaje = (SELECT MAX(vt2.viaje) FROM trViajesTractoresTbl vt2 ".
                      "WHERE vt2.claveChofer = ch.claveChofer) ".
                      "AND ch.claveChofer NOT IN  ".
                      "(SELECT tmp.claveChofer from trViajesTractoresTmp tmp) ";
                      "AND tr.estatus = 1 ";
    
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct(substr($_REQUEST['trViajesTractoresMovDispHdn'], 0,-1), "vt.claveMovimiento", 1);
            $lsWhereStr .= $lsCondicionStr." OR vt.claveMovimiento IS NULL) ";
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresCompaniaHdn'], "tr.compania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        if ($_REQUEST['trViajesTractoresChoferHdn'] != "") {
            $lsWhereStr .= "OR ch.claveChofer = ".$_REQUEST['trViajesTractoresChoferHdn']." ";
        }
    
        $sqlGetTractoresDisponibles = "SELECT ch.* ".
                                      "FROM caChoferesTbl ch ".
                                      "LEFT JOIN trViajesTractoresTbl vt ON vt.claveChofer = ch.claveChofer ".
                                      $lsWhereStr.
                                      "ORDER BY ch.claveChofer ";

        $rs = fn_ejecuta_query($sqlGetTractoresDisponibles);
        
        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['nombreChofer'] = $rs['root'][$iInt]['claveChofer']." - ".
                                                 $rs['root'][$iInt]['nombre']." ".
                                                 $rs['root'][$iInt]['apellidoPaterno']." ".
                                                 $rs['root'][$iInt]['apellidoMaterno'];
        }
            
        echo json_encode($rs);
    }

    function getTalonesViaje(){
        $lsWhereStr = "WHERE tv.distribuidor = dc.distribuidorCentro ".
                      "AND tv.companiaRemitente = co.compania ".
                      "AND tv.idViajeTractor = vt.idViajeTractor ".
                      "AND vt.idTractor = tr.idTractor ".
                      "AND ch.claveChofer = vt.claveChofer ".
                      "AND es.idPais = pa.idPais ".
                      "AND mu.idEstado = es.idEstado ".
                      "AND cl.idMunicipio = mu.idMunicipio ".
                      "AND dr.idColonia = cl.idColonia ".
                      "AND dr.direccion = tv.direccionEntrega ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdViajeHdn'], "tv.idViajeTractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdTalonHdn'], "tv.idTalon", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresDistribuidorHdn'], "tv.distribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresFolioTxt'], "tv.folio", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresCompaniaHdn'], "tr.compania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresClaveMovimientoHdn'], "tv.claveMovimiento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresCentroDistHdn'], "tv.centroDistribucion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdTractorHdn'], "tr.idTractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresClaveMovViajeHdn'], "vt.claveMovimiento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresTipoDoctoHdn'], "tv.tipoDocumento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetTalonesViajeStr = "SELECT tv.*, dc.descripcionCentro, co.descripcion AS descripcionCompania, tr.idTractor, vt.viaje, ".
                                 "vt.fechaEvento AS fechaViaje, vt.numeroRepartos, vt.kilometrosTabulados, ".
                                 "vt.claveMovimiento AS claveMovimientoViaje, vt.numeroUnidades AS numeroUnidadesViaje, ".
                                 " tr.tractor, tr.compania AS ciaTractor, ch.claveChofer, ch.nombre, ch.apellidoPaterno, ch.apellidoMaterno, ".
                                 "(SELECT co2.descripcion FROM caCompaniasTbl co2 WHERE co2.compania = tr.compania) AS descCiaTractor, ".
                                 "(SELECT pl.plaza FROM caPlazasTbl pl WHERE pl.idPlaza = tv.idPlazaOrigen) AS nombrePlazaOrigen, ".
                                 "(SELECT pl2.plaza FROM caPlazasTbl pl2 WHERE pl2.idPlaza = tv.idPlazaDestino) AS nombrePlazaDestino,  ".
                                 "(SELECT cg.nombre FROM caGeneralesTbl cg WHERE cg.tabla='trTalonesViajesTbl' ".
                                    "AND cg.columna='claveMovimiento' AND cg.valor = tv.claveMovimiento) AS descClaveMovTalon, ".
                                 "(SELECT cg2.nombre FROM caGeneralesTbl cg2 WHERE cg2.tabla='trTalonesViajesTbl' ".
                                    "AND cg2.columna='claveMovimiento' AND cg2.valor = tv.claveMovimiento) AS descTipoDocto, ".
                                 "(SELECT cg.nombre FROM caGeneralesTbl cg WHERE cg.tabla='trTalonesViajesTbl' ".
                                    "AND cg.columna='tipoTalon' AND cg.valor = tv.tipoTalon) AS nombreTipoTalon, ".
                                 "(SELECT dc2.descripcionCentro FROM caDistribuidoresCentrosTbl dc2 ".
                                    "WHERE dc2.distribuidorCentro = vt.centroDistribucion) AS nombreCentroDist, ".
                                 "pa.pais, es.estado, mu.municipio, cl.colonia, cl.cp, dr.calleNumero ".
                                 "FROM trTalonesViajesTbl tv, caDistribuidoresCentrosTbl dc, caCompaniasTbl co, ".
                                 "trViajesTractoresTbl vt, caTractoresTbl tr, caChoferesTbl ch, ".
                                 "caPaisesTbl pa, caEstadosTbl es, caMunicipiosTbl mu, caColoniasTbl cl, caDireccionesTbl dr ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetTalonesViajeStr);

        for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
            $rs['root'][$nInt]['descDistribuidor'] = $rs['root'][$nInt]['distribuidor']." - ".$rs['root'][$nInt]['descripcionCentro'];
            $rs['root'][$nInt]['descCompania'] = $rs['root'][$nInt]['companiaRemitente']." - ".$rs['root'][$nInt]['descripcionCompania'];
            $rs['root'][$nInt]['descTractorCia'] = $rs['root'][$nInt]['ciaTractor']." - ".$rs['root'][$nInt]['descCiaTractor'];
            $rs['root'][$nInt]['nombreChofer'] = $rs['root'][$nInt]['claveChofer']." - ".
                                                 $rs['root'][$nInt]['nombre']." ".
                                                 $rs['root'][$nInt]['apellidoPaterno']." ".
                                                 $rs['root'][$nInt]['apellidoMaterno'];
            $rs['root'][$nInt]['direccionCompleta'] = $rs['root'][$nInt]['calleNumero'].", ".
                                                      $rs['root'][$nInt]['colonia'].", ".
                                                      $rs['root'][$nInt]['municipio'].", ".
                                                      $rs['root'][$nInt]['estado'].", ".
                                                      $rs['root'][$nInt]['pais'].", ".
                                                      $rs['root'][$nInt]['cp'];

            $rs['root'][$nInt]['fechaEvento'] = date('Y-m-d', strtotime($rs['root'][$nInt]['fechaEvento']));
        }

        echo json_encode($rs);
    }

    function getUnidadesTalon(){
        $lsWhereStr = "WHERE un.vin = tvt.vin ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresNumTalonHdn'], "numeroTalon", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdViajeHdn'], "idViajeTractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresVinHdn'], "vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetTalonesViajeStr = "SELECT tvt.*, un.simboloUnidad, un.color,".
                                    "(SELECT su.descripcion FROM casimbolosunidadestbl su ".
                                        "WHERE su.simboloUnidad = un.simboloUnidad) AS nombreSimbolo,".
                                    "(SELECT co.descripcion FROM caColorUnidadesTbl co ".
                                        "WHERE co.color = un.color) AS nombreColor ".
                                "FROM trtalonesviajestmp tvt, alunidadestbl un ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetTalonesViajeStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['avanzada'] = substr($rs['root'][$iInt]['vin'], 9);
        }

        echo json_encode($rs);
    }

    function getPreviaje(){
        $lsWhereStr = "WHERE cc.claveChofer = vt.claveChofer ".
                      "AND vt.viaje = (SELECT MAX(vt2.viaje) FROM trViajesTractoresTbl vt2 ".
                        "WHERE vt2.idTractor = vt.idTractor) ".
                      "AND tr.idTractor = vt.idTractor ".
                      "AND vt.idTractor = ".$_REQUEST['trViajesTractoresIdTractorHdn']." ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresClaveChoferHdn'], "cc.claveChofer", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetPreviajeStr = "SELECT vt.idTractor, cc.claveChofer, cc.apellidoPaterno, cc.apellidoMaterno,".
                                "cc.nombre, vt.viaje, tr.rendimiento, ".
                                "(SELECT dc.idPlaza FROM caDistribuidoresCentrosTbl dc ".
                                    "WHERE dc.distribuidorCentro = 'CDTOL') AS plazaOrigen ".
                                "FROM caChoferesTbl cc, trViajesTractoresTbl vt, caTractoresTbl tr ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetPreviajeStr);

        $sqlGetDatosTractor = "SELECT tr.rendimiento FROM caTractoresTbl tr ".
                              "WHERE tr.idTractor=".$_REQUEST['trViajesTractoresIdTractorHdn'];

        $rsTr = fn_ejecuta_query($sqlGetDatosTractor);

        if (isset($rs['root'])) {
    		for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
    			if($rs['root'][$iInt]['nombre'] != ""){
        			$rs['root'][$iInt]['nombreChofer'] = $rs['root'][$iInt]['nombre'].' '.
                                                         $rs['root'][$iInt]['apellidoPaterno'].' '.
                                                         $rs['root'][$iInt]['apellidoMaterno'];

                    $rs['root'][$iInt]['origen'] = $_SESSION['usuCompania'];
    			}
    			if ($rs['root'][$iInt]['viaje'] == "") {
                    $rs['root'][$iInt]['viaje'] = 1;
                    $rs['root'][$iInt]['idTractor'] = $_REQUEST['trViajesTractoresIdTractorHdn'];
                    $rs['records'] = 1;
                } else {
                    $rs['root'][$iInt]['viaje'] += 1;
                }
            } 
        } else {
            $rs['root'][0]['viaje'] = 1;
            $rs['root'][0]['idTractor'] = $_REQUEST['trViajesTractoresIdTractorHdn'];
            $rs['root'][0]['rendimiento'] = $rsTr['root'][0]['rendimiento'];
            $rs['records'] = 1;
        }           
        echo json_encode($rs);
    }

    function getViajes(){
        $lsWhereStr = "WHERE vt3.claveChofer = cc.claveChofer ".
                      "AND vt3.idTractor = tr.idTractor ".
                      "AND vt3.viaje = (SELECT MAX(vt4.viaje) FROM trViajesTractoresTbl vt4 WHERE vt4.idTractor = vt3.idTractor) ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdViajeHdn'], "vt3.idViajeTractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdTractorHdn'], "vt3.idTractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdPlazaOrigenHdn'], "vt3.idPlazaOrigen", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdPlazaDestinoHdn'], "vt3.idPlazaDestino", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresCentroDistHdn'], "vt3.centroDistribucion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresViajeTxt'], "vt3.viaje", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresFechaTxt'], "vt3.fechaEvento", 2);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresKmTabuladosTxt'], "vt3.kilometrosTabulados", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresKmComprobadosTxt'], "vt3.kilometrosComprobados", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresKmSinUnidadTxt'], "vt3.kilometrosSinUnidad", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresNumUnidadesTxt'], "vt3.numeroUnidades", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresRepartosTxt'], "vt3.numeroRepartos", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdViajePadreHdn'], "vt3.idViajePadre", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresClaveMovimientoHdn'], "vt3.claveMovimiento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresClaveChoferHdn'], "cc.claveChofer", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetViajesStr = "SELECT vt3.*,cc.claveChofer, cc.apellidoPaterno, cc.apellidoMaterno, cc.nombre, ".
                                "vt3.claveMovimiento, tr.tractor, tr.rendimiento, tr.compania, ".
                            "(SELECT dc.idPlaza FROM caDistribuidoresCentrosTbl dc ".
                                "WHERE dc.distribuidorCentro = 'CDTOL') AS plazaOrigen, ".
                            "(SELECT dc2.descripcionCentro FROM caDistribuidoresCentrosTbl dc2 ".
                                "WHERE dc2.distribuidorCentro = vt3.centroDistribucion) AS nombreCentroDist, ".
                            "(SELECT COUNT(*) FROM trTalonesViajesTbl tv ".
                                "WHERE tv.idViajeTractor = vt3.idViajeTractor) AS numeroTalones, ".
                            "(SELECT pl.plaza FROM caplazastbl pl WHERE pl.idPlaza = vt3.idPlazaOrigen) AS descPlazaOrigen, ".
                            "(SELECT pl2.plaza FROM caplazastbl pl2 WHERE pl2.idPlaza = vt3.idPlazaDestino) AS descPlazaDestino ".
                            "FROM caChoferesTbl cc, trViajesTractoresTbl vt3, caTractoresTbl tr ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetViajesStr); 

        for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
            $rs['root'][$nInt]['nombreChofer'] = $rs['root'][$nInt]['claveChofer']." - ".
                                                 $rs['root'][$nInt]['nombre']." ".
                                                 $rs['root'][$nInt]['apellidoPaterno']." ".
                                                 $rs['root'][$nInt]['apellidoMaterno'];
        }

        echo json_encode($rs);
    }

    function getHistoricoViajes(){
        $lsWhereStr = "WHERE vt.claveChofer = ch.claveChofer ".
                      "AND vt.idTractor = tr.idTractor ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdViajeHdn'], "vt.idViajeTractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdTractorHdn'], "vt.idTractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdPlazaOrigenHdn'], "vt.idPlazaOrigen", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdPlazaDestinoHdn'], "vt.idPlazaDestino", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresCentroDistHdn'], "vt.centroDistribucion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresViajeTxt'], "vt.viaje", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresKmTabuladosTxt'], "vt.kilometrosTabulados", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresKmComprobadosTxt'], "vt.kilometrosComprobados", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresKmSinUnidadTxt'], "vt.kilometrosSinUnidad", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresNumUnidadesTxt'], "vt.numeroUnidades", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresRepartosTxt'], "vt.numeroRepartos", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresIdViajePadreHdn'], "vt.idViajePadre", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresClaveMovimientoHdn'], "vt.claveMovimiento", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresClaveChoferHdn'], "ch.claveChofer", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractoresTractorHdn'], "tr.tractor", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        //FECHA
        //Desde
        if ($_REQUEST['trViajesTractoresFechaDesdeTxt'] != "") {
            if ($lsWhereStr == "") {
                   $lsWhereStr .= " WHERE vt.fechaEvento >= '".$_REQUEST['trViajesTractoresFechaDesdeTxt']."' ";
            } else {
                $lsWhereStr .= " AND vt.fechaEvento >= '".$_REQUEST['trViajesTractoresFechaDesdeTxt']."' ";
            }
        }
        //Hasta
        if ($_REQUEST['trViajesTractoresFechaHastaTxt'] != "") {
            if ($lsWhereStr == "") {
                $lsWhereStr .= " WHERE date_format(vt.fechaEvento, '%Y-%m-%d') <= '".$_REQUEST['trViajesTractoresFechaHastaTxt']."' ";
            } else {
                $lsWhereStr .= " AND date_format(vt.fechaEvento, '%Y-%m-%d') <= '".$_REQUEST['trViajesTractoresFechaHastaTxt']."' ";
            }
        }

        //CHOFERES
        //Desde
        if ($_REQUEST['trViajesTractoresChoferDesdeTxt'] != "") {
            if ($lsWhereStr == "") {
                   $lsWhereStr .= " WHERE vt.claveChofer >= '".$_REQUEST['trViajesTractoresChoferDesdeTxt']."' ";
            } else {
                $lsWhereStr .= " AND vt.claveChofer >= '".$_REQUEST['trViajesTractoresChoferDesdeTxt']."' ";
            }
        }
        //Hasta
        if ($_REQUEST['trViajesTractoresChoferHastaTxt'] != "") {
            if ($lsWhereStr == "") {
                $lsWhereStr .= " WHERE vt.claveChofer <= '".$_REQUEST['trViajesTractoresChoferHastaTxt']."' ";
            } else {
                $lsWhereStr .= " AND vt.claveChofer <= '".$_REQUEST['trViajesTractoresChoferHastaTxt']."' ";
            }
        }
        //Tractor, descripcion clave mov
        $sqlGetHistoricoViajesStr = "SELECT vt.*, ch.apellidoPaterno, ch.apellidoMaterno, ch.nombre, tr.tractor, ".
                                    "COUNT(tv.idTalon) AS numeroTalones, SUM(tv.numeroUnidades) AS totalUnidades, ".
                                    "tr.rendimiento, tr.compania, ".
                                    "(SELECT co.descripcion FROM caCompaniasTbl co WHERE co.compania = tr.compania) AS nombreCompania, ".
                                    "(SELECT tr.tractor FROM caTractoresTbl tr WHERE tr.idTractor = vt.idTractor) AS tractor, ".
                                    "(SELECT cg.nombre FROM caGeneralesTbl cg WHERE cg.tabla='trViajesTractoresTbl' ".
                                    "AND cg.columna='claveMovimiento' AND cg.valor = vt.claveMovimiento) AS nombreClaveMov, ".
                                    "(SELECT pl.plaza FROM caPlazasTbl pl WHERE pl.idPlaza = vt.idPlazaOrigen) AS nombrePlazaOrigen, ".
                                    "(SELECT pl2.plaza FROM caPlazasTbl pl2 WHERE pl2.idPlaza = vt.idPlazaDestino) AS nombrePlazaDestino ".
                                    "FROM caChoferesTbl ch, caTractoresTbl tr, trViajesTractoresTbl vt ".
                                    "LEFT JOIN trtalonesviajestbl tv ON tv.idViajeTractor = vt.idViajeTractor ".
                                    $lsWhereStr." GROUP BY vt.idViajeTractor ";

        $rs = fn_ejecuta_query($sqlGetHistoricoViajesStr);

        for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
            $rs['root'][$nInt]['nombreChofer'] = $rs['root'][$nInt]['claveChofer']." - ".
                                                 $rs['root'][$nInt]['nombre']." ".
                                                 $rs['root'][$nInt]['apellidoPaterno']." ".
                                                 $rs['root'][$nInt]['apellidoMaterno'];

            $rs['root'][$nInt]['fechaEvento'] = date('Y-m-d', strtotime($rs['root'][$nInt]['fechaEvento']));
            $rs['root'][$nInt]['descCompania'] = $rs['root'][$nInt]['compania']." - ".$rs['root'][$nInt]['nombreCompania'];

            //Obtiene y concatena todos los talones del viaje
            $sqlGetTalonesViajeStr = "SELECT folio FROM trTalonesViajesTbl ".
                                     "WHERE idViajeTractor = ".$rs['root'][$nInt]['idViajeTractor'];

            $rsTalones = fn_ejecuta_query($sqlGetTalonesViajeStr);
            $temp = "";

            for ($mInt=0; $mInt < sizeof($rsTalones['root']); $mInt++) {
                if ($mInt != 0) {
                  $temp .= " - ";
                }
                $temp .= $rsTalones['root'][$mInt]['folio'];
            }

            $rs['root'][$nInt]['talonesViaje'] = $temp;
        }

        echo json_encode($rs);
    }

    function addViajes(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trViajesTractoresIdTractorHdn'] == ""){
            $e[] = array('id'=>'trViajesTractoresIdTractorHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trViajesTractoresClaveChoferHdn'] == ""){
            $e[] = array('id'=>'trViajesTractoresClaveChoferHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trViajesTractoresClaveMovimientoHdn'] == ""){
            $e[] = array('id'=>'trViajesTractoresClaveMovimientoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if ($_REQUEST['trViajesTractoresClaveMovimientoTalonHdn'] == "") {
            $e[] = array('id'=>'trViajesTractoresClaveMovimientoTalonHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if ($_REQUEST['trViajesTractoresTipoDoctoHdn'] == "") {
            $e[] = array('id'=>'trViajesTractoresTipoDoctoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if ($_REQUEST['trViajesTractoresCompaniaHdn'] == "") {
            $e[] = array('id'=>'trViajesTractoresCompaniaHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if ($_REQUEST['trViajesTractoresTipoTalonHdn'] == "") {
            $e[] = array('id'=>'trViajesTractoresTipoTalonHdn','msg'=>getRequerido());
            $a['success'] = false;
        }

        //TALONES -- Inserción Masiva
        $distArr = explode("|", substr($_REQUEST['trViajesTractoresDistribuidorHdn'], 0, -1));
        if (in_array("", $distArr)) {
            $e[] = array('id'=>'trViajesTractoresDistribuidorHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $direccionArr = explode("|", substr($_REQUEST['trViajesTractoresDireccionHdn'], 0, -1));
        if (in_array("", $direccionArr)) {
            $e[] = array('id'=>'trViajesTractoresDireccionHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $destinoArr = explode("|", substr($_REQUEST['trViajesTractoresDestinoHdn'], 0, -1));
        if (in_array("", $destinoArr)) {
            $e[] = array('id'=>'trViajesTractoresDestinoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $unidadesArr = explode("|", substr($_REQUEST['trViajesTractoresUnidadesHdn'], 0, -1));
        if (in_array("", $unidadesArr)) {
            $e[] = array('id'=>'trViajesTractoresUnidadesHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $remitenteArr = explode("|", substr($_REQUEST['trViajesTractoresRemitenteHdn'], 0, -1));
        if (in_array("", $remitenteArr)) {
            $e[] = array('id'=>'trViajesTractoresRemitenteHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $kilometrosArr = explode("|", substr($_REQUEST['trViajesTractoresKmTabuladosTxt'], 0, -1));
        if (in_array("", $kilometrosArr)) {
            $e[] = array('id'=>'trViajesTractoresKmTabuladosTxt','msg'=>getRequerido());
            $a['success'] = false;
        }
        //Checar el destino más largo
        $plazaDestino = -1;
        $kmTabulados = 0;
        for ($iInt=0; $iInt < sizeof($kilometrosArr); $iInt++) { 
            if ($plazaDestino < $kilometrosArr[$iInt]) {
                $plazaDestino = $destinoArr[$iInt];
                $kmTabulados = $kilometrosArr[$iInt];
            }
        }

        if($a['success'] == true){
            if($plazaDestino > 0){
                $sqlAddViajeTractorStr = "INSERT INTO trViajesTractoresTbl (idTractor,claveChofer,idPlazaOrigen,idPlazaDestino,".
                                         "centroDistribucion,viaje,fechaEvento,kilometrosTabulados,kilometrosComprobados,".
                                         "kilometrosSinUnidad,numeroUnidades,numeroRepartos,idViajePadre,claveMovimiento,usuario,ip) ".
                                         "VALUES(".
                                            $_REQUEST['trViajesTractoresIdTractorHdn'].",".
                                            $_REQUEST['trViajesTractoresClaveChoferHdn'].",".
                                            "(SELECT dc.idPlaza FROM caDistribuidoresCentrosTbl dc ".
                                                "WHERE dc.distribuidorCentro = '".$_SESSION['usuCto']."'),".
                                            $plazaDestino.",".
                                            "'".$_SESSION['usuCto']."',".
                                            "IFNULL((SELECT viaje FROM trViajesTractoresTbl vt1 ".
                                                "WHERE vt1.idViajeTractor = ".
                                                    "(SELECT MAX(vt2.idViajeTractor) ".
                                                        "FROM trViajesTractoresTbl vt2 WHERE vt2.idTractor=vt1.idTractor)".
                                                "AND vt1.idTractor = ".$_REQUEST['trViajesTractoresIdTractorHdn'].")+1,1),".
                                            "'".date("Y-m-d H:i:s")."',".
                                            replaceEmptyDec($kmTabulados).",".
                                            replaceEmptyDec($_REQUEST['trViajesTractoresKmComprobadosTxt']).",".
                                            replaceEmptyDec($_REQUEST['trViajesTractoresKmSinUnidadTxt']).",".
                                            array_sum($unidadesArr).",".
                                            sizeof($unidadesArr).",".
                                            replaceEmptyNull($_REQUEST['trViajesTractoresIdViajePadreHdn']).",".
                                            "'".$_REQUEST['trViajesTractoresClaveMovimientoHdn']."',".
                                            "'".$_SESSION['usuario']."',".
                                            "'".$_SERVER['REMOTE_ADDR']."')";

                $rs = fn_ejecuta_query($sqlAddViajeTractorStr);
                $idViajeInt = mysql_insert_id();

                if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") { 
                    //Obtener el folio
                    $sqlGetFolioStr = "SELECT folio FROM trFoliosTbl ".
                                      "WHERE tipoDocumento='".$_REQUEST['trViajesTractoresTipoDoctoHdn']."' ".
                                      "AND centroDistribucion='".$_SESSION['usuCto']."' ".
                                      "AND compania = '".$_REQUEST['trViajesTractoresCompaniaHdn']."'";

                    $rs = fn_ejecuta_query($sqlGetFolioStr);
                    $folio = $rs['root'][0]['folio'];
                    
                    if(isset($folio) && $folio != ""){
                        for ($iInt=0; $iInt < sizeof($unidadesArr); $iInt++) { 
                            if ($iInt != 0) {
                                $sqlAddTalonesViajeStr .= ",";
                            } 

                            if ((integer) $folio < 9) {
                                $folio = '0'.(string)((integer)$folio+1);
                            } else {
                                $folio = (string)((integer)$folio+1);
                            }

                            $sqlAddTalonesViajeStr = "INSERT INTO trTalonesViajesTbl (distribuidor,folio,idViajeTractor,companiaRemitente,".
                                                    "idPlazaOrigen,idPlazaDestino,direccionEntrega,centroDistribucion,tipoTalon,".
                                                    "fechaEvento,observaciones,numeroUnidades,importe,seguro,tarifaCobrar,kilometrosCobrar,".
                                                    "impuesto,retencion,claveMovimiento,tipoDocumento) VALUES(".
                                                    "'".$distArr[$iInt]."',".
                                                    "'".$folio."',".
                                                    $idViajeInt.",".
                                                    "'".$remitenteArr[$iInt]."',".
                                                    "(SELECT dc.idPlaza FROM caDistribuidoresCentrosTbl dc ".
                                                        "WHERE dc.distribuidorCentro = '".$_SESSION['usuCto']."'),".
                                                    $destinoArr[$iInt].",".
                                                    $direccionArr[$iInt].",".
                                                    "'".$_SESSION['usuCto']."',".
                                                    "'".$_REQUEST['trViajesTractoresTipoTalonHdn']."',".
                                                    "'".date("Y-m-d")."',".
                                                    "'".$observacionesArr[$iInt]."',".
                                                    $unidadesArr[$iInt].",".
                                                    replaceEmptyDec($_REQUEST['trViajesTractoresImporteTxt']).",".
                                                    replaceEmptyDec($_REQUEST['trViajesTractoresSeguroTxt']).",".
                                                    replaceEmptyDec($_REQUEST['trViajesTractoresTarifaCobrarTxt']).",".
                                                    replaceEmptyDec($_REQUEST['trViajesTractoresKmCobrarTxt']).",".
                                                    replaceEmptyDec($_REQUEST['trViajesTractoresImpuestoTxt']).",".
                                                    replaceEmptyDec($_REQUEST['trViajesTractoresRetencionTxt']).",".
                                                    "'".$_REQUEST['trViajesTractoresClaveMovimientoTalonHdn']."',".
                                                    "'".$_REQUEST['trViajesTractoresTipoDoctoHdn']."')";
                            
                            $rs = fn_ejecuta_query($sqlAddTalonesViajeStr);

                            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                                $sqlUpdFolioStr = "UPDATE trFoliosTbl ".
                                                  "SET folio = '".$folio."' ".
                                                  "WHERE centroDistribucion = '".$_SESSION['usuCto']."' ".
                                                  "AND compania = '".$_REQUEST['trViajesTractoresCompaniaHdn']."' ".
                                                  "AND tipoDocumento = '".$_REQUEST['trViajesTractoresTipoDoctoHdn']."'";

                                $rs = fn_ejecuta_query($sqlUpdFolioStr);

                                if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                                    $a['successMessage'] = getPreViajeSuccessMsg();
                                    $a['sql'] = $sqlAddTalonesViajeStr;
                                } else {
                                    $a['success'] = false;
                                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $a['sql'];
                                }
                            } else {
                                $a['success'] = false;
                                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $a['sql'];
                                break;
                            }
                        }
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = "Folio no existente";
                    }
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddViajeTractorStr;
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = "Error al obtener la Plaza Destino";
            }
        } else {
            $a['errorMessage'] = getErrorRequeridos();
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function addViajeVacio(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trap484TractorHdn'] == ""){
            $e[] = array('id'=>'trap484TractorHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap484ChoferHdn'] == ""){
            $e[] = array('id'=>'trap484ChoferHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap484ClaveMovimientoHdn'] == ""){
            $e[] = array('id'=>'trap484ClaveMovimientoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap484DestinoHdn'] == ""){
            $e[] = array('id'=>'trap484DestinoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap484CompaniaHdn'] == ""){
            $e[] = array('id'=>'trap484CompaniaHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap484TipoDoctoHdn'] == ""){
            $e[] = array('id'=>'trap484TipoDoctoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap484ImportesHdn'] == ""){
            $e[] = array('id'=>'trap484ImportesHdn','msg'=>getRequerido());
            $a['success'] = false;
        }

        if($a['success'] == true){
            $sqlAddViajeVacioStr = "INSERT INTO trViajesTractoresTbl (idTractor,claveChofer,idPlazaOrigen,idPlazaDestino,".
                                     "centroDistribucion,viaje,fechaEvento,kilometrosTabulados,kilometrosComprobados,".
                                     "kilometrosSinUnidad,numeroUnidades,numeroRepartos,idViajePadre,claveMovimiento,usuario,ip) ".
                                     "VALUES(".
                                        $_REQUEST['trap484TractorHdn'].",".
                                        $_REQUEST['trap484ChoferHdn'].",".
                                        "(SELECT dc.idPlaza FROM caDistribuidoresCentrosTbl dc ".
                                            "WHERE dc.distribuidorCentro = '".$_SESSION['usuCto']."'),".
                                        "(SELECT dc2.idPlaza FROM caDistribuidoresCentrosTbl dc2 ".
                                            "WHERE dc2.distribuidorCentro = '".$_REQUEST['trap484DestinoHdn']."'),".
                                        "'".$_SESSION['usuCto']."',".
                                        "IFNULL((SELECT viaje FROM trViajesTractoresTbl vt1 ".
                                            "WHERE vt1.idViajeTractor = ".
                                                "(SELECT MAX(vt2.idViajeTractor) ".
                                                    "FROM trViajesTractoresTbl vt2 WHERE vt2.idTractor=vt1.idTractor)".
                                            "AND vt1.idTractor = ".$_REQUEST['trap484TractorHdn'].")+1,1),".
                                        "'".date("Y-m-d H:i:s")."',".
                                        replaceEmptyDec($_REQUEST['trap484KmTabuladosTxt']).",".
                                        replaceEmptyDec($_REQUEST['trap484KmComprobadosTxt']).",".
                                        replaceEmptyDec($_REQUEST['trap484KmSinUnidadTxt']).",".
                                        "0,".
                                        "0,".
                                        replaceEmptyNull($_REQUEST['trap484IdViajePadreHdn']).",".
                                        "'".$_REQUEST['trap484ClaveMovimientoHdn']."',".
                                        "'".$_SESSION['usuario']."',".
                                        "'".$_SERVER['REMOTE_ADDR']."')";

            $rs = fn_ejecuta_query($sqlAddViajeVacioStr);
            $idViajeInt = mysql_insert_id();

            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {

                $data = addGastosViajeTractor($idViajeInt, $_REQUEST['trap484CompaniaHdn'],$_REQUEST['trap484ConceptosHdn'], $_REQUEST['trap484ImportesHdn'], '', 'GV', $_REQUEST['trap484TipoDoctoHdn']);
                
                if ($data['success'] == true) {
                    $a['successMessage'] = getGastosViajeVacioSuccessMsg();
                } else {
                    $a['success'] = $data['success'];
                    $a['errorMessage'] = $data['errorMessage'];
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddViajeVacioStr;
            }
        } else {
            $a['errorMessage'] = getErrorRequeridos();
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updTalones(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trap446IdViajeHdn'] == ""){
            $e[] = array('id'=>'trap446IdViajeHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap446TipoTalonHdn'] == ""){
            $e[] = array('id'=>'trap446TipoTalonHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap446CompaniaHdn'] == ""){
            $e[] = array('id'=>'trap446CompaniaHdn','msg'=>getRequerido());
            $a['success'] = false;
        }

        $distArr = explode('|', substr($_REQUEST['trap446DistribuidorHdn'], 0, -1));
        if(in_array("", $distArr)){
            $e[] = array('id'=>'trap446DistribuidorHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $remitenteArr = explode('|', substr($_REQUEST['trap446RemitenteHdn'], 0, -1));
        if(in_array("", $remitenteArr)){
            $e[] = array('id'=>'trap446RemitenteHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $direccionArr = explode('|', substr($_REQUEST['trap446DireccionEntregaHdn'], 0, -1));
        if(in_array("", $direccionArr)){
            $e[] = array('id'=>'trap446DireccionEntregaHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $numUnidadesArr = explode('|', substr($_REQUEST['trap446NumeroUnidadesHdn'], 0, -1));
        if(in_array("", $numUnidadesArr)){
            $e[] = array('id'=>'trap446NumeroUnidadesHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $claveMovArr = explode('|', substr($_REQUEST['trap446ClaveMovimientoHdn'], 0, -1));
        if(in_array("", $claveMovArr)){
            $e[] = array('id'=>'trap446ClaveMovimientoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $tipoDocumentoArr = explode('|', substr($_REQUEST['trap446TipoDocumentoHdn'], 0, -1));
        if(in_array("", $tipoDocumentoArr)){
            $e[] = array('id'=>'trap446TipoDocumentoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        
        if ($a['success'] == true) {
          $idTalonArr = explode('|', substr($_REQUEST['trap446IdTalonHdn'], 0, -1));
          $destinoArr = explode('|', substr($_REQUEST['trap446PlazaDestinoHdn'], 0, -1));
          $importeArr = explode('|', substr($_REQUEST['trap446ImporteHdn'], 0, -1));
          $seguroArr = explode('|', substr($_REQUEST['trap446SeguroHdn'], 0, -1));
          $tarifaCobrarArr = explode('|', substr($_REQUEST['trap446TarifaCobrarHdn'], 0, -1));
          $kmCobrarArr = explode('|', substr($_REQUEST['trap446KmCobrarHdn'], 0, -1));
          $impuestoArr = explode('|', substr($_REQUEST['trap446ImpuestoHdn'], 0, -1));
          $retencionArr = explode('|', substr($_REQUEST['trap446RetencionHdn'], 0, -1));
          $observacionesArr = explode('|', substr($_REQUEST['trap446ObservacionesHdn'], 0, -1));

          for ($nInt=0; $nInt < sizeof($distArr); $nInt++) { 

              if ($idTalonArr[$nInt] != "" && $idTalonArr[$nInt] > 0) {
                  $sqlUpdTalonStr = "UPDATE trTalonesViajesTbl ".
                                    "SET companiaRemitente = '".$remitenteArr[$nInt]."',".
                                    "direccionEntrega = ".$direccionArr[$nInt].",".  
                                    "tipoTalon = '".$_REQUEST['trap446TipoTalonHdn']."',".
                                    "observaciones = '".$observacionesArr[$nInt]."',".
                                    "numeroUnidades = ".$numUnidadesArr[$nInt].",".
                                    "importe = ".replaceEmptyDec($importeArr[$nInt]).",".
                                    "seguro = ".replaceEmptyDec($seguroArr[$nInt]).",".
                                    "tarifaCobrar = ".replaceEmptyDec($tarifaCobrarArr[$nInt]).",".
                                    "kilometrosCobrar = ".replaceEmptyDec($kmCobrarArr[$nInt]).",".
                                    "impuesto = ".replaceEmptyDec($impuestoArr[$nInt]).",".
                                    "retencion = ".replaceEmptyDec($retencionArr[$nInt]).",".
                                    "claveMovimiento = '".$claveMovArr[$nInt]."',".
                                    "tipoDocumento = '".$tipoDocumentoArr[$nInt]."' ".
                                    "WHERE idTalon = ".$idTalonArr[$nInt];

                  $rs = fn_ejecuta_query($sqlUpdTalonStr);

                  if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {

                  } else {
                      $a['success'] = false;
                      $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddTalonesViajeStr;
                      break;
                  }

              } else {
                  //Obtener el folio
                  $sqlGetFolioStr = "SELECT folio FROM trFoliosTbl ".
                                    "WHERE tipoDocumento='".$tipoDocumentoArr[$nInt]."' ".
                                    "AND centroDistribucion='".$_SESSION['usuCto']."' ".
                                    "AND compania = '".$_REQUEST['trap446CompaniaHdn']."'";

                  $rs = fn_ejecuta_query($sqlGetFolioStr);
                  $folio = $rs['root'][0]['folio'];
                  
                  if(isset($folio) && $folio != ""){
                    if ((integer) $folio < 9) {
                        $folio = '0'.(string)((integer)$folio+1);
                  } else {
                      $folio = (string)((integer)$folio+1);
                  }

                  $sqlAddTalonesViajeStr = "INSERT INTO trTalonesViajesTbl (distribuidor,folio,idViajeTractor,companiaRemitente,".
                                                    "idPlazaOrigen,idPlazaDestino,direccionEntrega,centroDistribucion,tipoTalon,".
                                                    "fechaEvento,observaciones,numeroUnidades,importe,seguro,tarifaCobrar,kilometrosCobrar,".
                                                    "impuesto,retencion,claveMovimiento,tipoDocumento) VALUES(".
                                                    "'".$distArr[$nInt]."',".
                                                    "'".$folio."',".
                                                    $_REQUEST['trap446IdViajeHdn'].",".
                                                    "'".$remitenteArr[$nInt]."',".
                                                    "(SELECT dc.idPlaza FROM caDistribuidoresCentrosTbl dc ".
                                                        "WHERE dc.distribuidorCentro = '".$_SESSION['usuCto']."'),".
                                                    "(SELECT dc2.idPlaza FROM caDistribuidoresCentrosTbl dc2 ".
                                                        "WHERE dc2.distribuidorCentro = '".$distArr[$nInt]."'),".
                                                    $direccionArr[$nInt].",".
                                                    "'".$_SESSION['usuCto']."',".
                                                    "'".$_REQUEST['trap446TipoTalonHdn']."',".
                                                    "'".date("Y-m-d")."',".
                                                    "'".$observacionesArr[$nInt]."',".
                                                    $numUnidadesArr[$nInt].",".
                                                    replaceEmptyDec($importeArr[$nInt]).",".
                                                    replaceEmptyDec($seguroArr[$nInt]).",".
                                                    replaceEmptyDec($tarifaCobrarArr[$nInt]).",".
                                                    replaceEmptyDec($kmCobrarArr[$nInt]).",".
                                                    replaceEmptyDec($impuestoArr[$nInt]).",".
                                                    replaceEmptyDec($retencionArr[$nInt]).",".
                                                    "'".$claveMovArr[$nInt]."',".
                                                    "'".$tipoDocumentoArr[$nInt]."')";
                    
                    $rs = fn_ejecuta_query($sqlAddTalonesViajeStr);

                    if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                        $sqlUpdFolioStr = "UPDATE trFoliosTbl ".
                                          "SET folio = '".$folio."' ".
                                          "WHERE centroDistribucion = '".$_SESSION['usuCto']."' ".
                                          "AND compania = '".$_REQUEST['trap446CompaniaHdn']."' ".
                                          "AND tipoDocumento = '".$tipoDocumentoArr[$nInt]."'";

                        $rs = fn_ejecuta_query($sqlUpdFolioStr);
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddTalonesViajeStr;
                        break;
                    }
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlGetFolioStr;
                    break;
                }
              }
          }
          if ($a['success'] == true) {
              $a['successMessage'] = getTalonesViajeUpdMsg();
          }
        } else {
          $a['errorMessage'] = getErrorRequeridos();
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function entregarTalon(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trap455IdViajeHdn'] == ""){
            $e[] = array('id'=>'trap455IdViajeHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap455IdTalonHdn'] == ""){
            $e[] = array('id'=>'trap455IdTalonHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap455CentroDistHdn'] == ""){
            $e[] = array('id'=>'trap455CentroDistHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap455DistribuidorHdn'] == ""){
            $e[] = array('id'=>'trap455DistribuidorHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap455ClaveEntregaViajeHdn'] == ""){
            $e[] = array('id'=>'trap455ClaveEntregaViajeHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        //Se usa para obtener el estatus para el talon
        if($_REQUEST['trap455ColumnaEstatusHdn'] == ""){
            $e[] = array('id'=>'trap455ColumnaEstatusHdn','msg'=>getRequerido());
            $a['success'] = false;
        }

        $vinArr = explode('|', substr($_REQUEST['trap455VinHdn'], 0, -1));
        if(in_array('', $vinArr)){
            $e[] = array('id'=>'trap455VinHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $estatusArr = explode('|', substr($_REQUEST['trap455ClaveEntregaUnidadHdn'], 0, -1));
        if(in_array('', $estatusArr)){
            $e[] = array('id'=>'trap455ClaveEntregaUnidadHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $tarifaArr = explode('|', substr($_REQUEST['trap455TarifaHdn'], 0, -1));
        if(in_array('', $tarifaArr)){
            $e[] = array('id'=>'trap455TarifaHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $localizacionArr = explode('|', substr($_REQUEST['trap455LocalizacionHdn'], 0, -1));
        if(in_array('', $localizacionArr)){
            $e[] = array('id'=>'trap455LocalizacionHdn','msg'=>getRequerido());
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            //Se actualiza el estatus del viaje
            $sqlUpdEstatusViajeStr = "UPDATE trViajesTractoresTbl ".
                                     "SET claveMovimiento = '".$_REQUEST['trap455ClaveEntregaViajeHdn']."' ".
                                     "WHERE idViajeTractor = ".$_REQUEST['trap455IdViajeHdn'];

            $rs = fn_ejecuta_query($sqlUpdEstatusViajeStr);

            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                //Se actualiza el estatus del talon
                $sqlUpdEstatusTalonStr = "UPDATE trTalonesViajesTbl tv ".
                                         "SET tv.claveMovimiento = (SELECT cg.valor FROM caGeneralesTbl cg ".
                                            "WHERE cg.tabla='trTalonesViajesTbl' AND UPPER(cg.columna)='".$_REQUEST['trap455ColumnaEstatusHdn']."') ".
                                         "WHERE tv.idTalon = ".$_REQUEST['trap455IdTalonHdn'];

                $rs = fn_ejecuta_query($sqlUpdEstatusTalonStr);

                if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                    for ($nInt=0; $nInt < sizeof($vinArr); $nInt++) { 
                        $data = addHistoricoUnidad($_REQUEST['trap455CentroDistHdn'],$vinArr[$nInt],$estatusArr[$nInt],
                                            $_REQUEST['trap455DistribuidorHdn'],$tarifaArr[$nInt],
                                            $localizacionArr[$nInt],$_REQUEST['trap455ChoferHdn'],
                                            '',$_SESSION['idUsuario'], $nInt*2);

                        if ($data['success'] == true) {
                            $a['successMessage'] = getEntregaTalonSuccessMsg();
                        } else {
                            $a['success'] = $data['success'];
                            $data['errorMessage'] = $data['errorMessage'];
                        }
                    }
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdEstatusTalonStr;
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdEstatusViajeStr;
            }
        } else {
            $a['errorMessage'] = getErrorRequeridos();
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function addUnidadTalon(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trViajesTractoresNumTalonHdn'] == ""){
            $e[] = array('id'=>'trViajesTractoresNumTalonHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trViajesTractoresIdViajeHdn'] == ""){
            $e[] = array('id'=>'trViajesTractoresIdViajeHdn','msg'=>getRequerido());
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlCleanUnidadesTalonStr = "DELETE FROM trTalonesViajesTmp ".
                                        "WHERE idViajeTractor = ".$_REQUEST['trViajesTractoresIdViajeHdn']." ".
                                        "AND numeroTalon = ".$_REQUEST['trViajesTractoresNumTalonHdn']." ";

            $rs = fn_ejecuta_query($sqlCleanUnidadesTalonStr);

            if ($_REQUEST['trViajesTractoresVinHdn'] != "") {
                $vinArr = explode("|", substr($_REQUEST['trViajesTractoresVinHdn'], 0, -1));
            }

            if (isset($vinArr) && sizeof($vinArr) > 0) {
                for ($nInt=0; $nInt < sizeof($vinArr); $nInt++) { 
                    $sqlAddUnidadTalon = "INSERT INTO trTalonesViajesTmp (idViajeTractor, numeroTalon, vin) ".
                                         "VALUES(".
                                            $_REQUEST['trViajesTractoresIdViajeHdn'].",".
                                            $_REQUEST['trViajesTractoresNumTalonHdn'].",".
                                            "'".$vinArr[$nInt]."'".
                                         ")";

                    $rs = fn_ejecuta_query($sqlAddUnidadTalon);

                    if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                        $a['successMessage'] = getUnidadTalonSuccessMsg();
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddUnidadTalon;
                    }              
                }
            } else {
                $a['successMessage'] = getUnidadTalonDltMsg();
            }
        } else {
            $a['errorMessage'] = getErrorRequeridos();
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updUnidadTalon(){     
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trap446IdTalonHdn'] == ""){
            $e[] = array('id'=>'trap446IdTalonHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $vinArr = explode('|', substr($_REQUEST['trap446VinHdn'], 0, -1));
        if(in_array('', $vinArr)){
            $e[] = array('id'=>'trap446VinHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        $estatusArr = explode('|', substr($_REQUEST['trap446EstatusHdn'], 0, -1));
        if(in_array('', $estatusArr)){
            $e[] = array('id'=>'trap446EstatusHdn','msg'=>getRequerido());
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            for ($nInt=0; $nInt < sizeof($vinArr); $nInt++) { 
                if ($estatusArr[$nInt] == 0) {
                    $sqlAddUnidadTalonStr = "INSERT INTO trUnidadesDetallesTalonesTbl ".
                                            "VALUES(".
                                                $_REQUEST['trap446IdTalonHdn'].",".
                                                "'".$vinArr[$nInt]."', ".
                                                "1)"; //Siempre es 1 cuando se inserta porque entra activa la unidad;

                    $rs = fn_ejecuta_query($sqlAddUnidadTalonStr);

                    if(!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == ""){
                        $a['successMessage'] = getUnidadTalonUpdateMsg();
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddUnidadTalonStr;
                    }
                }
            }
        } else {
            $a['errorMessage'] = getErrorRequeridos();
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function getDetalleTalon(){
        $lsWhereStr = "WHERE tv.idTalon = td.idTalon ".
                      "AND td.vin = u.vin ".
                      "AND u.vin = h.vin ".
                      "AND h.fechaEvento=(".
                      "SELECT MAX(h1.fechaEvento) ".
                      "FROM alHistoricoUnidadesTbl h1 ". 
                      "WHERE h1.vin = u.vin) ".
                      "AND su.simboloUnidad = u.simboloUnidad ".
                      "AND mu.marca = su.marca ".
                      "AND h.idTarifa = tf.idTarifa ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap453IdTalonTxt'], "td.idTalon", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap453FolioTxt'], "tv.folio", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap453VinTxt'], "td.vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trap453EstatusHdn'], "td.estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetDetalleTalonStr = "SELECT td.*, tv.*, tv.fechaEvento AS fechaTalon, h.*, date_format(h.fechaEvento, '%Y-%m-%d') AS fechaUltimoMov, ".
                                 "u.simboloUnidad, tf.tarifa, tf.tipoTarifa, tf.descripcion AS nombreTarifa, mu.marca, ".
                                 "mu.descripcion AS nombreMarca, u.color, ".
                                 "(SELECT co.descripcion FROM caColorUnidadesTbl co WHERE co.color = u.color) AS nombreColor, ".
                                 "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.valor=h.claveMovimiento ".
                                 "AND g.tabla='alHistoricoUnidadesTbl' AND g.columna='claveMovimiento') AS nombreClaveMov, ".
                                 "(SELECT d.descripcionCentro FROM caDistribuidoresCentrosTbl d ".
                                 "WHERE d.distribuidorCentro=h.distribuidor) AS nombreDistribuidor, ".
                                 "(SELECT su.descripcion FROM caSimbolosUnidadesTbl su WHERE su.simboloUnidad = u.simboloUnidad) AS nombreSimbolo ".
                                 "FROM trUnidadesDetallesTalonesTbl td, trTalonesViajesTbl tv, alHistoricoUnidadesTbl h, ".
                                 "alUnidadesTbl u, caTarifasTbl tf, caSimbolosUnidadesTbl su ,caMarcasUnidadesTbl mu ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetDetalleTalonStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['avanzada'] = substr($rs['root'][$iInt]['vin'], 9);
            $rs['root'][$iInt]['descDistribuidor'] = $rs['root'][$iInt]['distribuidor']." - ".$rs['root'][$iInt]['nombreDistribuidor'];
            $rs['root'][$iInt]['descTarifa'] = $rs['root'][$iInt]['tarifa']." - ".$rs['root'][$iInt]['nombreTarifa'];
            $rs['root'][$iInt]['descClaveMov'] = $rs['root'][$iInt]['claveMovimiento']." - ".$rs['root'][$iInt]['nombreClaveMov'];
            $rs['root'][$iInt]['descSimbolo'] = $rs['root'][$iInt]['simboloUnidad']." - ".$rs['root'][$iInt]['nombreSimbolo'];
            $rs['root'][$iInt]['descMarca'] = $rs['root'][$iInt]['marca']." - ".$rs['root'][$iInt]['nombreMarca'];

            //Para la pantalla de Recepcion de Estatus
            $rs['root'][$iInt]['estatusEntrega'] = 'OM';
        }

        echo json_encode($rs);
    }

    function cancelarViaje(){
      $a = array();
      $e = array();
      $a['success'] = true;

      if($_REQUEST['trap481IdViajeHdn'] == ""){
          $e[] = array('id'=>'trap481IdViajeHdn','msg'=>getRequerido());
          $a['success'] = false;
      }
      if($_REQUEST['trap481TipoCancelacionHdn'] == ""){
          $e[] = array('id'=>'trap481TipoCancelacionHdn','msg'=>getRequerido());
          $a['success'] = false;
      }

      if ($a['success'] == true) {
          switch ($_REQUEST['trap481TipoCancelacionHdn']) {
            //Previaje
            case 'VV':
                $sqlCancelarTalonesStr = "UPDATE trTalonesViajesTbl SET claveMovimiento = 'TC' ".
                                         "WHERE idViajeTractor = ".$_REQUEST['trap481IdViajeHdn'];

                $rs = fn_ejecuta_query($sqlCancelarTalonesStr);

                if(!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == ""){
                    $sqlCancelarViajeStr = "UPDATE trViajesTractoresTbl SET claveMovimiento = 'VX' ".
                                           "WHERE idViajeTractor = ".$_REQUEST['trap481IdViajeHdn'];

                    $rs = fn_ejecuta_query($sqlCancelarViajeStr);

                    if(!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == ""){
                        $a['successMessage'] = getViajeCanceladoSuccessMsg($_REQUEST['trap481TipoCancelacionHdn']);
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCancelarViajeStr;
                    }
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCancelarTalonesStr;
                }
                break;
            //Gastos
            case 'VG':
                //Se cancelan los gastos
                $sqlCancelarTalonesStr = "UPDATE trGastosViajeTractorTbl SET claveMovimiento = 'GX' ".
                                         "WHERE idViajeTractor = ".$_REQUEST['trap481IdViajeHdn'];

                $rs = fn_ejecuta_query($sqlCancelarTalonesStr);

                if(!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == ""){
                    $sqlCancelarViajeStr = "UPDATE trViajesTractoresTbl SET claveMovimiento = 'VV' ".
                                           "WHERE idViajeTractor = ".$_REQUEST['trap481IdViajeHdn'];

                    $rs = fn_ejecuta_query($sqlCancelarViajeStr);

                    if(!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == ""){
                        $a['successMessage'] = getViajeCanceladoSuccessMsg($_REQUEST['trap481TipoCancelacionHdn']);
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCancelarViajeStr;
                    }
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCancelarTalonesStr;
                }
                break;
            //Viaje Asignado
            case 'VF':
                $sqlGetTalonesViajeStr = "SELECT idTalon FROM trTalonesViajesTbl ".
                                        "WHERE idViajeTractor = ".$_REQUEST['trap481IdViajeHdn']." AND claveMovimiento != 'TC'";

                $rs = fn_ejecuta_query($sqlGetTalonesViajeStr);

                for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
                    $talonesArr[$nInt] = $rs['root'][$nInt]['idTalon'];
                }

                for ($mInt=0; $mInt < sizeof($talonesArr); $mInt++) { 
                    $sqlGetVinViajeStr = "SELECT vin FROM trUnidadesDetallesTalonesTbl WHERE idTalon = ".$talonesArr[$mInt];

                    $rs = fn_ejecuta_query($sqlGetVinViajeStr);

                    for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
                        $vinArr[$nInt] = $rs['root'][$nInt]['vin'];
                    }
                    for ($nInt=0; $nInt < sizeof($vinArr); $nInt++){
                        if($vinArr[$nInt] != ""){
                            $sqlGetVinDataStr = "SELECT hu.* FROM alHistoricoUnidadesTbl hu ".
                                                "WHERE hu.idHistorico = (SELECT MAX(hu2.idHistorico) ".
                                                  "FROM alHistoricoUnidadesTbl hu2 WHERE hu2.vin = '".$vinArr[$nInt]."') ".
                                                "AND hu.vin = '".$vinArr[$nInt]."'";
                            
                            $rs = fn_ejecuta_query($sqlGetVinDataStr);

                            $data = addHistoricoUnidad($rs['root'][0]['centroDistribucion'],$vinArr[$nInt],'UC',$rs['root'][0]['distribuidor'],$rs['root'][0]['idTarifa'],$rs['root'][0]['localizacionUnidad'],$rs['root'][0]['claveChofer'],'',$_SESSION['usuarioGlobal'], $timeAdd);

                            if ($data['success'] == true) {
                                $data = addHistoricoUnidad($rs['root'][0]['centroDistribucion'],$vinArr[$nInt],'LA',$rs['root'][0]['distribuidor'],$rs['root'][0]['idTarifa'],$rs['root'][0]['localizacionUnidad'],$rs['root'][0]['claveChofer'],'',$_SESSION['usuarioGlobal'], $timeAdd+2);

                                if($data['success'] == true){
                                    //Se cancela la unidad del detalle del talon
                                    $data = cancelarUnidadTalon($talonesArr[$mInt], $vinArr[$nInt]);
                                    if ($data['success'] == true) {
                                        $sqlUpdateTalonStr = "UPDATE trTalonesViajesTbl ".
                                                             "SET claveMovimiento = 'TP', tipoDocumento = 'TP' ".
                                                             "WHERE idViajeTractor = ".$_REQUEST['trap481IdViajeHdn'];

                                        $rs = fn_ejecuta_query($sqlUpdateTalonStr);

                                    } else {
                                        $a['success'] = false;
                                        $a['errorMessage'] = $data['errorMessage'];
                                        $a['errors'] = $data['errors'];
                                        break;
                                    }
                                } else {
                                    $a['success'] = false;
                                    $a['errorMessage'] = $data['errorMessage'];
                                    $a['errors'] = $data['errors'];
                                    break;
                                }              
                            } else {
                                $a['success'] = false;
                                $a['errorMessage'] = $data['errorMessage'];
                                $a['errors'] = $data['errors'];
                                break;
                            }
                            //Para que el siguiente vin tenga 2 segundos más de diferencia respecto al primero
                            $timeAdd += 4;
                        }
                    }
                }
                if(!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == ""){
                    $sqlCancelarViajeStr = "UPDATE trViajesTractoresTbl SET claveMovimiento = 'VG' ".
                                           "WHERE idViajeTractor = ".$_REQUEST['trap481IdViajeHdn'];

                    $rs = fn_ejecuta_query($sqlCancelarViajeStr);

                    if(!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == ""){
                        $a['successMessage'] = getViajeCanceladoSuccessMsg($_REQUEST['trap481TipoCancelacionHdn']);
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCancelarViajeStr;
                    }
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCancelarTalonesStr;
                }
                break;
          }
      } else {
        $a['errorMessage'] = getErrorRequeridos();
      }
      $a['errors'] = $e;
      $a['successTitle'] = getMsgTitulo();
      echo json_encode($a);
    }

    function cancelarTalon(){
        //Cancelacion del talon y sus unidades
        $a = array();
        $e = array();
        $a['success'] = true;
        
        //Unitarios
        if ($_REQUEST['trap446CentroDistHdn'] == "") {
            $e[] = array('id'=>'trap446CentroDistHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['trap446DistribuidorHdn'] == "") {
            $e[] = array('id'=>'trap446DistribuidorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['trap446ChoferHdn'] == "") {
            $e[] = array('id'=>'trap446ChoferHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['trap446IdTalonHdn'] == "") {
            $e[] = array('id'=>'trap446IdTalonHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            //Se cancela el Talón
            $sqlCancelarTalonStr = "UPDATE trTalonesViajesTbl ".
                                   "SET claveMovimiento = 'TC' ".
                                   "WHERE idTalon = ".$_REQUEST['trap446IdTalonHdn'];

            $rs = fn_ejecuta_query($sqlCancelarTalonStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                //Se cancelan las unidades del Talón
                $vinArr = explode('|', substr($_REQUEST['trap446VinHdn'], 0, -1));
                $tarifaArr = explode('|', substr($_REQUEST['trap446TarifaHdn'], 0, -1));
                $localizacionArr = explode('|', substr($_REQUEST['trap446LocalizacionUnidadHdn'], 0, -1));

                $timeAdd = 0;
                for ($nInt=0; $nInt < sizeof($vinArr); $nInt++){
                    if($vinArr[$nInt] != ""){
                        $data = addHistoricoUnidad($_REQUEST['trap446CentroDistHdn'],$vinArr[$nInt],'UC',$_REQUEST['trap446DistribuidorHdn'],$tarifaArr[$nInt],$localizacionArr[$nInt],$_REQUEST['trap446ChoferHdn'],'',$_SESSION['usuarioGlobal'], $timeAdd);

                        if ($data['success'] == true) {
                            $data = addHistoricoUnidad($_REQUEST['trap446CentroDistHdn'],$vinArr[$nInt],'LA',$_REQUEST['trap446DistribuidorHdn'],$tarifaArr[$nInt],$localizacionArr[$nInt],$_REQUEST['trap446ChoferHdn'],'',$_SESSION['usuarioGlobal'], $timeAdd+2);

                            if($data['success'] == true){
                                //Se cancela la unidad del detalle del talon
                                $data = cancelarUnidadTalon($_REQUEST['trap446IdTalonHdn'], $vinArr[$nInt]);
                                if ($data['success'] == true) {
                                    $a['successMessage'] = getTalonCanceladoSuccessMsg();
                                } else {
                                    $a['success'] = false;
                                    $a['errorMessage'] = $data['errorMessage'];
                                    $a['errors'] = $data['errors'];
                                    break;
                                }
                            } else {
                                $a['success'] = false;
                                $a['errorMessage'] = $data['errorMessage'];
                                $a['errors'] = $data['errors'];
                                break;
                            }              
                        } else {
                            $a['success'] = false;
                            $a['errorMessage'] = $data['errorMessage'];
                            $a['errors'] = $data['errors'];
                            break;
                        }
                        //Para que el siguiente vin tenga 2 segundos más de diferencia respecto al primero
                        $timeAdd += 4;
                    }
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCancelarTalonStr;
            }
        } else {
            $a['errorMessage'] = getErrorRequeridos();
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function cancelarUnidadTalon($idTalon, $vin){
        //Cancelacion unitaria de unidad en un talón
        $a = array();
        $e = array();
        $a['success'] = true;

        if($idTalon == ""){
            $e[] = array('id'=>'trap446IdTalonHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($vin == ""){
            $e[] = array('id'=>'trap446VinHdn','msg'=>getRequerido());
            $a['success'] = false;
        }

        if($a['success'] == true){
            $sqlCancelarUnidadTalonStr = "UPDATE trUnidadesDetallesTalonesTbl ".
                                         "SET estatus = 0 ".
                                         "WHERE idTalon = ".$idTalon." ".
                                         "AND vin = '".$vin."'";

            $rs = fn_ejecuta_query($sqlCancelarUnidadTalonStr);

            if(!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == ""){
                $a['successMessage'] = getCancelarUnidadSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCancelarUnidadTalonStr;
            }
        } else {
            $a['errorMessage'] = getErrorRequeridos();
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        return $a;
    }

    function comprobacionViaje(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['trap460IdViajeHdn'] == ""){
            $e[] = array('id'=>'trap460IdViajeHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap460ConceptoHdn'] == ""){
            $e[] = array('id'=>'trap460ConceptoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap460CompaniaTractorHdn'] == ""){
            $e[] = array('id'=>'trap460CompaniaTractorHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap460ImporteHdn'] == ""){
            $e[] = array('id'=>'trap460ImporteHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap460ObservacionesHdn'] == ""){
            $e[] = array('id'=>'trap460ObservacionesHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap460ClaveMovimientoHdn'] == ""){
            $e[] = array('id'=>'trap460ClaveMovimientoHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap460CuentaContableHdn'] == ""){
            $e[] = array('id'=>'trap460CuentaContableHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap460UsuarioAsignarHdn'] == ""){
            $e[] = array('id'=>'trap460UsuarioAsignarHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['trap460FolioHdn'] == ""){
            $e[] = array('id'=>'trap460FolioHdn','msg'=>getRequerido());
            $a['success'] = false;
        }

        if ($a['success'] ==  true) {
            $sqlAddGastoStr = "INSERT INTO trGastosViajeTractorTbl ".
                              "(idViajeTractor,concepto,centroDistribucion,folio,fechaEvento,cuentaContable,".
                                "mesAfectacion,importe,observaciones,claveMovimiento,usuario,ip) ".
                              "VALUES (".
                                $_REQUEST['trap460IdViajeHdn'].",".
                                "'".$_REQUEST['trap460ConceptoHdn']."',".
                                "'".$_SESSION['usuCto']."',".
                                "'".$_REQUEST['trap460FolioHdn']."',".
                                "'".date("Y-m-d H:i:s")."',".
                                "'".$_REQUEST['trap460CuentaContableHdn']."',".
                                "'0',".
                                $_REQUEST['trap460ImporteHdn'].",".
                                "'".$_REQUEST['trap460ObservacionesHdn']."',".
                                "'".$_REQUEST['trap460ClaveMovimientoHdn']."',".
                                $_REQUEST['trap460UsuarioAsignarHdn'].",".
                                "'".$_SERVER['REMOTE_ADDR']."')";

            $rs = fn_ejecuta_query($sqlAddGastoStr);

            if(!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == ""){
                $sqlUpdEstatusViajeStr = "UPDATE trViajesTractoresTbl ".
                                         "SET claveMovimiento = '".$_REQUEST['trap460ClaveMovimientoHdn']."' ".
                                         "WHERE idViajeTractor = ".$_REQUEST['trap460IdViajeHdn'];

                $rs = fn_ejecuta_query($sqlUpdEstatusViajeStr);

                if(!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == ""){
                    $a['successMessage'] = getComprobacionViajeSuccessMsg();
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdEstatusViajeStr;
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddGastoStr;
            }                
        } else {
          $a['errorMessage'] = getErrorRequeridos();
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

?>