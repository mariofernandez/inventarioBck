<?php
	session_start();
	$_SESSION['modulo'] = "catConceptosCentros";
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

    switch($_REQUEST['catConceptosCentrosActionHdn']){
        case 'getConceptosCentros':
            getConceptosCentros();
            break;
        case 'getConceptosCentroSesion':
            getConceptosCentroSesion();
            break;
        case 'addConceptosCentros':
        	addConceptosCentros(); 
            break;
        case 'updConceptosCentros':
            updConceptosCentros();  
            break;                                                                                                  
        default:
            echo '';
    }

    function getConceptosCentros(){
    	$lsWhereStr = "WHERE cc.centroDistribucion = cd.distribuidorCentro ".
                      "AND cp.concepto = cc.concepto ";
	
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosCentrosCdistribucionHdn'], "centroDistribucion", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosCentrosConceptoHdn'], "concepto", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosCentrosCtaContableTxt'], "cuentaContable", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosCentrosTipoCuentaHdn'], "tipoCuenta", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosCentrosCptoNominaTxt'], "conceptoNomina", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosCentrosCalculoTxt'], "calculo", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catConceptosCentrosImporteTxt'], "importe", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }

	    $sqlGetConceptosCentrosStr = "SELECT cc.*, cd.descripcionCentro, cp.nombre, ".
	    							 "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.tabla='caConceptosCentrosTbl' ".
	    							 	"AND g.columna='tipoCuenta' AND g.valor = cc.tipoCuenta) as nombreTipoCuenta, ".
									 "(SELECT c.cuenta FROM coCuentasTbl c WHERE c.idCuenta = cc.cuentaContable ) as numeroCuentaContable  ".
	    							 "FROM caConceptosCentrosTbl cc, caDistribuidoresCentrosTbl cd, caConceptosTbl cp " . $lsWhereStr;     

		$rs = fn_ejecuta_query($sqlGetConceptosCentrosStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['descConcepto'] = $rs['root'][$iInt]['concepto']." - ".$rs['root'][$iInt]['nombre'];
        }
			
		echo json_encode($rs);
    }

    function getConceptosCentroSesion(){
        $sqlGetConceptosCentrosSesionStr = "SELECT cc.centroDistribucion, cc.concepto, cp.nombre ".
                                            "FROM caconceptoscentrostbl cc, caconceptostbl cp ".
                                            "WHERE cc.concepto = cp.concepto ".
                                            "AND cc.centroDistribucion = '".$_SESSION['usuCto']."'";

        $rs = fn_ejecuta_query($sqlGetConceptosCentrosSesionStr);

        echo json_encode($rs);
    }

    function addConceptosCentros(){
    	$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catConceptosCentrosCdistribucionHdn'] == ""){
            $e[] = array('id'=>'catConceptosCentrosCdistribucionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosConceptoHdn'] == ""){
            $e[] = array('id'=>'catConceptosCentrosConceptoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosCtaContableTxt'] == ""){
            $e[] = array('id'=>'catConceptosCentrosCtaContableTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosTipoCuentaHdn'] == ""){
            $e[] = array('id'=>'catConceptosCentrosTipoCuentaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosCptoNominaTxt'] == ""){
            $e[] = array('id'=>'catConceptosCentrosCptoNominaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosCalculoTxt'] == ""){
            $e[] = array('id'=>'catConceptosCentrosCalculoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosImporteTxt'] == ""){
            $e[] = array('id'=>'catConceptosCentrosImporteTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if($a['success'] == true){
        	$sqlAddConceptoCentro = "INSERT INTO caConceptosCentrosTbl ".
        							"VALUES(".
        							"'".$_REQUEST['catConceptosCentrosCdistribucionHdn']."', ".
        							"'".$_REQUEST['catConceptosCentrosConceptoHdn']."', ".
        							"'".$_REQUEST['catConceptosCentrosCtaContableTxt']."', ".
        							"'".$_REQUEST['catConceptosCentrosTipoCuentaHdn']."', ".
        							"'".$_REQUEST['catConceptosCentrosCptoNominaTxt']."', ".
        							"'".$_REQUEST['catConceptosCentrosCalculoTxt']."', ".
        							$_REQUEST['catConceptosCentrosImporteTxt'].")";

			$rs = fn_ejecuta_query($sqlAddConceptoCentro);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlAddConceptoCentro;
                $a['successMessage'] = getConceptosCentrosSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddConceptoCentro;

                $errorNoArr = explode(":", $_SESSION['error_sql']);
            	if($errorNoArr[0] == '1062'){
            		$e[] = array('id'=>'duplicate','msg'=>getConceptosCentrosDuplicateMsg());	
            	}
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updConceptosCentros(){
    	$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catConceptosCentrosCdistribucionHdn'] == ""){
            $e[] = array('id'=>'catConceptosCentrosCdistribucionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosConceptoHdn'] == ""){
            $e[] = array('id'=>'catConceptosCentrosConceptoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosCtaContableTxt'] == ""){
            $e[] = array('id'=>'catConceptosCentrosCtaContableTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosTipoCuentaHdn'] == ""){
            $e[] = array('id'=>'catConceptosCentrosTipoCuentaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosCptoNominaTxt'] == ""){
            $e[] = array('id'=>'catConceptosCentrosCptoNominaTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosCalculoTxt'] == ""){
            $e[] = array('id'=>'catConceptosCentrosCalculoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catConceptosCentrosImporteTxt'] == ""){
            $e[] = array('id'=>'catConceptosCentrosImporteTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
        	$sqlUpdateConceptosCentroStr =  "UPDATE caConceptosCentrosTbl ".
         							   		"SET cuentaContable= '".$_REQUEST['catConceptosCentrosCtaContableTxt']."', ".
   	 	        							"tipoCuenta= '".$_REQUEST['catConceptosCentrosTipoCuentaHdn']."', ".
   	 	        							"conceptoNomina= '".$_REQUEST['catConceptosCentrosCptoNominaTxt']."', ".
   	 	        							"calculo= '".$_REQUEST['catConceptosCentrosCalculoTxt']."', ".
   	 	        							"importe=".$_REQUEST['catConceptosCentrosImporteTxt']." ".
   	 	        							"WHERE centroDistribucion= '".$_REQUEST['catConceptosCentrosCdistribucionHdn']."' ".
   		        							"AND concepto= '".$_REQUEST['catConceptosCentrosConceptoHdn']."'";

        	$rs = fn_ejecuta_query($sqlUpdateConceptosCentroStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdateConceptosCentroStr;
                $a['successMessage'] = getConceptosCentrosUpdateMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdateConceptosCentroStr;
            }   
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>