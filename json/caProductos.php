<?php
    /************************************************************************
    * Autor: Mario Adrián Martínez Fernández
    * Fecha: 07-Julio-2014
    * Tablas afectadas: caProductos
    * Descripción: Programa para afectar Productos
    *************************************************************************/   
    session_start();
    $_SESSION['modulo'] = "caProducto";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");

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

    switch($_REQUEST['caProductoActionHdn']){
        case 'getProductos':
            getProductos();
            break;
        case 'addProductos':
            addProductos();
            break;
        case 'updProductos':
            updProductos();
            break;    
        case 'delProductos':
             delProductos();
             break;
}

    function getProductos(){
        $lsWhereStr = "WHERE po.claveCompania = cc.claveCompania ".
                      "AND po.tipoProducto = cg.valor ";
        if ($_REQUEST['caProductoComboHdn'] != ''){
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, " po.activosInventario > 0 ");
        }

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caProductoClaveProductoTxt'], "po.claveProducto", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caProductoClaveCompaniaHdn'], "cc.claveCompania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caProductoDescripcionTxt'], "po.descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caProductoPrecioTxt'], "po.precio", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caProductoPrecioCompraTxt'], "po.precioCompra", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caProductoMinProductoTxt'], "po.minProducto", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetRetrabajosStr = "SELECT po.*, cc.descripcion as descProveedor, cg.valor as descTipoProducto ".
                               "FROM caProducto po, caCompanias cc, caGenerales cg ".$lsWhereStr.
                                " ORDER BY po.claveProducto ASC";

        $rs = fn_ejecuta_query($sqlGetRetrabajosStr);
        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descProducto'] = $rs['root'][$iInt]['claveProducto']." - ".$rs['root'][$iInt]['descripcion'];
        }

        echo json_encode($rs);
    }

    function addProductos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['caProductoClaveProductoTxt'] == "") {
            $e[] = array('id'=>'caProductoClaveProductoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['caProductoDescripcionTxt'] == "") {
            $e[] = array('id'=>'caProductoDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $productoArr = explode('|', substr($_REQUEST['caProductoClaveProductoTxt'], 0, -1));
            $claveCompaniaArr = explode('|', substr($_REQUEST['caProductoClaveCompaniaHdn'], 0, -1));
            $descipcionArr = explode('|', substr($_REQUEST['caProductoDescripcionTxt'], 0, -1));
            $precioArr = explode('|', substr($_REQUEST['caProductoPrecioTxt'], 0, -1));
            $precioCompraArr = explode('|', substr($_REQUEST['caProductoPrecioCompraTxt'], 0, -1));
            $minProductoArr = explode('|', substr($_REQUEST['caProductoMinProductoTxt'], 0, -1));
            $tipoProductoArr = explode('|', substr($_REQUEST['caProductoTipoProductoTxt'], 0, -1));


            for($nInt = 0; $nInt < sizeof($productoArr);$nInt++){
                $sqlAddProductosStr = "INSERT INTO caProducto ".
                                      "(claveProducto, claveCompania, descripcion, precio, ".
                                      "precioCompra, minProducto, tipoProducto)".
                                      "VALUES(".
                                      replaceEmptyNull("'".$productoArr[$nInt]."'").",".
                                      replaceEmptyNull("'".$claveCompaniaArr[$nInt]."'").",".
                                      replaceEmptyNull("'".$descipcionArr[$nInt]."'").",".
                                      replaceEmptyNull("'".$precioArr[$nInt]."'").",".
                                      replaceEmptyNull("'".$precioCompraArr[$nInt]."'").",".
                                      replaceEmptyNull("'".$minProductoArr[$nInt]."'").",".
                                      replaceEmptyNull("'".$tipoProductoArr[$nInt]."'").")";

                $rs = fn_ejecuta_query($sqlAddProductosStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $productoArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getProductosSuccessMsg();
            } else {
                $a['errorMessage'] = getProductosFailedMsg();
                for ($nInt=0; $nInt < sizeof($errorArr); $nInt++) { 
                    $a['errorMessage'] .= $errorArr[$nInt].", ";
                }
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

 
    function updProductos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['caProductoClaveProductoTxt'] == "") {
            $e[] = array('id'=>'caProductoClaveProductoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $productoArr = explode('|', substr($_REQUEST['caProductoClaveProductoTxt'], 0, -1));
            $proveedorArr = explode('|', substr($_REQUEST['caProductoClaveCompaniaHdn'], 0, -1));
            $descipcionArr = explode('|', substr($_REQUEST['caProductoDescripcionTxt'], 0, -1));
            $precioArr = explode('|', substr($_REQUEST['caProductoPrecioTxt'], 0, -1));
            $precioCompraArr = explode('|', substr($_REQUEST['caProductoPrecioCompraTxt'], 0, -1));
            $minProductoArr = explode('|', substr($_REQUEST['caProductoMinProductoTxt'], 0, -1));
            $tipoProductoArr = explode('|', substr($_REQUEST['caProductoTipoProductoTxt'], 0, -1));
            $activosProductoArr = explode('|', substr($_REQUEST['caProductoActivosProductoTxt'], 0, -1));


            for($nInt = 0; $nInt < sizeof($productoArr);$nInt++){
                
                $sqlUpdProductosStr = "UPDATE caproducto SET ";
                    if (isset($_REQUEST['caProductoClaveCompaniaHdn']) && $_REQUEST['caProductoClaveCompaniaHdn'] != '') {
                        $sqlUpdProductosStr .= " claveCompania = '".replaceEmptyNull($proveedorArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caProductoDescripcionTxt']) && $_REQUEST['caProductoDescripcionTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdProductosStr .= ",";
                        }

                        $sqlUpdProductosStr .= " descripcion = '".replaceEmptyNull($descipcionArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caProductoPrecioTxt']) && $_REQUEST['caProductoPrecioTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdProductosStr .= ",";
                        }

                        $sqlUpdProductosStr .= " precio = '".replaceEmptyNull($precioArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caProductoPrecioCompraTxt']) && $_REQUEST['caProductoPrecioCompraTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdProductosStr .= ",";
                        }

                        $sqlUpdProductosStr .= " precioCompra = '".replaceEmptyNull($precioCompraArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caProductoMinProductoTxt']) && $_REQUEST['caProductoMinProductoTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdProductosStr .= ",";
                        }

                        $sqlUpdProductosStr .= " minProducto = '".replaceEmptyNull($minProductoArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caProductoTipoProductoTxt']) && $_REQUEST['caProductoTipoProductoTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdProductosStr .= ",";
                        }

                        $sqlUpdProductosStr .= " tipoProducto = '".replaceEmptyNull($tipoProductoArr[$nInt])."'";
                        $updInt++;
                    }
                    if (isset($_REQUEST['caProductoActivosProductoTxt']) && $_REQUEST['caProductoActivosProductoTxt'] != '') {
                        if ($updInt > 0) {
                            $sqlUpdProductosStr .= ",";
                        }

                        $sqlUpdProductosStr .= " activosInventario = '".replaceEmptyNull($activosProductoArr[$nInt])."'";
                        $updInt++;
                    }
                    if ($updInt > 0) {
                        $sqlUpdProductosStr .= " WHERE claveProducto = '".replaceEmptyNull($productoArr[$nInt])."'; ";
                    }

                

                $rs = fn_ejecuta_query($sqlUpdProductosStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $productoArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getProductosUpdMsg();
            } else {
                $a['errorMessage'] = getProductosFailedMsg();
                for ($nInt=0; $nInt < sizeof($errorArr); $nInt++) { 
                    $a['errorMessage'] .= $errorArr[$nInt].", ";
                }
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        if($_REQUEST['caProductoActualizaVentasTxt'] == ''){
            echo json_encode($a);
        }
    }

    function delProductos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['caProductoClaveProductoTxt'] == "") {
            $e[] = array('id'=>'caProductoClaveProductoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($a['success'] == true) {
            $sqldelProductosStr = "DELETE FROM caProducto ".
                                   "WHERE ClaveProducto = ".$_REQUEST['caProductoClaveProductoTxt'];

            $rs = fn_ejecuta_query($sqldelProductosStr);

            if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['successMessage'] = getProductosDeleteMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql']." <br> ".$sqldelProductosStr;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>