<?php
    session_start();
	$_SESSION['modulo'] = "catGenerales";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");
	
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
	
    switch($_REQUEST['catGeneralesActionHdn']){
        case 'getGeneralesGroup':
            getGeneralesGroup();
            break;
        case 'getGenerales':
            getGenerales();
            break;
		case 'addGenerales':
            addGenerales();
            break;
        default:
            echo '';
    }
			
	function getGeneralesGroup() {
    	$lsWhereStr = "";
		
		if($_SESSION['idioma'] && $_SESSION['idioma'] != "")
			$lsWhereStr = "WHERE idioma = '" . $_SESSION['idioma'] . "' ";

	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catGeneralesTablaTxt'], "tabla", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catGeneralesColumnaTxt'], "columna", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

		$sqlGetGeneralesGroupStr = "SELECT tabla, columna " .
            		               "FROM caGeneralesTbl  " . $lsWhereStr .
            		               "GROUP BY tabla, columna";      
		
		$rs = fn_ejecuta_query($sqlGetGeneralesGroupStr);
			
		echo json_encode($rs);
	}	

	function getGenerales() {
		$lsWhereStr = "";
		
		if($_SESSION['idioma'] && $_SESSION['idioma'] != ""){
			$lsWhereStr = "WHERE idioma = '" . $_SESSION['idioma'] . "' ";
        }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catGeneralesTablaTxt'], "tabla", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catGeneralesColumnaTxt'], "columna", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catGeneralesValorTxt'], "valor", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catGeneralesNombreTxt'], "nombre", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catGeneralesEstatusHdn'], "estatus", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catGeneralesIdiomaHdn'], "idioma", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }

		$sqlGetGeneralesStr = "SELECT tabla, columna, valor, nombre, estatus, idioma " .
		                      "FROM caGeneralesTbl " . $lsWhereStr;

		$rs = fn_ejecuta_query($sqlGetGeneralesStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['nombreValor'] = $rs['root'][$iInt]['valor']." - ".$rs['root'][$iInt]['nombre'];
        }
			
		echo json_encode($rs);	
	}
	
	function addGenerales() {
        $a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catGeneralesTablaTxt'] == "")
        {
            $e[] = array('id'=>'catGeneralesTablaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesColumnaTxt'] == "")
        {
            $e[] = array('id'=>'catGeneralesColumnaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesValorHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesValorHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesNombreHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesNombreHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catGeneralesIdiomaHdn'] == "")
        {
            $e[] = array('id'=>'catGeneralesIdiomaHdn','msg'=>getRequerido());
		    $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

		//Revisar que ningun dato del grid esté vacio
        $valorArr = explode('|', substr($_REQUEST['catGeneralesValorHdn'], 0, -1));
        if(in_array('', $valorArr)){
        	$e[] = array('id'=>'catGeneralesValorHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

		$estatusArr =  explode('|', substr($_REQUEST['catGeneralesEstatusHdn'], 0, -1));
		if(in_array('', $estatusArr)){
        	$e[] = array('id'=>'catGeneralesEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

		$nombreArr = explode('|', substr($_REQUEST['catGeneralesNombreHdn'], 0, -1));
		if(in_array('', $nombreArr)){
        	$e[] = array('id'=>'catGeneralesNombreHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		$idiomaArr = explode('|', substr($_REQUEST['catGeneralesIdiomaHdn'], 0, -1));
		if(in_array('', $idiomaArr)){
        	$e[] = array('id'=>'catGeneralesIdiomaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){

            $sqlCleanGeneralesTblColStr = "DELETE FROM caGeneralesTbl ".
                                          "WHERE tabla = '".$_REQUEST['catGeneralesTablaTxt']."' ".
                                          "AND columna = '".$_REQUEST['catGeneralesColumnaTxt']."' ";

            $rs = fn_ejecuta_query($sqlCleanGeneralesTblColStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['errorMessage'] = "";
                for($nInt=0;$nInt<count($valorArr);$nInt++){
                    $sqlAddGeneralesStr = "INSERT INTO caGeneralesTbl VALUES".
                                            "('".$_REQUEST['catGeneralesTablaTxt']."', ".
                                            "'".$_REQUEST['catGeneralesColumnaTxt']."', ".
                                            "'".$valorArr[$nInt]."', ".
                                            "'".$nombreArr[$nInt]."', ".
                                            "'".$estatusArr[$nInt]."', ".
                                            "'".$idiomaArr[$nInt]."')";

                    $rs = fn_ejecuta_query($sqlAddGeneralesStr);

                    if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                        $a['sql'] = $sqlAddGeneralesStr;
                        $a['successMessage'] = getGeneralesSuccessMsg();
                        $a['id'] = $_REQUEST['catGeneralesTablaTxt'];
                    } else {
                        $a['success'] = false;
                        if ($a['errorMessage'] != "") {
                            $a['errorMessage'] .= ",";
                        }
                        $a['errorMessage'] .= $nInt;
                    }
                }
                $a['errorMessage'] = "Error al insertar registro(s): ".$a['errorMessage'];   
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddGeneralesStr;
            }
		}
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
?>