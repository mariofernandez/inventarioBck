
<?php
    /************************************************************************
    * Autor: Mario Adrián Martínez Fernández
    * Fecha: 07-Julio-2014
    * Tablas afectadas: caCompanias
    * Descripción: Programa para afectar Compañias
    *************************************************************************/   
    session_start();
    $_SESSION['modulo'] = "caCompanias";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");
    require_once("caDirecciones.php");

    //$_REQUEST = trasformUppercase($_REQUEST);
    
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

    switch($_REQUEST['caCompaniasActionHdn']){
        case 'getCompanias':
            getCompanias();
            break;
        case 'addCompanias':
            addCompanias();
            break;
        case 'updCompanias':
            updCompanias();
            break;    
        case 'delCompanias':
             delCompanias();
             break;
}

    function getCompanias(){
        $lsWhereStr = "where cg3.valor = cc.estatus and cg3.tabla = 'cacompanias' and cg3.columna = 'estatus'";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caCompaniasClaveCompaniaHdn'], "cc.clavecompania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caCompaniasDescripcionTxt'], "cc.descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caCompaniasTipoCompaniaHdn'], "cc.tipoCompania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caCompaniasSucursalDeHdn'], "cc.sucursalDe", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caCompaniasTelefonoTxt'], "cc.telefono", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caCompaniasContactoTxt'], "cc.contacto", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caCompaniasEmailTxt'], "cc.email", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caCompaniasDireccionHdn'], "cc.direccion", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caCompaniasEstatusHdn'], "cc.estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetCompaniasStr = "SELECT cc.*, (SELECT (concat(cd.calleNumero,', ',co.descripcion,', ',cm.descripcion,', ', ".
                              "ce.descripcion,', ', cp.descripcion,', ',co.codigoPostal)) ".
                              "FROM cadirecciones cd, cacolonias co, camunicipios cm, caestados ce, capaises cp ".
                              "WHERE co.idColonia = cd.idColonia AND co.idMunicipio = cm.idMunicipio ".
                              "AND cm.idEstado = ce.idEstado AND ce.idPais = cp.idPais ".
                              "AND cc.direccion = cd.idDireccion ) as direccionCompleta, ".
                              "(SELECT concat(claveCompania,' - ',descripcion) FROM caCompanias cc2 WHERE cc.sucursalDe = cc2.clavecompania) AS descSucursalDe, ".
                              "(SELECT concat(valor,' - ',nombre) FROM cagenerales cg WHERE cc.tipoCompania = cg.valor) AS descTipoCompania, ".
                              "concat(cg3.valor,' - ',cg3.nombre) AS descEstatus ".
                              "FROM caCompanias cc, cagenerales cg3 ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetCompaniasStr);

               for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descCompania'] = $rs['root'][$iInt]['claveCompania']." - ".$rs['root'][$iInt]['descripcion'];
        }
        

        echo json_encode($rs);
    }

        function addCompanias(){
        $a = array();
        $e = array();
        $errorArr= array();
        $a['success'] = true;

        if ($_REQUEST['caCompaniasClaveCompaniaHdn'] == "") {
            $e[] = array('id'=>'caCompaniasClaveCompaniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }else{
            $_REQUEST['caDireccionesCompaniaTxt'] = $_REQUEST['caCompaniasClaveCompaniaHdn'].'|';
        }
        if ($_REQUEST['caCompaniasDescripcionTxt'] == "") {
            $e[] = array('id'=>'caCompaniasDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caCompaniasTipoCompaniaHdn'] == "") {
            $e[] = array('id'=>'caCompaniasTipoCompaniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }else{
            $_REQUEST['caDireccionesTipoDireccionTxt'] = $_REQUEST['caCompaniasTipoCompaniaHdn'].'|';
        }
        if ($_REQUEST['caCompaniasEstatusHdn'] == "") {
            $e[] = array('id'=>'caCompaniasEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caCompaniasDireccionHdn'] == "") {
            $e[] = array('id'=>'caCompaniasDireccionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }else{
           $_REQUEST['caDireccionesIdDireccionHdn'] = $_REQUEST['caCompaniasDireccionHdn'].'|';
           $_REQUEST['caDireccionesNuevaCompaniaHdn'] = 'add';
        }
        if ($a['success'] == true) {

                $sqlAddCompaniasStr = "INSERT INTO caCompanias ".
                                      "(clavecompania, descripcion, tipoCompania, sucursalDe, telefono, contacto, ".
                                        "email, direccion, estatus)".
                                      "VALUES('".
                                      $_REQUEST['caCompaniasClaveCompaniaHdn']."', '".
                                      $_REQUEST['caCompaniasDescripcionTxt']."', '".
                                      $_REQUEST['caCompaniasTipoCompaniaHdn']."', ".
                                      replaceEmptyNull("'".$_REQUEST['caCompaniasSucursalDeHdn']."'").", ".
                                      replaceEmptyNull("'".$_REQUEST['caCompaniasTelefonoTxt']."'").", ".
                                      replaceEmptyNull("'".$_REQUEST['caCompaniasContactoTxt']."'").", ".
                                      replaceEmptyNull("'".$_REQUEST['caCompaniasEmailTxt']."'").", ".
                                      $_REQUEST['caCompaniasDireccionHdn'].", ".
                                      $_REQUEST['caCompaniasEstatusHdn'].")";

                $rs = fn_ejecuta_query($sqlAddCompaniasStr);
                if ((!($_SESSION['error_sql'])) || (($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {

                   $data = updDirecciones($_REQUEST['caDireccionesIdDireccionHdn'], $_REQUEST['caDireccionesCompaniaTxt'], $_REQUEST['caDireccionesTipoDireccionTxt'], $_REQUEST['caDireccionesNuevaCompaniaHdn']);
                   if ($data['success'] == true) {
                        $a['success'] = $data['success'];
                        $a['successMessage'] = $getCompaniasSuccessMsg;

                   } else {
                        $a['success'] = $data['success'];
                        $a['errorMessage'] = $data['errorMessage'];
                    }
                } else {
                $a['success'] = false; 
                $a['errorMessage'] = getCompaniasFailedMsg().$_REQUEST['caCompaniasDescripcionTxt']." ".$_SESSION['error_sql']."<br>";
                $a['errorMessage']= substr($a['errorMessage'], 0, -4);
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        if($a['success'] === false){
            echo json_encode($a);
        }
        /**
         * La Respuesta del addCompanias si es Valido se mandara desde el php de Direcciones,
         * si la respuesta es invalida se mandara desde aqui.
         */
        
    }

    function updCompanias(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['caCompaniasClaveCompaniaHdn'] == "") {
            $e[] = array('id'=>'caCompaniasClaveCompaniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }else{
            $_REQUEST['caDireccionesCompaniaTxt'] = $_REQUEST['caCompaniasClaveCompaniaHdn'].'|';
            $_REQUEST['caDireccionesNuevaCompaniaHdn'] = 'update';
        }

        if ($_REQUEST['caCompaniasDireccionHdn'] == "") {
            $e[] = array('id'=>'caCompaniasDireccionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }else{
            $_REQUEST['caDireccionesIdDireccionHdn'] = $_REQUEST['caCompaniasDireccionHdn'].'|';
        }

        if ($_REQUEST['caCompaniasTipoCompaniaHdn'] == "") {
            $e[] = array('id'=>'caCompaniasTipoCompaniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }else{
            $_REQUEST['caDireccionesTipoDireccionTxt'] = $_REQUEST['caCompaniasTipoCompaniaHdn'].'|';
        }
        
        

        if ($a['success'] == true) {

            $sqlUpdCompaniasStr = "UPDATE caCompanias SET ";
            if (isset($_REQUEST['caCompaniasDescripcionTxt']) && $_REQUEST['caCompaniasDescripcionTxt'] != '') {
                        $sqlUpdCompaniasStr .= " descripcion = '".$_REQUEST['caCompaniasDescripcionTxt']."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caCompaniasClaveCompaniaNuevoHdn']) && $_REQUEST['caCompaniasClaveCompaniaNuevoHdn'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdCompaniasStr .= ",";
                        }

                        $sqlUpdCompaniasStr .= " clavecompania = '".$_REQUEST['caCompaniasClaveCompaniaNuevoHdn']."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caCompaniasTipoCompaniaHdn']) && $_REQUEST['caCompaniasTipoCompaniaHdn'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdCompaniasStr .= ",";
                        }

                        $sqlUpdCompaniasStr .= " tipoCompania = '".$_REQUEST['caCompaniasTipoCompaniaHdn']."'";
                        $updInt++;
                    }
                    //if (isset($_REQUEST['caCompaniasSucursalDeHdn']) && $_REQUEST['caCompaniasSucursalDeHdn'] != '') {
                    if (isset($_REQUEST['caCompaniasSucursalDeHdn'])) {
                        if( $_REQUEST['caCompaniasSucursalDeHdn'] != ''){
                        if ($updInt > 0) {
                            $sqlUpdCompaniasStr .= ",";
                        }
                        $sqlUpdCompaniasStr .= " sucursalDe = '".$_REQUEST['caCompaniasSucursalDeHdn']."'";
                        $updInt++;
                         }else{
                            if ($updInt > 0) {
                                $sqlUpdCompaniasStr .= ",";
                            }
                            $sqlUpdCompaniasStr .= " sucursalDe = null";
                            $updInt++;
                         }
                    }
                    if (isset($_REQUEST['caCompaniasTelefonoTxt']) && $_REQUEST['caCompaniasTelefonoTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdCompaniasStr .= ",";
                        }

                        $sqlUpdCompaniasStr .= " telefono = '".$_REQUEST['caCompaniasTelefonoTxt']."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caCompaniasContactoTxt']) && $_REQUEST['caCompaniasContactoTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdCompaniasStr .= ",";
                        }

                        $sqlUpdCompaniasStr .= " contacto = '".$_REQUEST['caCompaniasContactoTxt']."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caCompaniasEmailTxt']) && $_REQUEST['caCompaniasEmailTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdCompaniasStr .= ",";
                        }

                        $sqlUpdCompaniasStr .= " email = '".$_REQUEST['caCompaniasEmailTxt']."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caCompaniasDireccionHdn']) && $_REQUEST['caCompaniasDireccionHdn'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdCompaniasStr .= ",";
                        }

                        $sqlUpdCompaniasStr .= " direccion = ".$_REQUEST['caCompaniasDireccionHdn'];
                        $updInt++;
                    }
                    if (isset($_REQUEST['caCompaniasEstatusHdn']) && $_REQUEST['caCompaniasEstatusHdn'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdCompaniasStr .= ",";
                        }

                        $sqlUpdCompaniasStr .= " estatus = ".$_REQUEST['caCompaniasEstatusHdn'];
                        $updInt++;
                    }
                    if ($updInt > 0) {
                        $sqlUpdCompaniasStr .= " WHERE clavecompania = '".$_REQUEST['caCompaniasClaveCompaniaHdn']."'";
                    }               

                $rs = fn_ejecuta_query($sqlUpdCompaniasStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $data = updDirecciones($_REQUEST['caDireccionesIdDireccionHdn'], $_REQUEST['caDireccionesCompaniaTxt'], $_REQUEST['caDireccionesTipoDireccionTxt'], $_REQUEST['caDireccionesNuevaCompaniaHdn']);
                        if ($data['success'] == true) {                              
                            $a['successMessage'] = $getCompaniasUpdMsg;                            
                        } else {
                            $a['success'] = $data['success'];
                            $a['errorMessage'] = $data['errorMessage'];
                        }
                        $a['success'] = true;        
                } else {
                    $a['success'] = false;   
                    $a['errorMessage'] = getCompaniasFailedMsg().$_REQUEST['caCompaniasDescripcionTxt']." ".$_SESSION['error_sql']."<br>";
                    $a['errorMessage']= substr($a['errorMessage'], 0, -4);
                 }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();  
        if($a['success'] == false){
            echo json_encode($a);
        }
    }

    function delCompanias(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['caCompaniasClaveCompaniaHdn'] == "") {
            $e[] = array('id'=>'caCompaniasClaveCompaniaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($a['success'] == true) {
            $sqlDelCompaniasStr = "DELETE FROM caCompanias ".
                                   "WHERE clavecompania = ".$_REQUEST['caCompaniasClaveCompaniaHdn'];

            $rs = fn_ejecuta_query($sqlDelCompaniasStr);

            if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['successMessage'] = getCompaniasDeleteMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql']." <br> ".$sqlDelCompaniasStr;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>