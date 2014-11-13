<?php
    session_start();
    $_SESSION['modulo'] = "catDirecciones";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");

    $_REQUEST = trasformUppercase($_REQUEST);
	
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

    switch($_REQUEST['catDireccionesActionHdn']){
        case 'getDireccionesCombo':
            getDireccionesCombo();
            break;
        case 'getDirecciones':
        	getDirecciones();
        	break;
        case 'getDireccionesGrid':
            getDireccionesGrid();
            break;
        case 'getDireccionDistribuidor':
            getDireccionDistribuidor();
            break;
        case 'addDirecciones':
        	addDirecciones($_REQUEST['catDireccionesCalleNumeroHdn'], $_REQUEST['catDireccionesIdColoniaHdn'], $_REQUEST['catDireccionesDistribuidorHdn'], $_REQUEST['catDireccionesTipoHdn']);
        	break;
        case 'updDirecciones':
            updDirecciones($_REQUEST['catDireccionesDireccionHdn'], $_REQUEST['catDireccionesCalleNumeroHdn'], $_REQUEST['catDireccionesIdColoniaHdn'], $_REQUEST['catDireccionesDistribuidorHdn'], $_REQUEST['catDireccionesTipoHdn']);
            break;
        case 'dltDireccion':
            dltDireccion($_REQUEST['catDireccionesDireccionHdn']);
            break;
        case 'dltDireccionesMult':
            dltDireccionesMult();
        default:
        	echo '';          
    }

   	function getDireccionesCombo(){
		$lsWhereStr = "WHERE d.idColonia = c.idColonia ". 
		              "AND c.idMunicipio = m.idMunicipio ".
		              "AND m.idEstado = e.idEstado ".
		              "AND e.idPais = p.idPais ".
                      "AND (tipoDireccion='".$_REQUEST['catDistCentroTipoDireccionHdn']."' ".
                      "AND distribuidor='".$_REQUEST['catDistCentroDistribuidorHdn']."' ".
                      "OR distribuidor IS NULL ".
                      "AND tipoDireccion='".$_REQUEST['catDistCentroTipoDireccionHdn']."')";


		$sqlGetDireccionesComboStr = "SELECT d.calleNumero, c.colonia, m.municipio, ".
									 "e.estado, p.pais, c.cp, d.direccion " .
		       						 "FROM cadireccionestbl d, cacoloniastbl c, camunicipiostbl m, capaisestbl p, ".
		       						 "caestadostbl e  " . $lsWhereStr;       
		
		$rs = fn_ejecuta_query($sqlGetDireccionesComboStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['direccionCompleta'] = $rs['root'][$iInt]['calleNumero'].", ".
                                                      $rs['root'][$iInt]['colonia'].", ".
                                                      $rs['root'][$iInt]['municipio'].", ".
                                                      $rs['root'][$iInt]['estado'].", ".
                                                      $rs['root'][$iInt]['pais'].", ".
                                                      $rs['root'][$iInt]['cp'];
        }
			
		echo json_encode($rs);
	}

	function getDirecciones(){
		$lsWhereStr = "WHERE d.idColonia = c.idColonia ". 
		              "AND c.idMunicipio = m.idMunicipio ".
		              "AND m.idEstado = e.idEstado ".
		              "AND e.idPais = p.idPais ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroDistribuidorHdn'], "d.distribuidor", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['catDistCentroTipoDireccionHdn'], "d.tipoDireccion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

		$sqlGetDireccionesStr = "SELECT d.direccion, d.calleNumero, c.idColonia, c.colonia, d.distribuidor, ".
								"m.idMunicipio, m.municipio, e.idEstado, e.estado, p.idPais, p.pais, d.tipoDireccion ".
								"FROM cadireccionestbl d, cacoloniastbl c, camunicipiostbl m, caestadostbl e, capaisestbl p ".$lsWhereStr;

		$rs = fn_ejecuta_query($sqlGetDireccionesStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['direccionCompleta'] = $rs['root'][$iInt]['calleNumero'].", ".
                                                      $rs['root'][$iInt]['colonia'].", ".
                                                      $rs['root'][$iInt]['municipio'].", ".
                                                      $rs['root'][$iInt]['estado'].", ".
                                                      $rs['root'][$iInt]['pais'].", ".
                                                      $rs['root'][$iInt]['cp'];
        }
        
		echo json_encode($rs);
	}

    function getDireccionesGrid(){
        $lsWhereStr = "WHERE d.idColonia = c.idColonia ". 
                      "AND c.idMunicipio = m.idMunicipio ".
                      "AND m.idEstado = e.idEstado ".
                      "AND e.idPais = p.idPais ".
                      "AND (tipoDireccion='".$_REQUEST['catDistCentroTipoDireccionHdn']."' ".
                      "AND distribuidor='".$_REQUEST['catDistCentroDistribuidorHdn']."' ".
                      "OR distribuidor IS NULL ".
                      "AND tipoDireccion='".$_REQUEST['catDistCentroTipoDireccionHdn']."')";
        
        
        $sqlGetDirGridStr = "SELECT d.direccion, d.calleNumero, c.idColonia, c.colonia, d.distribuidor, ".
                            "m.idMunicipio, m.municipio, e.idEstado, e.estado, p.idPais, p.pais, d.tipoDireccion ".
                            "FROM cadireccionestbl d, cacoloniastbl c, camunicipiostbl m, caestadostbl e, capaisestbl p ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetDirGridStr);
            
        echo json_encode($rs);
    }

    function getDireccionDistribuidor(){
        $lsWhereStr = "WHERE d.idColonia = c.idColonia ".
                      "AND c.idMunicipio = m.idMunicipio ".
                      "AND m.idEstado = e.idEstado ".
                      "AND e.idPais = p.idPais ";

        if ($_REQUEST['catDireccionesTipoDireccionHdn'] == 'entrega') {
            $lsWhereStr .= "AND dc.direccionEntrega = d.direccion ";
        } elseif ($_REQUEST['catDireccionesTipoDireccionHdn'] == 'fiscal') {
            $lsWhereStr .= "AND dc.direccionFiscal = d.direccion ";
        }

        $sqlGetDireccionDistribuidorStr = "SELECT d.calleNumero, c.colonia, m.municipio, e.estado, p.pais, c.cp, d.direccion " .
                                          "FROM cadireccionestbl d, cacoloniastbl c, camunicipiostbl m, capaisestbl p, ".
                                          "caestadostbl e, cadistribuidorescentrostbl dc " . $lsWhereStr.
                                          "AND dc.distribuidorCentro = d.distribuidor ".
                                          "AND d.distribuidor = '".$_REQUEST['catDireccionesDistribuidorHdn']."'";  
        
        $rs = fn_ejecuta_query($sqlGetDireccionDistribuidorStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['direccionCompleta'] = $rs['root'][$iInt]['calleNumero'].", ".
                                                      $rs['root'][$iInt]['colonia'].", ".
                                                      $rs['root'][$iInt]['municipio'].", ".
                                                      $rs['root'][$iInt]['estado'].", ".
                                                      $rs['root'][$iInt]['pais'].", ".
                                                      $rs['root'][$iInt]['cp'];
        }
            
        echo json_encode($rs);
    }

	function addDirecciones($RQcalleNumero, $RQidColonia, $RQdistribuidor, $RQtipo){
		$a = array();
        $e = array();
        $a['success'] = true;
        $massive=0;
        
        if(substr($RQtipo, 0, 2) != 'DI'){
		  if($RQcalleNumero == "") {
                $e[] = array('id'=>'RQcalleNumero','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            }
            if($RQidColonia == "") {
                $e[] = array('id'=>'RQidColonia','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            }
        } else {
            $massive = 1;
            //Revisar que ningun dato del grid esté vacio
            $calleNumeroArr = explode('|', substr($RQcalleNumero, 0, -1));
            if(in_array('', $calleNumeroArr)){
        	   $e[] = array('id'=>'RQcalleNumeroArr','msg'=>getRequerido());
               $a['errorMessage'] = getErrorRequeridos();
               $a['success'] = false;
            }
            $coloniaArr = explode('|', substr($RQidColonia, 0, -1));
            if(in_array('', $coloniaArr)){
        	   $e[] = array('id'=>'RQidColoniaArr','msg'=>getRequerido());
               $a['errorMessage'] = getErrorRequeridos();
               $a['success'] = false;
            }
            $tipoDireccionArr = explode('|', substr($RQtipo, 0, -1));
            if(in_array('', $tipoDireccionArr)){
                $e[] = array('id'=>'RQtipoArr','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            }

            //Explode al campo que puede ser vacio
            $distribuidorArr = explode('|', substr($RQdistribuidor, 0, -1));
        }

        if ($a['success'] == true) {
        	$sqlAddDireccionesStr = "INSERT INTO cadireccionestbl (calleNumero, idColonia, distribuidor, tipoDireccion) VALUES";

        	if(count($calleNumeroArr)){
                for($nInt=0; $nInt<count($calleNumeroArr); $nInt++){
        		  $sqlAddDireccionesStr .= "('".$calleNumeroArr[$nInt]."', ".
        								    $coloniaArr[$nInt].", ".
                                            replaceEmptyNull("'".$distribuidorArr[$nInt]."'").", ".
                                            "'".$tipoDireccionArr[$nInt]."')";

    
				    if($nInt+1<count($calleNumeroArr)){
                		$sqlAddDireccionesStr .= ",";
            	   }
        	   }
            } else {
                $sqlAddDireccionesStr .= "('".$RQcalleNumero."', ".
                                         $RQidColonia.", ".
                                         replaceEmptyNull("'".$RQdistribuidor."'").", ".
                                         "'".$RQtipo."')";

            }

        	$rs = fn_ejecuta_query($sqlAddDireccionesStr);
        	
        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
			    $a['sql'] = $sqlAddDireccionesStr;
                $a['successMessage'] = getDireccionesSuccessMsg($massive);
                $a['id'] = $RQcalleNumero;
			} else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddDireccionesStr;
			}
		}
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        if(substr($RQtipo, 0, 2) == 'DI'){
            echo json_encode($a);
        }
	}

	function updDirecciones($RQdireccion, $RQcalleNumero, $RQidColonia, $RQdistribuidor, $RQtipo){
		    $a = array();
        $e = array();
        $a['success'] = true;
        $arrSize = 0;
        $massive = 0;
        $new = 0;
        $sqlCompletoStr = "";
		
		if(substr($RQtipo, 0, 2) != 'DI'){
            if($RQcalleNumero == "") {
                $e[] = array('id'=>'RQcalleNumero','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            }
            if($RQidColonia == "") {
                $e[] = array('id'=>'RQidColonia','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            } 
            $arrSize = 1;           
        } else {
            $massive = 1;
            //Revisar que ningun dato del grid esté vacio
            $calleNumeroArr = explode('|', substr($RQcalleNumero, 0, -1));
            if(in_array('', $calleNumeroArr)){
               $e[] = array('id'=>'RQcalleNumero','msg'=>getRequerido());
               $a['errorMessage'] = getErrorRequeridos();
               $a['success'] = false;
            }
            $coloniaArr = explode('|', substr($RQidColonia, 0, -1));
            if(in_array('', $coloniaArr)){
               $e[] = array('id'=>'RQidColonia','msg'=>getRequerido());
               $a['errorMessage'] = getErrorRequeridos();
               $a['success'] = false;
            }
            $tipoDireccionArr = explode('|', substr($RQtipo, 0, -1));
            if(in_array('', $tipoDireccionArr)){
                $e[] = array('id'=>'RQtipo','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            }
            
            //Explode al campo que puede ser vacio
            $direccionArr = explode('|', substr($RQdireccion, 0, -1));
            $distribuidorArr = explode('|', substr($RQdistribuidor, 0, -1));
            
            $arrSize = count($calleNumeroArr);
        }

        if ($a['success'] == true) {
        	$sqlAddDireccionesStr = "INSERT INTO cadireccionestbl (calleNumero, idColonia, distribuidor, tipoDireccion) VALUES";

        	for($nInt=0; $nInt<$arrSize; $nInt++){
                if($massive == 1 && $a['success'] == true){
        		  //Si es nueva y no tiene ID registrado
        		  if($direccionArr[$nInt] == ''){
                        if($new != 0){
                            $sqlAddDireccionesStr .= ",";
                        }
                        $new++;

                        $sqlAddDireccionesStr .= "('".$calleNumeroArr[$nInt]."', ".
        								 	   $coloniaArr[$nInt].", ".
        								 	   replaceEmptyNull("'".$distribuidorArr[$nInt]."'").", ".
                                               "'".$tipoDireccionArr[$nInt]."')";
                        
            	   //Si ya tiene le da UPDATE a sus campos
            	   } else {
                        $sqlUpdDireccionesStr = "UPDATE cadireccionestbl ".
        									    "SET calleNumero='".$calleNumeroArr[$nInt]."', ".
                                                "idColonia='".$coloniaArr[$nInt]."', ".
                                                "tipoDireccion='".$tipoDireccionArr[$nInt]."', ".
                                                "distribuidor= ".replaceEmptyNull("'".$distribuidorArr[$nInt]."'")." ".
        									    "WHERE direccion=".$direccionArr[$nInt];
                   
        			    $rs = fn_ejecuta_query($sqlUpdDireccionesStr);
                        
                        if(isset($_SESSION['error_sql']) && $_SESSION['error_sql'] != ""){
                            $a['success'] = false;
                            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdDireccionesStr;
                        } else {
                            $sqlCompletoStr .= $sqlUpdDireccionesStr." && ";
                        }
            	   }
        	   } else if($massive == 0 && $a['success'] == true){
                    if ($RQdireccion == '') {
                        $sqlAddDireccionesStr .= "('".$RQcalleNumero."', ".
                                              $RQidColonia.", ".
                                              replaceEmptyNull("'".$RQdistribuidor."'").", ".
                                              "'".$RQtipo."')";
                        
                        $rs = fn_ejecuta_query($sqlAddDireccionesStr);
                        $sqlCompletoStr .= $sqlAddDireccionesStr." && ";
                    } else {

                        $sqlUpdDireccionesStr = "UPDATE cadireccionestbl ".
                                              "SET calleNumero='".$RQcalleNumero."', ".
                                              "idColonia=".$RQidColonia.", ".
                                              "tipoDireccion='".$RQtipo."', ".
                                              "distribuidor= ".replaceEmptyNull("'".$RQdistribuidor."'")." ". 
                                              "WHERE direccion=".$RQdireccion;

                        $rs = fn_ejecuta_query($sqlUpdDireccionesStr);
                        $sqlCompletoStr .= $sqlUpdDireccionesStr." && ";
                    }
               }
            }

        	   if ($new > 0) {
                    $rs = fn_ejecuta_query($sqlAddDireccionesStr);
                    $sqlCompletoStr .= $sqlAddDireccionesStr." && ";
                }
        	
        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    $a['sql'] = $sqlCompletoStr;
                $a['successMessage'] = getDireccionesUpdateMsg($massive);
                $a['id'] = $RQcalleNumero;
			} else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCompleto;
			}
		}
        
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        
        if(substr($RQtipo, 0, 2) == 'DI'){
            echo json_encode($a);
        }
	}

    function dltDireccion($RQdireccion){
        //Se usa en addDistribuidor si el distribuidor no se crea bien
        $sqlDltDireccionStr = "DELETE FROM cadireccionestbl ".
                              "WHERE direccion=".$RQdireccion;
        
        $rs = fn_ejecuta_query($sqlDltDireccionStr);
    }

    function dltDireccionesMult(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if (isset($_REQUEST['catDireccionesDireccionHdn']) && $_REQUEST['catDireccionesDireccionHdn'] != "") {
            $idDireccionArr = explode('|', substr($_REQUEST['catDireccionesDireccionHdn'], 0, -1));
            if(in_array('', $idDireccionArr)) {
                $e[] = array('id'=>'catDireccionesDireccionHdn','msg'=>getRequerido());
                $a['errorMessage'] = getErrorRequeridos();
                $a['success'] = false;
            }
        } else {
            $e[] = array('id'=>'catDireccionesDireccionHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            for ($nInt=0; $nInt < sizeof($idDireccionArr); $nInt++) { 
                $sqlDltDireccionStr = "DELETE FROM caDireccionesTbl ".
                                      "WHERE direccion=".$idDireccionArr[$nInt];
        
                $rs = fn_ejecuta_query($sqlDltDireccionStr);

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                    if (!isset($a['successMessage'])) {
                        $a['successMessage'] = getDireccionesDltMult();
                    }
                    $a['successMessage'] .= $idDireccionArr[$nInt]." ";
                } else {
                    $a['success'] = false;
                    if (!isset($a['errorMessage'])) {
                        $a['errorMessage'] = "No se pudieron borrar las direcciones con ID: ";
                    }
                    $a['errorMessage'] .= $idDireccionArr[$nInt]." ";
                }
            }
        }

        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

?>