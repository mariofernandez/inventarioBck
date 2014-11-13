<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
	session_start();
	$_SESSION['modulo'] = "catProveedores";
    require("../funciones/generalesPDO.php");
    require("../funciones/construct.php");

    switch($_SESSION['idioma'])
	{
        case 'ES':
            include("../funciones/idiomas/mensajesES.php");
            break;
        case 'EN':
            include("../funciones/idiomas/mensajesEN.php");
            break;
        default:
            include("../funciones/idiomas/mensajesES.php");
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
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaRfcTxt'], "rfc", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaNumeroEstacionTxt'], "numeroEstacion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaNombreTxt'], "nombre", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaDireccionHdn'], "direccionGasolinera", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaContactoTxt'], "contacto", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaTelefonoTxt'], "telefono", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaFaxTxt'], "fax", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaEmailTxt'], "eMail", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaPorcentajeIvaTxt'], "porcentajeIva", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaCapacidadMaximaTxt'], "capacidadMaxima", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaCapacidadMinimaTxt'], "capacidadMinima", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaEstatusTxt'], "estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }
        if ($gb_error_filtro == 0){
            $ls_condicion = fn_construct($_REQUEST['catProveedorGasolinaObservacionesTxt'], "observaciones", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
        }

        $sqlGetProveedoresStr = "SELECT * FROM caproveedorgasolinatbl ".$ls_where;
        
        $rs = fn_ejecuta_query($sqlGetProveedoresStr);
          
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
    }

    function addProveedor(){
        $a = array();
        $e = array();
        $a['success'] = true;
        
        if($_REQUEST['catProveedorGasolinaRfcTxt'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaRfcTxt','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaNumeroEstacionTxt'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaNumeroEstacionTxt','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaNombreTxt'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaNombreTxt','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaDireccionHdn'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaDireccionHdn','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaPorcentajeIvaTxt'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaPorcentajeIvaTxt','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaEstatusTxt'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaEstatusTxt','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlAddProveedorStr = "INSERT INTO caproveedorgasolinatbl ".
                                  "(rfc, numeroEstacion, nombre, direccionGasolinera, contacto, telefono, fax,".
                                  "eMail, porcentajeIva, capacidadMaxima, capacidadMinima, estatus, observaciones) ".
                                  "VALUES(".
                                  "'".$_REQUEST['catProveedorGasolinaRfcTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaNumeroEstacionTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaNombreTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaDireccionHdn']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaContactoTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaTelefonoTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaFaxTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaEmailTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaPorcentajeIvaTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaCapacidadMaximaTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaCapacidadMinimaTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaEstatusTxt']."', ".
                                  "'".$_REQUEST['catProveedorGasolinaObservacionesTxt']."')";

            $rs = fn_ejecuta_query($sqlAddProveedorStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == ""))
            {
                $a['sql'] = $sqlAddProveedorStr;
                $a['successMessage'] = getProveedoresSuccessMsg();
                $a['id'] = $_REQUEST['catProveedorGasolinaNumeroEstacionTxt'];
            }
            else
            {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddProveedorStr;
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
        
        if($_REQUEST['catProveedorGasolinaRfcTxt'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaRfcTxt','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaNumeroEstacionTxt'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaNumeroEstacionTxt','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaNombreTxt'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaNombreTxt','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaDireccionHdn'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaDireccionHdn','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaPorcentajeIvaTxt'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaPorcentajeIvaTxt','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catProveedorGasolinaEstatusTxt'] == "")
        {
            $e[] = array('id'=>'catProveedorGasolinaEstatusTxt','msg'=>getRequerido());
            $a['errormessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdProveedorStr = "UPDATE caproveedorgasolinatbl ".
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
                                  "estatus= '".$_REQUEST['catProveedorGasolinaEstatusTxt']."', ".
                                  "observaciones= '".$_REQUEST['catProveedorGasolinaObservacionesTxt']."' ".
                                  "WHERE proveedorGasolina=".$_REQUEST['catProveedorGasolinaProveedorHdn'];

            $rs = fn_ejecuta_query($sqlUpdProveedorStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == ""))
            {
                $a['sql'] = $sqlUpdProveedorStr;
                $a['successMessage'] = getProveedoresUpdateMsg();
                $a['id'] = $_REQUEST['catProveedorGasolinaNumeroEstacionTxt'];
            }
            else
            {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdProveedorStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>