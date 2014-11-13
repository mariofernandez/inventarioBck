    <?php
    /************************************************************************	
    * Autor: Mario Adrián Martínez Fernández
    * Fecha: 01-Septiembreca-2014
    * Tablas afectadas: veVentas, veDetalleVenta, vePagos
    * Descripción: Programa para afectar Ventas
    *************************************************************************/   
    session_start();
    $_SESSION['modulo'] = "veVentas";
    $_SESSION['idUsuario'] = 1;
    $_SESSION['usuCompania'] = 'Prue';
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");
    require_once("caProductos.php");

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

    switch($_REQUEST['veVentasActionHdn']){
        case 'getVentas':
            getVentas();
            break;
        case 'getUltimaVenta':
            getUltimaVenta();
            break;
        case 'addVentasDetalle':
            addVentasDetalle();
            break;
        case 'addVentas':
            addVentas();
            break;
        case 'addPagos':
            addPagos();
            break;
        case 'updVentas':
            updVentas();
            break;    
        case 'delVentas':
             delVentas();
             break;
}

    function getVentas(){
        $lsWhereStr = "WHERE vv.idUsuario = su.idUsuario AND vv.estatus = cg.valor ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasidVentaHdn'], "vv.idVenta", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasFechaEventoTxt'], "vv.fechaEvento", 2);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasTotalTxt'], "vv.Total", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasIdUsuarioHdn'], "vv.idUsuario", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasEstatusTxt'], "vv.estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasCompaniaHdn'], "vv.compania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasfolioHdn'], "vv.folio", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetVentasStr = "SELECT vv.*, su.nombre as descUsario, cg.nombre as descEstatus". 
						   "FROM veventas vv, segusuarios su, cagenerales cg ".$lsWhereStr.";";

        $rs = fn_ejecuta_query($sqlGetVentasStr);

        echo json_encode($rs);
    }
    function getUltimaVenta(){
        $lsWhereStr = "WHERE vv.idUsuario = su.idUsuario AND vv.estatus = cg.valor ".
        			  "AND vv.folio = (SELECT max(vv2.folio) FROM veVentas vv2 ".
        			  //"WHERE vv2.fechaEvento = vv.fechaEvento) ".
        			  "WHERE vv2.fechaEvento LIKE '%".date("Y-m-d")."%') ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasidVentaHdn'], "vv.idVenta", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasFechaEventoTxt'], "vv.fechaEvento", 2);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasTotalTxt'], "vv.Total", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasIdUsuarioHdn'], "vv.idUsuario", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasEstatusTxt'], "vv.estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasCompaniaHdn'], "vv.compania", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['veVentasfolioHdn'], "vv.folio", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetVentasStr = "SELECT vv.*, su.nombre as descUsario, cg.nombre as descEstatus ". 
						   "FROM veventas vv, segusuariosTbl su, cagenerales cg ".$lsWhereStr.";";

        $rs = fn_ejecuta_query($sqlGetVentasStr);

        echo json_encode($rs);
    }
        function addVentasDetalle(){
        $a = array();
        $e = array();
        $errorArr = array();
        $a['success'] = true;

        if ($_REQUEST['veVentasidVentaHdn'] == "") {
            $e[] = array('id'=>'veVentasidVentaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['veVentasClaveProductoHdn'] == "") {
            $e[] = array('id'=>'veVentasClaveProductoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['veVentasPrecioHdn'] == "") {
            $e[] = array('id'=>'veVentasPrecioHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['veVentasCantidadHdn'] == "") {
            $e[] = array('id'=>'veVentasCantidadHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $claveProductoArr = explode('|', substr($_REQUEST['veVentasClaveProductoHdn'], 0, -1));
            $cantidadArr = explode('|', substr($_REQUEST['veVentasCantidadHdn'], 0, -1));
            $precioArr = explode('|', substr($_REQUEST['veVentasPrecioHdn'], 0, -1));

            for($nInt = 0; $nInt < sizeof($claveProductoArr);$nInt++){
                $sqlAddDetalleVentaStr = "INSERT INTO vedetalleventa ".
                                      "(idVenta, claveProducto, precio, cantidad, fechaEvento, idUsuario)".
                                      "VALUES(".
                                      $_REQUEST['veVentasidVentaHdn'].", ".
                                      replaceEmptyNull("'".$claveProductoArr[$nInt]."'").", ".
                                      replaceEmptyNull("'".$precioArr[$nInt]."'").", ".
                                      replaceEmptyNull("'".$cantidadArr[$nInt]."'").", ".
                                      "'".date("Y-m-d H:i:s")."', ".
                                      $_SESSION['idUsuario'].")";

                $rs = fn_ejecuta_query($sqlAddDetalleVentaStr);

	            if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {

	            	$sqlRestaActivos ="SELECT (activosInventario -".$cantidadArr[$nInt].") AS resta, tipoProducto ". 
	            					  "FROM caproducto WHERE claveProducto =".$claveProductoArr[$nInt].";";

	            	$rsActivos = fn_ejecuta_query($sqlRestaActivos);
	            	for ($iInt=0; $iInt < sizeof($rsActivos['root']); $iInt++) { 
	            		if($rsActivos['root'][$iInt]['tipoProducto'] == 'UNI'){
	            			$rsResta = $rsActivos['root'][$iInt]['resta']. '|';
	            			$rsProducto = $claveProductoArr[$nInt].'|';
	            		}
	            	}
	            	if($rsProducto != ''){
		            	$_REQUEST['caProductoActivosProductoTxt'] = $rsResta;
		            	$_REQUEST['caProductoClaveProductoTxt'] = $rsProducto;
		            	$_REQUEST['caProductoActualizaVentasTxt'] = 'si';
		               	$data = updProductos();
                   }
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $claveProductoArr[$nInt]);
                }
            }

            if ($a['success'] == true) {
                $a['successMessage'] = getVentasSuccessMsg();
            } else {
                $a['errorMessage'] = getVentasFailedMsg();
                for ($nInt=0; $nInt < sizeof($errorArr); $nInt++) { 
                    $a['errorMessage'] .= $errorArr[$nInt].", ";
                }
            }
        } 

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        return $a;
    }

    function addVentas(){
        $a = array();
        $e = array();
        $a['success'] = true;


        if ($_REQUEST['veVentasTotalTxt'] == "") {
            $e[] = array('id'=>'veVentasTotalTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['veVentasEstatusTxt'] == "") {
            $e[] = array('id'=>'veVentasEstatusTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['veVentasfolioHdn'] == "") {
            $e[] = array('id'=>'veVentasfolioHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }/*else{
        	$_REQUEST['veVentasfolioHdn'] = $_REQUEST['veVentasfolioHdn'] + 1;
        }*/
        

        if ($a['success'] == true) {

                $sqlAddVentasStr = "INSERT INTO veVentas ".
                                      "(fechaEvento, total, idUsuario, estatus, compania, folio)".
                                      "VALUES(".
                                      "'".date("Y-m-d H:i:s")."',".
                                      "'".$_REQUEST['veVentasTotalTxt']."', ".
                                      $_SESSION['idUsuario'].", ".
                                      "'".$_REQUEST['veVentasEstatusTxt']."', ".
                                      "'".$_SESSION['usuCompania']."', ".
                                      $_REQUEST['veVentasfolioHdn'].")";

                $rs = fn_ejecuta_query($sqlAddVentasStr);
                $idViajeInt = mysql_insert_id();
                $_REQUEST['veVentasidVentaHdn'] = $idViajeInt;

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
        			$data = addVentasDetalle();
                
		            if ($data['success'] == true) {
		                $a['successMessage'] = getVentasSuccessMsg();
                        /**
                        * Si se inserto el detalle de la venta es valido insertara el/los pagos(s) de la venta
                        */
                        $pagos = addPagos();
		            } else {
		                $a['success'] = $data['success'];
		                $a['errorMessage'] = $data['errorMessage'];
		                $e = $data['errors'];
		                $_REQUEST['veVentasErrorAltaHdn'] = 'si';
		                delVentas($_REQUEST['veVentasErrorAltaHdn'],$_REQUEST['veVentasidVentaHdn']);
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

    function addPagos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['veVentasidVentaHdn'] == "") {
            $e[] = array('id'=>'veVentasidVentaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['veVentasImporteTxt'] == "") {
            $e[] = array('id'=>'veVentasImporteTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['veVentasTipoPagoHdn'] == "") {
            $e[] = array('id'=>'veVentasTipoPagoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            //$idVentaArr = explode('|', substr($_REQUEST['veVentasidVentaHdn'], 0, -1));
            $importeArr = explode('|', substr($_REQUEST['veVentasImporteTxt'], 0, -1));
            $tipoPagoArr = explode('|', substr($_REQUEST['veVentasTipoPagoHdn'], 0, -1));
            $noReferenciaArr = explode('|', substr($_REQUEST['veVentasNoReferenciaTxt'], 0, -1));

            for($nInt = 0; $nInt < sizeof($importeArr);$nInt++){
                $sqlAddPagosStr = "INSERT INTO vePagos ".
                                      "(idVenta, importe, tipoPago, noReferencia) ".
                                      "VALUES(".
                                      $_REQUEST['veVentasidVentaHdn'].", ".
                                      replaceEmptyNull("'".$importeArr[$nInt]."'").",".
                                      replaceEmptyNull("'".$tipoPagoArr[$nInt]."'").",".
                                      replaceEmptyNull("'".$noReferenciaArr[$nInt]."'").")";

                $rs = fn_ejecuta_query($sqlAddPagosStr);

                if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                   
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $importeArr[$nInt]);
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
        //return $a;
        //echo json_encode($a);
    }

    function updVentas(){
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


            for($nInt = 0; $nInt < sizeof($productoArr);$nInt++){
                $sqlUpdProductosStr = "UPDATE caproducto SET ".
                "claveCompania = ".replaceEmptyNull("'".$proveedorArr[$nInt]."'").",". 
                "descripcion = ".replaceEmptyNull("'".$descipcionArr[$nInt]."'").",".
                "precio = ".replaceEmptyNull("'".$precioArr[$nInt]."'").",".
                "precioCompra = ".replaceEmptyNull("'".$precioCompraArr[$nInt]."'").",".
                "minProducto = ".replaceEmptyNull("'".$minProductoArr[$nInt]."' ").
                "WHERE ClaveProducto = ".replaceEmptyNull("'".$productoArr[$nInt]."'");
                

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
        echo json_encode($a);
    }

    function delVentas(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['veVentasidVentaHdn'] == "") {
            $e[] = array('id'=>'veVentasidVentaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($a['success'] == true) {
            $sqldelVentasStr = "DELETE FROM veVentas ".
                                   "WHERE idVenta = ".$_REQUEST['veVentasidVentaHdn'];

            $rs = fn_ejecuta_query($sqldelVentasStr);

            if ((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['successMessage'] = getVentasDeleteMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql']." <br> ".$sqldelVentasStr;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        if($_REQUEST['veVentasErrorAltaHdn'] === ''){
        echo json_encode($a);
    	}
    }
?>