<?php
    session_start();
	$_SESSION['modulo'] = "catSimboloUnidades";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");

    $_REQUEST = trasformUppercase($_REQUEST);

	switch($_SESSION['idioma']){
        case 'ES':
            include("../funciones/idiomas/mensajesES.php");
            break;
        case 'EN':
            include("../funciones/idiomas/mensajesEN.php");
            break;
        default:
            include("../funciones/idiomas/mensajesES.php");
    }    
	
    switch($_REQUEST['catSimbolosUnidadesActionHdn']){
        case 'getSimbolosUnidades':
            getSimbolosUnidades();
            break;
        case 'addSimboloUnidades':
            addSimboloUnidades();
            break;
        case 'updSimbolosUnidades':
            updSimbolosUnidades();
            break;  
        case 'dltSimboloUnidades':
            dltSimboloUnidades();                                                   
        default:
           
    }


    function getSimbolosUnidades(){  	
      	$lsWhereStr = "WHERE ct.clasificacion = su.clasificacion ".
                      "AND ct.idTarifa = tf.idTarifa ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catSimbolosUnidadesSimboloTxt'], "su.simboloUnidad", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catSimbolosUnidadesDescripcionTxt'], "su.descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catSimbolosUnidadesTipoOrigenUnidadHdn'], "su.tipoOrigenUnidad", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catSimbolosUnidadesClasificacionHdn'], "su.clasificacion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catSimbolosUnidadesImporteBonificacionTxt'], "su.importeBonificacion", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catSimbolosUnidadesRepuveTxt'], "su.tieneRepuve", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catSimbolosUnidadesMarcaHdn'], "su.marca", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catSimbolosUnidadesTipoUnidadHdn'], "su.tipoUnidad", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catSimbolosUnidadesTarifaHdn'], "tf.tarifa", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        //TIPO TARIFA
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catSimbolosUnidadesTipoTarifaHdn'], "tf.tipoTarifa", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }


		$sqlGetSimbolosUnidadesStr = "SELECT su.*, tf.idTarifa, tf.tarifa, tf.descripcion AS descripcionTarifa, ".
                                     "(SELECT descripcion FROM caMarcasUnidadesTbl mu ".
                                        "WHERE mu.marca = su.marca) AS nombreMarca, ".
                                     "(SELECT cg.nombre FROM caGeneralesTbl cg ".
                                        "WHERE cg.valor = su.tipoOrigenUnidad AND tabla = 'caSimbolosUnidadesTbl' ".
                                        "AND columna = 'tipoOrigenUnidad') AS nombreTipoOrigen, ".
                                     "(SELECT cg2.nombre FROM caGeneralesTbl cg2 ".
                                        "WHERE cg2.valor = su.tipoUnidad AND tabla = 'caSimbolosUnidadesTbl' ".
                                        "AND columna = 'tipoUnidad') AS nombreTipoUnidad,".
                                     "(SELECT cm.descripcion FROM caClasificacionMarcaTbl cm ".
                                        "WHERE cm.marca = su.marca AND cm.clasificacion = su.clasificacion) as descClasificacion ".
                                     "FROM caSimbolosUnidadesTbl su, caClasificacionTarifasTbl ct, caTarifasTbl tf " . $lsWhereStr;      		      

		$rs = fn_ejecuta_query($sqlGetSimbolosUnidadesStr);
		
        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descSimbolo'] = $rs['root'][$iInt]['simboloUnidad']." - ".$rs['root'][$iInt]['descripcion'];
            $rs['root'][$iInt]['descTarifa'] = $rs['root'][$iInt]['tarifa']." - ".$rs['root'][$iInt]['descripcionTarifa'];
        }
			
		echo json_encode($rs);
      }

    function addSimboloUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catSimbolosUnidadesSimboloTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesSimboloTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catSimbolosUnidadesDescripcionTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catSimbolosUnidadesTipoOrigenUnidadHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesTipoOrigenUnidadHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesClasificacionMarcaHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesClasificacionMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesImporteBonificacionTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesImporteBonificacionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesRepuveTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesRepuveTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesMarcaHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesTipoUnidadHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesTipoUnidadHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }        
         if ($a['success'] == true) {
            $sqlAddSimbolUnidadStr = "INSERT INTO caSimbolosUnidadesTbl (simboloUnidad, descripcion, tipoOrigenUnidad, ".
                                     "clasificacion, importeBonificacion, tieneRepuve, marca, tipoUnidad) " . 
                                     "VALUES (" . 
                                        "'".$_REQUEST['catSimbolosUnidadesSimboloTxt'] . "', ".
                                        "'".$_REQUEST['catSimbolosUnidadesDescripcionTxt']."', " . 
                                        "'" . $_REQUEST['catSimbolosUnidadesTipoOrigenUnidadHdn'] . "', " .
                                        "'".$_REQUEST['catSimbolosUnidadesClasificacionMarcaHdn'] . "', " .
                                        "'" . $_REQUEST['catSimbolosUnidadesImporteBonificacionTxt'] . "', " .
                                        $_REQUEST['catSimbolosUnidadesRepuveTxt'] . ", " .
                                        "'" . $_REQUEST['catSimbolosUnidadesMarcaHdn'] . "', " .
                                        "'".$_REQUEST['catSimbolosUnidadesTipoUnidadHdn'] . "')";

            $rs = fn_ejecuta_query($sqlAddSimbolUnidadStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == ""))
            {
                $a['sql'] = $sqlAddSimbolUnidadStr;
                $a['successMessage'] = getSimboloUnidadesSuccessMsg();
                $a['id'] = $_REQUEST['catSimbolosUnidadesSimboloTxt'];
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddSimbolUnidadStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
	
    function updSimbolosUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catSimbolosUnidadesDescripcionTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catSimbolosUnidadesTipoOrigenUnidadHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesTipoOrigenUnidadHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesClasificacionMarcaHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesClasificacionMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesImporteBonificacionTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesImporteBonificacionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesRepuveTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesRepuveTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesMarcaHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesMarcaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catSimbolosUnidadesTipoUnidadHdn'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesTipoUnidadHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		
        if ($a['success']){
            $sqlUpdSimboloUnidadStr = "UPDATE caSimbolosUnidadesTbl ".
                                      "SET descripcion = '" . $_REQUEST['catSimbolosUnidadesDescripcionTxt'] . "', " .
                                      "tipoOrigenUnidad = '" . $_REQUEST['catSimbolosUnidadesTipoOrigenUnidadHdn'] . "', " .
                                      "clasificacion = '" . $_REQUEST['catSimbolosUnidadesClasificacionMarcaHdn'] . "', " .
                                      "importeBonificacion = '" . $_REQUEST['catSimbolosUnidadesImporteBonificacionTxt'] . "', " .
                                      "tieneRepuve = " . $_REQUEST['catSimbolosUnidadesRepuveTxt'] . ", " .
                                      "marca = '" . $_REQUEST['catSimbolosUnidadesMarcaHdn'] . "', " .
                                      "tipoUnidad = '" . $_REQUEST['catSimbolosUnidadesTipoUnidadHdn'] . "' " .
                                      "WHERE simboloUnidad = '" . $_REQUEST['catSimbolosUnidadesSimboloTxt'] . "' ";			
			

			$rs = fn_ejecuta_query($sqlUpdSimboloUnidadStr);
			
			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    $a['sql'] = $sqlUpdSimboloUnidadStr;
                $a['successMessage'] = getSimbolosUnidadesUpdateMsg();
                $a['id'] = $_REQUEST['catSimbolosUnidadesSimboloTxt'];
			} else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdSimboloUnidadStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}

    function dltSimboloUnidades(){
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catSimbolosUnidadesSimboloTxt'] == ""){
            $e[] = array('id'=>'catSimbolosUnidadesSimboloTxt','msg'=>'Falto Seleccionar el Simbolo');
			$a['errorMessage'] = 'Los campos marcados con "*" son Requeridos';
            $a['success'] = false;
        }
			
        if ($a['success']) {
            $sqlDltSimboloUnidadStr = "DELETE FROM caSimbolosUnidadesTbl " . 
			                          "WHERE simboloUnidad = '" . $_REQUEST['catSimbolosUnidadesSimboloTxt'] . "' "; 
			
			$rs = fn_ejecuta_query($sqlDltSimboloUnidadStr);
			
			if($_SESSION['error_sql'] == ""){
			    $a['sql'] = $sqlDltSimboloUnidadStr;
                $a['successmessage'] = getSimboloUnidadesDeleteMsg();
                $a['id'] = $_REQUEST['catSimbolosUnidadesSimboloTxt'];
			} else {
                $a['success'] = false;
                $a['errormessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDltSimboloUnidadStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
?>