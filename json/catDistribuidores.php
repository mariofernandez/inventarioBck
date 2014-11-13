<?php
    /************************************************************************
    * Autor: Alfonso César Martínez Fuertes
    * Fecha: 09-Enero-2014
    * Tablas afectadas: caDistribuidoresCentrosTbl
    * Descripción: Programa para dar mantenimiento a los Distribuidores
    *************************************************************************/
    session_start();
    $_SESSION['modulo'] = "catDistribuidores";
    //SESION PRUEBA
    $_SESSION['usuCto'] = 'CDTOL';
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");
    require_once("catDirecciones.php");

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

        switch($_REQUEST['catDistCentroActionHdn']){
        case 'getDistribuidores':
            getDistribuidores();
            break;
        case 'getDistribuidoresKilometros':
            getDistribuidoresKilometros();
            break;
        case 'getSucursalCombo':
            getSucursalCombo();
            break;
        case 'getDireccionDistViajes':
            getDireccionDistViajes();
            break;
        case 'addDistribuidor':
        	addDistribuidor();
            break;
        case 'updDistribuidor':
            updDistribuidor();                                                   
            break;
        case 'addDistribuidorEspecial':
            addDistribuidorEspecial();
            break;
        case 'updDistribuidorEspecial':
            updDistribuidorEspecial();                                                   
            break;
        default:
            echo '';
    }

   	function getDistribuidores(){
		$lsWhereStr = "" ; 

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroTipoHdn'], "tipoDistribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catDistCentroDistribuidorTxt'], "distribuidorCentro", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroDescripcionTxt'], "descripcionCentro", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroPlazaHdn'], "idPlaza", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroObservacionesTxa'], "observaciones", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroTelefonoTxt'], "telefono", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroFaxTxt'], "fax", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroContactoTxt'], "contacto", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroEmailTxt'], "eMail", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroRutaDestinoTxt'], "rutaDestino", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroSucursalDeHdn'], "sucursalDe", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }    
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroSueldoTxt'], "sueldoGarantizado", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroIdRegionHdn'], "idRegion", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroEstatusHdn'], "estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

		$sqlGetDistribuidoresStr = "SELECT * FROM caDistribuidoresCentrosTbl ".$lsWhereStr;

		$rs = fn_ejecuta_query($sqlGetDistribuidoresStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['distDesc'] = $rs['root'][$iInt]['distribuidorCentro']." - ".$rs['root'][$iInt]['descripcionCentro'];
        }
			
		echo json_encode($rs);
    }

    function getDistribuidoresKilometros(){
        $lsWhereStr = "WHERE (SELECT dc2.idPlaza FROM caDistribuidoresCentrosTbl dc2 ".
                      "WHERE distribuidorCentro='".$_SESSION['usuCto']."') = kp.idPlazaOrigen ".
                      "AND dc.idPlaza = kp.idPlazaDestino ".
                      "AND d.idColonia = c.idColonia ".
                      "AND c.idMunicipio = m.idMunicipio ".
                      "AND m.idEstado = e.idEstado ".
                      "AND e.idPais = p.idPais ".
                      "AND d.direccion = dc.direccionEntrega ".
                      "AND pl.idPlaza = dc.idPlaza ".
                      "AND dc.tipoDistribuidor = 'DI'"; 

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroTipoHdn'], "dc.tipoDistribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroDistribuidorTxt'], "dc.distribuidorCentro", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroDescripcionTxt'], "dc.descripcionCentro", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroPlazaHdn'], "dc.idPlaza", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroObservacionesTxa'], "dc.observaciones", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroTelefonoTxt'], "dc.telefono", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroFaxTxt'], "dc.fax", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroContactoTxt'], "dc.contacto", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroEmailTxt'], "dc.eMail", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroRutaDestinoTxt'], "dc.rutaDestino", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroSucursalDeHdn'], "dc.sucursalDe", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }    
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroSueldoTxt'], "dc.sueldoGarantizado", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroIdRegionHdn'], "dc.idRegion", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroEstatusHdn'], "dc.estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetDistKmStr = "SELECT dc.distribuidorCentro, dc.descripcionCentro,dc.idPlaza, dc.direccionEntrega, dc.idPlaza,".
                           "d.calleNumero, c.colonia, c.cp, m.municipio, e.estado, p.pais, kp.kilometros, pl.plaza ".
                           "FROM caDistribuidoresCentrosTbl dc, caKilometrosPlazaTbl kp, caDireccionesTbl d, caColoniasTbl c,".
                           "caMunicipiosTbl m, caEstadosTbl e, caPaisesTbl p, caPlazasTbl pl ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetDistKmStr);
        
        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['direccionCompleta'] = $rs['root'][$iInt]['calleNumero'].", ".
                                                      $rs['root'][$iInt]['colonia'].", ".
                                                      $rs['root'][$iInt]['municipio'].", ".
                                                      $rs['root'][$iInt]['estado'].", ".
                                                      $rs['root'][$iInt]['pais'].", ".
                                                      $rs['root'][$iInt]['cp'];

            $rs['root'][$iInt]['distDesc'] = $rs['root'][$iInt]['distribuidorCentro']." - ".$rs['root'][$iInt]['descripcionCentro'];
        }
            
        echo json_encode($rs);
    }

   	function getSucursalCombo(){

		$sqlGetSucursalComboStr = "SELECT distribuidorCentro, descripcionCentro " .
                    		      "FROM caDistribuidoresCentrosTbl  ".
                                  "WHERE sucursalDe is NULL ".
                                  "AND tipoDistribuidor = 'DI' ".
                                  "AND distribuidorCentro != '".$_REQUEST['catDistribuidoresDistribuidorTxt']."'";       
		
		$rs = fn_ejecuta_query($sqlGetSucursalComboStr);

		for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descDistribuidor'] = $rs['root'][$iInt]['distribuidorCentro']." - ".$rs['root'][$iInt]['descripcionCentro'];
        }
        

		echo json_encode($rs);		
    }

    function getDireccionDistViajes(){
        $lsWhereStr = "WHERE d.idColonia = c.idColonia ".
                      "AND c.idMunicipio = m.idMunicipio ".
                      "AND m.idEstado = e.idEstado ".
                      "AND e.idPais = p.idPais ".
                      "AND pl.idPlaza = dc.idPlaza ".
                      "AND d.direccion = dc.direccionEntrega "; 

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['trViajesTractorDistribuidorHdn'], "dc.distribuidorCentro", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetDireccionDistViajesStr = "SELECT dc.distribuidorCentro, dc.descripcionCentro, dc.direccionEntrega, dc.idPlaza, ".
                                        "d.calleNumero, c.colonia, c.cp, m.municipio, e.estado, p.pais, pl.plaza ".
                                        "FROM caDistribuidoresCentrosTbl dc, caDireccionesTbl d, caColoniasTbl c, ".
                                        "caMunicipiosTbl m, caEstadosTbl e, caPaisesTbl p, caPlazasTbl pl ".$lsWhereStr;
             
        $rs = fn_ejecuta_query($sqlGetDireccionDistViajesStr);
        
        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['direccionCompleta'] = $rs['root'][$iInt]['calleNumero'].", ".
                                                      $rs['root'][$iInt]['colonia'].", ".
                                                      $rs['root'][$iInt]['municipio'].", ".
                                                      $rs['root'][$iInt]['estado'].", ".
                                                      $rs['root'][$iInt]['pais'].", ".
                                                      $rs['root'][$iInt]['cp'];
        }
            
        echo json_encode($rs);
    }

    function addDistribuidor(){
    	$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catDistribuidoresDistribuidorTxt'] == ""){
            $e[] = array('id'=>'catDistribuidoresDistribuidorTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDescripcionTxt'] == ""){
            $e[] = array('id'=>'catDistribuidoresDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistCentroTipoHdn'] == ""){
            $e[] = array('id'=>'catDistCentroTipoHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresPlazaHdn'] == ""){
            $e[] = array('id'=>'catDistribuidoresPlazaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDirFiscalHdn'] == ""){
            $e[] = array('id'=>'catDistribuidoresDirFiscalHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDirEntregaHdn'] == ""){
            $e[] = array('id'=>'catDistribuidoresDirEntregaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresEstatusHdn'] == ""){
            $e[] = array('id'=>'catDistribuidoresEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){
        	$sqlAddDistribuidorStr = "INSERT INTO caDistribuidoresCentrosTbl ".
        						  "VALUES (".
        						  "'".$_REQUEST['catDistribuidoresDistribuidorTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresDescripcionTxt']."', ".
        						  "'".$_REQUEST['catDistCentroTipoHdn']."', ".
        						  $_REQUEST['catDistribuidoresPlazaHdn'].", ".
        						  "'".$_REQUEST['catDistribuidoresObservacionesTxa']."', ".
        						  "'".$_REQUEST['catDistribuidoresTelefonoTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresFaxTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresContactoTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresEmailTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresRutaDestinoTxt']."', ".
        						  replaceEmptyNull("'".$_REQUEST['catDistribuidoresSucursalDeHdn']."'"). ", ".
								  $_REQUEST['catDistribuidoresDirFiscalHdn'].", ".
								  $_REQUEST['catDistribuidoresDirEntregaHdn'].", ".
								  replaceEmptyNull($_REQUEST['catDistribuidoresSueldoTxt']).", ".
								  replaceEmptyNull($_REQUEST['catDistribuidoresIdRegionHdn']).", ".
								  "'".$_REQUEST['catDistribuidoresEstatusHdn']."', ".
                                  "NULL,".
                                  $_REQUEST['catDistribuidoresDetieneUnidadesHdn'].")";

			$rs = fn_ejecuta_query($sqlAddDistribuidorStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddDistribuidorStr;
            	$a['successMessage'] = getDistribuidoresSuccessMsg();
			} else {
            	$a['success'] = false;
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddDistribuidorStr;
        	}
		}
        //Si se inserta correctamente, se agrega el id a las direcciones correspondientes
        if ($a['success'] == true) {

             //UPDATE a campos NULL de las direcciones por el distribuidor ya creado
                $sqlUpdDireccionesNULLStr = "UPDATE cadireccionestbl ".
                                            "SET distribuidor= '".$_REQUEST['catDistribuidoresDistribuidorTxt']."' ".
                                            "WHERE direccion=".$_REQUEST['catDistribuidoresDirFiscalHdn']." ".
                                            "AND direccion=".$_REQUEST['catDistribuidoresDirEntregaHdn'];
                
                $rs = fn_ejecuta_query($sqlUpdDireccionesNULLStr);

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $a['sql'] = $sqlUpdDireccionesNULLStr;
                } else {
                    $a['success'] = false;
                }
        }

		$a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updDistribuidor() {
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catDistribuidoresDistribuidorTxt'] == ""){
            $e[] = array('id'=>'catDistribuidoresDistribuidorTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDescripcionTxt'] == ""){
            $e[] = array('id'=>'catDistribuidoresDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistCentroTipoHdn'] == ""){
            $e[] = array('id'=>'catDistCentroTipoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresPlazaHdn'] == ""){
            $e[] = array('id'=>'catDistribuidoresPlazaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDirFiscalHdn'] == ""){
            $e[] = array('id'=>'catDistribuidoresDirFiscalHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDirEntregaHdn'] == ""){
            $e[] = array('id'=>'catDistribuidoresDirEntregaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresEstatusHdn'] == ""){
            $e[] = array('id'=>'catDistribuidoresEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdDistribuidoresStr = "UPDATE caDistribuidoresCentrosTbl ".
                                       "SET descripcionCentro= '".$_REQUEST['catDistribuidoresDescripcionTxt']."', ".
                                       "tipoDistribuidor= '".$_REQUEST['catDistCentroTipoHdn']."', ".
                                       "idPlaza=".$_REQUEST['catDistribuidoresPlazaHdn'].", ".
                                       "observaciones= '".$_REQUEST['catDistribuidoresObservacionesTxa']."', ".
                                       "telefono= '".$_REQUEST['catDistribuidoresTelefonoTxt']."', ".
                                       "fax= '".$_REQUEST['catDistribuidoresFaxTxt']."', ".
                                       "contacto= '".$_REQUEST['catDistribuidoresContactoTxt']."', ".
                                       "email= '".$_REQUEST['catDistribuidoresEmailTxt']."', ".
                                       "rutaDestino= '".$_REQUEST['catDistribuidoresRutaDestinoTxt']."', ".
                                       "sucursalDe=".replaceEmptyNull("'".$_REQUEST['catDistribuidoresSucursalDeHdn']."'").", ".
                                       "direccionFiscal=".$_REQUEST['catDistribuidoresDirFiscalHdn'].", ".
                                       "direccionEntrega=".$_REQUEST['catDistribuidoresDirEntregaHdn'].", ".
                                       "sueldoGarantizado= ".replaceEmptyDec($_REQUEST['catDistribuidoresSueldoTxt']).", ".
                                       "idRegion=".replaceEmptyNull($_REQUEST['catDistribuidoresIdRegionHdn']).", ".
                                       "estatus= '".$_REQUEST['catDistribuidoresEstatusHdn']."', ".
                                       "tieneRepuve= NULL, ".
                                       "detieneUnidades= ".$_REQUEST['catDistribuidoresDetieneUnidadesHdn']." ".
                                       "WHERE distribuidorCentro= '".$_REQUEST['catDistribuidoresDistribuidorTxt']."'";
            
            $rs = fn_ejecuta_query($sqlUpdDistribuidoresStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdDistribuidoresStr;
                $a['successMessage'] = getDistribuidoresUpdateMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdDistribuidoresStr;
            }
        }

        if ($a['success'] == true) {

            $calleNumeroArr = explode('|', substr($_REQUEST['catDireccionesCalleNumeroHdn'], 0, -1));
            $coloniaArr = explode('|', substr($_REQUEST['catDireccionesIdColoniaHdn'], 0, -1));
            $distArr = explode('|', substr($_REQUEST['catDireccionesDistribuidorHdn'], 0, -1));
            $direccionArr = explode('|', substr($_REQUEST['catDireccionesDireccionHdn'], 0, -1));
            
            for($nInt=0; $nInt<count($calleNumeroArr); $nInt++){

                if($distArr[$nInt] == NULL){
                    //UPDATE a campos NULL de las direcciones por el distribuidor ya creado
                    $sqlUpdDireccionesNULLStr = "UPDATE cadireccionestbl ".
                                                "SET distribuidor= '".$_REQUEST['catDistribuidoresDistribuidorTxt']."' ".
                                                "WHERE direccion=".$direccionArr[$nInt];

                    $rs = fn_ejecuta_query($sqlUpdDireccionesNULLStr);

                    if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                        $a['sql'] = $sqlUpdDireccionesNULLStr;
                    } else {
                        $a['successMessage'] =getDistribuidoresUpdateNoDirectionUpdMsg();
                    }
                }
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    //DESTINOS ESPECIALES, CENTROS DE DISTRIBUCION y PATIOS
    function addDistribuidorEspecial(){
        
        switch($_REQUEST['catDistCentroTipoHdn']){
            case 'DE':
                $RQdist = $_REQUEST['catDestinosEspecialesDistribuidorTxt'];
                $RQdesc = $_REQUEST['catDestinosEspecialesDescripcionTxt'];
                $RQtipo = $_REQUEST['catDistCentroTipoHdn'];
                $RQplaza = $_REQUEST['catDestinosEspecialesPlazaHdn'];
                $RQobserv = $_REQUEST['catDestinosEspecialesObservacionesTxa'];
                $RQtel = $_REQUEST['catDestinosEspecialesTelefonoTxt'];
                $RQfax = $_REQUEST['catDestinosEspecialesFaxTxt'];
                $RQcontacto = $_REQUEST['catDestinosEspecialesContactoTxt'];
                $RQemail = $_REQUEST['catDestinosEspecialesEmailTxt'];
                $RQdestino = $_REQUEST['catDestinosEspecialesRutaDestinoTxt'];
                $RQsueldo = $_REQUEST['catDestinosEspecialesSueldoTxt'];
                $RQregion = $_REQUEST['catDestinosEspecialesIdRegionHdn'];
                $RQestatus = $_REQUEST['catDestinosEspecialesEstatusHdn'];
                $RQrepuve = "0";
                $RQdetiene = $_REQUEST['catDestinosEspecialesDetieneUnidadesHdn'];
                $RQcalleNum = $_REQUEST['catDestinosEspecialesCalleNumeroTxt'];
                $RQcolonia = $_REQUEST['catDestinosEspecialesIdColoniaHdn'];
                $RQtipoDir = $_REQUEST['catDistCentroTipoDireccionHdn'];
                break;
            case 'CD':
                $RQdist = $_REQUEST['catCentroDistribucionDistribuidorTxt'];
                $RQdesc = $_REQUEST['catCentroDistribucionDescripcionTxt'];
                $RQtipo = $_REQUEST['catDistCentroTipoHdn'];
                $RQplaza = $_REQUEST['catCentroDistribucionPlazaHdn'];
                $RQobserv = $_REQUEST['catCentroDistribucionObservacionesTxa'];
                $RQtel = $_REQUEST['catCentroDistribucionTelefonoTxt'];
                $RQfax = $_REQUEST['catCentroDistribucionFaxTxt'];
                $RQcontacto = $_REQUEST['catCentroDistribucionContactoTxt'];
                $RQemail = $_REQUEST['catCentroDistribucionEmailTxt'];
                $RQdestino = $_REQUEST['catCentroDistribucionRutaDestinoTxt'];
                $RQsueldo = $_REQUEST['catCentroDistribucionSueldoTxt'];
                $RQregion = $_REQUEST['catCentroDistribucionIdRegionHdn'];
                $RQestatus = $_REQUEST['catCentroDistribucionEstatusHdn'];
                $RQrepuve = $_REQUEST['catCentroDistribucionRepuveHdn'];
                $RQdetiene = $_REQUEST['catCentroDistribucionDetieneUnidadesHdn'];
                $RQcalleNum = $_REQUEST['catCentroDistribucionCalleNumeroTxt'];
                $RQcolonia = $_REQUEST['catCentroDistribucionIdColoniaHdn'];
                $RQtipoDir = $_REQUEST['catDistCentroTipoDireccionHdn'];
                break;
            case 'PA':
                $RQdist = $_REQUEST['catPatiosDistribuidorTxt'];
                $RQdesc = $_REQUEST['catPatiosDescripcionTxt'];
                $RQtipo = $_REQUEST['catDistCentroTipoHdn'];
                $RQplaza = $_REQUEST['catPatiosPlazaHdn'];
                $RQobserv = $_REQUEST['catPatiosObservacionesTxa'];
                $RQtel = $_REQUEST['catPatiosTelefonoTxt'];
                $RQfax = $_REQUEST['catPatiosFaxTxt'];
                $RQcontacto = $_REQUEST['catPatiosContactoTxt'];
                $RQemail = $_REQUEST['catPatiosEmailTxt'];
                $RQdestino = $_REQUEST['catPatiosRutaDestinoTxt'];
                $RQsueldo = $_REQUEST['catPatiosSueldoTxt'];
                $RQregion = $_REQUEST['catPatiosIdRegionHdn'];
                $RQestatus = $_REQUEST['catPatiosEstatusHdn'];
                $RQrepuve = "0";
                $RQdetiene = $_REQUEST['catPatiosDetieneUnidadesHdn'];
                $RQcalleNum = $_REQUEST['catPatiosCalleNumeroTxt'];
                $RQcolonia = $_REQUEST['catPatiosIdColoniaHdn'];
                $RQtipoDir = $_REQUEST['catDistCentroTipoDireccionHdn'];
                break;
        }

        $a = array();
        $e = array();
        $a['success'] = true;

        if($RQdist == ""){
            $e[] = array('id'=>'DistribuidorTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQdesc == ""){
            $e[] = array('id'=>'DescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQtipo == ""){
            $e[] = array('id'=>'TipoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQplaza == ""){
            $e[] = array('id'=>'PlazaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQestatus == ""){
            $e[] = array('id'=>'EstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQrepuve == ""){
            $e[] = array('id'=>'RepuveHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQcalleNum == ""){
            $e[] = array('id'=>'CalleNumeroTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQcolonia == ""){
            $e[] = array('id'=>'IdColoniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQtipoDir == ""){
            $e[] = array('id'=>'TipoDireccionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }       

        if($a['success'] == true){
            //Se guarda primero la dirección
            addDirecciones($RQcalleNum, $RQcolonia, NULL, $RQtipoDir);

            $sqlGetIdDirStr = "SELECT direccion FROM cadireccionestbl ".
                              "WHERE calleNumero='".$RQcalleNum."' ".
                              "AND idColonia=".$RQcolonia." ".
                              "AND distribuidor IS NULL ".
                              "AND tipoDireccion='".$RQtipoDir."'";

            $rs = fn_ejecuta_query($sqlGetIdDirStr);
            $idDireccion = $rs['root'][0]['direccion'];
        }  

        if ($a['success'] == true && $idDireccion != false){
            $sqlAddDistribuidorEspecialStr = "INSERT INTO caDistribuidoresCentrosTbl ".
                                        "VALUES (".
                                        "'".$RQdist."', ".
                                        "'".$RQdesc."', ".
                                        "'".$RQtipo."', ".
                                        $RQplaza.", ".
                                        "'".$RQobserv."', ".
                                        "'".$RQtel."', ".
                                        "'".$RQfax."', ".
                                        "'".$RQcontacto."', ".
                                        "'".$RQemail."', ".
                                        "'".$RQdestino."', ".
                                        "NULL, ". //No tiene sucursal
                                        $idDireccion.", ".
                                        $idDireccion.", ".
                                        replaceEmptyDec($RQsueldo).", ".
                                        replaceEmptyNull($RQregion).", ".
                                        "'".$RQestatus."', ".
                                        $RQrepuve.",".
                                        $RQdetiene.")";
            
            $rs = fn_ejecuta_query($sqlAddDistribuidorEspecialStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddDistribuidorEspecialStr;
                $a['successMessage'] = getDistEspecialSuccessMsg($RQtipo);

                //Finalmente se agrega el distribuidor a la dirección
                updDirecciones($idDireccion, $RQcalleNum, $RQcolonia, $RQdist, $RQtipoDir);

            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddDistribuidorEspecialStr;
                
                //Si el distribuidor no se crea, se borra la dirección asociada
                dltDireccion($idDireccion);
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updDistribuidorEspecial(){
        $a = array();
        $e = array();
        $a['success'] = true;

        switch($_REQUEST['catDistCentroTipoHdn']){
            case 'DE':
                $RQdist = $_REQUEST['catDestinosEspecialesDistribuidorTxt'];
                $RQdesc = $_REQUEST['catDestinosEspecialesDescripcionTxt'];
                $RQtipo = $_REQUEST['catDistCentroTipoHdn'];
                $RQplaza = $_REQUEST['catDestinosEspecialesPlazaHdn'];
                $RQobserv = $_REQUEST['catDestinosEspecialesObservacionesTxa'];
                $RQtel = $_REQUEST['catDestinosEspecialesTelefonoTxt'];
                $RQfax = $_REQUEST['catDestinosEspecialesFaxTxt'];
                $RQcontacto = $_REQUEST['catDestinosEspecialesContactoTxt'];
                $RQemail = $_REQUEST['catDestinosEspecialesEmailTxt'];
                $RQdestino = $_REQUEST['catDestinosEspecialesRutaDestinoTxt'];
                $RQsueldo = $_REQUEST['catDestinosEspecialesSueldoTxt'];
                $RQregion = $_REQUEST['catDestinosEspecialesIdRegionHdn'];
                $RQestatus = $_REQUEST['catDestinosEspecialesEstatusHdn'];
                $RQrepuve = "0";
                $RQdetiene = $_REQUEST['catDestinosEspecialesDetieneUnidadesHdn'];
                $RQdireccion = $_REQUEST['catDistCentroDireccionHdn'];
                $RQcalleNum = $_REQUEST['catDestinosEspecialesCalleNumeroTxt'];
                $RQcolonia = $_REQUEST['catDestinosEspecialesIdColoniaHdn'];
                $RQtipoDir = $_REQUEST['catDistCentroTipoDireccionHdn'];
                break;
            case 'CD':
                $RQdist = $_REQUEST['catCentroDistribucionDistribuidorTxt'];
                $RQdesc = $_REQUEST['catCentroDistribucionDescripcionTxt'];
                $RQtipo = $_REQUEST['catDistCentroTipoHdn'];
                $RQplaza = $_REQUEST['catCentroDistribucionPlazaHdn'];
                $RQobserv = $_REQUEST['catCentroDistribucionObservacionesTxa'];
                $RQtel = $_REQUEST['catCentroDistribucionTelefonoTxt'];
                $RQfax = $_REQUEST['catCentroDistribucionFaxTxt'];
                $RQcontacto = $_REQUEST['catCentroDistribucionContactoTxt'];
                $RQemail = $_REQUEST['catCentroDistribucionEmailTxt'];
                $RQdestino = $_REQUEST['catCentroDistribucionRutaDestinoTxt'];
                $RQsueldo = $_REQUEST['catCentroDistribucionSueldoTxt'];
                $RQregion = $_REQUEST['catCentroDistribucionIdRegionHdn'];
                $RQestatus = $_REQUEST['catCentroDistribucionEstatusHdn'];
                $RQrepuve = $_REQUEST['catCentroDistribucionRepuveHdn'];
                $RQdetiene = $_REQUEST['catCentroDistribucionDetieneUnidadesHdn'];
                $RQdireccion = $_REQUEST['catDistCentroDireccionHdn'];
                $RQcalleNum = $_REQUEST['catCentroDistribucionCalleNumeroTxt'];
                $RQcolonia = $_REQUEST['catCentroDistribucionIdColoniaHdn'];
                $RQtipoDir = $_REQUEST['catDistCentroTipoDireccionHdn'];
                break;
            case 'PA':
                $RQdist = $_REQUEST['catPatiosDistribuidorTxt'];
                $RQdesc = $_REQUEST['catPatiosDescripcionTxt'];
                $RQtipo = $_REQUEST['catDistCentroTipoHdn'];
                $RQplaza = $_REQUEST['catPatiosPlazaHdn'];
                $RQobserv = $_REQUEST['catPatiosObservacionesTxa'];
                $RQtel = $_REQUEST['catPatiosTelefonoTxt'];
                $RQfax = $_REQUEST['catPatiosFaxTxt'];
                $RQcontacto = $_REQUEST['catPatiosContactoTxt'];
                $RQemail = $_REQUEST['catPatiosEmailTxt'];
                $RQdestino = $_REQUEST['catPatiosRutaDestinoTxt'];
                $RQsueldo = $_REQUEST['catPatiosSueldoTxt'];
                $RQregion = $_REQUEST['catPatiosIdRegionHdn'];
                $RQestatus = $_REQUEST['catPatiosEstatusHdn'];
                $RQrepuve = "0";
                $RQdetiene = $_REQUEST['catPatiosDetieneUnidadesHdn'];
                $RQdireccion = $_REQUEST['catDistCentroDireccionHdn'];
                $RQcalleNum = $_REQUEST['catPatiosCalleNumeroTxt'];
                $RQcolonia = $_REQUEST['catPatiosIdColoniaHdn'];
                $RQtipoDir = $_REQUEST['catDistCentroTipoDireccionHdn'];
                break;
        }

        if($RQdireccion == ""){
            $e[] = array('id'=>'DireccionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQdist == ""){
            $e[] = array('id'=>'DistribuidorTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQdesc == ""){
            $e[] = array('id'=>'DescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQtipo == ""){
            $e[] = array('id'=>'TipoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQplaza == ""){
            $e[] = array('id'=>'PlazaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQestatus == ""){
            $e[] = array('id'=>'EstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQrepuve == ""){
            $e[] = array('id'=>'RepuveHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQcalleNum == ""){
            $e[] = array('id'=>'CalleNumero','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQcolonia == ""){
            $e[] = array('id'=>'IdColoniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($RQtipoDir == ""){
            $e[] = array('id'=>'TipoDireccionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }   

        if ($a['success'] == true){
            $sqlUpdDestinoEspecialStr = "UPDATE caDistribuidoresCentrosTbl ".
                                        "SET descripcionCentro='".$RQdesc."',".
                                        "tipoDistribuidor='".$RQtipo."', ".
                                        "idPlaza=".$RQplaza.", ".
                                        "observaciones='".$RQobserv."', ".
                                        "telefono='".$RQtel."', ".
                                        "fax='".$RQfax."', ".
                                        "contacto='".$RQcontacto."', ".
                                        "eMail='".$RQemail."', ".
                                        "rutaDestino='".$RQdestino."', ".
                                        "NULL, ". //No tiene sucursal
                                        "direccionFiscal=".$RQdireccion.", ".
                                        "direccionEntrega=".$RQdireccion.", ".
                                        "sueldoGarantizado=".replaceEmptyDec($RQsueldo).", ".
                                        "idRegion=".replaceEmptyNull("'".$RQregion."'").",".
                                        "estatus='".$RQestatus."', ".
                                        "tieneRepuve=".$RQrepuve.", ".
                                        "detieneUnidades= ".$RQdetiene." ".
                                        "WHERE distribuidorCentro='".$RQdist."'";

            $rs = fn_ejecuta_query($sqlUpdDestinoEspecialStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdDestinoEspecialStr;
                $a['successMessage'] = getDistEspecialUpdMsg($RQtipo);
                //UPDATE DE LA DIRECCION
                updDirecciones($RQdireccion ,$RQcalleNum, $RQcolonia, $RQdist, $RQtipoDir);

            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdDestinoEspecialStr;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>