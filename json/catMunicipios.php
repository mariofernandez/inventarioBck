<?php
	session_start();
	$_SESSION['modulo'] = "catMunicipios";
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
	
    switch($_REQUEST['catMunicipiosActionHdn']){
        case 'getMunicipios':
            getMunicipios();
            break;
        case 'getMunicipiosPorNombre':
            getMunicipiosPorNombre();
            break;
        case 'addMunicipio':
        	addMunicipio();
        	break;
        case 'updMunicipio':
        	updMunicipio();
        	break;
        case 'dltMunicipio':
        	dltMunicipio();
        	break;
    }

    function getMunicipios(){
    	$lsWhereStr = "WHERE e.idEstado = m.idEstado ".
                      "AND p.idPais = e.idPais ";

    	if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catMunicipiosIdMunicipioHdn'], "m.idMunicipio", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catMunicipiosIdEstadoHdn'], "m.idEstado", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catMunicipiosMunicipioTxt'], "m.municipio", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }

		$sqlGetMunicipiosStr = "SELECT m.*, e.estado, p.pais, p.idPais ".
                               "FROM caMunicipiosTbl m, caEstadosTbl e, caPaisesTbl p ". $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetMunicipiosStr);

		echo json_encode($rs);
    }

    function getMunicipiosPorNombre(){

        $sqlGetMunicipiosStr = "SELECT m.municipio FROM caMunicipiosTbl m, caEstadosTbl e ".
                            "WHERE m.idEstado=e.idEstado AND e.estado='".$_REQUEST['catDistribuidoresEstadoHdn']."'";

        $rs = fn_ejecuta_query($sqlGetMunicipiosStr);
            
        echo json_encode($rs);
    }

    function addMunicipio(){

    	$a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catMunicipiosIdEstadoHdn'] == ""){
            $e[] = array('id'=>'catMunicipiosIdEstadoHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catMunicipiosMunicipioTxt'] == ""){
            $e[] = array('id'=>'catMunicipiosMunicipioTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

         //Revisa que no exista un municipio igual en el mismo estado
        $sqlCheckMunicipioStr = "SELECT municipio FROM caMunicipiosTbl ".
            					"WHERE idEstado=".$_REQUEST['catMunicipiosIdEstadoHdn']." ".
            					"AND municipio= '".$_REQUEST['catMunicipiosMunicipioTxt']."'";
        
        $rs = fn_ejecuta_query($sqlCheckMunicipioStr);

        if(sizeof($rs['root']) > 0){
        	$a['errorMessage'] = getMunicipioDuplicateMsg();
        	$a['success'] = false;
        }

        if($a['success'] == true){

        	$sqlAddMunicipioStr = "INSERT INTO caMunicipiosTbl (idEstado, municipio) VALUES(".
        						  $_REQUEST['catMunicipiosIdEstadoHdn'].", ".
        						  "'".$_REQUEST['catMunicipiosMunicipioTxt']."')";
        	
        	$rs = fn_ejecuta_query($sqlAddMunicipioStr);
        	
        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    $a['sql'] = $sqlAddMunicipioStr;
                $a['successMessage'] = getMunicipioSuccessMsg();
                $a['id'] = $_REQUEST['catMunicipiosMunicipioTxt'];;
			} else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddMunicipioStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updMunicipio(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catMunicipiosMunicipioTxt'] == ""){
            $e[] = array('id'=>'catMunicipiosMunicipioTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        //Revisa que no exista un municipio de nombre igual en el mismo estado
        $sqlCheckMunicipioStr = "SELECT * FROM caMunicipiosTbl ".
                                "WHERE idEstado=".$_REQUEST['catMunicipiosIdEstadoHdn']." ".
                                "AND municipio ='".$_REQUEST['catMunicipiosMunicipioTxt']."'";
        
        $rs = fn_ejecuta_query($sqlCheckMunicipioStr);

        if($rs == false || sizeof($rs['root'])>0){
            $a['errorMessage'] = getMunicipioDuplicateMsg();
            $a['success'] = false;
        }

        if($a['success'] == true){

            $sqlUpdMunicipioStr = "UPDATE caMunicipiosTbl ".
                                  "SET municipio= '".$_REQUEST['catMunicipiosMunicipioTxt']."' ".
                                  "WHERE idMunicipio=".$_REQUEST['catMunicipiosIdMunicipioHdn'];
            
            $rs = fn_ejecuta_query($sqlUpdMunicipioStr);


            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlUpdMunicipioStr;
                $a['successMessage'] = getMunicipioUpdateMsg();
                $a['id'] = $_REQUEST['catMunicipiosIdMunicipioHdn'];;
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdMunicipioStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function dltMunicipio(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $sqlDeleteMunicipioStr = "DELETE FROM caMunicipiosTbl WHERE idMunicipio=".$_REQUEST['catMunicipiosIdMunicipioHdn'];
       
        $rs = fn_ejecuta_query($sqlDeleteMunicipioStr);

        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
            $a['sql'] = $sqlDeleteMunicipioStr;
            $a['successMessage'] = getMunicipioDeleteMsg();
            $a['id'] = $_REQUEST['catMunicipiosIdMunicipioHdn'];
        } else {
            $a['success'] = false;
            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteMunicipioStr;
        }
        
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>