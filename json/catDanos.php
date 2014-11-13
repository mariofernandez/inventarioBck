<?php
	session_start();
	$_SESSION['modulo'] = "catDanos";
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

	switch ($_REQUEST['catDanosActionHdn']) {
		case 'getDanos':
			getDanos();
			break;
		case 'addDanos':
			addDanos();
			break;
		case 'updDanos':
			updDanos();
			break;
	}

	function getDanos(){
		$lsWhereStr = "";

		if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDanosIdDanoHdn'], "da.iDano", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDanosAreaHdn'], "da.areaDano", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDanosTipoHdn'], "da.tipoDano", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDanosSeveridadHdn'], "da.severidadDano", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

		$sqlGetDanosStr = "SELECT da.*, ".
						  "(SELECT cg.nombre FROM caGeneralesTbl cg WHERE cg.tabla = 'caDanosTbl' ".
						  		"AND cg.columna = 'areaDano' AND cg.valor = da.areaDano) AS nombreArea, ".
						  "(SELECT cg1.nombre FROM caGeneralesTbl cg1 WHERE cg1.tabla = 'caDanosTbl' ".
						  	"AND cg1.columna = 'tipoDano' AND cg1.valor = da.tipoDano) AS nombreTipo, ".
						  "(SELECT cg2.nombre FROM caGeneralesTbl cg2 WHERE cg2.tabla = 'caDanosTbl' ".
						  	"AND cg2.columna = 'severidadDano' AND cg2.valor = da.severidadDano) AS nombreSeveridad ".
						  "FROM caDanosTbl da ";

        $rs = fn_ejecuta_query($sqlGetDanosStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descArea'] = $rs['root'][$iInt]['areaDano']." - ".$rs['root'][$iInt]['nombreArea'];
            $rs['root'][$iInt]['descTipo'] = $rs['root'][$iInt]['tipoDano']." - ".$rs['root'][$iInt]['nombreTipo'];
            $rs['root'][$iInt]['descSeveridad'] = $rs['root'][$iInt]['severidadDano']." - ".$rs['root'][$iInt]['nombreSeveridad'];
            
        }

		echo json_encode($rs);
	}

	function addDanos(){
		$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catDanosAreaHdn'] == ""){
            $e[] = array('id'=>'catDanosAreaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDanosTipoHdn'] == ""){
            $e[] = array('id'=>'catDanosTipoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDanosSeveridadHdn'] == ""){
            $e[] = array('id'=>'catDanosSeveridadHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
        	$sqlAddDanosStr = "INSERT INTO caDanosTbl (areaDano,tipoDano,severidadDano) ".
        					  "VALUES (".
        					  	"'".$_REQUEST['catDanosAreaHdn']."',".
        					  	"'".$_REQUEST['catDanosTipoHdn']."',".
        					  	"'".$_REQUEST['catDanosSeveridadHdn']."')";

			$rs = fn_ejecuta_query($sqlAddDanosStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddDanosStr;
                $a['successMessage'] = getDanosSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddDanosStr;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}

	function updDanos(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catDanosIdDanoHdn'] == ""){
            $e[] = array('id'=>'catDanosIdDanoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDanosAreaHdn'] == ""){
            $e[] = array('id'=>'catDanosAreaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDanosTipoHdn'] == ""){
            $e[] = array('id'=>'catDanosTipoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDanosSeveridadHdn'] == ""){
            $e[] = array('id'=>'catDanosSeveridadHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdDanosStr = "UPDATE caDanosTbl SET ".
                              "areaDano = '".$_REQUEST['catDanosAreaHdn']."', ".
                              "tipoDano = '".$_REQUEST['catDanosTipoHdn']."', ".
                              "severidadDano = '".$_REQUEST['catDanosSeveridadHdn']."' ".
                              "WHERE idDano = ".$_REQUEST['catDanosIdDanoHdn'];


            $rs = fn_ejecuta_query($sqlUpdDanosStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdDanosStr;
                $a['successMessage'] = getDanosUpdMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdDanosStr;
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
?>