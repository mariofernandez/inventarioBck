<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
	session_start();
	$_SESSION['modulo'] = "catMunicipios";
    require("../funciones/generalesPDO.php");
    require("../funciones/construct.php");
	
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
    	$lsWhereStr = "";

    	if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catMunicipiosIdMunicipioHdn'], "idMunicipio", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catMunicipiosIdEstadoHdn'], "idEstado", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catMunicipiosMunicipioTxt'], "municipio", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }

		$sqlGetMunicipiosStr = "SELECT * FROM camunicipiostbl " . $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetMunicipiosStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
    }

    function getMunicipiosPorNombre(){

        $sqlGetMunicipiosStr = "SELECT m.municipio FROM camunicipiostbl m, caestadostbl e ".
                            "WHERE m.idEstado=e.idEstado AND e.estado='".$_REQUEST['catDistribuidoresEstadoHdn']."'";

        $rs = fn_ejecuta_query($sqlGetMunicipiosStr);
          
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
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
        if (count($rs)) {
            $sqlCheckMunicipioStr = "SELECT * FROM camunicipiostbl ".
        	   				        "WHERE idEstado=".$_REQUEST['catMunicipiosIdEstadoHdn']." ".
        					        "AND municipio= '".$_REQUEST['catMunicipiosMunicipioTxt']."'";
        
            $rs = fn_ejecuta_query($sqlCheckMunicipioStr);

            if($rs == false || mysql_num_rows($rs)){
            	$a['errorMessage'] = getMunicipioDuplicateMsg();
        	   $a['success'] = false;
            }
        }

        if($a['success'] == true){

        	$sqlAddMunicipioStr = "INSERT INTO camunicipiostbl ".
        						  "(idEstado, municipio) ".
        						  "VALUES(".
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
        if($a['success'] == true){
            $sqlCheckMunicipioStr = "SELECT * FROM camunicipiostbl ".
                                    "WHERE idEstado=".$_REQUEST['catMunicipiosIdEstadoHdn']." ".
                                    "AND municipio ='".$_REQUEST['catMunicipiosMunicipioTxt']."' ".
                                    "AND idMunicipio !=".$_REQUEST['catMunicipiosIdMunicipioHdn'];
        
            $rs = fn_ejecuta_query($sqlCheckMunicipioStr);
            
            if(count($rs)){
                $a['errorMessage'] = getMunicipioDuplicateMsg();
                $a['success'] = false;
            }
        }

        if($a['success'] == true){

            $sqlUpdMunicipioStr = "UPDATE camunicipiostbl ".
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

        $sqlDeleteMunicipioStr = "DELETE FROM camunicipiostbl WHERE idMunicipio=".$_REQUEST['catMunicipiosIdMunicipioHdn'];
       
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