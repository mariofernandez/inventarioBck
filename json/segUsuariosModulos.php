<?php
	session_start();
	$_SESSION['modulo'] = "segUsuariosModulo";
    require("../funciones/generales.php");
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
	
    switch($_REQUEST['segUsuariosModulosActionHdn']){
        case 'getUsuariosModulos':
        	getUsuariosModulos();
        	break;
        case 'addUsuariosModulos':
        	addUsuariosModulos();
        	break;
        case 'dltUsuariosModulos':
        	dltUsuariosModulos();
        	break;
        default:
            echo '';
    }

    function getUsuariosModulos(){
    	$lsWhereStr = "WHERE sum.idModulo = sm.idModulo ";

		if ($gb_error_filtro == 0){
    		$condicionStr = fn_construct($_REQUEST['segUsuariosModulosUsuarioHdn'], "sum.idUsuario", 1);
    		$lsWhereStr = fn_concatena_condicion($lsWhereStr, $condicionStr);
		}
        if ($gb_error_filtro == 0){
    		$condicionStr = fn_construct($_REQUEST['segUsuariosModulosClaveMenuHdn'], "sum.idModulo", 0);
    		$lsWhereStr = fn_concatena_condicion($lsWhereStr, $condicionStr);
        }
    	$sqlGetUsuariosModulosStr = "SELECT sum.*,sm.* ".
                                    "FROM segUsuariosModulosTbl sum, sismodulostbl sm " . $lsWhereStr;
		
		$rs = fn_ejecuta_query($sqlGetUsuariosModulosStr);
		
		echo json_encode($rs);
    }

    function addUsuariosModulos() {
		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['segUsuariosModulosUsuarioHdn'] == ""){
            $e[] = array('id'=>'segUsuariosModulosUsuarioHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosModulosClaveMenuHdn'] == ""){
            $e[] = array('id'=>'segUsuariosModulosClaveMenuHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosModulosInsertarChk'] == ""){
            $e[] = array('id'=>'segUsuariosModulosInsertarChk','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosModulosEliminarChk'] == ""){
            $e[] = array('id'=>'segUsuariosModulosEliminarChk','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosModulosModificarChk'] == ""){
            $e[] = array('id'=>'segUsuariosModulosModificarChk','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosModulosConsultarChk'] == ""){
            $e[] = array('id'=>'segUsuariosModulosConsultarChk','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosModulosEjecutarChk'] == ""){
            $e[] = array('id'=>'segUsuariosModulosEjecutarChk','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosModulosReporteChk'] == ""){
            $e[] = array('id'=>'segUsuariosModulosReporteChk','msg'=>getRequerido());
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlCleanUsuarioStr = "DELETE FROM segUsuariosModulosTbl ".
                                  "WHERE idUsuario=".$_REQUEST['segUsuariosModulosUsuarioHdn']." ".
                                  "AND idModulo=".$_REQUEST['segUsuariosModulosClaveMenuHdn'];

            $rs = fn_ejecuta_query($sqlCleanUsuarioStr);

        	$sqlAddUsuModStr = "INSERT INTO segUsuariosModulosTbl ".
        					  "VALUES(".
        					  "'".$_REQUEST['segUsuariosModulosUsuarioHdn']."',".
        					  "'".date("Y-m-d")."',".
        					  $_REQUEST['segUsuariosModulosClaveMenuHdn'].",".
        					  $_REQUEST['segUsuariosModulosInsertarChk'].",".
        					  $_REQUEST['segUsuariosModulosEliminarChk'].",".
        					  $_REQUEST['segUsuariosModulosModificarChk'].",".
        					  $_REQUEST['segUsuariosModulosConsultarChk'].",".
        					  $_REQUEST['segUsuariosModulosEjecutarChk'].",".
        					  $_REQUEST['segUsuariosModulosReporteChk'].",".
        					  $_SESSION['idUsuario'].",".
        					  "'".$_SERVER['REMOTE_ADDR']."',".
        					  "'1')";

			$rs = fn_ejecuta_query($sqlAddUsuModStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddUsuModStr;
            	$a['successMessage'] = getUsuariosModulosSuccessMsg();
			} else {
            	$a['success'] = false;
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddUsuModStr;
        	}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function dltUsuariosModulos() {
		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['segUsuariosModulosUsuarioHdn'] == ""){
            $e[] = array('id'=>'segUsuariosModulosUsuarioHdn','msg'=>getRequerido());
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosModulosClaveMenuHdn'] == ""){
            $e[] = array('id'=>'segUsuariosModulosClaveMenuHdn','msg'=>getRequerido());
            $a['success'] = false;
        }      

        if ($a['success'] == true) {
        	$sqlDltUsuModStr = "DELETE FROM segUsuariosModulosTbl ".
        					   "WHERE idUsuario = ".$_REQUEST['segUsuariosModulosUsuarioHdn']." ".
                               "AND idModulo = ".$_REQUEST['segUsuariosModulosClaveMenuHdn']."";

			$rs = fn_ejecuta_query($sqlDltUsuModStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlDltUsuModStr;
            	$a['successMessage'] = getUsuariosModulosDltMsg();
			} else {
            	$a['success'] = false;
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDltUsuModStr;
        	}
        } else {
            $a['errorMessage'] = getErrorRequeridos();
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>