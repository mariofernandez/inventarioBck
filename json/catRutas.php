<?php
    session_start();
	$_SESSION['modulo'] = "catImpuestos";
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
	
    switch($_REQUEST['catRutasActionHdn']){
        case 'getRutas':
            getRutas();
            break;
        case 'addRutas':
        	addRutas();
        	break;
        case 'updRutas':
        	updRutas();
        	break;
        case 'dltRutas':
        	dltRutas();
        	break;
        default:            
    }

    function getRutas(){
        $lsWhereStr = "";

    	if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catRutasRutaTxt'], "r.ruta", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catRutasDescripcionTxt'], "r.rutaDescripcion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catRutasEstatusHdn'], "r.estatus", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catRutasPlazaOrigenHdn'], "rd.idPlazaOrigen", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catRutasPlazaDestinoHdn'], "rd.IdPlazaDestino", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

	    $sqlGetRutasStr = "SELECT r.ruta, r.rutaDescripcion, r.estatus, rd.idPlazaOrigen, rd.IdPlazaDestino, ".
                            "(SELECT cg.nombre FROM caGeneralesTbl cg WHERE cg.tabla = 'caRutasTbl' ".
                                "AND cg.columna = 'estatus' AND cg.valor = r.estatus) nombreEstatus, ".
                            "(SELECT p1.plaza FROM caPlazasTbl p1 where p1.idPlaza = rd.idPlazaOrigen) as nombreOrigen, ".
                            "(SELECT p2.plaza FROM caPlazasTbl p2 where p2.idPlaza = rd.idPlazaDestino) as nombreDestino ".
                            "FROM caRutasTbl r LEFT JOIN caRutasDetalleTbl rd ON rd.ruta = r.ruta " . $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetRutasStr);
			
		echo json_encode($rs);
    }

    function addRutas(){
    	$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catRutasRutaTxt'] == ""){
            $e[] = array('id'=>'catRutasRutaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catRutasDescripcionTxt'] == ""){
            $e[] = array('id'=>'catRutasDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catRutasEstatusHdn'] == ""){
            $e[] = array('id'=>'catRutasEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        
        //Detalle paipeado
        if ($_REQUEST['catRutasPlazaOrigenHdn'] != "") {
            $origenArr = explode('|', substr($_REQUEST['catRutasPlazaOrigenHdn'], 0, -1));
            if(in_array('', $origenArr)){
                $e[] = array('id'=>'catRutasPlazaOrigenHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            }
        } else {
            $e[] = array('id'=>'catRutasPlazaOrigenHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['catRutasPlazaDestinoHdn'] != "") {
            $destinoArr = explode('|', substr($_REQUEST['catRutasPlazaDestinoHdn'], 0, -1));
            if(in_array('', $destinoArr)){
                $e[] = array('id'=>'catRutasPlazaDestinoHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            }
        } else {
            $e[] = array('id'=>'catRutasPlazaDestinoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
            $sqlAddTarifaStr = "INSERT INTO caRutasTbl (ruta, rutaDescripcion, estatus) ".
                               "VALUES (".
                               "'".$_REQUEST['catRutasRutaTxt']."', ".
                               "'".$_REQUEST['catRutasDescripcionTxt']."', ".
                               "'".$_REQUEST['catRutasEstatusHdn']."')";
            
            $rs = fn_ejecuta_query($sqlAddTarifaStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $sqlCleanStr = "DELETE FROM caRutasDetalleTbl ".
                               "WHERE ruta='".$_REQUEST['catRutasRutaTxt']."'";

                $rs = fn_ejecuta_query($sqlCleanStr);

                $sqlAddRutaDetalleStr = "INSERT INTO caRutasDetalleTbl (ruta, idPlazaOrigen, IdPlazaDestino) VALUES";

                for ($iInt=0; $iInt < sizeof($origenArr); $iInt++) { 
                    if ($iInt != 0) {
                        $sqlAddRutaDetalleStr .= ",";
                    }

                    $sqlAddRutaDetalleStr .= "('".$_REQUEST['catRutasRutaTxt']."',".
                                                  $origenArr[$iInt].",".
                                                  $destinoArr[$iInt].")";
                }

                $rs = fn_ejecuta_query($sqlAddRutaDetalleStr);   

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $a['sql'] = $sqlAddTarifaStr."<br>".$sqlAddRutaDetalleStr;
                    $a['successMessage'] = getRutasSuccessMsg();
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddRutaDetalleStr;
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddTarifaStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updRutas(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catRutasRutaTxt'] == ""){
            $e[] = array('id'=>'catRutasRutaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catRutasDescripcionTxt'] == ""){
            $e[] = array('id'=>'catRutasDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catRutasEstatusHdn'] == ""){
            $e[] = array('id'=>'catRutasEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        //Detalle paipeado
        if ($_REQUEST['catRutasPlazaOrigenHdn'] != "") {
            $origenArr = explode('|', substr($_REQUEST['catRutasPlazaOrigenHdn'], 0, -1));
            if(in_array('', $origenArr)){
                $e[] = array('id'=>'catRutasPlazaOrigenHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            }
        } else {
            $e[] = array('id'=>'catRutasPlazaOrigenHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($_REQUEST['catRutasPlazaDestinoHdn'] != "") {
            $destinoArr = explode('|', substr($_REQUEST['catRutasPlazaDestinoHdn'], 0, -1));
            if(in_array('', $destinoArr)){
                $e[] = array('id'=>'catRutasPlazaDestinoHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            }
        } else {
            $e[] = array('id'=>'catRutasPlazaDestinoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
            $sqlUpdRutaStr =  "UPDATE caRutasTbl ".
                              "SET rutaDescripcion = '".$_REQUEST['catRutasDescripcionTxt']."', ".
                              "estatus = '".$_REQUEST['catRutasEstatusHdn']."' ".
                              "WHERE ruta ='".$_REQUEST['catRutasRutaTxt']."'";

            $rs = fn_ejecuta_query($sqlUpdRutaStr);
            
            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $sqlCleanStr = "DELETE FROM caRutasDetalleTbl ".
                               "WHERE ruta='".$_REQUEST['catRutasRutaTxt']."'";

                $rs = fn_ejecuta_query($sqlCleanStr);

                $sqlAddRutaDetalleStr = "INSERT INTO caRutasDetalleTbl (ruta, idPlazaOrigen, IdPlazaDestino) VALUES";

                for ($iInt=0; $iInt < sizeof($origenArr); $iInt++) { 
                    if ($iInt != 0) {
                        $sqlAddRutaDetalleStr .= ",";
                    }

                    $sqlAddRutaDetalleStr .= "('".$_REQUEST['catRutasRutaTxt']."',".
                                                  $origenArr[$iInt].",".
                                                  $destinoArr[$iInt].")";
                }

                $rs = fn_ejecuta_query($sqlAddRutaDetalleStr);   

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $a['sql'] = $sqlUpdRutaStr."<br>".$sqlAddRutaDetalleStr;
                    $a['successMessage'] = getRutasSuccessMsg();
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddRutaDetalleStr;
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdRutaStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>