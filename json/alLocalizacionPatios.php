<?php
	session_start();
	$_SESSION['modulo'] = "alLocalizacionPatios";
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

	switch ($_REQUEST['alLocalizacionPatiosActionHdn']) {
		case 'getLocalizacionPatios':
			getLocalizacionPatios();
			break;
        case 'getGruposPatio':
            getGruposPatio();
            break;
        case 'getGruposPatioCombo':
            getGruposPatioCombo();
            break;
        case 'getDistribuidoresDisponiblesGrupo':
            getDistribuidoresDisponiblesGrupo();
            break;
        case 'getClasificacionDisponiblesGrupo':
            getClasificacionDisponiblesGrupo();
            break;
		case 'addLocalizacionPatios':
			addLocalizacionPatios();
			break;
        case 'dltLocalizacionPatios':
            dltLocalizacionPatios();
            break;
        case 'addGrupo':
            addGrupo();
            break;
        case 'addLugaresGrupo':
            addLugaresGrupo();
            break;
		case 'ordenMinimo':
			ordenMinimo($_REQUEST['alLocalizacionPatiosPatioHdn'], $_REQUEST['alLocalizacionPatiosGrupoHdn']);
            break;
        case 'getGrupoPorSimbolo':
            getGrupoPorSimbolo($_REQUEST['alLocalizacionPatiosSimboloHdn']);
            break;
        case 'getGrupoPorDistribuidor':
            getGrupoPorDistribuidor($_REQUEST['alLocalizacionPatiosDistribuidorHdn']);
            break;
	}

	function getLocalizacionPatios(){
		$lsWhereStr = "WHERE dc.distribuidorCentro = lp.patio ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosIdLocalizacionHdn'], "lp.idLocalizacion", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosPatioHdn'], "lp.patio", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosGrupoHdn'], "lp.grupo", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosGrupo1Hdn'], "lp.grupo1", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosGrupo2Hdn'], "lp.grupo2", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosFilaHdn'], "lp.fila", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosLugarHdn'], "lp.lugar", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosVinTxt'], "lp.vin", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosOrdenHdn'], "lp.orden", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosOrden1Hdn'], "lp.orden1", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosOrden2Hdn'], "lp.orden2", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetLocalizacionPatiosStr = "SELECT lp.*, dc.descripcionCentro ".
                                       "FROM alLocalizacionPatiosTbl lp, caDistribuidoresCentrosTbl dc ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetLocalizacionPatiosStr);
            
        echo json_encode($rs);
	}

    function getGruposPatio(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosIdGrupoHdn'], "gp.idGrupo", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosGrupoHdn'], "gp.grupo", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosClasificacionHdn'], "gp.clasificacion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosMarcaClasificacionHdn'], "gp.marcaClasificacion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosDistribuidorHdn'], "gp.distribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosMarcaDistHdn'], "gp.marcaDistribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosCentroDistHdn'], "gp.centroDistribucion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosColorHdn'], "gp.color", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetGruposPatioStr = "SELECT gp.*, ".
                                "(SELECT dc.descripcionCentro FROM caDistribuidoresCentrosTbl dc ".
                                    "WHERE dc.distribuidorCentro = gp.distribuidor) AS descripcionCentro, ".
                                "(SELECT cm.descripcion FROM caClasificacionMarcaTbl cm ".
                                    "WHERE cm.clasificacion = gp.clasificacion ".
                                    "AND cm.marca = gp.marcaClasificacion) AS descClasificacion ".
                                "FROM alGruposPatioTbl gp ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetGruposPatioStr);
            
        echo json_encode($rs);
    }

    function getGruposPatioCombo(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosGrupoHdn'], "gp.grupo", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosClasificacionHdn'], "gp.clasificacion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosMarcaClasificacionHdn'], "gp.marcaClasificacion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosDistribuidorHdn'], "gp.distribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosMarcaDistHdn'], "gp.marcaDistribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosCentroDistHdn'], "gp.centroDistribucion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosColorHdn'], "gp.color", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetGruposPatioStr = "SELECT gp.grupo ".
                                "FROM alGruposPatioTbl gp ".$lsWhereStr." GROUP BY gp.grupo";
        
        $rs = fn_ejecuta_query($sqlGetGruposPatioStr);
            
        echo json_encode($rs);
    }

    function getDistribuidoresDisponiblesGrupo(){
        $lsWhereStr = "WHERE (md.distribuidor, md.marca) NOT IN (".
                            "SELECT gp.distribuidor, gp.marcaDistribuidor ".
                            "FROM alGruposPatioTbl gp ".
                            "WHERE gp.distribuidor IS NOT NULL) ".
                      "AND dc.distribuidorCentro = md.distribuidor ".
                      "AND dc.tipoDistribuidor = 'DI'";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosDistribuidorHdn'], "md.distribuidorCentro", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosMarcaHdn'], "md.marca", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }  

        $sqlGetDistDispGrupo = "SELECT md.distribuidor, md.marca AS marcaDistribuidor, dc.descripcionCentro ".
                               "FROM caMarcasDistribuidoresCentrosTbl md, caDistribuidoresCentrosTbl dc ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetDistDispGrupo);
            
        echo json_encode($rs);
    }

    function getClasificacionDisponiblesGrupo(){
        $lsWhereStr = "WHERE (cm.clasificacion, cm.marca) NOT IN (".
                            "SELECT gp.clasificacion, gp.marcaClasificacion ".
                            "FROM alGruposPatioTbl gp ".
                            "WHERE gp.clasificacion IS NOT NULL)";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosClasificacionHdn'], "cm.clasificacion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['alLocalizacionPatiosMarcaHdn'], "cm.marca", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        } 

        $sqlGetDistDispGrupo = "SELECT cm.clasificacion, cm.marca AS marcaClasificacion, cm.descripcion AS descClasificacion ".
                               "FROM caClasificacionMarcaTbl cm ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlGetDistDispGrupo);
            
        echo json_encode($rs);
    }

	function addLocalizacionPatios(){
		$a = array();
        $e = array();
        $a['success'] = true;

        $simboloArr = explode('|', substr($_REQUEST['alLocalizacionPatiosSimboloHdn'], 0, -1));
        if(in_array('', $simboloArr)){
            $e[] = array('id'=>'alLocalizacionPatiosSimboloHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $distArr = explode('|', substr($_REQUEST['alLocalizacionPatiosDistribuidorHdn'], 0, -1));
        if(in_array('', $distArr)){
            $e[] = array('id'=>'alLocalizacionPatiosDistribuidorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $vinArr = explode('|', substr($_REQUEST['alLocalizacionPatiosVinTxt'], 0, -1));
        if(in_array('', $vinArr)){
            $e[] = array('id'=>'alLocalizacionPatiosVinTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $vinError = array();
            $success = false;

            for ($nInt=0; $nInt < sizeof($vinArr); $nInt++) {
                $grupoCol = 'grupo';
                $ordenCol = 'orden';

                $grupo = getGrupoPorDistribuidor($distArr[$nInt]);

                if ($grupo == -1) {
                    $grupo = getGrupoPorSimbolo($simboloArr[$nInt]);
                }
                if ($grupo == -1) {
                        $a['success'] = false;  
                        array_push($vinError, $vinArr[$nInt]);
                } else {
                    $dataArr = ordenMinimo($grupo, $grupoCol, $ordenCol);
                    if (sizeof($dataArr) == 0) {
                        $grupoCol = 'grupo1';
                        $ordenCol = 'orden1';
                        $dataArr = ordenMinimo($grupo, $grupoCol, $ordenCol);
                    }
                    if (sizeof($dataArr) == 0) {
                        $grupoCol = 'grupo2';
                        $ordenCol = 'orden2';
                        $dataArr = ordenMinimo($grupo, $grupoCol, $ordenCol);
                    }
                    
                    if (sizeof($dataArr) == 0) {
                        $a['success'] = false;
                        array_push($vinError, $vinArr[$nInt]);
                    } else {
                        $sqlAddLocalizacionPatios = "UPDATE alLocalizacionPatiosTbl ".
                                                    "SET vin='".$vinArr[$nInt]."' ".
                                                    "WHERE ".$grupoCol."=".$grupo." ".
                                                    "AND ".$ordenCol."=".$dataArr['orden'];

                        $rs = fn_ejecuta_query($sqlAddLocalizacionPatios);

                        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                            $sqlUpdateHistoricoStr = "UPDATE alHistoricoUnidadesTbl SET ".
                                                     "localizacionUnidad = '".$dataArr['patio']."' ".
                                                     "WHERE vin = '".$vinArr[$nInt]."'";

                            $rs = fn_ejecuta_query($sqlUpdateHistoricoStr);

                            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                                $a['sql'] = $sqlAddLocalizacionPatios;
                                $success = true;
                            } else {
                                $a['success'] = false;
                                array_push($vinError, $vinArr[$nInt]);
                            }
                            
                        } else {
                            $a['success'] = false;
                            array_push($vinError, $vinArr[$nInt]);
                        }
                    }
                }
            }

            //Concatenate Errors
            if (sizeof($vinError) > 0) {
                if ($success == true) {
                    $a['errorMessage'] =  getLocalizacionSuccessMsg()."<br>".getLocalizacionFailedMsg();
                    foreach ($vinError as $vin) {
                        $a['errorMessage'] .= "<br>".$vin;
                    }
                } else if ($success == false){
                    $a['errorMessage'] = getLocalizacionFailedMsg();
                    foreach ($vinError as $vin) {
                        $a['errorMessage'] .= "<br>".$vin;
                    }
                }   
            } else if($success == true){
                $a['successMessage'] = getLocalizacionSuccessMsg();
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}

    /*function updLocalizacionPatios(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $idLocalizacionArr = explode('|', substr($_REQUEST['alLocalizacionPatiosIdLocalizacionHdn'], 0, -1));
        if(in_array('', $idLocalizacionArr)){
            $e[] = array('id'=>'alLocalizacionPatiosIdLocalizacionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $grupo1Arr = explode('|', substr($_REQUEST['alLocalizacionPatiosGrupo1Hdn'], 0, -1));
        if(in_array('', $grupo1Arr)){
            $e[] = array('id'=>'alLocalizacionPatiosGrupo1Hdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $grupo2Arr = explode('|', substr($_REQUEST['alLocalizacionPatiosGrupo2Hdn'], 0, -1));
        if(in_array('', $grupo2Arr)){
            $e[] = array('id'=>'alLocalizacionPatiosGrupo2Hdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $orden1Arr = explode('|', substr($_REQUEST['alLocalizacionPatiosOrden1Hdn'], 0, -1));
        if(in_array('', $orden1Arr)){
            $e[] = array('id'=>'alLocalizacionPatiosOrden1Hdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $orden2Arr = explode('|', substr($_REQUEST['alLocalizacionPatiosOrden2Hdn'], 0, -1));
        if(in_array('', $orden2Arr)){
            $e[] = array('id'=>'alLocalizacionPatiosOrden2Hdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $filaArr = explode('|', substr($_REQUEST['alLocalizacionPatiosFilaHdn'], 0, -1));
        if(in_array('', $filaArr)){
            $e[] = array('id'=>'alLocalizacionPatiosFilaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $lugarArr = explode('|', substr($_REQUEST['alLocalizacionPatiosLugarHdn'], 0, -1));
        if(in_array('', $lugarArr)){
            $e[] = array('id'=>'alLocalizacionPatiosLugarHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            for ($nInt=0; $nInt < sizeof($idLocalizacionArr); $nInt++) { 
                $sqlUpdLocalizacionPatiosStr = "UPDATE alLocalizacionPatiosTbl SET ".
                                               "grupo1 = ".$grupo1Arr[$nInt].",".
                                               "grupo2 = ".$grupo2Arr[$nInt].",".
                                               "orden1 = ".$orden1Arr[$nInt].",".
                                               "orden2 = ".$orden2Arr[$nInt]." ".
                                               "WHERE idLocalizacion = ".$idLocalizacionArr[$nInt];

                $rs = fn_ejecuta_query($sqlUpdLocalizacionPatiosStr);

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $a['sql'] = $sqlUpdLocalizacionPatiosStr;
                } else {
                    $a['success'] = false;
                    array_push($errorArr, $filaArr[$nInt]." - ".$lugarArr[$nInt]);
                }  
            }

            //Concatenate Errors
            if (sizeof($errorArr) > 0) {
                if ($success == true) {
                    $a['errorMessage'] =  getLocalizacionPatiosUpdMsg()."<br>".getLocalizacionPatiosUpdFailedMsg();
                    foreach ($errorArr as $error) {
                        $a['errorMessage'] .= "<br>".$error;
                    }
                } else if ($success == false){
                    $a['errorMessage'] = getLocalizacionPatiosUpdFailedMsg();
                    foreach ($errorArr as $error) {
                        $a['errorMessage'] .= "<br>".$error;
                    }
                }   
            } else if($success == true){
                $a['successMessage'] = getLocalizacionPatiosUpdMsg();
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }*/

    function dltLocalizacionPatios(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['alLocalizacionPatiosVinTxt'] == ""){
            $e[] = array('id'=>'alLocalizacionPatiosVinTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlDltLocalizacionPatiosStr = "UPDATE alLocalizacionPatiosTbl ".
                                           "SET vin= '' WHERE vin='".$_REQUEST['alLocalizacionPatiosVinTxt']."'";

            $rs = fn_ejecuta_query($sqlDltLocalizacionPatiosStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlDltLocalizacionPatiosStr;
                $a['successMessage'] = getLocalizacionPatiosDeleteMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDltLocalizacionPatiosStr;
            }   
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function addGrupo(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['alLocalizacionPatiosGrupoHdn'] == ""){
            $e[] = array('id'=>'alLocalizacionPatiosGrupoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['alLocalizacionPatiosCentroDistHdn'] == ""){
            $e[] = array('id'=>'alLocalizacionPatiosCentroDistHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $marcaArr = explode('|', substr($_REQUEST['alLocalizacionPatiosMarcaHdn'], 0, -1));
        if(in_array('', $marcaArr)){
            $e[] = array('id'=>'alLocalizacionPatiosMarcaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
         
        if($a['success'] == true){
            $clasificacionArr = explode('|', substr($_REQUEST['alLocalizacionPatiosClasificacionHdn'], 0, -1));
            $distArr = explode('|', substr($_REQUEST['alLocalizacionPatiosDistribuidorHdn'], 0, -1));

            if ($_REQUEST['alLocalizacionPatiosClasificacionHdn'] != "") {
                $marcaClasifArr = $marcaArr;
                $marcaDistArr = '';
                $records = sizeof($clasificacionArr);
            } else if($_REQUEST['alLocalizacionPatiosDistribuidorHdn'] != ""){
                $marcaDistArr = $marcaArr;
                $marcaClasifArr = '';
                $records = sizeof($clasificacionArr);
            } else {
                $a['success'] = false;
            }

            if ($a['success'] == true) {
                //Clean data of group of the table to insert new
                $sqlCleanGrupoStr = "DELETE FROM alGruposPatioTbl WHERE grupo = ".$_REQUEST['alLocalizacionPatiosGrupoHdn'];

                $rs = fn_ejecuta_query($sqlCleanGrupoStr);  

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $errorarr = array();
                    
                    for ($nInt=0; $nInt < $records; $nInt++) {
                        $sqlAddGrupoStr = "INSERT INTO alGruposPatioTbl ".
                                          "(grupo, clasificacion, marcaClasificacion, distribuidor, marcaDistribuidor, centroDistribucion) ".
                                          "VALUES(".
                                          $_REQUEST['alLocalizacionPatiosGrupoHdn'].", ".
                                          replaceEmptyNull("'".$clasificacionArr[$nInt]."'").", ".
                                          replaceEmptyNull("'".$marcaClasifArr[$nInt]."'").", ".
                                          replaceEmptyNull("'".$distArr[$nInt]."'").", ".
                                          replaceEmptyNull("'".$marcaDistArr[$nInt]."'").", ".
                                          "'".$_REQUEST['alLocalizacionPatiosCentroDistHdn']."', ".
                                          replaceEmptyNull("'".$_REQUEST['alLocalizacionPatiosColorHdn']."'").")";
                        
                        $rs = fn_ejecuta_query($sqlAddGrupoStr);

                        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                            $a['successMessage'] = getGrupoSuccessMsg();
                        } else {
                            if($marcaClasif != ''){
                                array_push($errorArr, $clasificacionArr[$nInt]);
                            } else {
                                array_push($errorArr, $distArr[$nInt]);
                            }
                            $a['success'] = false;
                        }  
                    }
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCleanGrupoStr;
                }

                //Concatenate errors
                if ($a['success'] == false) {
                    if ($marcaClasif != '') {
                        $a['errorMessage'] = getGrupoFailedClasifMsg();
                    } else {
                        $a['errorMessage'] = getGrupoFailedDistMsg();
                    }

                    foreach ($errorArr as $error) {
                        $a['errorMessage'] .= "<br>".$error;
                    }
                }

            } else {
                $a['errorMessage'] = getGrupoMissingDataMsg();
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
    
    function addLugaresGrupo(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['alLocalizacionPatiosPatioHdn'] == ""){
            $e[] = array('id'=>'alLocalizacionPatiosPatioHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['alLocalizacionPatiosGrupoHdn'] == ""){
            $e[] = array('id'=>'alLocalizacionPatiosGrupoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $grupo1Arr = explode('|', substr($_REQUEST['alLocalizacionPatiosGrupo1Hdn'], 0, -1));
        if(in_array('', $grupo1Arr)){
            $e[] = array('id'=>'alLocalizacionPatiosGrupo1Hdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $grupo2Arr = explode('|', substr($_REQUEST['alLocalizacionPatiosGrupo2Hdn'], 0, -1));
        if(in_array('', $grupo2Arr)){
            $e[] = array('id'=>'alLocalizacionPatiosGrupo2Hdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $filaArr = explode('|', substr($_REQUEST['alLocalizacionPatiosFilaHdn'], 0, -1));
        if(in_array('', $filaArr)){
            $e[] = array('id'=>'alLocalizacionPatiosFilaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $lugarArr = explode('|', substr($_REQUEST['alLocalizacionPatiosLugarHdn'], 0, -1));
        if(in_array('', $lugarArr)){
            $e[] = array('id'=>'alLocalizacionPatiosLugarHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $ordenArr = explode('|', substr($_REQUEST['alLocalizacionPatiosOrdenHdn'], 0, -1));
        if(in_array('', $ordenArr)){
            $e[] = array('id'=>'alLocalizacionPatiosOrdenHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $orden1Arr = explode('|', substr($_REQUEST['alLocalizacionPatiosOrden1Hdn'], 0, -1));
        if(in_array('', $orden1Arr)){
            $e[] = array('id'=>'alLocalizacionPatiosOrden1Hdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $orden2Arr = explode('|', substr($_REQUEST['alLocalizacionPatiosOrden2Hdn'], 0, -1));
        if(in_array('', $orden2Arr)){
            $e[] = array('id'=>'alLocalizacionPatiosOrden2Hdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
         

        if ($a['success'] == true) {
            $errorArr = array();
            $idLocalizacionArr = explode('|', substr($_REQUEST['alLocalizacionPatiosIdLocalizacionHdn'], 0, -1));
            
            for ($nInt=0; $nInt < sizeof($lugarArr); $nInt++) {
                if ($idLocalizacionArr[$nInt] == '') {
                    $sqlAddLugaresGrupoStr = "INSERT INTO alLocalizacionPatiosTbl ".
                                             "(patio, grupo, grupo1, grupo2, fila, lugar, orden, orden1, orden2)".
                                             "VALUES (".
                                                "'".$_REQUEST['alLocalizacionPatiosPatioHdn']."',".
                                                $_REQUEST['alLocalizacionPatiosGrupoHdn'].",".
                                                $grupo1Arr[$nInt].",".
                                                $grupo2Arr[$nInt].",".
                                                "'".$filaArr[$nInt]."',".
                                                $lugarArr[$nInt].",".
                                                $ordenArr[$nInt].",".
                                                $orden1Arr[$nInt].",".
                                                $orden2Arr[$nInt].")";
                    
                    $rs = fn_ejecuta_query($sqlAddLugaresGrupoStr);

                    if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                        $a['successMessage'] = getGrupoLugaresSuccessMsg();
                    } else {
                        $a['success'] = false;
                        array_push($errorArr, "Fila: ".$filaArr[$nInt].", Lugar: ".$lugarArr[$nInt]);
                    }
                } else {
                     $sqlUpdLocalizacionPatiosStr = "UPDATE alLocalizacionPatiosTbl SET ".
                                                   "grupo1 = ".$grupo1Arr[$nInt].",".
                                                   "grupo2 = ".$grupo2Arr[$nInt].",".
                                                   "orden1 = ".$orden1Arr[$nInt].",".
                                                   "orden2 = ".$orden2Arr[$nInt]." ".
                                                   "WHERE idLocalizacion = ".$idLocalizacionArr[$nInt];

                    $rs = fn_ejecuta_query($sqlUpdLocalizacionPatiosStr);         

                    if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                        $a['successMessage'] = getGrupoLugaresSuccessMsg();
                    } else {
                        $a['success'] = false;
                        array_push($errorArr, "Fila: ".$filaArr[$nInt].", Lugar: ".$lugarArr[$nInt]);
                    }
                }
            }

            //Concatenate errors
            if ($a['success'] == false) {
                $a['errorMessage'] = getGrupoLugaresFailedMsg();
                foreach ($errorArr as $error) {
                    $a['errorMessage'] .= "<br>".$error;
                }
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

	function ordenMinimo($grupo, $grupoCol, $ordenCol){
		$sqlGetPosiblesLugaresStr = "SELECT patio, vin, ".$ordenCol.", fila, lugar ".
									"FROM alLocalizacionPatiosTbl ".
									"WHERE ".$grupoCol."=".$grupo." ".
                                    "ORDER BY ".$ordenCol;

		$rs = fn_ejecuta_query($sqlGetPosiblesLugaresStr);

		if (sizeof($rs['root']) > 0) {
            for ($nInt=0; $nInt < sizeof($rs['root']); $nInt++) { 
                if ($rs['root'][$nInt]['vin'] == '') {
                    return array('patio'=>$rs['root'][$nInt]['patio'], 'orden'=>$rs['root'][$nInt][$ordenCol],'fila'=>$rs['root'][$nInt]['fila'], 'lugar'=>$rs['root'][$nInt]['lugar']);
                }
            }
		}

		return array();
	}

    function getGrupoPorSimbolo($simbolo){
        $sqlGetGrupoPorSimboloStr = "SELECT gp.grupo ".
                                    "FROM caSimbolosUnidadesTbl su, alGruposPatioTbl gp ".
                                    "WHERE gp.clasificacion = su.clasificacion ".
                                    "AND su.simboloUnidad = '".$simbolo."'";

        $rs = fn_ejecuta_query($sqlGetGrupoPorSimboloStr);
        
        if (sizeof($rs['root']) > 0) {
            return $rs['root'][0]['grupo'];
        }

        return -1;
    }

    function getGrupoPorDistribuidor($distribuidor){
        $sqlGetGrupoPorDistribuidorStr = "SELECT grupo ".
                                         "FROM alGruposPatioTbl ".
                                         "WHERE distribuidor ='".$distribuidor."'";

        $rs = fn_ejecuta_query($sqlGetGrupoPorDistribuidorStr);

        if (sizeof($rs['root']) > 0) {
            return $rs['root'][0]['grupo'];
        }

        return -1;
    }
?>