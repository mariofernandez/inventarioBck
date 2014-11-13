<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
    session_start();
	$_SESSION['modulo'] = "catMarcasUnidades";
    require("../funciones/generalesPDO.php");
    require("../funciones/construct.php");
    require("../funciones/utilidades.php");
	
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

        switch($_REQUEST['catDistribuidoresActionHdn'])
	{
        case 'getDistribuidores':
            getDistribuidores();
            break;
        case 'getSucursalDe':
            getSucursalDe();
            break;
        case 'addDistribuidor':
        	addDistribuidor();
            break;
        case 'updDistribuidor':
            updDistribuidor();
            break;                                                 
        default:
            echo '';
    }

   	function getDistribuidores() {
		$ls_where = "" ; 

	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['distribuidortxt'], "distribuidorCentro", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }

		$sqlGetDistribuidoresStr = "SELECT distribuidorCentro, descripcionCentro, observaciones, telefono, fax, contacto, email, rutaDestino,sueldoGarantizado " .
		                           "FROM cadistribuidorescentrostbl  " . $ls_where;       
		
		$rs = fn_ejecuta_query($sqlGetDistribuidoresStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
    }

   	function getSucursalDe() {
		$ls_where = "" ; 

	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['distribuidortxt'], "distribuidorCentro", 1);
		    $ls_where = fn_concatena_condicion($ls_where, $ls_condicion);
	    }

		$sqlGetSucursalDeStr = "SELECT distribuidorCentro, concat(distribuidorCentro,' - ',descripcionCentro) as descDistribuidor " .
		                       "FROM cadistribuidorescentrostbl ".$ls_where;       
		
		$rs = fn_ejecuta_query($sqlGetSucursalDeStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
    }

    function addDistribuidor() {
    	$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catDistribuidoresDistribuidorTxt'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresDistribuidorTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDescripcionTxt'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresDescripcionTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresTipoHdn'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresTipoHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresPlazaHdn'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresPlazaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDirFiscalHdn'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresDirFiscalHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDirEntregaHdn'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresDirEntregaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresEstatusHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true){
        	$sqlAddDistribuidorStr = "INSERT INTO cadistribuidorescentrostbl ".
        						  "VALUES (".
        						  "'".$_REQUEST['catDistribuidoresDistribuidorTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresDescripcionTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresTipoHdn']."', ".
        						  $_REQUEST['catDistribuidoresPlazaHdn'].", ".
        						  "'".$_REQUEST['catDistribuidoresObservacionesTxa']."', ".
        						  "'".$_REQUEST['catDistribuidoresTelefonoTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresFaxTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresContactoTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresEmailTxt']."', ".
        						  "'".$_REQUEST['catDistribuidoresRutaDestinoHdn']."', ".
        						  replaceEmptyNull("'".$_REQUEST['catDistribuidoresSucursalDeHdn']."'"). ", ".
								  $_REQUEST['catDistribuidoresDirFiscalHdn'].", ".
								  $_REQUEST['catDistribuidoresDirEntregaHdn'].", ".
								  "'".$_REQUEST['catDistribuidoresSueldoTxt']."', ".
								  replaceEmptyNull($_REQUEST['catDistribuidoresIdRegionHdn']).", ".
								  "'".$_REQUEST['catDistribuidoresEstatusHdn']."')";

			$rs = fn_ejecuta_query($sqlAddDistribuidorStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddDistribuidorStr;
            	$a['successMessage'] = getDistribuidoresSuccessMsg();
			} else {
            	$a['success'] = false;
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddDistribuidorStr;
        	}
		}

        if ($a['success'] == true) {

            $calleNumeroArr = explode('|', substr($_REQUEST['catDireccionesCalleNumeroTxt'], 0, -1));
            $coloniaArr = explode('|', substr($_REQUEST['catDireccionesIdColoniaHdn'], 0, -1));
            
            for($nInt=0; $nInt<count($calleNumeroArr); $nInt++){

                //SELECT para obtener ID de las direcciones a actualizar
                $sqlGetIdDireccionStr = "SELECT direccion from cadireccionestbl ".
                                        "WHERE calleNumero= '".$calleNumeroArr[$nInt]."' ".
                                        "AND idColonia= '".$coloniaArr[$nInt]."' ".
                                        "AND distribuidor IS NULL";

                $rs = fn_ejecuta_query($sqlGetIdDireccionStr);
                
                foreach($rs as $line){
                    $idDireccionInt = $line['direccion'];
                }
                
                //UPDATE a campos NULL de las direcciones por el distribuidor ya creado
                $sqlUpdDireccionesNULLStr = "UPDATE cadireccionestbl ".
                                            "SET distribuidor= '".$_REQUEST['catDistribuidoresDistribuidorTxt']."' ".
                                            "WHERE direccion=".$idDireccionInt;

                $rs = fn_ejecuta_query($sqlUpdDireccionesNULLStr);

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                    $a['sql'] = $sqlUpdDireccionesNULLStr;
                } else {
                    $a['success'] = false;
                }
            }
        }

		$a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updDistribuidor() {
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catDistribuidoresDistribuidorTxt'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresDistribuidorTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDescripcionTxt'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresTipoHdn'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresTipoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresPlazaHdn'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresPlazaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDirFiscalHdn'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresDirFiscalHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresDirEntregaHdn'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresDirEntregaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDistribuidoresEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catDistribuidoresEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdDistribuidoresStr = "UPDATE cadistribuidorescentrostbl ".
                                       "SET descripcionCentro= '".$_REQUEST['catDistribuidoresDescripcionTxt']."', ".
                                       "tipoDistribuidor= '".$_REQUEST['catDistribuidoresTipoHdn']."', ".
                                       "idPlaza=".$_REQUEST['catDistribuidoresPlazaHdn'].", ".
                                       "observaciones= '".$_REQUEST['catDistribuidoresObservacionesTxa']."', ".
                                       "telefono= '".$_REQUEST['catDistribuidoresTelefonoTxt']."', ".
                                       "fax= '".$_REQUEST['catDistribuidoresFaxTxt']."', ".
                                       "contacto= '".$_REQUEST['catDistribuidoresContactoTxt']."', ".
                                       "email= '".$_REQUEST['catDistribuidoresEmailTxt']."', ".
                                       "rutaDestino= '".$_REQUEST['catDistribuidoresRutaDestinoHdn']."', ".
                                       "sucursalDe=".replaceEmptyNull("'".$_REQUEST['catDistribuidoresSucursalDeHdn']."'").", ".
                                       "direccionFiscal=".$_REQUEST['catDistribuidoresDirFiscalHdn'].", ".
                                       "direccionEntrega=".$_REQUEST['catDistribuidoresDirEntregaHdn'].", ".
                                       "sueldoGarantizado= '".$_REQUEST['catDistribuidoresSueldoTxt']."', ".
                                       "idRegion=".replaceEmptyNull($_REQUEST['catDistribuidoresIdRegionHdn']).", ".
                                       "estatus= '".$_REQUEST['catDistribuidoresEstatusHdn']."' ".
                                       "WHERE distribuidorCentro= '".$_REQUEST['catDistribuidoresDistribuidorTxt']."'";
            
            $rs = fn_ejecuta_query($sqlUpdDistribuidoresStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlUpdDistribuidoresStr;
                $a['successMessage'] = getDistribuidoresUpdateMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdDistribuidoresStr;
            }
        }

        if ($a['success'] == true) {

            $calleNumeroArr = explode('|', substr($_REQUEST['catDireccionesCalleNumeroTxt'], 0, -1));
            $coloniaArr = explode('|', substr($_REQUEST['catDireccionesIdColoniaHdn'], 0, -1));
            $distArr = explode('|', substr($_REQUEST['catDireccionesDistribuidorHdn'], 0, -1));
            
            for($nInt=0; $nInt<count($calleNumeroArr); $nInt++){

                if($distArr[$nInt] == NULL){
                    //SELECT para obtener ID de las direcciones a actualizar
                    $sqlGetIdDireccionStr = "SELECT direccion from cadireccionestbl ".
                                            "WHERE calleNumero= '".$calleNumeroArr[$nInt]."' ".
                                            "AND idColonia= '".$coloniaArr[$nInt]."' ".
                                            "AND distribuidor IS NULL";

                    $rs = fn_ejecuta_query($sqlGetIdDireccionStr);
                
                    foreach($rs as $line){
                        $idDireccionInt = $line['direccion'];
                    }
                
                    //UPDATE a campos NULL de las direcciones por el distribuidor ya creado
                    $sqlUpdDireccionesNULLStr = "UPDATE cadireccionestbl ".
                                                "SET distribuidor= '".$_REQUEST['catDistribuidoresDistribuidorTxt']."' ".
                                                "WHERE direccion=".$idDireccionInt;

                    $rs = fn_ejecuta_query($sqlUpdDireccionesNULLStr);

                    if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                        $a['sql'] = $sqlUpdDireccionesNULLStr;
                    } else {
                        $a['success'] = false;
                    }
                }
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>