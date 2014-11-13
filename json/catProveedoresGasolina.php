<?php
	session_start();
	$_SESSION['modulo'] = "catProveedoresGasolina";
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
	
    switch($_REQUEST['catProveedorGasolinaActionHdn']) {
        case 'getProveedores':
            getProveedores();
            break;
		case 'addProveedor':
			addProveedor();
            break;
		case 'updProveedor':
			updProveedor();
            break;
        default:
            echo '';
    }

    function getProveedores(){
        $lsWhereStr = "WHERE pg.direccionGasolinera = d.direccion ".
                      "AND c.idColonia = d.idColonia ".
                      "AND m.idMunicipio = c.idMunicipio ".
                      "AND e.idEstado = m.idEstado ".
                      "AND p.idPais = e.idPais";


        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaRfcTxt'], "pg.rfc", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaNumeroEstacionTxt'], "pg.numeroEstacion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaNombreTxt'], "pg.nombre", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaDireccionHdn'], "pg.direccionGasolinera", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaContactoTxt'], "pg.contacto", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaTelefonoTxt'], "pg.telefono", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaFaxTxt'], "pg.fax", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaEmailTxt'], "pg.eMail", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaPorcentajeIvaTxt'], "pg.porcentajeIva", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaCapacidadMaximaTxt'], "pg.capacidadMaxima", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaCapacidadMinimaTxt'], "pg.capacidadMinima", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaEstatusHdn'], "pg.estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catProveedorGasolinaObservacionesTxa'], "pg.observaciones", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetProveedoresStr = "SELECT pg.*, d.*, m.idMunicipio, e.idEstado, p.idPais ".
                                "FROM caProveedorGasolinaTbl pg, cadireccionestbl d, camunicipiostbl m, caestadostbl e, ".
                                "cacoloniastbl c, capaisestbl p ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetProveedoresStr);
        
        echo json_encode($rs);
    }

    function addProveedor(){
        $a = array();
        $e = array();
        $a['success'] = true;
        
        if($_REQUEST['catProveedorGasolinaRfcTxt'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaRfcTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaNumeroEstacionTxt'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaNumeroEstacionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaNombreTxt'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaNombreTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaPorcentajeIvaTxt'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaPorcentajeIvaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaEstatusHdn'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaCalleNumeroTxt'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaCalleNumeroTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaColoniaHdn'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaColoniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaTipoDireccionHdn'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaTipoDireccionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
            //Se guarda primero la dirección
            addDirecciones($_REQUEST['catProveedorGasolinaCalleNumeroTxt'], $_REQUEST['catProveedorGasolinaColoniaHdn'], NULL, $_REQUEST['catProveedorGasolinaTipoDireccionHdn']);

            $sqlGetIdDirStr = "SELECT direccion FROM cadireccionestbl ".
                              "WHERE calleNumero='".$_REQUEST['catProveedorGasolinaCalleNumeroTxt']."' ".
                              "AND idColonia=".$_REQUEST['catProveedorGasolinaColoniaHdn']." ".
                              "AND distribuidor IS NULL ".
                              "AND tipoDireccion='".$_REQUEST['catProveedorGasolinaTipoDireccionHdn']."'";

            $rs = fn_ejecuta_query($sqlGetIdDirStr);
            $idDireccionInt = $rs['root'][0]['direccion'];
        }

        if ($a['success'] == true) {
            $sqlAddProveedorStr = "INSERT INTO caProveedorGasolinaTbl ".
                                  "(rfc, numeroEstacion, nombre, direccionGasolinera, contacto, telefono, fax,".
                                  "eMail, porcentajeIva, capacidadMaxima, capacidadMinima, estatus, observaciones) ".
                                  "VALUES(".
                                  "'".$_REQUEST['catProveedorGasolinaRfcTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaNumeroEstacionTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaNombreTxt']."', ".
                                  $idDireccionInt.", ".
                                  "'".$_REQUEST['catProveedorGasolinaContactoTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaTelefonoTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaFaxTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaEmailTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaPorcentajeIvaTxt']."', ".
                                  replaceEmptyDec($_REQUEST['catProveedorGasolinaCapacidadMaximaTxt']).", ".
                                  replaceEmptyDec($_REQUEST['catProveedorGasolinaCapacidadMinimaTxt']).", ".
                                  "'".$_REQUEST['catProveedorGasolinaEstatusHdn']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaObservacionesTxa']."')";

            $rs = fn_ejecuta_query($sqlAddProveedorStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlAddProveedorStr;
                $a['successMessage'] = getProveedoresSuccessMsg();
                $a['id'] = $_REQUEST['catProveedorGasolinaNumeroEstacionTxt'];

                //Finalmente se agrega el distribuidor a la dirección
                updDirecciones($idDireccion, $RQcalleNum, $RQcolonia, $RQdist, $RQtipoDir);
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddProveedorStr;

                //Si el distribuidor no se crea, se borra la dirección asociada
                dltDireccion($idDireccion);
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updProveedor(){
        $a = array();
        $e = array();
        $a['success'] = true;
        
        if($_REQUEST['catProveedorGasolinaProveedorHdn'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaProveedorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaRfcTxt'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaRfcTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaNumeroEstacionTxt'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaNumeroEstacionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaNombreTxt'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaNombreTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaPorcentajeIvaTxt'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaPorcentajeIvaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaEstatusHdn'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaCalleNumeroTxt'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaCalleNumeroTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaColoniaHdn'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaColoniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaTipoDireccionHdn'] == ""){
            $e[] = array('id'=>'catProveedorGasolinaTipoDireccionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdProveedorStr = "UPDATE caProveedorGasolinaTbl ".
                                  "SET rfc= '".$_REQUEST['catProveedorGasolinaRfcTxt']."', ".
                                  "numeroEstacion= '".$_REQUEST['catProveedorGasolinaNumeroEstacionTxt']."', ".
                                  "nombre= '".$_REQUEST['catProveedorGasolinaNombreTxt']."', ".
                                  "direccionGasolinera=".$_REQUEST['catProveedorGasolinaDireccionHdn'].", ".
                                  "contacto= '".$_REQUEST['catProveedorGasolinaContactoTxt']."', ".
                                  "telefono= '".$_REQUEST['catProveedorGasolinaTelefonoTxt']."', ".
                                  "fax= '".$_REQUEST['catProveedorGasolinaFaxTxt']."', ".
                                  "eMail= '".$_REQUEST['catProveedorGasolinaEmailTxt']."', ".
                                  "porcentajeIva= '".$_REQUEST['catProveedorGasolinaPorcentajeIvaTxt']."', ".
                                  "capacidadMaxima= '".$_REQUEST['catProveedorGasolinaCapacidadMaximaTxt']."', ".
                                  "capacidadMinima= '".$_REQUEST['catProveedorGasolinaCapacidadMinimaTxt']."', ".
                                  "estatus= '".$_REQUEST['catProveedorGasolinaEstatusHdn']."', ".
                                  "observaciones= '".$_REQUEST['catProveedorGasolinaObservacionesTxa']."' ".
                                  "WHERE proveedorGasolina=".$_REQUEST['catProveedorGasolinaProveedorHdn'];

            $rs = fn_ejecuta_query($sqlUpdProveedorStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlUpdProveedorStr;
                $a['successMessage'] = getProveedoresUpdateMsg();
                $a['id'] = $_REQUEST['catProveedorGasolinaNumeroEstacionTxt'];

                //Actualiza la dirección
                 updDirecciones($_REQUEST['catProveedorGasolinaDireccionHdn'], $_REQUEST['catProveedorGasolinaCalleNumeroTxt'], $_REQUEST['catProveedorGasolinaColoniaHdn'], $_REQUEST['catProveedorGasolinaProveedorHdn'], $_REQUEST['catProveedorGasolinaTipoDireccionHdn']);
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdProveedorStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>